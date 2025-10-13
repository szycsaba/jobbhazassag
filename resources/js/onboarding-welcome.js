document.addEventListener('DOMContentLoaded', function() {
    // Check if this is the first visit to onboarding page
    const hasSeenWelcome = sessionStorage.getItem('onboarding_welcome_shown');
    
    if (!hasSeenWelcome) {
        // Show welcome popup
        Swal.fire({
            title: 'Üdvözölünk!',
            text: 'Kösz, hogy belevágtál.❤️ A következő 5 hétben abban szeretnénk segíteni neked, hogy kicsit jobban megismerd önmagad és a kapcsolatotok működését.',
            icon: 'success',
            confirmButtonText: 'Kezdjük!',
            confirmButtonColor: '#326e6c',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCloseButton: false,
            customClass: {
                popup: 'onboarding-welcome-popup',
                title: 'onboarding-welcome-title',
                content: 'onboarding-welcome-content'
            }
        }).then(() => {
            // Mark as seen in session storage
            sessionStorage.setItem('onboarding_welcome_shown', 'true');
        });
    }
});
