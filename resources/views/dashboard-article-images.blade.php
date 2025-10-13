<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cikk képek') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="max-w-[1220px] w-[100%] shadow-sm bg-white m-auto mt-[32px] rounded-lg p-[16px] flex flex-col">
            <div class="mt-6">
                <div class="flex flex-row justify-between items-center mb-4">
                    <p class="text-gray-600">Kezeld a cikkeidben használt képeket. Tölts fel, rendszerezz és törölj képeket szükség szerint.</p>
                    <button id="uploadButton" class="w-auto px-4 h-[32px] flex justify-center items-center p-[12px] rounded-md bg-blue-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 cursor-pointer hover:bg-blue-600">
                        + Feltöltés
                    </button>
                </div>
                
                <!-- Images Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
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

                    @if(count($images) > 0)
                        @foreach($images as $image)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden" data-image-card="{{ $image }}">
                                <!-- Image -->
                                <div class="aspect-square bg-gray-100 flex items-center justify-center">
                                    <img src="{{ asset('img/article/' . $image) }}" 
                                         alt="{{ $image }}" 
                                         class="max-w-full max-h-full object-contain">
                                </div>
                                
                                <!-- Image Info -->
                                <div class="p-3">
                                    <h3 class="text-sm font-medium text-gray-900 truncate" title="{{ $image }}">
                                        {{ $image }}
                                    </h3>
                                    
                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('dashboard-article-images.delete') }}" 
                                          class="mt-2"
                                          data-crud="delete"
                                          data-confirm="true"
                                          data-confirm-title="Biztosan törölni szeretnéd?"
                                          data-confirm-text="Ez a művelet nem vonható vissza!"
                                          data-loading-title="Törlés..."
                                          data-loading-text="Kérjük várjon..."
                                          data-remove-element="div[data-image-card='{{ $image }}']">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="image_name" value="{{ $image }}">
                                        <button type="submit" 
                                                class="w-full h-[32px] flex justify-center items-center rounded-md bg-red-500 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 hover:bg-red-600 cursor-pointer">
                                            Törlés
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- No images placeholder -->
                        <div class="col-span-full border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No images found</h3>
                                <p class="mt-1 text-sm text-gray-500">No images found in the article directory.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Képek feltöltése</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Upload Form -->
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                                Válassz képeket (max 20MB/fájl)
                            </label>
                            <input type="file" 
                                   id="images" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">
                                Támogatott formátumok: JPEG, JPG, PNG, GIF, WEBP
                            </p>
                        </div>

                        <!-- File Preview -->
                        <div id="filePreview" class="mb-4 hidden">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Kiválasztott fájlok:</h4>
                            <div id="fileList" class="space-y-1 max-h-32 overflow-y-auto"></div>
                        </div>

                        <!-- Modal Buttons -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" 
                                    id="cancelUpload" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Mégse
                            </button>
                            <button type="submit" 
                                    id="submitUpload"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                Feltöltés
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        @vite(['resources/js/notification-handler.js'])
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const uploadButton = document.getElementById('uploadButton');
                const uploadModal = document.getElementById('uploadModal');
                const closeModal = document.getElementById('closeModal');
                const cancelUpload = document.getElementById('cancelUpload');
                const uploadForm = document.getElementById('uploadForm');
                const fileInput = document.getElementById('images');
                const filePreview = document.getElementById('filePreview');
                const fileList = document.getElementById('fileList');
                const submitUpload = document.getElementById('submitUpload');

                // Open modal
                uploadButton.addEventListener('click', function() {
                    uploadModal.classList.remove('hidden');
                });

                // Close modal
                function closeModalFunction() {
                    uploadModal.classList.add('hidden');
                    uploadForm.reset();
                    filePreview.classList.add('hidden');
                    fileList.innerHTML = '';
                }

                closeModal.addEventListener('click', closeModalFunction);
                cancelUpload.addEventListener('click', closeModalFunction);

                // Close modal when clicking outside
                uploadModal.addEventListener('click', function(e) {
                    if (e.target === uploadModal) {
                        closeModalFunction();
                    }
                });

                // File selection preview
                fileInput.addEventListener('change', function() {
                    const files = Array.from(this.files);
                    
                    if (files.length > 0) {
                        filePreview.classList.remove('hidden');
                        fileList.innerHTML = '';
                        
                        files.forEach((file, index) => {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'flex items-center justify-between text-sm bg-gray-50 p-2 rounded';
                            
                            const fileInfo = document.createElement('span');
                            fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                            
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'text-red-500 hover:text-red-700 text-xs';
                            removeBtn.textContent = 'Eltávolít';
                            removeBtn.addEventListener('click', function() {
                                fileItem.remove();
                                if (fileList.children.length === 0) {
                                    filePreview.classList.add('hidden');
                                }
                            });
                            
                            fileItem.appendChild(fileInfo);
                            fileItem.appendChild(removeBtn);
                            fileList.appendChild(fileItem);
                        });
                    } else {
                        filePreview.classList.add('hidden');
                    }
                });

                // Form submission
                uploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const files = fileInput.files;
                    
                    if (files.length === 0) {
                        Swal.fire({
                            title: 'Hiba!',
                            text: 'Kérjük válassz ki legalább egy képet!',
                            icon: 'error',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Check file sizes
                    let hasOversizedFile = false;
                    Array.from(files).forEach(file => {
                        if (file.size > 20 * 1024 * 1024) { // 20MB
                            hasOversizedFile = true;
                        }
                    });

                    if (hasOversizedFile) {
                        Swal.fire({
                            title: 'Hiba!',
                            text: 'Egy vagy több kép mérete meghaladja a 20MB-os korlátot!',
                            icon: 'error',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Show loading
                    Swal.fire({
                        title: 'Feltöltés...',
                        text: 'Képek feltöltése folyamatban...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    fetch('{{ route("dashboard-article-images.upload") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        
                        if (data.success) {
                            Swal.fire({
                                title: 'Sikeres!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#3b82f6'
                            }).then(() => {
                                // Reload page to show new images
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Hiba!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#3b82f6'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            title: 'Hiba!',
                            text: 'Hálózati hiba történt. Kérjük próbálja újra.',
                            icon: 'error',
                            confirmButtonColor: '#3b82f6'
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
