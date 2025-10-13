/**
 * Article Form Handler
 * Handles common functionality for article create and edit forms
 */
class ArticleFormHandler {
    constructor(options = {}) {
        this.options = {
            typeSelectId: "type_id",
            textContentFieldId: "text-content-field",
            imageContentFieldId: "image-content-field",
            contentFieldId: "content",
            imageContentInputId: "image-content-input",
            currentImageDisplayId: "current-image-display",
            colorPickerSectionId: "color-picker-section",
            backgroundColorId: "background-color",
            backgroundColorTextId: "background-color-text",
            resetBackgroundId: "reset-background",
            textColorId: "text-color",
            textColorTextId: "text-color-text",
            resetTextId: "reset-text",
            reflectionDropdownSectionId: "reflection-dropdown-section",
            reflectionSelectId: "reflection_id",
            quizDropdownSectionId: "quiz-dropdown-section",
            quizSelectId: "quiz_id",
            ...options,
        };

        this.currentTypeId = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeOnLoad();
    }

    bindEvents() {
        // Type selection change
        const typeSelect = document.getElementById(this.options.typeSelectId);
        if (typeSelect) {
            typeSelect.addEventListener("change", () => {
                this.toggleContentField();
                this.toggleColorPicker();
                this.toggleReflectionDropdown();
                this.toggleQuizDropdown();
            });
        }

        // Image selection events
        document.addEventListener("click", (e) => {
            if (e.target.closest(".image-option")) {
                const imageName =
                    e.target.closest(".image-option").dataset.image;
                if (imageName) {
                    this.selectImage(imageName);
                }
            }
        });


        // Color picker events
        this.bindColorPickerEvents();
    }

    initializeOnLoad() {
        // Initialize on page load
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => {
                this.toggleContentField();
                this.toggleColorPicker();
                this.toggleReflectionDropdown();
                this.toggleQuizDropdown();
            });
        } else {
            this.toggleContentField();
            this.toggleColorPicker();
            this.toggleReflectionDropdown();
            this.toggleQuizDropdown();
            this.toggleHeaderSection();
        }
    }

    toggleContentField() {
        const typeSelect = document.getElementById(this.options.typeSelectId);
        if (!typeSelect) return;

        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const typeName = selectedOption.text.toLowerCase();
        const typeNameAttribute = selectedOption.getAttribute("data-type-name");

        const textField = document.getElementById(
            this.options.textContentFieldId
        );
        const imageField = document.getElementById(
            this.options.imageContentFieldId
        );
        const textarea = document.getElementById(this.options.contentFieldId);
        const imageInput = document.getElementById(
            this.options.imageContentInputId
        );

        if (typeName === "image") {
            if (textField) textField.style.display = "none";
            if (imageField) imageField.style.display = "block";
            if (textarea) textarea.disabled = true;
            if (imageInput) imageInput.disabled = false;
        } else if (typeNameAttribute === "self-awareness") {
            // Hide both text and image fields for self-awareness
            if (textField) textField.style.display = "none";
            if (imageField) imageField.style.display = "none";
            if (textarea) textarea.disabled = true;
            if (imageInput) imageInput.disabled = true;
        } else if (typeNameAttribute === "quiz") {
            // Hide both text and image fields for quiz
            if (textField) textField.style.display = "none";
            if (imageField) imageField.style.display = "none";
            if (textarea) textarea.disabled = true;
            if (imageInput) imageInput.disabled = true;
        } else {
            if (textField) textField.style.display = "block";
            if (imageField) imageField.style.display = "none";
            if (textarea) textarea.disabled = false;
            if (imageInput) imageInput.disabled = true;
        }
    }

    selectImage(imageName) {
        // Remove previous selection
        document.querySelectorAll(".image-option").forEach((option) => {
            option.classList.remove("border-blue-500", "bg-blue-50");
            option.classList.add("border-gray-300");
        });

        // Add selection to clicked image
        const selectedOption = document.querySelector(
            `[data-image="${imageName}"]`
        );
        if (selectedOption) {
            selectedOption.classList.remove("border-gray-300");
            selectedOption.classList.add("border-blue-500", "bg-blue-50");
        }

        // Update hidden input
        const imageInput = document.getElementById(
            this.options.imageContentInputId
        );
        if (imageInput) {
            imageInput.value = "/img/article/" + imageName;
        }

        // Update current image display
        this.updateCurrentImageDisplay(imageName);
    }

    removeCurrentImage() {
        const imageInput = document.getElementById(
            this.options.imageContentInputId
        );
        const currentImageDisplay = document.getElementById(
            this.options.currentImageDisplayId
        );

        if (imageInput) imageInput.value = "";
        if (currentImageDisplay) currentImageDisplay.innerHTML = "";

        // Remove selection from all options
        document.querySelectorAll(".image-option").forEach((option) => {
            option.classList.remove("border-blue-500", "bg-blue-50");
            option.classList.add("border-gray-300");
        });
    }

    updateCurrentImageDisplay(imageName) {
        const display = document.getElementById(
            this.options.currentImageDisplayId
        );
        if (display) {
            display.innerHTML = `
                <div class="flex items-center gap-4 p-4 border border-gray-300 rounded-lg bg-gray-50">
                    <img src="/img/article/${imageName}" alt="Selected image" class="w-20 h-20 object-cover rounded border">
                    <div>
                        <p class="text-sm text-gray-600">Kiválasztott kép:</p>
                        <p class="text-sm font-medium">${imageName}</p>
                        <button type="button" onclick="window.articleFormHandler.removeCurrentImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                            Kép eltávolítása
                        </button>
                    </div>
                </div>
            `;
        }
    }

    // Color picker methods
    bindColorPickerEvents() {
        const backgroundColorInput = document.getElementById(
            this.options.backgroundColorId
        );
        const backgroundColorTextInput = document.getElementById(
            this.options.backgroundColorTextId
        );
        const resetBackgroundButton = document.getElementById(
            this.options.resetBackgroundId
        );
        const textColorInput = document.getElementById(
            this.options.textColorId
        );
        const textColorTextInput = document.getElementById(
            this.options.textColorTextId
        );
        const resetTextButton = document.getElementById(
            this.options.resetTextId
        );

        if (backgroundColorInput) {
            backgroundColorInput.addEventListener("change", (e) => {
                this.updateBackgroundColorText(e.target.value);
            });
        }

        if (backgroundColorTextInput) {
            backgroundColorTextInput.addEventListener("input", (e) => {
                this.updateBackgroundColorPicker(e.target.value);
            });
        }

        if (resetBackgroundButton) {
            resetBackgroundButton.addEventListener("click", () => {
                this.resetBackgroundColor();
            });
        }

        if (textColorInput) {
            textColorInput.addEventListener("change", (e) => {
                this.updateTextColorText(e.target.value);
            });
        }

        if (textColorTextInput) {
            textColorTextInput.addEventListener("input", (e) => {
                this.updateTextColorPicker(e.target.value);
            });
        }

        if (resetTextButton) {
            resetTextButton.addEventListener("click", () => {
                this.resetTextColor();
            });
        }
    }

    toggleColorPicker() {
        const typeSelect = document.getElementById(this.options.typeSelectId);
        const colorPickerSection = document.getElementById(
            this.options.colorPickerSectionId
        );

        if (!typeSelect || !colorPickerSection) return;

        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const hasAttributes =
            selectedOption.getAttribute("data-has-attributes") === "true";
        const typeId = selectedOption.value;

        this.currentTypeId = typeId;

        if (hasAttributes && typeId) {
            colorPickerSection.classList.remove("hidden");
            this.loadTypeAttributes(typeId);
        } else {
            colorPickerSection.classList.add("hidden");
        }
    }

    loadTypeAttributes(typeId) {
        if (!window.typeAttributesData) return;

        const typeData = window.typeAttributesData.find(
            (item) => item.id == typeId
        );
        if (!typeData || !typeData.attributes) return;

        const attributes = typeData.attributes;
        const backgroundColorInput = document.getElementById(
            this.options.backgroundColorId
        );
        const backgroundColorTextInput = document.getElementById(
            this.options.backgroundColorTextId
        );
        const textColorInput = document.getElementById(
            this.options.textColorId
        );
        const textColorTextInput = document.getElementById(
            this.options.textColorTextId
        );

        if (backgroundColorInput && backgroundColorTextInput) {
            const currentBackground =
                attributes.background ||
                attributes.default_background ||
                "#ffffff";
            backgroundColorInput.value = currentBackground;
            backgroundColorTextInput.value = currentBackground;
        }

        if (textColorInput && textColorTextInput) {
            const currentText =
                attributes.text || attributes.default_text || "#000000";
            textColorInput.value = currentText;
            textColorTextInput.value = currentText;
        }
    }

    updateBackgroundColorText(colorValue) {
        const backgroundColorTextInput = document.getElementById(
            this.options.backgroundColorTextId
        );
        if (backgroundColorTextInput) {
            backgroundColorTextInput.value = colorValue;
        }
        this.updateTypeAttribute("background", colorValue);
    }

    updateBackgroundColorPicker(textValue) {
        const backgroundColorInput = document.getElementById(
            this.options.backgroundColorId
        );
        if (backgroundColorInput && this.isValidColor(textValue)) {
            backgroundColorInput.value = textValue;
        }
        this.updateTypeAttribute("background", textValue);
    }

    resetBackgroundColor() {
        if (!this.currentTypeId || !window.typeAttributesData) return;

        const typeData = window.typeAttributesData.find(
            (item) => item.id == this.currentTypeId
        );
        if (!typeData || !typeData.attributes) return;

        const attributes = typeData.attributes;
        const defaultBackground = attributes.default_background || "#ffffff";

        const backgroundColorInput = document.getElementById(
            this.options.backgroundColorId
        );
        const backgroundColorTextInput = document.getElementById(
            this.options.backgroundColorTextId
        );

        if (backgroundColorInput)
            backgroundColorInput.value = defaultBackground;
        if (backgroundColorTextInput)
            backgroundColorTextInput.value = defaultBackground;

        this.updateTypeAttribute("background", defaultBackground);
    }

    updateTextColorText(colorValue) {
        const textColorTextInput = document.getElementById(
            this.options.textColorTextId
        );
        if (textColorTextInput) {
            textColorTextInput.value = colorValue;
        }
        this.updateTypeAttribute("text", colorValue);
    }

    updateTextColorPicker(textValue) {
        const textColorInput = document.getElementById(
            this.options.textColorId
        );
        if (textColorInput && this.isValidColor(textValue)) {
            textColorInput.value = textValue;
        }
        this.updateTypeAttribute("text", textValue);
    }

    resetTextColor() {
        if (!this.currentTypeId || !window.typeAttributesData) return;

        const typeData = window.typeAttributesData.find(
            (item) => item.id == this.currentTypeId
        );
        if (!typeData || !typeData.attributes) return;

        const attributes = typeData.attributes;
        const defaultText = attributes.default_text || "#000000";

        const textColorInput = document.getElementById(
            this.options.textColorId
        );
        const textColorTextInput = document.getElementById(
            this.options.textColorTextId
        );

        if (textColorInput) textColorInput.value = defaultText;
        if (textColorTextInput) textColorTextInput.value = defaultText;

        this.updateTypeAttribute("text", defaultText);
    }

    updateTypeAttribute(attribute, value) {
        if (!this.currentTypeId || !window.typeAttributesData) return;

        // Update the local data
        const typeData = window.typeAttributesData.find(
            (item) => item.id == this.currentTypeId
        );
        if (typeData && typeData.attributes) {
            typeData.attributes[attribute] = value;
        }

        // Save to database via AJAX
        this.saveTypeAttribute(attribute, value);
    }

    saveTypeAttribute(attribute, value) {
        if (!this.currentTypeId || !window.typeAttributesData) return;

        // Get current values for both attributes
        const typeData = window.typeAttributesData.find(
            (item) => item.id == this.currentTypeId
        );
        if (!typeData || !typeData.attributes) return;

        const currentAttributes = typeData.attributes;

        // Prepare both background and text values
        const backgroundValue =
            attribute === "background"
                ? value
                : currentAttributes.background || "";
        const textValue =
            attribute === "text" ? value : currentAttributes.text || "";

        const formData = new FormData();
        formData.append("article_type_id", this.currentTypeId);
        formData.append("background", backgroundValue);
        formData.append("text", textValue);
        formData.append(
            "_token",
            document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content")
        );

        fetch("/dashboard-article-type-colors/update", {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    console.log(
                        `Successfully updated ${attribute} to ${value}`
                    );
                } else {
                    console.error("Error updating color:", data.message);
                }
            })
            .catch((error) => {
                console.error("Error updating color:", error);
            });
    }

    isValidColor(color) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color);
    }

    toggleReflectionDropdown() {
        const typeSelect = document.getElementById(this.options.typeSelectId);
        const reflectionSection = document.getElementById(
            this.options.reflectionDropdownSectionId
        );
        const reflectionSelect = document.getElementById(
            this.options.reflectionSelectId
        );

        if (!typeSelect || !reflectionSection || !reflectionSelect) return;

        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const typeName = selectedOption.getAttribute("data-type-name");

        if (typeName === "self-awareness") {
            reflectionSection.classList.remove("hidden");
            reflectionSelect.required = true;
        } else {
            reflectionSection.classList.add("hidden");
            reflectionSelect.required = false;
            reflectionSelect.value = ""; // Clear selection
        }
    }

    toggleQuizDropdown() {
        const typeSelect = document.getElementById(this.options.typeSelectId);
        const quizSection = document.getElementById(
            this.options.quizDropdownSectionId
        );
        const quizSelect = document.getElementById(this.options.quizSelectId);

        if (!typeSelect || !quizSection || !quizSelect) return;

        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const typeName = selectedOption.getAttribute("data-type-name");

        if (typeName === "quiz") {
            quizSection.classList.remove("hidden");
            quizSelect.required = true;
        } else {
            quizSection.classList.add("hidden");
            quizSelect.required = false;
            quizSelect.value = ""; // Clear selection
        }
    }

}

// Global functions for backward compatibility
window.toggleContentField = function () {
    if (window.articleFormHandler) {
        window.articleFormHandler.toggleContentField();
    }
};

window.selectImage = function (imageName) {
    if (window.articleFormHandler) {
        window.articleFormHandler.selectImage(imageName);
    }
};

window.removeCurrentImage = function () {
    if (window.articleFormHandler) {
        window.articleFormHandler.removeCurrentImage();
    }
};


// Initialize function
window.initializeArticleForm = function (options = {}) {
    window.articleFormHandler = new ArticleFormHandler(options);
};
