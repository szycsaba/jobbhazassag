// Universal notification handler with SweetAlert2 for all CRUD operations
document.addEventListener("DOMContentLoaded", function () {
    // Handle all CRUD forms with data-crud attribute
    const crudForms = document.querySelectorAll("form[data-crud]");

    crudForms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = this.action;
            const method = formData.get("_method") || "POST";
            const operation = this.getAttribute("data-crud"); // create, read, update, delete
            const confirmRequired =
                this.getAttribute("data-confirm") === "true";
            const confirmTitle =
                this.getAttribute("data-confirm-title") ||
                "Biztosan folytatni szeretnéd?";
            const confirmText =
                this.getAttribute("data-confirm-text") ||
                "Ez a művelet nem vonható vissza!";
            const loadingTitle =
                this.getAttribute("data-loading-title") || "Feldolgozás...";
            const loadingText =
                this.getAttribute("data-loading-text") || "Kérjük várjon...";
            const successTitle =
                this.getAttribute("data-success-title") || "Sikeres!";
            const errorTitle = this.getAttribute("data-error-title") || "Hiba!";
            const redirectAfterSuccess = this.getAttribute("data-redirect");
            const removeElement = this.getAttribute("data-remove-element");
            const reloadAfterSuccess =
                this.getAttribute("data-reload") === "true";

            // Function to show loading toast
            const showLoading = () => {
                Swal.fire({
                    title: loadingTitle,
                    text: loadingText,
                    icon: "info",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            };

            // Function to show success toast
            const showSuccess = (message) => {
                Swal.fire({
                    title: successTitle,
                    text: message,
                    icon: "success",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                }).then(() => {
                    // Handle post-success actions
                    if (redirectAfterSuccess) {
                        window.location.href = redirectAfterSuccess;
                    } else if (removeElement) {
                        const elementToRemove =
                            document.querySelector(removeElement);
                        if (elementToRemove) {
                            elementToRemove.remove();
                        }
                    } else if (reloadAfterSuccess) {
                        // Force a hard reload to ensure fresh data
                        window.location.reload(true);
                    }
                });
            };

            // Function to show error toast
            const showError = (message) => {
                Swal.fire({
                    title: errorTitle,
                    text: message,
                    icon: "error",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                });
            };

            // Function to make AJAX request
            const makeRequest = () => {
                showLoading();

                fetch(url, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        Swal.close();

                        if (data.success) {
                            showSuccess(data.message);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch((error) => {
                        Swal.close();
                        showError(
                            "Hálózati hiba történt. Kérjük próbálja újra."
                        );
                    });
            };

            // Check if confirmation is required
            if (confirmRequired) {
                Swal.fire({
                    title: confirmTitle,
                    text: confirmText,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Igen, folytatás!",
                    cancelButtonText: "Mégse",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        makeRequest();
                    }
                });
            } else {
                makeRequest();
            }
        });
    });
});

// Demo limit handler for article creation
document.addEventListener("DOMContentLoaded", function () {
    const limit = 5;
    const message = 'Ez a weboldal jelenleg trial módban működik, kizárólag bemutatási és tesztelési célból. A teljes funkcionalitás és az adminisztrációs lehetőségek a hátralék rendezése után aktiválódnak. Köszönjük a megértést és az együttműködést.';

    function showWarning() {
        Swal.fire({
            icon: 'warning',
            title: 'Figyelmeztetés',
            text: message,
            confirmButtonText: 'Értettem'
        });
    }

    function wireButton(button) {
        if (!button) return;
        const demo = (button.getAttribute('data-demo') || 'off').toLowerCase();
        const articleCount = parseInt(button.getAttribute('data-article-count') || '0', 10);

        button.addEventListener('click', function (e) {
            if (demo === 'on' && articleCount >= limit) {
                e.preventDefault();
                showWarning();
            }
        });
    }

    wireButton(document.getElementById('create-article-top'));
    wireButton(document.getElementById('create-article-bottom'));

    const demoLimitReached = document.querySelector('meta[name="demo-limit-reached"]');
    if (demoLimitReached && demoLimitReached.getAttribute('content') === 'true') {
        showWarning();
    }
});
