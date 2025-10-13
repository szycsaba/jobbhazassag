<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kérdés szerkesztése') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col">
            <!-- Top Action Buttons -->
            <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mb-4">
                <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                    <button type="submit" form="quiz-question-form" class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">Mentés</button>
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500" href="{{ route('dashboard-quiz.show', $quiz->id) }}">Elvetés</a>
                </div>
            </div>

            <form id="quiz-question-form" method="POST" action="{{ route('dashboard-quiz.update-question', ['id' => $quiz->id, 'question_id' => $question->id]) }}" data-crud="update" data-redirect="{{ route('dashboard-quiz.show', $quiz->id) }}" data-loading-title="Mentés folyamatban" data-loading-text="Kérjük várjon..." data-success-title="Sikeres!" data-error-title="Hiba!">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">Kérdés szövege</label>
                    <textarea id="question_text" name="question_text" rows="3" class="w-full border-2 rounded p-2" placeholder="Írd ide a kérdést..." required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">Magyarázat (opcionális)</label>
                    <textarea id="explanation" name="explanation" rows="2" class="w-full border-2 rounded p-2" placeholder="Írd ide a magyarázatot...">{{ old('explanation', $question->explanation) }}</textarea>
                </div>

                <!-- Answer Options -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Válaszlehetőségek</label>
                    <div id="options-container">
                        @foreach($question->options as $index => $option)
                            <div class="option-row mb-2 flex items-center gap-2">
                                <input type="radio" name="correct_option" value="{{ $index }}" class="correct-option" {{ $option->is_correct ? 'checked' : '' }} required>
                                <input type="text" name="options[{{ $index }}][option_text]" class="flex-1 border-2 rounded p-2" placeholder="Válaszlehetőség {{ $index + 1 }}" value="{{ old('options.' . $index . '.option_text', $option->option_text) }}" required>
                                <input type="hidden" name="options[{{ $index }}][is_correct]" value="{{ $option->is_correct ? 'true' : 'false' }}">
                                @if($question->options->count() > 2)
                                    <button type="button" class="remove-option px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Törlés</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-option" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">+ Válaszlehetőség hozzáadása</button>
                </div>

                <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mt-4">
                    <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                        <button type="submit" class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">Mentés</button>
                        <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500" href="{{ route('dashboard-quiz.show', $quiz->id) }}">Elvetés</a>
                    </div>
                </div>
            </form>

        </div>
    @endsection

    @push('scripts')
        @vite(['resources/js/notification-handler.js', 'resources/js/quiz-question-manager.js'])
    @endpush
</x-app-layout>
