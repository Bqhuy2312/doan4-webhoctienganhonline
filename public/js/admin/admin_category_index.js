function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "flex";
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "none";
    }
}

function closeModalOnOverlay(event) {
    if (event.target.classList.contains("modal-overlay")) {
        event.target.style.display = "none";
    }
}
function openEditModal(id, name) {
    const form = document.getElementById("editCategoryForm");
    form.action = "/admin/categories/" + id;
    document.getElementById("edit_category_name").value = name;
    openModal("editCategoryModal");
}
