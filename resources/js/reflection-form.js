class ReflectionFormHandler {
    constructor(formId = "reflection-create-form") {
        this.form = document.getElementById(formId);
        if (!this.form) return;
        this.bindSubmit();
    }

    bindSubmit() {
        this.form.addEventListener("submit", (e) => {
            e.preventDefault();
            this.submit();
        });
    }

    async submit() {
        const submitButtons = this.form.querySelectorAll(
            'button[type="submit"]'
        );
        submitButtons.forEach((btn) => (btn.disabled = true));

        try {
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: formData,
            });

            const data = await response.json();
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                this.showError(data.message || "Ismeretlen hiba történt.");
            }
        } catch (err) {
            this.showError("Hálózati hiba történt.");
            console.error(err);
        } finally {
            submitButtons.forEach((btn) => (btn.disabled = false));
        }
    }

    showError(message) {
        let errorBox = document.getElementById("reflection-form-error");
        if (!errorBox) {
            errorBox = document.createElement("div");
            errorBox.id = "reflection-form-error";
            errorBox.className = "max-w-[1220px] w-[100%] m-auto mt-[16px]";
            errorBox.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">${message}</div>`;
            this.form.parentElement.insertBefore(errorBox, this.form);
        } else {
            errorBox.innerHTML = `<div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded\">${message}</div>`;
        }
    }
}

window.initializeReflectionForm = function () {
    new ReflectionFormHandler();
};
