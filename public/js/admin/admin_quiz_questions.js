// document.addEventListener("DOMContentLoaded", function () {
//     window.openModal = function (modalId) {
//         const modal = document.getElementById(modalId);
//         if (modal) modal.style.display = "flex";
//     };

//     window.closeModal = function (modalId) {
//         const modal = document.getElementById(modalId);
//         if (modal) modal.style.display = "none";
//     };

//     window.closeModalOnOverlay = function (event) {
//         if (event.target.classList.contains("modal-overlay")) {
//             event.target.style.display = "none";
//         }
//     };

//     window.addOption = function () {
//         const container = document.getElementById("options-container");
//         const optionCount = container.children.length;
//         const newOption = document.createElement("div");
//         newOption.className = "option-group";
//         newOption.innerHTML = `
//             <input type="radio" name="correct_option" value="${optionCount}" required>
//             <input type="text" name="options[]" placeholder="Đáp án ${String.fromCharCode(
//                 65 + optionCount
//             )}" required>
//             <button type="button" class="remove-option-btn" onclick="this.parentElement.remove()">&times;</button>
//         `;
//         container.appendChild(newOption);
//     };

//     window.openEditModal = function (id, text, options) {
//         const form = document.getElementById("editQuestionForm");
//         form.action = "/admin/questions/" + id;

//         document.getElementById("edit_question_text").value = text;

//         const container = document.getElementById("edit_options_container");
//         container.innerHTML = "";

//         options.forEach((option, index) => {
//             const newOption = document.createElement("div");
//             newOption.className = "option-group";
//             newOption.innerHTML = `
//                 <input type="radio" name="correct_option" value="${index}" ${
//                 option.is_correct ? "checked" : ""
//             } required>
//                 <input type="text" name="options[]" value="${escapeHtml(
//                     option.option_text
//                 )}" required>
//                 <button type="button" class="remove-option-btn" onclick="this.parentElement.remove()">&times;</button>
//             `;
//             container.appendChild(newOption);
//         });

//         openModal("editQuestionModal");
//     };

//     window.addEditOption = function () {
//         const container = document.getElementById("edit_options_container");
//         const optionCount = container.children.length;
//         const newOption = document.createElement("div");
//         newOption.className = "option-group";
//         newOption.innerHTML = `
//             <input type="radio" name="correct_option" value="${optionCount}" required>
//             <input type="text" name="options[]" placeholder="Đáp án ${String.fromCharCode(
//                 65 + optionCount
//             )}" required>
//             <button type="button" class="remove-option-btn" onclick="this.parentElement.remove()">&times;</button>
//         `;
//         container.appendChild(newOption);
//     };

//     function escapeHtml(unsafe) {
//         return unsafe
//             .replace(/&/g, "&amp;")
//             .replace(/</g, "&lt;")
//             .replace(/>/g, "&gt;")
//             .replace(/"/g, "&quot;")
//             .replace(/'/g, "&#039;");
//     }
// });
document.addEventListener("DOMContentLoaded", function () {
    //==================================================
    // 1. CÁC HÀM QUẢN LÝ MODAL (Giữ nguyên)
    //==================================================
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

    // Hàm escape (Giữ nguyên)
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/\"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    //==================================================
    // 2. HÀM HELPER ẨN/HIỆN (MỚI)
    //==================================================

    /**
     * Hàm helper để ẩn/hiện các container dựa trên 'type'
     * @param {HTMLElement} modal - (Modal Thêm hoặc Modal Sửa)
     * @param {string} visibleType - (ví dụ: 'multiple_choice')
     */
    function toggleTypeContainers(modal, visibleType) {
        // Tìm các container CHỈ bên trong modal đó
        const containers = modal.querySelectorAll(".question-type-container");

        containers.forEach((container) => {
            const containerType = container.id.split("-")[1]; // Lấy 'multiple_choice' từ 'type-multiple_choice'
            const inputs = container.querySelectorAll(
                "input, textarea, select"
            );

            if (containerType === visibleType) {
                // Kích hoạt 'required' cho các trường trong container hiển thị
                container.style.display = "block";
                inputs.forEach((input) => (input.disabled = false));
            } else {
                // Vô hiệu hóa 'required' cho các trường bị ẩn
                container.style.display = "none";
                inputs.forEach((input) => (input.disabled = true));
            }
        });
    }

    //==================================================
    // 3. LOGIC CHO MODAL THÊM CÂU HỎI (NÂNG CẤP)
    //==================================================

    const addModal = document.getElementById("addQuestionModal");
    if (addModal) {
        const typeSelect = document.getElementById("question_type_select");

        // 3.1. Lắng nghe sự kiện thay đổi Dropdown
        typeSelect.addEventListener("change", function () {
            toggleTypeContainers(addModal, this.value);
        });

        // 3.2. Kích hoạt lần đầu khi mở modal
        toggleTypeContainers(addModal, typeSelect.value);

        // 3.3. Logic thêm/xóa cho Dạng Sắp Xếp (Ordering)
        const addOrderingBtn = document.getElementById("add-ordering-option");
        const orderingWrapper = document.getElementById(
            "ordering-options-wrapper"
        );
        let optionIndex = 0;

        addOrderingBtn.addEventListener("click", function () {
            const newOption = document.createElement("div");
            newOption.classList.add("input-group", "mb-2");

            // Tên input phải khớp với Controller
            newOption.innerHTML = `
                <input type="text" name="ordering_options[${optionIndex}][text]" class="form-control" placeholder="Mảnh câu (ví dụ: 'I am')" required>
                <input type="number" name="ordering_options[${optionIndex}][order]" class="form-control" placeholder="Thứ tự (ví dụ: 1)" style="max-width: 100px;" required>
                <button type="button" class="btn btn-danger remove-option-btn">&times;</button>
            `;

            orderingWrapper.appendChild(newOption);
            optionIndex++;
            toggleTypeContainers(addModal, "ordering"); // Kích hoạt lại required
        });

        // Nút Xóa (Ordering)
        orderingWrapper.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-option-btn")) {
                e.target.closest(".input-group").remove();
            }
        });
    }

    //==================================================
    // 4. LOGIC CHO MODAL SỬA CÂU HỎI (NÂNG CẤP TOÀN DIỆN)
    //==================================================

    const editModal = document.getElementById("editQuestionModal");

    // 4.1. Nâng cấp hàm openEditModal
    // (Phải khớp với file Blade: id, text, options, type)
    window.openEditModal = function (id, text, options, type) {
        const form = document.getElementById("editQuestionForm");
        form.action = "/admin/questions/" + id; // (Route của bạn)

        // 1. Điền các trường chung
        document.getElementById("edit_question_text").value = text;
        const typeSelect = document.getElementById("edit_question_type_select");
        typeSelect.value = type;

        // 2. Xóa sạch container cũ
        const mcContainer = document.getElementById("edit_options_container");
        const fbContainer = document.getElementById(
            "edit_fill_in_blank_answer"
        );
        const orderWrapper = document.getElementById(
            "edit_ordering-options-wrapper"
        );

        mcContainer.innerHTML = "";
        fbContainer.value = "";
        orderWrapper.innerHTML = "";

        let editOptionIndex = 0;

        // 3. Đổ (populate) dữ liệu dựa trên 'type'
        switch (type) {
            case "multiple_choice":
                options.forEach((option, index) => {
                    const newOption = document.createElement("div");
                    newOption.className = "input-group mb-2"; // (Giống file JS cũ của bạn)
                    newOption.innerHTML = `
                        <div class="input-group-text">
                            <input type="radio" name="correct_option" value="${index}" ${
                        option.is_correct ? "checked" : ""
                    } required>
                        </div>
                        <input type="text" name="options[]" value="${escapeHtml(
                            option.option_text
                        )}" class="form-control" required>
                    `;
                    mcContainer.appendChild(newOption);
                });
                break;

            case "fill_in_blank":
                // Tìm đáp án đúng (là option duy nhất)
                const answer = options.find((opt) => opt.is_correct);
                if (answer) {
                    fbContainer.value = escapeHtml(answer.option_text);
                }
                break;

            case "ordering":
                // Sắp xếp các mảnh theo thứ tự
                options.sort((a, b) => a.order - b.order);

                options.forEach((option) => {
                    const newOption = document.createElement("div");
                    newOption.classList.add("input-group", "mb-2");
                    newOption.innerHTML = `
                        <input type="text" name="ordering_options[${editOptionIndex}][text]" value="${escapeHtml(
                        option.option_text
                    )}" class="form-control" required>
                        <input type="number" name="ordering_options[${editOptionIndex}][order]" value="${
                        option.order
                    }" class="form-control" style="max-width: 100px;" required>
                        <button type="button" class="btn btn-danger remove-option-btn">&times;</button>
                    `;
                    orderWrapper.appendChild(newOption);
                    editOptionIndex++;
                });
                break;
        }

        // 4. Ẩn/hiện container
        toggleTypeContainers(editModal, type);

        // 5. Mở modal
        openModal("editQuestionModal");
    };

    // 4.2. Lắng nghe thay đổi Dropdown (Modal Sửa)
    const editTypeSelect = document.getElementById("edit_question_type_select");
    if (editTypeSelect) {
        editTypeSelect.addEventListener("change", function () {
            toggleTypeContainers(editModal, this.value);
        });
    }

    // 4.3. Logic thêm/xóa cho Dạng Sắp Xếp (Modal Sửa)
    // (Sử dụng lại biến editOptionIndex)
    if (editModal) {
        const addBtn = editModal.querySelector(".add-ordering-option-btn");
        const orderWrapper = document.getElementById(
            "edit_ordering-options-wrapper"
        );

        addBtn.addEventListener("click", function () {
            let currentIndex = orderWrapper.children.length; // Đếm số con hiện tại
            const newOption = document.createElement("div");
            newOption.classList.add("input-group", "mb-2");
            newOption.innerHTML = `
                <input type="text" name="ordering_options[${currentIndex}][text]" class="form-control" placeholder="Mảnh câu" required>
                <input type="number" name="ordering_options[${currentIndex}][order]" class="form-control" placeholder="Thứ tự" style="max-width: 100px;" required>
                <button type="button" class="btn btn-danger remove-option-btn">&times;</button>
            `;
            orderWrapper.appendChild(newOption);
            toggleTypeContainers(editModal, "ordering"); // Kích hoạt lại required
        });

        // Nút Xóa (Ordering - Modal Sửa)
        orderWrapper.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-option-btn")) {
                e.target.closest(".input-group").remove();
            }
        });
    }

    // 5. HÀM CŨ (addEditOption) KHÔNG CÒN DÙNG NỮA
    // (Vì logic đã được gộp vào openEditModal)
    // window.addEditOption = function () { ... };
});
