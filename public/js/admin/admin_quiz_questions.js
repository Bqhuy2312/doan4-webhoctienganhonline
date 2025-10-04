document.addEventListener("DOMContentLoaded", function () {
    window.openModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "flex";
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "none";
    };

    window.closeModalOnOverlay = function (event) {
        if (event.target.classList.contains("modal-overlay")) {
            event.target.style.display = "none";
        }
    };

    window.addOption = function () {
        const container = document.getElementById("options-container");
        const optionCount = container.children.length;
        const newOption = document.createElement("div");
        newOption.className = "option-group";
        newOption.innerHTML = `
            <input type="radio" name="correct_option" value="${optionCount}" required>
            <input type="text" name="options[]" placeholder="Đáp án ${String.fromCharCode(
                65 + optionCount
            )}" required>
            <button type="button" class="remove-option-btn" onclick="this.parentElement.remove()">&times;</button>
        `;
        container.appendChild(newOption);
    };

    window.openEditModal = function (id, text, options) {
        const form = document.getElementById("editQuestionForm");
        form.action = "/admin/questions/" + id;

        document.getElementById("edit_question_text").value = text;

        const container = document.getElementById("edit_options_container");
        container.innerHTML = "";

        options.forEach((option, index) => {
            const newOption = document.createElement("div");
            newOption.className = "option-group";
            newOption.innerHTML = `
                <input type="radio" name="correct_option" value="${index}" ${
                option.is_correct ? "checked" : ""
            } required>
                <input type="text" name="options[]" value="${escapeHtml(
                    option.option_text
                )}" required>
                <button type="button" class="remove-option-btn" onclick="this.parentElement.remove()">&times;</button>
            `;
            container.appendChild(newOption);
        });

        openModal("editQuestionModal");
    };

    window.addEditOption = function () {
        const container = document.getElementById("edit_options_container");
        const optionCount = container.children.length;
        const newOption = document.createElement("div");
        newOption.className = "option-group";
        newOption.innerHTML = `
            <input type="radio" name="correct_option" value="${optionCount}" required>
            <input type="text" name="options[]" placeholder="Đáp án ${String.fromCharCode(
                65 + optionCount
            )}" required>
            <button type="button" class="remove-option-btn" onclick="this.parentElement.remove()">&times;</button>
        `;
        container.appendChild(newOption);
    };

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
