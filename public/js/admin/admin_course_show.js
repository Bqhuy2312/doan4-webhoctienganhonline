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

function openEditModal(id, currentTitle) {
    const form = document.getElementById("editSectionForm");
    form.action = "/admin/sections/" + id;
    document.getElementById("edit_section_title").value = currentTitle;
    openModal("editSectionModal");
}

function openAddLessonModal(modalId, sectionId) {
    const inputId = modalId.replace("Modal", "_section_id");
    const hiddenInput = document.getElementById(inputId);
    if (hiddenInput) {
        hiddenInput.value = sectionId;
    }
    openModal(modalId);
}

function openEditLessonModal(lesson) {
    let modalId, formId, titleInput, contentInput;

    if (lesson.type === "video") {
        modalId = "editVideoModal";
        formId = "editVideoForm";
        document.getElementById("edit_video_title").value = lesson.title;
        document.getElementById("edit_video_url").value = lesson.video_url;
    } else if (lesson.type === "pdf") {
        modalId = "editPdfModal";
        formId = "editPdfForm";
        document.getElementById("edit_pdf_title").value = lesson.title;
    } else if (lesson.type === "quiz") {
        modalId = "editQuizModal";
        formId = "editQuizForm";
        document.getElementById("edit_quiz_title").value = lesson.title;
        document.getElementById("edit_quiz_id").value = lesson.quiz_id;
    }

    if (modalId) {
        document.getElementById(formId).action = "/admin/lessons/" + lesson.id;
        openModal(modalId);
    }
}
