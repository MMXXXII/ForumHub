import os
import re
import torch
from fastapi import FastAPI
from pydantic import BaseModel, Field
from transformers import AutoTokenizer, AutoModelForSequenceClassification

MODEL_NAME = os.getenv("MODEL_NAME", "cointegrated/rubert-tiny-toxicity")
THRESHOLD = float(os.getenv("TOXICITY_THRESHOLD", "0.5"))

app = FastAPI(title="ForumHub Moderation Service", version="1.1.0")

tokenizer = None
model = None

HOMOGLYPHS = str.maketrans({
    "a": "а", "e": "е", "o": "о", "p": "р", "c": "с", "y": "у", "x": "х",
    "k": "к", "m": "м", "t": "т", "b": "в", "h": "н", "3": "з", "0": "о",
    "4": "ч", "6": "б", "@": "а", "$": "s", "1": "и", "!": "и",
})


@app.on_event("startup")
def load_model():
    global tokenizer, model
    tokenizer = AutoTokenizer.from_pretrained(MODEL_NAME)
    model = AutoModelForSequenceClassification.from_pretrained(MODEL_NAME)
    model.eval()


def normalize(text: str) -> str:
    normalized = text.lower().translate(HOMOGLYPHS)
    normalized = re.sub(r"[^\w\s]", "", normalized)
    normalized = re.sub(r"(?<=\b\w)\s+(?=\w\b)", "", normalized)
    normalized = re.sub(r"(\w)\1{2,}", r"\1", normalized)
    normalized = re.sub(r"\s+", " ", normalized).strip()
    return normalized


class ModerationRequest(BaseModel):
    text: str = Field(..., min_length=1, max_length=10000)


class ModerationResponse(BaseModel):
    status: str
    score: float
    label: str
    details: dict


def score_text(text: str) -> dict:
    with torch.no_grad():
        inputs = tokenizer(text, return_tensors="pt", truncation=True, max_length=512)
        logits = model(**inputs).logits
        probs = torch.sigmoid(logits).squeeze().tolist()

    if isinstance(probs, float):
        probs = [probs]

    labels = list(model.config.id2label.values())
    scored = dict(zip(labels, [round(p, 4) for p in probs]))
    toxicity = round(1.0 - scored.get("non-toxic", 0.0), 4)

    worst = max(
        ((k, v) for k, v in scored.items() if k != "non-toxic"),
        key=lambda kv: kv[1],
        default=("none", 0.0),
    )[0]

    return {"toxicity": toxicity, "worst_label": worst, "details": scored}


@app.get("/health")
def health():
    return {"status": "ok", "model": MODEL_NAME, "loaded": model is not None}


@app.post("/moderate", response_model=ModerationResponse)
def moderate(request: ModerationRequest):
    original = score_text(request.text)

    normalized_text = normalize(request.text)
    was_normalized = normalized_text and normalized_text != request.text.lower()

    if was_normalized:
        normalized = score_text(normalized_text)
        best = original if original["toxicity"] >= normalized["toxicity"] else normalized
        best = dict(best)
        best["details"] = {
            **best["details"],
            "raw_score": original["toxicity"],
            "normalized_score": normalized["toxicity"],
            "normalized_text": normalized_text,
        }
    else:
        best = original

    is_toxic = best["toxicity"] >= THRESHOLD

    return ModerationResponse(
        status="rejected" if is_toxic else "approved",
        score=best["toxicity"],
        label=best["worst_label"] if is_toxic else "none",
        details=best["details"],
    )