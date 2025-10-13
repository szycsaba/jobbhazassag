document.addEventListener('DOMContentLoaded', function () {
    // Function to check if user is logged in
    function isUserLoggedIn() {
        // Check if there's a meta tag indicating auth status
        const authMeta = document.querySelector('meta[name="user-auth"]');
        if (authMeta && authMeta.getAttribute('content') === 'true') {
            return true;
        }
        
        // Check for common logged-in indicators
        const dashboardLinks = document.querySelectorAll('a[href*="dashboard"]');
        if (dashboardLinks.length > 0) {
            return true;
        }
        
        return false;
    }

    // Function to disable signin/registration links
    function disableSigninLinks() {
        // Look for the specific signin link with id="signin"
        const signinLink = document.getElementById('signin');
        if (signinLink) {
            disableLink(signinLink);
        }

        // Also look for other common signin/registration patterns
        const signinSelectors = [
            'a[href*="register"]',
            'a[href*="signin"]',
            'a[href*="login"]',
            'a[href*="auth/google"]',
            'button[onclick*="signin"]',
            'button[onclick*="register"]',
            '.signin-link',
            '.register-link',
            '.signup-link'
        ];

        signinSelectors.forEach(selector => {
            const links = document.querySelectorAll(selector);
            links.forEach(link => {
                // Only disable if the link text suggests it's for registration/signin
                const linkText = link.textContent.toLowerCase();
                if (linkText.includes('regisztrálok') || 
                    linkText.includes('signin') || 
                    linkText.includes('sign up') ||
                    linkText.includes('register') ||
                    linkText.includes('bejelentkezés')) {
                    disableLink(link);
                }
            });
        });
    }

    // Function to disable a specific link
    function disableLink(link) {
        if (!link) return;

        // Remove href attribute or make it non-functional
        if (link.tagName === 'A') {
            link.removeAttribute('href');
        }
        
        // Remove onclick handlers
        link.removeAttribute('onclick');
        
        // Add disabled styling
        link.style.opacity = '0.5';
        link.style.cursor = 'not-allowed';
        link.style.pointerEvents = 'none';
        
        // Add a disabled class for additional styling
        link.classList.add('disabled-signin-link');
        
        // Optionally change the text to indicate it's disabled
        const originalText = link.textContent;
        if (!link.dataset.originalText) {
            link.dataset.originalText = originalText;
        }
        
        // Add a tooltip or modify text to show it's disabled
        link.title = 'Már be vagy jelentkezve';
        
        // Prevent any click events
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    }

    // Run the check when page loads
    if (isUserLoggedIn()) {
        disableSigninLinks();
    }

    // Also run it after a short delay to catch dynamically loaded content
    setTimeout(() => {
        if (isUserLoggedIn()) {
            disableSigninLinks();
        }
    }, 1000);
});
