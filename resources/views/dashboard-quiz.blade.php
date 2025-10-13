<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kvíz') }}
        </h2>
    </x-slot>

    @section('content')
        @if(session('success'))
            <div class="max-w-[1220px] w-[100%] m-auto mt-[16px]">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col">
            <!-- Top navigation button (placeholder) -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mb-4">
                <div class="w-[100%] flex flex-row justify-end">
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500" href="{{ route('dashboard-quiz.create') }}">+ Új létrehozása</a>
                </div>
            </div>

            <!-- Table headers -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <div class="w-[25%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortQuizzes('title')">
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
                <div class="w-[25%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortQuizzes('article_title')">
                    <span>Kapcsolódó cikk</span>
                    <span class="sort-indicator" data-column="article_title">
                        @if($sortBy === 'article_title')
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
                <div class="w-[15%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortQuizzes('created_at')">
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
                <div class="w-[15%] cursor-pointer hover:bg-gray-100 p-2 rounded flex items-center justify-between" onclick="sortQuizzes('updated_at')">
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
                <div class="w-[20%] p-2">Műveletek</div>
            </div>

            @forelse($quizzes as $quiz)
                <div class="flex flex-row font-medium p-[5px] text-gray-700 pt-[8px] pb-[8px] border-gray-200 hover:bg-gray-200">
                    <h3 class="w-[25%]">{{ $quiz->short_title ?? $quiz->title }}</h3>
                    <h3 class="w-[25%]">{{ $quiz->article->title ?? 'Nincs kapcsolódó cikk' }}</h3>
                    <h3 class="w-[15%]">{{ $quiz->created_at->format('Y-m-d') }}</h3>
                    <h3 class="w-[15%]">{{ $quiz->updated_at->format('Y-m-d') }}</h3>
                    <div class="w-[20%] flex flex-row gap-[5px]">
                        <a class="w-[45%] h-[32px] flex justify-center items-center rounded-md bg-blue-500 hover:bg-blue-600 text-sm font-semibold text-white" href="{{ route('dashboard-quiz.show', $quiz->id) }}">Szerkesztés</a>
                        <form method="POST" action="{{ route('dashboard-quiz.destroy', $quiz->id) }}" data-crud="delete" data-confirm="true" data-confirm-title="Biztosan törlöd?" data-confirm-text="A kvíz és minden kapcsolódó adat törlődik." data-success-title="Sikeres törlés" data-error-title="Hiba!" data-reload="true" class="w-[45%]">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-[100%] h-[32px] flex justify-center items-center rounded-md bg-red-500 hover:bg-red-600 text-sm font-semibold text-white">Törlés</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Jelenleg nincs megjeleníthető adat.
                </div>
            @endforelse

            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <div class="w-[100%] flex flex-row justify-end">
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500" href="{{ route('dashboard-quiz.create') }}">+ Új létrehozása</a>
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
            .cursor-pointer:hover .sort-indicator { color: #374151; }
            .cursor-pointer:hover { background-color: #f3f4f6; }
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/notification-handler.js'])
        <script>
            function sortQuizzes(column) {
                const currentSort = '{{ $sortBy }}';
                const currentDirection = '{{ $sortDirection }}';
                let newDirection = 'asc';
                if (currentSort === column && currentDirection === 'asc') {
                    newDirection = 'desc';
                }
                const url = new URL(window.location);
                url.searchParams.set('sort', column);
                url.searchParams.set('direction', newDirection);
                window.location.href = url.toString();
            }
        </script>
    @endpush

</x-app-layout>
