// Drag and Drop Reorder functionality for article blocks and reflection questions
document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector("[data-drag-container]");

    if (!container) {
        console.log(
            "No drag container found, skipping drag-drop initialization"
        );
        return;
    }

    // Only initialize if we're on a show page (articles, reflections, or onboarding)
    const currentPath = window.location.pathname;
    if (
        !currentPath.includes("/dashboard-articles/") &&
        !currentPath.includes("/dashboard-reflections/") &&
        !currentPath.includes("/dashboard-onboarding")
    ) {
        console.log("Not on a show page, skipping drag-drop initialization");
        return;
    }

    // Ensure we're on a show page, not a list page
    if (
        currentPath === "/dashboard-reflections" ||
        currentPath === "/dashboard-articles"
    ) {
        console.log("On list page, skipping drag-drop initialization");
        return;
    }

    console.log("Initializing drag-drop for:", currentPath);

    let draggedElement = null;
    let draggedIndex = null;

    // Add drag and drop attributes to all draggable items
    function initializeDragDrop() {
        const draggableItems = container.querySelectorAll("[data-drag-item]");

        draggableItems.forEach((item, index) => {
            item.draggable = true;
            item.setAttribute("data-original-index", index);

            // Add drag event listeners
            item.addEventListener("dragstart", handleDragStart);
            item.addEventListener("dragend", handleDragEnd);
            item.addEventListener("dragover", handleDragOver);
            item.addEventListener("drop", handleDrop);
            item.addEventListener("dragenter", handleDragEnter);
            item.addEventListener("dragleave", handleDragLeave);
        });
    }

    function handleDragStart(e) {
        draggedElement = this;
        draggedIndex = parseInt(this.getAttribute("data-original-index"));

        // Add visual feedback
        this.classList.add("dragging");
        this.style.opacity = "0.5";

        // Set drag data
        e.dataTransfer.effectAllowed = "move";
        e.dataTransfer.setData("text/html", this.outerHTML);
    }

    function handleDragEnd(e) {
        // Remove visual feedback
        this.classList.remove("dragging");
        this.style.opacity = "1";

        // Remove all drag-over classes
        const allItems = container.querySelectorAll("[data-drag-item]");
        allItems.forEach((item) => {
            item.classList.remove("drag-over");
        });

        draggedElement = null;
        draggedIndex = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = "move";
    }

    function handleDragEnter(e) {
        e.preventDefault();
        if (this !== draggedElement) {
            this.classList.add("drag-over");
        }
    }

    function handleDragLeave(e) {
        this.classList.remove("drag-over");
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove("drag-over");

        if (this === draggedElement) return;

        const dropIndex = parseInt(this.getAttribute("data-original-index"));

        console.log(`Moving from index ${draggedIndex} to index ${dropIndex}`);

        // Reorder elements in DOM
        reorderElements(draggedIndex, dropIndex);

        // Update data-original-index attributes and position numbers
        updateIndexAttributes();

        // Send AJAX request to update positions in database
        updatePositionsInDatabase();
    }

    function reorderElements(fromIndex, toIndex) {
        const items = Array.from(
            container.querySelectorAll("[data-drag-item]")
        );

        console.log(`Before reorder: ${items.length} items`);
        console.log(`Moving item from ${fromIndex} to ${toIndex}`);

        // Get the dragged item
        const draggedItem = items[fromIndex];

        // Remove the dragged item from its current position
        draggedItem.remove();

        // Get the target item after removal
        const updatedItems = Array.from(
            container.querySelectorAll("[data-drag-item]")
        );

        console.log(`After removal: ${updatedItems.length} items`);

        if (toIndex >= updatedItems.length) {
            // Moving to the end
            console.log("Moving to the end");
            container.appendChild(draggedItem);
        } else {
            // Insert at the new position
            console.log(`Inserting before item at index ${toIndex}`);
            const targetItem = updatedItems[toIndex];
            container.insertBefore(draggedItem, targetItem);
        }

        console.log("Reorder complete");
    }

    function updateIndexAttributes() {
        const items = container.querySelectorAll("[data-drag-item]");
        items.forEach((item, index) => {
            item.setAttribute("data-original-index", index);

            // Update the position number display
            const positionElement = item.querySelector(
                'h3[class*="font-bold text-center"]'
            );
            if (positionElement) {
                positionElement.textContent = index + 1;
            }
        });
    }

    function updatePositionsInDatabase() {
        const items = container.querySelectorAll("[data-drag-item]");
        const positions = [];

        items.forEach((item, index) => {
            // Check for both article blocks and reflection questions
            const blockId = item.getAttribute("data-block-id");
            const questionId = item.getAttribute("data-question-id");
            const id = blockId || questionId;

            positions.push({
                id: id,
                position: index + 1,
            });
        });

        console.log("Positions to send:", positions);

        // Show loading toast
        Swal.fire({
            title: "Újrarendezés...",
            text: "Pozíciók mentése...",
            icon: "info",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });

        // Determine the correct reorder URL based on current page
        const urlParts = window.location.pathname
            .split("/")
            .filter((part) => part !== "");
        let reorderUrl;

        console.log("Current URL:", window.location.pathname);
        console.log("URL parts:", urlParts);

        if (window.location.pathname.includes("/dashboard-reflections/")) {
            // Reflection reordering
            const reflectionId = urlParts[urlParts.length - 1];
            reorderUrl = `/dashboard-reflections/${reflectionId}/reorder`;
            console.log("Using reflection reorder URL:", reorderUrl);
        } else if (window.location.pathname.includes("/dashboard-articles/")) {
            // Article reordering
            const articleSlug = urlParts[urlParts.length - 1];
            reorderUrl = `/dashboard-articles/${articleSlug}/reorder`;
            console.log("Using article reorder URL:", reorderUrl);
        } else if (window.location.pathname.includes("/dashboard-onboarding")) {
            // Onboarding reordering
            reorderUrl = `/dashboard-onboarding/reorder`;
            console.log("Using onboarding reorder URL:", reorderUrl);
        } else {
            console.error(
                "Unable to determine reorder URL for current page:",
                window.location.pathname
            );
            return;
        }

        // Make AJAX request
        fetch(reorderUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                positions: positions,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Success toast
                    Swal.fire({
                        title: "Sikeres!",
                        text: data.message,
                        icon: "success",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                } else {
                    // Error toast
                    Swal.fire({
                        title: "Hiba!",
                        text: data.message,
                        icon: "error",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    // Reload page to restore original order
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            })
            .catch((error) => {
                // Error toast for network issues
                Swal.fire({
                    title: "Hiba!",
                    text: "Hálózati hiba történt. Kérjük próbálja újra.",
                    icon: "error",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                // Reload page to restore original order
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });
    }

    // Initialize drag and drop when page loads
    initializeDragDrop();
});
