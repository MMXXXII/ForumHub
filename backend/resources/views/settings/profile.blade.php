@extends('settings.layout')

@section('settings')
<form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PATCH')

    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">Аватар</label>
        <div id="dropzone" class="flex items-center gap-4 border-2 border-dashed border-neutral-200 rounded-xl p-4 transition cursor-pointer hover:border-neutral-400">
            <img id="avatarPreview" src="{{ $user->avatarUrl() ?? '' }}" alt="" class="w-20 h-20 rounded-lg object-cover bg-neutral-100 shrink-0 {{ $user->avatarUrl() ? '' : 'hidden' }}">
            <div id="avatarPlaceholder" class="w-20 h-20 rounded-lg bg-neutral-100 text-neutral-400 flex items-center justify-center text-2xl font-semibold shrink-0 {{ $user->avatarUrl() ? 'hidden' : '' }}">
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="text-sm text-neutral-700">Перетащите изображение сюда или нажмите, чтобы выбрать</div>
                <div class="text-xs text-neutral-400 mt-0.5">JPG, PNG, WEBP или GIF, до 4 МБ</div>
                <div id="fileName" class="text-xs text-black mt-1 truncate"></div>
            </div>
            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden">
        </div>
        @error('avatar') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror

        @if ($user->avatarUrl())
            <label class="flex items-center gap-2 mt-2 text-xs text-neutral-500 cursor-pointer">
                <input type="checkbox" name="remove_avatar" value="1" class="rounded border-neutral-300">
                Удалить аватар
            </label>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">Статус</label>
        <input type="text" name="status" value="{{ old('status', $user->status) }}" maxlength="100" placeholder="Пара слов о себе" class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
        @error('status') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">Дата рождения</label>
        <input type="date" name="birthday" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}" class="border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
        <div class="text-xs text-neutral-400 mt-1">Необязательно. Будет видно в вашем профиле.</div>
        @error('birthday') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <div class="text-sm font-medium text-neutral-700 mb-2">Контакты</div>
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="w-28 flex items-center gap-1.5 text-sm text-neutral-500 shrink-0"><i class="ti ti-brand-telegram text-base"></i>Telegram</span>
                <input type="text" name="telegram" value="{{ old('telegram', $user->telegram) }}" placeholder="username" class="flex-1 border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
            </div>
            @error('telegram') <div class="text-xs text-red-600">{{ $message }}</div> @enderror

            <div class="flex items-center gap-2">
                <span class="w-28 flex items-center gap-1.5 text-sm text-neutral-500 shrink-0"><i class="ti ti-brand-vk text-base"></i>ВКонтакте</span>
                <input type="text" name="vk" value="{{ old('vk', $user->vk) }}" placeholder="id123 или username" class="flex-1 border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
            </div>
            @error('vk') <div class="text-xs text-red-600">{{ $message }}</div> @enderror

            <div class="flex items-center gap-2">
                <span class="w-28 flex items-center gap-1.5 text-sm text-neutral-500 shrink-0"><i class="ti ti-brand-steam text-base"></i>Steam</span>
                <input type="text" name="steam" value="{{ old('steam', $user->steam) }}" placeholder="username" class="flex-1 border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
            </div>
            @error('steam') <div class="text-xs text-red-600">{{ $message }}</div> @enderror

            <div class="flex items-center gap-2">
                <span class="w-28 flex items-center gap-1.5 text-sm text-neutral-500 shrink-0"><i class="ti ti-link text-base"></i>Сайт</span>
                <input type="url" name="website" value="{{ old('website', $user->website) }}" placeholder="https://" class="flex-1 border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black">
            </div>
            @error('website') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="bg-black text-white text-sm rounded-lg px-4 py-2 hover:bg-neutral-800 transition">Сохранить</button>
        <a href="{{ route('profile.show', $user) }}" class="text-sm text-neutral-500 hover:text-black">Открыть профиль</a>
    </div>
</form>

<script>
    (() => {
        const zone = document.getElementById('dropzone');
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        const fileName = document.getElementById('fileName');
        if (!zone) return;

        const showFile = (file) => {
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
            fileName.textContent = file.name;
        };

        zone.addEventListener('click', () => input.click());
        input.addEventListener('change', () => showFile(input.files[0]));

        ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, (e) => {
            e.preventDefault();
            zone.classList.add('border-black', 'bg-neutral-50');
        }));

        ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, (e) => {
            e.preventDefault();
            zone.classList.remove('border-black', 'bg-neutral-50');
        }));

        zone.addEventListener('drop', (e) => {
            const file = e.dataTransfer.files[0];
            if (file) {
                input.files = e.dataTransfer.files;
                showFile(file);
            }
        });
    })();
</script>
@endsection