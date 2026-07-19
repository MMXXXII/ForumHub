FROM python:3.11-slim

WORKDIR /app

RUN pip install --no-cache-dir torch==2.5.1 --index-url https://download.pytorch.org/whl/cpu

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

ENV MODEL_NAME=cointegrated/rubert-tiny-toxicity
RUN python -c "from transformers import AutoTokenizer, AutoModelForSequenceClassification; \
    AutoTokenizer.from_pretrained('$MODEL_NAME'); \
    AutoModelForSequenceClassification.from_pretrained('$MODEL_NAME')"

COPY app.py .

EXPOSE 8000
CMD ["uvicorn", "app:app", "--host", "0.0.0.0", "--port", "8000"]