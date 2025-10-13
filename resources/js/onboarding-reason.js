// Interactive reason functionality for onboarding yellow blocks
document.addEventListener('DOMContentLoaded', function() {
    // Find all textareas with id="reason" in yellow blocks
    const reasonTextareas = document.querySelectorAll('#reason');
    
    reasonTextareas.forEach(function(textarea) {
        const saveBtn = textarea.parentNode.querySelector('button');
        
        // Check if this is a Google user (we'll need to make an AJAX call to check)
        checkUserStatus().then(function(userStatus) {
            updateFormState(textarea, saveBtn, userStatus);
            
            // Add click event to save button
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    saveReason(textarea, saveBtn);
                });
            }
        });
    });
    
    function checkUserStatus() {
        return fetch(window.location.origin + '/onboarding/save-reason', {
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
                hasReason: data.has_reason || false,
                existingContent: data.existing_content || ''
            };
        })
        .catch(error => {
            console.error('Error checking user status:', error);
            return {
                isGoogleUser: false,
                hasReason: false,
                existingContent: ''
            };
        });
    }
    
    function updateFormState(textarea, saveBtn, userStatus) {
        if (!userStatus.isGoogleUser) {
            // Not logged in with Google
            textarea.placeholder = 'Csak regisztrált tagok tölthetik ki. Kérlek regisztrálj!';
            textarea.readOnly = true;
            textarea.classList.add('opacity-50', 'cursor-not-allowed');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.classList.add('opacity-50', 'cursor-not-allowed', 'hover:scale-100');
                saveBtn.classList.remove('hover:scale-105');
            }
        } else if (userStatus.hasReason) {
            // Already submitted - allow editing
            textarea.placeholder = 'Szerkesztheted a válaszod bármikor...';
            textarea.value = userStatus.existingContent;
            textarea.readOnly = false;
            textarea.classList.remove('opacity-50', 'cursor-not-allowed');
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'hover:scale-100');
                saveBtn.classList.add('hover:scale-105');
                saveBtn.textContent = 'Válasz frissítése';
            }
        } else {
            // Ready to submit
            textarea.placeholder = 'Írd le miért csatlakoztál a programhoz!...';
            textarea.readOnly = false;
            textarea.classList.remove('opacity-50', 'cursor-not-allowed');
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'hover:scale-100');
                saveBtn.classList.add('hover:scale-105');
            }
        }
    }
    
    function saveReason(textarea, saveBtn) {
        const content = textarea.value.trim();
        
        if (!content) {
            Swal.fire({
                title: 'Hiba!',
                text: 'Kérjük, írja le miért csatlakozott a programhoz!',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            return;
        }
        
        // Show loading
        Swal.fire({
            title: 'Mentés...',
            text: 'Válaszod mentése...',
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
        
        // Make AJAX request
        fetch(window.location.origin + '/onboarding/save-reason', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                content: content
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
                
                // Keep textarea and button enabled for future edits
                // Users can now modify their reason anytime
                if (saveBtn.textContent === 'Mentés') {
                    // First time submission - update button text
                    saveBtn.textContent = 'Válasz frissítése';
                }
                
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
