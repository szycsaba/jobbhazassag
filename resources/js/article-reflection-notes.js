// Interactive reflection notes functionality for article self-awareness blocks
document.addEventListener('DOMContentLoaded', function() {
    // Find all reflection textareas and save buttons
    const reflectionTextareas = document.querySelectorAll('.inpArea[placeholder*="Gépelhetsz ide"]');
    const saveButtons = document.querySelectorAll('.btn-mini-ref');
    
    if (reflectionTextareas.length === 0 || saveButtons.length === 0) {
        return; // No reflection blocks found
    }
    
    const articleSlug = getArticleSlugFromUrl();
    if (!articleSlug) {
        console.error('Could not determine article slug from URL');
        return;
    }
    
    // Check user status and update form state
    checkUserStatus().then(function(userStatus) {
        updateReflectionFormState(reflectionTextareas, saveButtons, userStatus);
        
        // Add click event to save buttons
        saveButtons.forEach(function(saveBtn) {
            saveBtn.addEventListener('click', function() {
                saveReflectionNotes(reflectionTextareas, saveBtn);
            });
        });
    });
    
    function getArticleSlugFromUrl() {
        const path = window.location.pathname;
        const match = path.match(/\/article\/([^\/]+)/);
        return match ? match[1] : null;
    }
    
    function checkUserStatus() {
        return fetch(window.location.origin + '/article/' + articleSlug + '/save-reflection-notes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                check_status: true // Special flag to just check status
            }),
        })
        .then(response => response.json())
        .then(data => {
            return {
                isGoogleUser: data.is_google_user || false,
                hasNotes: data.has_notes || false,
                existingNotes: data.existing_notes || {}
            };
        })
        .catch(error => {
            console.error('Error checking user status:', error);
            return {
                isGoogleUser: false,
                hasNotes: false,
                existingNotes: {}
            };
        });
    }
    
    function updateReflectionFormState(textareas, saveButtons, userStatus) {
        if (!userStatus.isGoogleUser) {
            // Not logged in with Google
            textareas.forEach(function(textarea) {
                textarea.placeholder = 'Csak regisztrált tagok tölthetik ki. Kérlek regisztrálj!';
                textarea.readOnly = true;
                textarea.classList.add('opacity-50', 'cursor-not-allowed');
            });
            saveButtons.forEach(function(btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'hover:scale-100');
                btn.classList.remove('hover:scale-105');
            });
        } else if (userStatus.hasNotes) {
            // Already submitted - allow editing
            textareas.forEach(function(textarea) {
                // Try to find the question ID from the textarea's context
                const questionId = findQuestionIdForTextarea(textarea);
                if (questionId && userStatus.existingNotes[questionId]) {
                    textarea.value = userStatus.existingNotes[questionId];
                }
                textarea.placeholder = 'Szerkesztheted a válaszaid bármikor...';
                textarea.readOnly = false;
                textarea.classList.remove('opacity-50', 'cursor-not-allowed');
            });
            saveButtons.forEach(function(btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed', 'hover:scale-100');
                btn.classList.add('hover:scale-105');
                btn.textContent = 'Frissítés';
            });
        } else {
            // Ready to submit
            textareas.forEach(function(textarea) {
                textarea.placeholder = 'Gépelhetsz ide...';
                textarea.readOnly = false;
                textarea.classList.remove('opacity-50', 'cursor-not-allowed');
            });
            saveButtons.forEach(function(btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed', 'hover:scale-100');
                btn.classList.add('hover:scale-105');
            });
        }
    }
    
    function findQuestionIdForTextarea(textarea) {
        // Try to find the question ID from the textarea's attributes
        if (textarea.hasAttribute('data-question-id')) {
            return textarea.getAttribute('data-question-id');
        }
        return null;
    }
    
    function saveReflectionNotes(textareas, saveBtn) {
        const notes = {};
        
        // Collect all textarea values (empty values are allowed)
        textareas.forEach(function(textarea) {
            const questionId = findQuestionIdForTextarea(textarea);
            const content = textarea.value.trim();
            
            if (questionId) {
                notes[questionId] = content; // Allow empty values
            }
        });
        
        // Show loading
        Swal.fire({
            title: 'Mentés...',
            text: 'Válaszaid mentése...',
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
        
        // Make AJAX request
        fetch(window.location.origin + '/article/' + articleSlug + '/save-reflection-notes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                notes: notes
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success
                Swal.fire({
                    title: 'Sikeres!',
                    text: data.message,
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                
                // Keep textareas and buttons enabled for future edits
                // Users can now modify their reflection notes anytime
                saveButtons.forEach(function(btn) {
                    if (btn.textContent === 'Mentés') {
                        // First time submission - update button text
                        btn.textContent = 'Frissítés';
                    }
                });
                
            } else {
                // Error
                Swal.fire({
                    title: 'Hiba!',
                    text: data.message,
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Hiba!',
                text: 'Hálózati hiba történt. Kérjük próbálja újra.',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        });
    }
});
