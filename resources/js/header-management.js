/**
 * Header Management Handler
 * Handles header CRUD operations and image selection
 */
class HeaderManagement {
    constructor(options = {}) {
        this.options = {
            headerFormId: "header-management-form",
            headerTitleId: "header_title",
            headerSubtitleId: "header_subtitle",
            headerImageInputId: "header_image_url",
            headerImageButtonId: "header-image-button",
            headerImageModalId: "header-image-modal",
            headerImageModalCloseId: "header-image-modal-close",
            headerImageModalOverlayId: "header-image-modal-overlay",
            headerImageGridId: "header-image-grid",
            currentImageDisplayId: "current-header-image-display",
            saveButtonId: "save-header-button",
            ...options,
        };

        this.currentImage = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCurrentHeader();
    }

    bindEvents() {
        // Image selection button
        const imageButton = document.getElementById(this.options.headerImageButtonId);
        if (imageButton) {
            imageButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.openImageModal();
            });
        }

        // Modal close events
        const modalClose = document.getElementById(this.options.headerImageModalCloseId);
        const modalOverlay = document.getElementById(this.options.headerImageModalOverlayId);
        
        if (modalClose) {
            modalClose.addEventListener('click', () => this.closeImageModal());
        }
        
        if (modalOverlay) {
            modalOverlay.addEventListener('click', () => this.closeImageModal());
        }

        // Image selection events
        document.addEventListener("click", (e) => {
            if (e.target.closest(".header-image-option")) {
                const imageName = e.target.closest(".header-image-option").dataset.image;
                if (imageName) {
                    this.selectImage(imageName);
                }
            }
        });

        // Form submission
        const form = document.getElementById(this.options.headerFormId);
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveHeader();
            });
        }

        // Save button
        const saveButton = document.getElementById(this.options.saveButtonId);
        if (saveButton) {
            saveButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.saveHeader();
            });
        }
    }

    openImageModal() {
        const modal = document.getElementById(this.options.headerImageModalId);
        if (modal) {
            modal.classList.remove('hidden');
            this.loadAvailableImages();
        }
    }

    closeImageModal() {
        const modal = document.getElementById(this.options.headerImageModalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    async loadAvailableImages() {
        const imageGrid = document.getElementById(this.options.headerImageGridId);
        if (!imageGrid) return;

        // Clear existing images and show loading
        imageGrid.innerHTML = '<div class="col-span-full text-center py-8">Képek betöltése...</div>';

        try {
            // Load images from server
            const images = await this.loadAvailableImagesFromServer();
            
            // Clear loading message
            imageGrid.innerHTML = '';

            if (images.length === 0) {
                imageGrid.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">Nincsenek elérhető képek</div>';
                return;
            }
            
            images.forEach(image => {
                const imageElement = document.createElement('div');
                imageElement.className = 'border border-gray-300 rounded-lg p-2 cursor-pointer hover:border-blue-500 hover:bg-blue-50 header-image-option';
                imageElement.dataset.image = image;
                
                imageElement.innerHTML = `
                    <div class="aspect-square w-full">
                        <img src="/img/article/${image}" alt="${image}" class="w-full h-full object-contain rounded">
                    </div>
                    <p class="text-xs text-center mt-1 truncate">${image}</p>
                `;
                
                imageGrid.appendChild(imageElement);
            });
        } catch (error) {
            console.error('Error loading images:', error);
            imageGrid.innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Hiba történt a képek betöltése során</div>';
        }
    }

    getAvailableImages() {
        // This will be loaded from the server via AJAX
        // For now, return empty array - images will be loaded when modal opens
        return [];
    }

    async loadAvailableImagesFromServer() {
        try {
            const response = await fetch('/dashboard/header/images', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
            
            if (response.ok) {
                const data = await response.json();
                return data.images || [];
            } else {
                console.error('Failed to load images');
                return [];
            }
        } catch (error) {
            console.error('Error loading images:', error);
            return [];
        }
    }

    selectImage(imageName) {
        this.currentImage = imageName;
        
        // Update hidden input
        const imageInput = document.getElementById(this.options.headerImageInputId);
        if (imageInput) {
            imageInput.value = "/img/article/" + imageName;
        }

        // Update display
        this.updateCurrentImageDisplay(imageName);
        
        // Close modal
        this.closeImageModal();
        
        // Remove previous selection
        document.querySelectorAll(".header-image-option").forEach((option) => {
            option.classList.remove("border-blue-500", "bg-blue-50");
            option.classList.add("border-gray-300");
        });

        // Add selection to clicked image
        const selectedOption = document.querySelector(`[data-image="${imageName}"]`);
        if (selectedOption) {
            selectedOption.classList.remove("border-gray-300");
            selectedOption.classList.add("border-blue-500", "bg-blue-50");
        }
    }

    updateCurrentImageDisplay(imageName) {
        const display = document.getElementById(this.options.currentImageDisplayId);
        if (display) {
            display.innerHTML = `
                <div class="flex items-center gap-4 p-4 border border-gray-300 rounded-lg bg-gray-50">
                    <img src="/img/article/${imageName}" alt="Selected header image" class="w-20 h-20 object-cover rounded border">
                    <div>
                        <p class="text-sm text-gray-600">Kiválasztott header kép:</p>
                        <p class="text-sm font-medium">${imageName}</p>
                        <button type="button" onclick="window.headerManagement.removeCurrentImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                            Kép eltávolítása
                        </button>
                    </div>
                </div>
            `;
        }
    }

    removeCurrentImage() {
        this.currentImage = null;
        
        const imageInput = document.getElementById(this.options.headerImageInputId);
        const currentImageDisplay = document.getElementById(this.options.currentImageDisplayId);

        if (imageInput) imageInput.value = "";
        if (currentImageDisplay) currentImageDisplay.innerHTML = "";

        // Remove selection from all options
        document.querySelectorAll(".header-image-option").forEach((option) => {
            option.classList.remove("border-blue-500", "bg-blue-50");
            option.classList.add("border-gray-300");
        });
    }

    loadCurrentHeader() {
        // This would typically load existing header data via AJAX
        // For now, we'll leave it empty - data will be loaded from the server
    }

    saveHeader() {
        const title = document.getElementById(this.options.headerTitleId)?.value;
        const subtitle = document.getElementById(this.options.headerSubtitleId)?.value;
        const imageUrl = document.getElementById(this.options.headerImageInputId)?.value;

        if (!title || !subtitle || !imageUrl) {
            Swal.fire({
                title: 'Hiányzó adatok!',
                text: 'Kérjük, töltse ki az összes mezőt!',
                icon: 'warning',
                confirmButtonText: 'Rendben'
            });
            return;
        }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('subtitle', subtitle);
        formData.append('image_url', imageUrl);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Determine article_id based on current page
        let articleId = 0; // Default for onboarding
        const currentPath = window.location.pathname;
        if (currentPath.includes('/dashboard-articles/') && !currentPath.includes('/dashboard-onboarding')) {
            // Extract article slug from URL and find article_id
            const slugMatch = currentPath.match(/\/dashboard-articles\/([^\/]+)/);
            if (slugMatch) {
                // For articles, we need to get the article_id from the page data
                // This could be passed from the server or extracted from a data attribute
                const articleElement = document.querySelector('[data-article-id]');
                if (articleElement) {
                    articleId = articleElement.getAttribute('data-article-id');
                }
            }
        }
        formData.append('article_id', articleId);

        // Show loading state
        const saveButton = document.getElementById(this.options.saveButtonId);
        if (saveButton) {
            saveButton.disabled = true;
            saveButton.textContent = 'Mentés...';
        }

        // Show loading SweetAlert
        Swal.fire({
            title: 'Mentés...',
            text: 'Header mentése folyamatban...',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        fetch('/dashboard/header/save', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            Swal.close(); // Close loading alert
            
            if (data.success) {
                Swal.fire({
                    title: 'Sikeres!',
                    text: data.message || 'Header sikeresen mentve!',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            } else {
                Swal.fire({
                    title: 'Hiba!',
                    text: data.message || 'Ismeretlen hiba történt',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.close(); // Close loading alert
            
            Swal.fire({
                title: 'Hiba!',
                text: 'Hálózati hiba történt. Kérjük próbálja újra.',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
            });
        })
        .finally(() => {
            // Reset button state
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.textContent = 'Header mentése';
            }
        });
    }
}

// Global functions for backward compatibility
window.removeCurrentHeaderImage = function () {
    if (window.headerManagement) {
        window.headerManagement.removeCurrentImage();
    }
};

// Initialize function
window.initializeHeaderManagement = function (options = {}) {
    window.headerManagement = new HeaderManagement(options);
};
