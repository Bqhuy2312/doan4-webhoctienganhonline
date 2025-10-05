document.addEventListener("DOMContentLoaded", function () {
    const authUserId = document.body.dataset.authUserId; // Sẽ thêm vào layout
    let selectedUserId = null;

    const conversationItems = document.querySelectorAll(".conversation-item");
    const messagesEl = document.querySelector(".chat-messages");
    const chatInput = document.querySelector(".chat-input input");
    const chatForm = document.querySelector(".chat-input");

    // 1. Xử lý khi chọn một cuộc trò chuyện
    conversationItems.forEach((item) => {
        item.addEventListener("click", () => {
            // Chuyển đến trang chat với user được chọn
            window.location.href = `/admin/chat?user_id=${item.dataset.id}`;
        });
    });

    // Lấy ID của user đang chat (nếu có)
    const urlParams = new URLSearchParams(window.location.search);
    selectedUserId = urlParams.get("user_id");

    if (selectedUserId) {
        // 2. Xử lý gửi tin nhắn
        chatForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            // Gửi tin nhắn lên server
            await fetch(`/admin/chat/${selectedUserId}/messages`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({ message: message }),
            });

            // Tự thêm tin nhắn của mình vào UI
            appendMessage(message, "admin-message");
            chatInput.value = "";
        });

        // 3. Lắng nghe tin nhắn mới từ Pusher
        Echo.private(`chat.${authUserId}`).listen("MessageSent", (e) => {
            // Chỉ hiển thị nếu tin nhắn đến từ người mình đang chat
            if (e.message.sender_id == selectedUserId) {
                appendMessage(e.message.message, "user-message");
            }
        });
    }

    // Hàm để thêm tin nhắn vào giao diện
    function appendMessage(text, type) {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", type);
        messageDiv.innerHTML = `<div class="message-bubble">${text}</div>`;
        messagesEl.appendChild(messageDiv);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }
});
