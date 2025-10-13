<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Új kérdés hozzáadása') }}
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

            <form id="quiz-question-form" method="POST" action="{{ route('dashboard-quiz.store-question', $quiz->id) }}" data-crud="create" data-redirect="{{ route('dashboard-quiz.show', $quiz->id) }}" data-loading-title="Mentés folyamatban" data-loading-text="Kérjük várjon..." data-success-title="Sikeres!" data-error-title="Hiba!">
                @csrf

                <div class="mb-4">
                    <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">Kérdés szövege</label>
                    <textarea id="question_text" name="question_text" rows="3" class="w-full border-2 rounded p-2" placeholder="Írd ide a kérdést..." required></textarea>
                </div>

                <div class="mb-4">
                    <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">Magyarázat (opcionális)</label>
                    <textarea id="explanation" name="explanation" rows="2" class="w-full border-2 rounded p-2" placeholder="Írd ide a magyarázatot..."></textarea>
                </div>

                <!-- Answer Options -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Válaszlehetőségek</label>
                    <div id="options-container">
                        <div class="option-row mb-2 flex items-center gap-2">
                            <input type="radio" name="correct_option" value="0" class="correct-option" required>
                            <input type="text" name="options[0][option_text]" class="flex-1 border-2 rounded p-2" placeholder="Válaszlehetőség 1" required>
                            <input type="hidden" name="options[0][is_correct]" value="false">
                        </div>
                        <div class="option-row mb-2 flex items-center gap-2">
                            <input type="radio" name="correct_option" value="1" class="correct-option" required>
                            <input type="text" name="options[1][option_text]" class="flex-1 border-2 rounded p-2" placeholder="Válaszlehetőség 2" required>
                            <input type="hidden" name="options[1][is_correct]" value="false">
                        </div>
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
