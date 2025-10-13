document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("email_form");
    if (!form) return;

    const messageBox = document.getElementById("response-message");
    const submitBtn  = form.querySelector('input[type="submit"]');
    const emailEl    = document.getElementById("email");
    const nameEl     = document.getElementById("name");
    const yourNameEl = document.getElementById("your_name");
    const sessionEl  = form.querySelector('input[name="session_id"]');
    const csrf       = document.querySelector('meta[name="csrf-token"]')?.content || "";

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const payload = {
            email: (emailEl?.value || "").trim(),
            name: (nameEl?.value || "").trim(),
            your_name: (yourNameEl?.value || "").trim(),
            session_id: sessionEl ? sessionEl.value : null,
        };

        submitBtn && (submitBtn.disabled = true);
        messageBox && (messageBox.textContent = "Küldés…");

        try {
            const res = await fetch("/invite", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrf
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok && data.ok) {
                messageBox.textContent =
                    "Ha gondolod, szólj a párodnak, hogy fog kapni egy üzenetet…";
                messageBox.classList.remove("error");
                messageBox.classList.add("success");
                submitBtn.value = "Köszönjük!";
                submitBtn.disabled = true;
                setTimeout(() => (window.location.href = "/"), 5000);
            } else {
                messageBox.textContent = data.msg || `Hiba történt (HTTP ${res.status}).`;
                messageBox.classList.remove("success");
                messageBox.classList.add("error");
                submitBtn.disabled = false;
            }
        } catch (err) {
            messageBox.textContent = "Hálózati hiba. Próbáld újra.";
            submitBtn.disabled = false;
        }
    });
});
