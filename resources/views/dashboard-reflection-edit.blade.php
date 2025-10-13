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
                    <button type="submit" form="reflection-question-form" class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">Mentés</button>
                    <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500" href="{{ route('dashboard-reflections.show', $reflection->id) }}">Elvetés</a>
                </div>
            </div>

            <form id="reflection-question-form" method="POST" action="{{ route('dashboard-reflections.update', ['id' => $reflection->id, 'question_id' => $question->id]) }}" data-crud="update" data-redirect="{{ route('dashboard-reflections.show', $reflection->id) }}" data-loading-title="Mentés folyamatban" data-loading-text="Kérjük várjon..." data-success-title="Sikeres!" data-error-title="Hiba!">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Kérdés leírása</label>
                    <textarea id="description" name="description" rows="5" class="w-full border-2 rounded p-2" placeholder="Írd ide a kérdést..." required>{{ old('description', $question->description) }}</textarea>
                </div>

                <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mt-4">
                    <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                        <button type="submit" class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">Mentés</button>
                        <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500" href="{{ route('dashboard-reflections.show', $reflection->id) }}">Elvetés</a>
                    </div>
                </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        @vite(['resources/js/notification-handler.js'])
    @endpush
</x-app-layout>
