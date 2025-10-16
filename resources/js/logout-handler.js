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
                    // Make AJAX request to logout immediately
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
                        // Immediately redirect to onboarding without waiting for toast
                        // Use replace to prevent back button from showing cached article page
                        // Add timestamp to prevent any caching issues
                        const timestamp = Date.now();
                        window.location.replace(`/onboarding?t=${timestamp}`);
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        // Even on error, redirect to onboarding
                        const timestamp = Date.now();
                        window.location.replace(`/onboarding?t=${timestamp}`);
                    });
                }
            });
        });
    }
});
