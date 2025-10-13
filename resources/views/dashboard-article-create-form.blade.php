<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cikkek') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col">

            <form method="POST" action="{{ route('dashboard-articles.store-article') }}" 
                  data-crud="create" 
                  data-loading-title="Létrehozás..." 
                  data-loading-text="Kérjük várjon..." 
                  data-success-title="Sikeres!" 
                  data-redirect="{{ route('dashboard-articles') }}">
                @csrf

                <div class="h-[50px]">
                    <label for="title">Cikk címe:</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           class="w-[70%] border-2 p-2" 
                           placeholder="Írd ide a cikk címét..." 
                           value="{{ old('title') }}" 
                           required>
                </div>

                <div class="h-[50px] mt-4">
                    <label for="excerpt">Leírás (opcionális):</label>
                    <input type="text" 
                           name="excerpt" 
                           id="excerpt" 
                           class="w-[70%] border-2 p-2" 
                           placeholder="Írd ide a cikk leírását..." 
                           value="{{ old('excerpt') }}">
                </div>

                <div class="flex flex-row font-medium p-[5px] text-gray-700 border-b-2 pb-[8px] border-gray-200 mt-4">
                    <div class="w-[100%] flex flex-row justify-end gap-[5px]">
                        <button type="submit"
                                class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
                            Mentés
                        </button>
                        <a class="w-[30%] h-[32px] flex justify-center items-center p-[12px] rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500"
                           href="{{ route('dashboard-articles') }}">
                            Elvetés
                        </a>
                    </div>
                </div>
            </form>

        </div>
    @endsection

    @push('scripts')
        @vite(['resources/js/notification-handler.js'])
    @endpush
</x-app-layout>
