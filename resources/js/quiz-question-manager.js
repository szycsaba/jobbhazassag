document.addEventListener('DOMContentLoaded', function() {
    const optionsContainer = document.getElementById('options-container');
    const addOptionBtn = document.getElementById('add-option');
    
    if (!optionsContainer || !addOptionBtn) {
        return; // Exit if elements don't exist
    }

    let optionCount = optionsContainer.children.length;

    // Add new option
    addOptionBtn.addEventListener('click', function() {
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row mb-2 flex items-center gap-2';
        optionRow.innerHTML = `
            <input type="radio" name="correct_option" value="${optionCount}" class="correct-option" required>
            <input type="text" name="options[${optionCount}][option_text]" class="flex-1 border-2 rounded p-2" placeholder="Válaszlehetőség ${optionCount + 1}" required>
            <input type="hidden" name="options[${optionCount}][is_correct]" value="false">
            <button type="button" class="remove-option px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Törlés</button>
        `;
        optionsContainer.appendChild(optionRow);
        optionCount++;
    });

    // Remove option
    optionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            const optionRow = e.target.closest('.option-row');
            const remainingOptions = optionsContainer.querySelectorAll('.option-row');
            
            // Don't allow removing if only 2 options remain
            if (remainingOptions.length <= 2) {
                alert('Legalább 2 válaszlehetőség szükséges!');
                return;
            }
            
            optionRow.remove();
            
            // Reindex remaining options
            reindexOptions();
        }
    });

    // Update correct option when radio button changes
    optionsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('correct-option')) {
            updateCorrectOptions();
        }
    });

    // Initialize correct options on page load
    updateCorrectOptions();

    function updateCorrectOptions() {
        // Reset all is_correct values
        document.querySelectorAll('input[name*="[is_correct]"]').forEach(input => {
            input.value = 'false';
        });
        
        // Set the selected option as correct
        const selectedRadio = document.querySelector('input[name="correct_option"]:checked');
        if (selectedRadio) {
            const selectedValue = selectedRadio.value;
            const selectedOption = document.querySelector(`input[name="options[${selectedValue}][is_correct]"]`);
            if (selectedOption) {
                selectedOption.value = 'true';
            }
        }
    }

    function reindexOptions() {
        const optionRows = optionsContainer.querySelectorAll('.option-row');
        optionRows.forEach((row, index) => {
            // Update radio button value
            const radio = row.querySelector('.correct-option');
            if (radio) {
                radio.value = index;
            }
            
            // Update input names
            const textInput = row.querySelector('input[type="text"]');
            if (textInput) {
                textInput.name = `options[${index}][option_text]`;
                textInput.placeholder = `Válaszlehetőség ${index + 1}`;
            }
            
            // Update hidden input name
            const hiddenInput = row.querySelector('input[type="hidden"]');
            if (hiddenInput) {
                hiddenInput.name = `options[${index}][is_correct]`;
            }
        });
        
        optionCount = optionRows.length;
    }
});
