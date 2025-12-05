document.addEventListener("DOMContentLoaded", function () {
    const authUserId = document.body.dataset.authUserId;
    let selectedUserId = null;

    const messagesEl = document.querySelector(".chat-messages");
    const chatForm = document.querySelector("#chat-form");
    const chatInput = chatForm.querySelector("input");
    const conversationListEl = document.querySelector(".conversation-list");

    if (!chatForm || !chatInput || !messagesEl) {
        return;
    }

    document.querySelectorAll(".conversation-item").forEach((item) => {
        item.addEventListener("click", () => {
            window.location.href = `/admin/chat?user_id=${item.dataset.id}`;
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    selectedUserId = urlParams.get("user_id");

    if (selectedUserId) {
        chatForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            try {
                console.log("Sending message:", message);
                if (!chatForm) return console.log("Chat form not found!");
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

                appendMessage(message, "admin-message", new Date().toISOString());
                chatInput.value = "";
            } catch (error) {
                console.error("Send message error:", error);
                alert("Không gửi được tin nhắn. Xem console để biết chi tiết.");
            }
        });
    }

    Echo.channel("public-chat-channel").listen(".MessageSent", (e) => {
        console.log("Received new message:", e.message);
        if (e.message.receiver_id != authUserId) return;

        if (e.message.sender_id == selectedUserId) {
            appendMessage(e.message.message, "user-message", e.message.created_at);
        } else {
            handleNewConversation(e.message);
        }
    });
    console.log("Echo instance:", Echo);

    Echo.connector.pusher.connection.bind("connected", function () {
        console.log("Pusher connected thành công!");
    });

    Echo.connector.pusher.connection.bind("error", function (err) {
        console.error("Pusher connection error:", err);
    });

    function handleNewConversation(message) {
        const sender = message.sender;
        if (!sender) return;

        let userItemLink = conversationListEl.querySelector(
            `.conversation-item[data-id="${sender.id}"]`
        );

        if (userItemLink) {
            userItemLink.classList.add("unread");
            conversationListEl.prepend(userItemLink.closest("a"));
        } else {
            const newConvoLink = document.createElement("a");
            newConvoLink.href = `/admin/chat?user_id=${sender.id}`;
            newConvoLink.classList.add("conversation-item-link");

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

            const emptyEl = conversationListEl.querySelector("p");
            if (emptyEl) {
                emptyEl.remove();
            }

            conversationListEl.prepend(newConvoLink);
        }
    }

    function appendMessage(text, type, createdAt = null) {
        if (!messagesEl) return;

        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", type);

        const date = createdAt ? new Date(createdAt) : new Date();

        const formattedTime =
            date.toLocaleTimeString("vi-VN", {
                hour: "2-digit",
                minute: "2-digit",
            }) +
            " " +
            date.toLocaleDateString("vi-VN");

        messageDiv.innerHTML = `
        <div class="message-bubble">
            ${text}
            <div class="message-time">${formattedTime}</div>
        </div>
    `;

        messagesEl.appendChild(messageDiv);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    const searchInput = document.getElementById("search-input");
    const conversationLinks = document.querySelectorAll(
        ".conversation-item-link"
    );

    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase().trim();

        conversationLinks.forEach((link) => {
            const name = link.querySelector(".name").textContent.toLowerCase();

            if (name.includes(keyword)) {
                link.style.display = "block";
            } else {
                link.style.display = "none";
            }
        });
    });
});
