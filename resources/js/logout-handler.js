document.addEventListener('DOMContentLoaded', function() {
    // Find the logout link
    const logoutLink = document.querySelector('a[href="#logout"]');
    
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Kijelentkezés',
                text: 'Biztosan ki szeretne jelentkezni?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Igen, kijelentkezés',
                cancelButtonText: 'Mégse',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading toast
                    Swal.fire({
                        title: 'Kijelentkezés...',
                        text: 'Kiszerelés folyamatban...',
                        icon: 'info',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        allowOutsideClick: false
                    });
                    
                    // Make AJAX request to logout
                    fetch('/auth/google/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Show success toast
                        Swal.fire({
                            title: 'Sikeres kijelentkezés!',
                            text: 'Sikeresen kijelentkezett a fiókjából.',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            // Redirect to onboarding after toast disappears
                            window.location.href = '/onboarding';
                        });
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        // Show error toast
                        Swal.fire({
                            title: 'Hiba!',
                            text: 'Hiba történt a kijelentkezés során. Kérjük, próbálja újra.',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                }
            });
        });
    }
});
