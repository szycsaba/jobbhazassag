<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statisztika') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    
    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Szűrők</h2>
        
        <form method="GET" action="{{ route('dashboard.statistics') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dátumtól</label>
                <input type="date" 
                       id="date_from" 
                       name="date_from" 
                       value="{{ $dateFrom }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <!-- Date To -->
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Dátumig</label>
                <input type="date" 
                       id="date_to" 
                       name="date_to" 
                       value="{{ $dateTo }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <!-- Article Filter -->
            <div>
                <label for="article_id" class="block text-sm font-medium text-gray-700 mb-2">Cikk</label>
                <select id="article_id" 
                        name="article_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Összes cikk</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}" {{ $articleId == $article->id ? 'selected' : '' }}>
                            {{ $article->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    Szűrés
                </button>
            </div>
        </form>
        
        <!-- Clear Filters -->
        <div class="mt-4">
            <a href="{{ route('dashboard.statistics') }}" 
               class="text-sm text-gray-600 hover:text-gray-800 underline">
                Szűrők törlése
            </a>
        </div>
    </div>
    
    <!-- Statistics Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
         <div class="px-6 py-4 border-b border-gray-200">
             <div class="flex justify-between items-center">
                 <div>
                     <h2 class="text-xl font-semibold text-gray-800">Felhasználói reflexió jegyzetek</h2>
                     <p class="text-sm text-gray-600 mt-1">
                         Összesen {{ $reflectionNotes->total() }} bejegyzés
                     </p>
                 </div>
                 @if($reflectionNotes->count() > 0)
                     <a href="{{ route('dashboard.statistics.export', request()->query()) }}" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                         </svg>
                         Excel
                     </a>
                 @endif
             </div>
         </div>
        
        @if($reflectionNotes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="statisticsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                Név
                                <span class="sort-indicator">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                Cikk
                                <span class="sort-indicator">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                Kitöltve
                                <span class="sort-indicator">↕</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reflectionNotes as $note)
                            @php
                                $article = null;
                                if ($note->reflectionQuestion && $note->reflectionQuestion->reflection) {
                                    $article = $note->reflectionQuestion->reflection->article;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $note->googleUser ? $note->googleUser->name : 'Ismeretlen felhasználó' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $note->googleUser ? $note->googleUser->email : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $article ? $article->title : 'Ismeretlen cikk' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $note->updated_at->format('Y-m-d H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $note->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reflectionNotes->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nincs adat</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        A kiválasztott szűrőkre nincs megfelelő reflexió jegyzet.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Table Sorting JavaScript -->
<script>
let sortDirection = {};

function sortTable(columnIndex) {
    const table = document.getElementById('statisticsTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Toggle sort direction
    if (!sortDirection[columnIndex]) {
        sortDirection[columnIndex] = 'asc';
    } else {
        sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
    }
    
    // Clear other column indicators
    document.querySelectorAll('.sort-indicator').forEach(indicator => {
        indicator.textContent = '↕';
    });
    
    // Set current column indicator
    const currentIndicator = table.querySelectorAll('th')[columnIndex].querySelector('.sort-indicator');
    currentIndicator.textContent = sortDirection[columnIndex] === 'asc' ? '↑' : '↓';
    
    // Sort rows
    rows.sort((a, b) => {
        let aText = a.cells[columnIndex].textContent.trim();
        let bText = b.cells[columnIndex].textContent.trim();
        
        // For date column (Kitöltve), convert to date for proper sorting
        if (columnIndex === 2) {
            aText = new Date(aText);
            bText = new Date(bText);
        }
        
        if (sortDirection[columnIndex] === 'asc') {
            return aText > bText ? 1 : -1;
        } else {
            return aText < bText ? 1 : -1;
        }
    });
    
    // Reorder rows in table
    rows.forEach(row => tbody.appendChild(row));
}
</script>

<style>
.sort-indicator {
    margin-left: 5px;
    font-size: 12px;
}

th:hover {
    background-color: #f9fafb;
}
</style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
