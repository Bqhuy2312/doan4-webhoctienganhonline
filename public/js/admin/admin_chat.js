document.addEventListener("DOMContentLoaded", function () {
    // Lấy ID của Admin từ thẻ body (bạn đã có trong layout)
    const authUserId = document.body.dataset.authUserId;
    let selectedUserId = null;

    // Lấy các element
    const messagesEl = document.querySelector(".chat-messages");
    const chatForm = document.querySelector("#chat-form");
    const chatInput = chatForm.querySelector("input");
    const conversationListEl = document.querySelector(".conversation-list");

    if (!chatForm || !chatInput || !messagesEl) {
        // Không ở trang có cuộc trò chuyện → thoát sớm
        return;
    }

    // 1. Xử lý khi chọn một cuộc trò chuyện (Giữ nguyên)
    document.querySelectorAll(".conversation-item").forEach((item) => {
        item.addEventListener("click", () => {
            window.location.href = `/admin/chat?user_id=${item.dataset.id}`;
        });
    });

    // Lấy ID của user đang chat (nếu có)
    const urlParams = new URLSearchParams(window.location.search);
    selectedUserId = urlParams.get("user_id");

    if (selectedUserId) {
        // 2. Xử lý gửi tin nhắn (Giữ nguyên)
        chatForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            try {
                console.log("Sending message:", message);
                if (!chatForm) return console.log("❌ Chat form not found!");
                const response = await fetch(
                    `/admin/chat/${selectedUserId}/messages`,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        },
                        body: JSON.stringify({ message }),
                    }
                );

                if (!response.ok) {
                    throw new Error("Request failed with " + response.status);
                }

                const data = await response.json();
                console.log("Server response:", data);

                appendMessage(message, "admin-message");
                chatInput.value = "";
            } catch (error) {
                console.error("Send message error:", error);
                alert("Không gửi được tin nhắn. Xem console để biết chi tiết.");
            }
        });
    }

    // 3. Lắng nghe tin nhắn mới từ Pusher (Đã sửa)
    Echo.channel("public-chat-channel").listen(".MessageSent", (e) => {
        console.log("Received new message:", e.message);
        if (e.message.receiver_id != authUserId) return;

        if (e.message.sender_id == selectedUserId) {
            appendMessage(e.message.message, "user-message");
        } else {
            handleNewConversation(e.message);
        }
    });
    console.log("✅ Echo instance:", Echo);

    Echo.connector.pusher.connection.bind("connected", function () {
        console.log("✅ Pusher connected thành công!");
    });

    Echo.connector.pusher.connection.bind("error", function (err) {
        console.error("❌ Pusher connection error:", err);
    });

    function handleNewConversation(message) {
        const sender = message.sender;
        if (!sender) return;

        // Tìm xem user đã có trong sidebar chưa
        let userItemLink = conversationListEl.querySelector(
            `.conversation-item[data-id="${sender.id}"]`
        );

        if (userItemLink) {
            // Nếu có, thêm class 'unread' và chuyển lên đầu
            userItemLink.classList.add("unread");
            conversationListEl.prepend(userItemLink.closest("a"));
        } else {
            // Nếu CHƯA CÓ (User mới), tạo HTML mới
            const newConvoLink = document.createElement("a");
            newConvoLink.href = `/admin/chat?user_id=${sender.id}`;
            newConvoLink.classList.add("conversation-item-link");

            // 'sender.name' sẽ hoạt động nhờ Bước 1 (Sửa Model)
            newConvoLink.innerHTML = `
                <div class="conversation-item unread" data-id="${sender.id}">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(
                        sender.name
                    )}&background=random" alt="Avatar">
                    <div class="conversation-details">
                        <div class="name">${sender.name}</div>
                        <p class="last-message"><em>Tin nhắn mới...</em></p>
                    </div>
                </div>`;

            // Xóa dòng "Chưa có cuộc trò chuyện nào"
            const emptyEl = conversationListEl.querySelector("p");
            if (emptyEl) {
                emptyEl.remove();
            }

            // Thêm vào đầu danh sách
            conversationListEl.prepend(newConvoLink);
        }
    }

    // Hàm để thêm tin nhắn vào giao diện (Giữ nguyên)
    function appendMessage(text, type) {
        if (!messagesEl) return; // An toàn nếu đang ở trang không có chat
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", type);
        messageDiv.innerHTML = `<div class="message-bubble">${text}</div>`;
        messagesEl.appendChild(messageDiv);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }
});
