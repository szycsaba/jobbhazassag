<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cikkek') }}
        </h2>
    </x-slot>

    @section('content')
        @if (session('demo_limit_reached'))
            <meta name="demo-limit-reached" content="true">
        @endif
        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col">
            <!-- Top navigation button -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mb-4">
                <div class="w-[100%] flex flex-row justify-end">
                    <a id="create-article-top" data-demo="{{ isset($isDemo) && $isDemo ? 'on' : 'off' }}" data-article-count="{{ isset($articleCount) ? (int)$articleCount : 0 }}" class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"  href="{{ route('dashboard-articles.create-article') }}">+Cikk létrehozása</a>
                </div>
            </div>
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <div class="w-[15%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortTable('title')">
                    <span>Cím</span>
                    <span class="sort-indicator" data-column="title">
                        @if($sortBy === 'title')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @else
                            ↕
                        @endif
                    </span>
                </div>
                <div class="w-[15%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortTable('slug')">
                    <span>Url</span>
                    <span class="sort-indicator" data-column="slug">
                        @if($sortBy === 'slug')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @else
                            ↕
                        @endif
                    </span>
                </div>
                <div class="w-[20%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortTable('excerpt')">
                    <span>Leírás</span>
                    <span class="sort-indicator" data-column="excerpt">
                        @if($sortBy === 'excerpt')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @else
                            ↕
                        @endif
                    </span>
                </div>
                <div class="w-[15%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortTable('created_at')">
                    <span>Létrehozva</span>
                    <span class="sort-indicator" data-column="created_at">
                        @if($sortBy === 'created_at')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @else
                            ↕
                        @endif
                    </span>
                </div>
                <div class="w-[15%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortTable('updated_at')">
                    <span>Módosítva</span>
                    <span class="sort-indicator" data-column="updated_at">
                        @if($sortBy === 'updated_at')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @else
                            ↕
                        @endif
                    </span>
                </div>
                <div class="w-[20%]">Műveletek</div>
            </div>
            @foreach($articles as $article)
                <div class="flex flex-row font-medium p-[5px] text-gray-700 pt-[8px] pb-[8px] border-gray-200 hover:bg-gray-200" data-article-row="{{ $article->slug }}">
                    <h3 class="w-[15%]">{{ $article->title }}</h3>
                    <h3 class="w-[15%]">{{ $article->slug }}</h3>
                    <h3 class="w-[20%]">{{ $article->short_excerpt }}</h3>
                    <h3 class="w-[15%]">{{ $article->created_at }}</h3>
                    <h3 class="w-[15%]">{{ $article->updated_at }}</h3>
                    <div class="w-[20%] flex flex-row gap-[5px]">
                        <a class="w-[45%] h-[32px] flex justify-center items-center rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"  href="/dashboard-articles/{{$article->slug}}">Szerkesztés</a>
                        <form class="w-[45%]" method="POST" action="{{ route('dashboard-articles.destroy-article', ['slug' => $article->slug]) }}" 
                              data-crud="delete" 
                              data-confirm="true" 
                              data-confirm-title="Biztosan törölni szeretnéd a cikket?" 
                              data-confirm-text="Ez a művelet törli a cikket és az összes kapcsolódó blokkot! Ez nem vonható vissza!" 
                              data-loading-title="Törlés..." 
                              data-loading-text="Kérjük várjon..." 
                              data-remove-element="div[data-article-row='{{ $article->slug }}']" 
                              data-reload="true">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-[32px] flex justify-center items-center rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 hover:bg-red-600">Törlés</button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <div class="w-[100%] flex flex-row justify-end">
                    <a id="create-article-bottom" data-demo="{{ isset($isDemo) && $isDemo ? 'on' : 'off' }}" data-article-count="{{ isset($articleCount) ? (int)$articleCount : 0 }}" class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"  href="{{ route('dashboard-articles.create-article') }}">+Cikk létrehozása</a>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .sort-indicator {
                font-size: 12px;
                color: #6b7280;
                transition: color 0.2s ease;
            }
            
            .cursor-pointer:hover .sort-indicator {
                color: #374151;
            }
            
            .cursor-pointer:hover {
                background-color: #f3f4f6;
            }
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/notification-handler.js'])
        <script>
            function sortTable(column) {
                const currentSort = '{{ $sortBy }}';
                const currentDirection = '{{ $sortDirection }}';
                
                // Determine new direction
                let newDirection = 'asc';
                if (currentSort === column && currentDirection === 'asc') {
                    newDirection = 'desc';
                }
                
                // Build URL with sort parameters
                const url = new URL(window.location);
                url.searchParams.set('sort', column);
                url.searchParams.set('direction', newDirection);
                
                // Navigate to sorted URL
                window.location.href = url.toString();
            }
        </script>
    @endpush

</x-app-layout>
