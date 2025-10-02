function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function closeModalOnOverlay(event) {
    if (event.target.classList.contains("modal-overlay")) {
        event.target.style.display = "none";
    }
}
