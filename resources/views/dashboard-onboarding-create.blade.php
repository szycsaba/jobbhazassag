<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Onboarding') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col">

            <!-- Top Action Buttons -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mb-4">
                <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                    <button type="submit" form="onboarding-create-form"
                            class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
                        Mentés
                    </button>
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500"
                       href="{{ route('dashboard-onboarding') }}">
                        Elvetés
                    </a>
                </div>
            </div>

            <form id="onboarding-create-form" method="POST" action="{{ route('dashboard-onboarding.store') }}" 
                  data-crud="create" 
                  data-loading-title="Létrehozás..." 
                  data-loading-text="Kérjük várjon..." 
                  data-success-title="Sikeres!" 
                  data-redirect="{{ route('dashboard-onboarding') }}">
                @csrf

                <div class="h-[50px]">
                    <label for="type_id">Típus:</label>
                    <select name="type_id" id="type_id" class="w-[30%] border-2" required onchange="toggleContentField()">
                        <option value="">Válassz típust...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" data-type-name="{{ $type->name }}">
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Color Picker Section -->
                <div id="color-picker-section" class="hidden mt-4 p-4 border border-gray-300 rounded-lg bg-gray-50">
                    <h3 class="text-lg font-semibold mb-3">Színbeállítások</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="background-color" class="block text-sm font-medium text-gray-700 mb-2">Háttérszín:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="background-color" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="background-color-text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md" placeholder="#ffffff">
                                <button type="button" id="reset-background" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Visszaállítás
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="text-color" class="block text-sm font-medium text-gray-700 mb-2">Betűszín:</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="text-color" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="text-color-text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md" placeholder="#000000">
                                <button type="button" id="reset-text" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Visszaállítás
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Text Content Field (for non-image types) -->
                <div id="text-content-field">
                    <textarea name="content" id="content" class="border-2 w-full mt-4" rows="10" placeholder="Írd ide a blokk tartalmát...">{{ old('content') }}</textarea>
                </div>

                <!-- Image Content Field (for image type) -->
                <div id="image-content-field" style="display: none;">
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kép kiválasztása:</label>
                        
                        <!-- Current Image Display -->
                        <div id="current-image-display" class="mb-4">
                        </div>

                        <!-- Image Selection -->
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @php
                                $imagePath = public_path('img/article');
                                $images = [];
                                if (is_dir($imagePath)) {
                                    $files = scandir($imagePath);
                                    foreach ($files as $file) {
                                        if ($file !== '.' && $file !== '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                            $images[] = $file;
                                        }
                                    }
                                }
                            @endphp

                            @foreach($images as $image)
                                <div class="border border-gray-300 rounded-lg p-2 cursor-pointer hover:border-blue-500 hover:bg-blue-50 image-option" 
                                     data-image="{{ $image }}" 
                                     onclick="selectImage('{{ $image }}')">
                                    <div class="aspect-square w-full">
                                        <img src="{{ asset('img/article/' . $image) }}" 
                                             alt="{{ $image }}" 
                                             class="w-full h-full object-contain rounded">
                                    </div>
                                    <p class="text-xs text-center mt-1 truncate">{{ $image }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Hidden input for selected image -->
                        <input type="hidden" name="content" id="image-content-input" value="">
                    </div>
                </div>

                <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mt-4">
                    <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                        <button type="submit"
                                class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
                            Mentés
                        </button>
                        <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500"
                           href="{{ route('dashboard-onboarding') }}">
                            Elvetés
                        </a>
                    </div>
                </div>
            </form>

        </div>
    @endsection

    @push('scripts')
        @vite(['resources/js/notification-handler.js', 'resources/js/article-form.js'])
        <script>
            // Pass type attributes data to JavaScript
            window.typeAttributesData = {!! json_encode($types->map(function($type) {
                return [
                    'id' => $type->id,
                    'attributes' => $type->articleTypeAttributes ? [
                        'background' => $type->articleTypeAttributes->background,
                        'text' => $type->articleTypeAttributes->text,
                        'default_background' => $type->articleTypeAttributes->default_background,
                        'default_text' => $type->articleTypeAttributes->default_text
                    ] : null
                ];
            })) !!};

            document.addEventListener('DOMContentLoaded', function() {
                if (typeof initializeArticleForm === 'function') {
                    initializeArticleForm();
                } else {
                    console.error('initializeArticleForm function not found');
                }
            });
        </script>
    @endpush
</x-app-layout>
