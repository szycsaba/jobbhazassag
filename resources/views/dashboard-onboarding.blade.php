@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Onboarding') }}
        </h2>
    </x-slot>

    @section('content')
        @if (session('status'))
            <div class="max-w-[1220px] w-[100%] m-auto mt-[16px]">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            </div>
        @endif
        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col" data-drag-container>
            <!-- Top navigation buttons -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mb-4">
                <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"  href="{{ route('dashboard-onboarding.create') }}">+Elem hozzáadás</a>
                </div>
            </div>

            <!-- Header Management Section -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mb-4">
                <div class="w-[100%]">
                    <h3 class="text-lg font-semibold mb-4">Header kezelése</h3>
                    
                    <form id="header-management-form" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label for="header_title" class="block text-sm font-medium text-gray-700 mb-2">Cím:</label>
                            <input type="text" 
                                   id="header_title" 
                                   name="header_title" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                   placeholder="Header címe..."
                                   value="{{ $header ? $header->title : '' }}">
                        </div>
                        
                        <div>
                            <label for="header_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Alcím:</label>
                            <input type="text" 
                                   id="header_subtitle" 
                                   name="header_subtitle" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                                   placeholder="Header alcíme..."
                                   value="{{ $header ? $header->subtitle : '' }}">
                        </div>
                        
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kép:</label>
                            <button type="button" 
                                    id="header-image-button"
                                    class="w-full h-[42px] px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-left">
                                {{ $header && $header->image_url ? basename($header->image_url) : 'Kép kiválasztása...' }}
                            </button>
                            <input type="hidden" id="header_image_url" name="header_image_url" value="{{ $header ? $header->image_url : '' }}">
                        </div>
                    </form>
                    
                    <!-- Current Header Image Display -->
                    <div id="current-header-image-display" class="mt-4">
                        @if(isset($header) && $header->image_url)
                            <div class="flex items-center gap-4 p-4 border border-gray-300 rounded-lg bg-gray-50">
                                <img src="{{ asset('img/article/' . basename($header->image_url)) }}" alt="Current header image" class="w-20 h-20 object-cover rounded border">
                                <div>
                                    <p class="text-sm text-gray-600">Jelenlegi header kép:</p>
                                    <p class="text-sm font-medium">{{ basename($header->image_url) }}</p>
                                    <button type="button" onclick="window.headerManagement.removeCurrentImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                        Kép eltávolítása
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button type="button" 
                                id="save-header-button"
                                class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Header mentése
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <h3 class="w-[8%]">Pozíció</h3>
                <h3 class="w-[12%]">Típus</h3>
                <h3 class="w-[30%]">Tartalom</h3>
                <h3 class="w-[12%]">Létrehozva</h3>
                <h3 class="w-[12%]">Módosítva</h3>
                <h3 class="w-[26%]">Műveletek</h3>
            </div>
            @foreach($onsiteBlocks as $onsiteBlock)
                <div class="flex flex-row font-medium p-[5px] text-gray-700 pt-[8px] pb-[8px] border-gray-200 hover:bg-gray-200 cursor-move transition-all duration-200" 
                     data-onsite-block="{{ $onsiteBlock->id }}" 
                     data-drag-item 
                     data-block-id="{{ $onsiteBlock->id }}">
                    <h3 class="w-[8%] font-bold text-center">{{ $onsiteBlock->position }}</h3>
                    <h3 class="w-[12%]">{{ $onsiteBlock->type->name }}</h3>
                    <div class="w-[30%]">
                        @if($onsiteBlock->type->name === 'image')
                            @php
                                // Handle both full paths and just filenames
                                $content = $onsiteBlock->content;
                                if (str_starts_with($content, '/img/article/')) {
                                    $imagePath = ltrim($content, '/'); // Remove leading slash
                                } else {
                                    $imagePath = 'img/article/' . $content;
                                }
                                $fullImagePath = public_path($imagePath);
                            @endphp
                            @if(file_exists($fullImagePath))
                                <img src="{{ asset($imagePath) }}" 
                                     alt="{{ basename($imagePath) }}" 
                                     class="w-16 h-16 object-cover rounded border border-gray-300">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded border border-gray-300 flex items-center justify-center text-xs text-gray-500">
                                    Kép nem található
                                </div>
                            @endif
                        @else
                            <h3>{{ Str::limit($onsiteBlock->content, 50) }}</h3>
                        @endif
                    </div>
                    <h3 class="w-[12%] text-sm">{{ $onsiteBlock->created_at->format('Y-m-d H:i') }}</h3>
                    <h3 class="w-[12%] text-sm">{{ $onsiteBlock->updated_at->format('Y-m-d H:i') }}</h3>
                    <div class="w-[26%] flex flex-row gap-[5px]">
                        <a class="w-[45%] flex h-[32px] justify-center items-center rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"  href="{{ route('dashboard-onboarding.edit', $onsiteBlock->id) }}">Szerkesztés</a>
                        <form class="w-[45%]" method="POST" action="{{ route('dashboard-onboarding.destroy', $onsiteBlock->id) }}" 
                              data-crud="delete" 
                              data-confirm="true" 
                              data-confirm-title="Biztosan törölni szeretnéd?" 
                              data-confirm-text="Ez a művelet nem vonható vissza!" 
                              data-loading-title="Törlés..." 
                              data-loading-text="Kérjük várjon..." 
                              data-reload="true">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-[32px] flex justify-center items-center rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 hover:bg-red-600">Törlés</button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"  href="{{ route('dashboard-onboarding.create') }}">+Elem hozzáadás</a>
                </div>
            </div>
        </div>

        <!-- Header Image Selection Modal -->
        <div id="header-image-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg max-w-4xl w-full max-h-[80vh] overflow-hidden">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-lg font-semibold">Header kép kiválasztása</h3>
                        <button type="button" id="header-image-modal-close" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        <div id="header-image-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <!-- Images will be loaded here by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="header-image-modal-overlay" class="fixed inset-0 hidden"></div>
    @endsection

    @push('styles')
        <style>
            .dragging {
                transform: rotate(2deg);
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
                z-index: 1000;
            }
            
            .drag-over {
                border-top: 3px solid #3b82f6;
                background-color: #eff6ff;
            }
            
            [data-drag-item] {
                transition: all 0.2s ease;
            }
            
            [data-drag-item]:hover {
                background-color: #f3f4f6;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/notification-handler.js', 'resources/js/drag-drop-reorder.js', 'resources/js/header-management.js'])
    @endpush

    <script>
        // Initialize header management when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeHeaderManagement();
        });
    </script>

</x-app-layout>
