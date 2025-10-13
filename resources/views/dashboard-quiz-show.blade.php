<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kvíz: ') . $quiz->title }}
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
                    <a class="w-[20%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-gray-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500" href="{{ route('dashboard-quiz') }}">Vissza</a>
                    <a class="w-[20%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-green-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500" href="{{ route('dashboard-quiz.edit', $quiz->id) }}">Kvíz szerkesztése</a>
                    <a class="w-[20%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500" href="{{ route('dashboard-quiz.create-question', $quiz->id) }}">+Kérdés hozzáadás</a>
                </div>
            </div>
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <h3 class="w-[8%]">Pozíció</h3>
                <h3 class="w-[35%]">Kérdés</h3>
                <h3 class="w-[15%]">Válaszok</h3>
                <h3 class="w-[12%]">Létrehozva</h3>
                <h3 class="w-[12%]">Módosítva</h3>
                <h3 class="w-[18%]">Műveletek</h3>
            </div>
            @foreach($quiz->questions as $question)
                <div class="flex flex-row font-medium p-[5px] text-gray-700 pt-[8px] pb-[8px] border-gray-200 hover:bg-gray-200 cursor-move transition-all duration-200" 
                     data-quiz-question="{{ $question->id }}" 
                     data-drag-item 
                     data-question-id="{{ $question->id }}">
                    <h3 class="w-[8%] font-bold text-center">{{ $question->position }}</h3>
                    <div class="w-[35%]">
                        <h3>{{ \Illuminate\Support\Str::limit($question->question_text, 50) }}</h3>
                        @if($question->explanation)
                            <p class="text-sm text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($question->explanation, 30) }}</p>
                        @endif
                    </div>
                    <div class="w-[15%]">
                        <span class="text-sm">{{ $question->options->count() }} válasz</span>
                    </div>
                    <h3 class="w-[12%] text-sm">{{ $question->created_at->format('Y-m-d H:i') }}</h3>
                    <h3 class="w-[12%] text-sm">{{ $question->updated_at->format('Y-m-d H:i') }}</h3>
                    <div class="w-[18%] flex flex-row gap-[5px]">
                        <a class="w-[45%] h-[32px] flex justify-center items-center rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 hover:bg-blue-600" href="{{ route('dashboard-quiz.edit-question', ['id' => $quiz->id, 'question_id' => $question->id]) }}">Szerkesztés</a>
                        <form class="w-[45%]" method="POST" action="{{ route('dashboard-quiz.destroy-question', ['id' => $quiz->id, 'question_id' => $question->id]) }}" 
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
            @if($quiz->questions->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Ehhez a kvízhez még nincs kérdés.
                </div>
            @endif
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2  pb-[8px] border-gray-200">
                <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                    <a class="w-[20%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-gray-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500" href="{{ route('dashboard-quiz') }}">Vissza</a>
                    <a class="w-[20%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-green-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500" href="{{ route('dashboard-quiz.edit', $quiz->id) }}">Kvíz szerkesztése</a>
                    <a class="w-[20%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500" href="{{ route('dashboard-quiz.create-question', $quiz->id) }}">+Kérdés hozzáadás</a>
                </div>
            </div>
        </div>
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
        @vite(['resources/js/notification-handler.js', 'resources/js/drag-drop-reorder.js'])
    @endpush
</x-app-layout>
