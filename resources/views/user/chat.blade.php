@extends('user.layout')

@section('title', 'Hỗ trợ trực tuyến')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/chat.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
    <div class="chat-page-container">
        <div class="chat-card">
            <div class="chat-header">
                <h3>Hỗ trợ trực tuyến</h3>
                <p>Chúng tôi sẽ phản hồi sớm nhất có thể</p>
            </div>

            <div class="chat-messages" id="chat-messages">
                <p style="text-align: center; color: #999;">Đang tải tin nhắn...</p>
            </div>

            <form class="chat-input-form" id="chat-form">
                <input type="text" id="message-input" class="chat-input" placeholder="Nhập tin nhắn..." autocomplete="off">
                <button type="submit" class="chat-send-btn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')

    @vite(['resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const chatMessages = document.getElementById('chat-messages');
            const chatForm = document.getElementById('chat-form');
            const messageInput = document.getElementById('message-input');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Lấy ID của user đang đăng nhập
            const currentUserId = {{ Auth::id() }};

            // Lấy route từ Blade
            const SEND_URL = "{{ route('user.chat.send') }}";
            const FETCH_URL = "{{ route('user.chat.fetch') }}";

            // (Hàm helper 1: Thêm 1 tin nhắn vào UI)
            function appendMessage(msg) {
                const bubble = document.createElement('div');
                bubble.classList.add('message-bubble');
                // Phân biệt tin nhắn Gửi (sent) hay Nhận (received)
                if (msg.sender_id === currentUserId) {
                    bubble.classList.add('sent');
                } else {
                    bubble.classList.add('received');
                }
                bubble.textContent = msg.message;
                chatMessages.appendChild(bubble);
            }

            // (Hàm helper 2: Hiển thị lịch sử chat)
            function renderMessages(messages) {
                chatMessages.innerHTML = '';
                if (messages.length === 0) {
                    chatMessages.innerHTML = '<p style="text-align: center; color: #999;">Hãy bắt đầu cuộc trò chuyện!</p>';
                    return;
                }
                messages.forEach(msg => appendMessage(msg));
                scrollToBottom();
            }

            // (Hàm helper 3: Gửi tin nhắn mới)
            async function sendMessage(messageText) {
                try {
                    await fetch(SEND_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ message: messageText })
                    });

                    // Tự thêm tin nhắn của MÌNH vào UI ngay lập tức
                    appendMessage({ message: messageText, sender_id: currentUserId });
                    scrollToBottom();

                } catch (error) {
                    console.error('Lỗi khi gửi tin nhắn:', error);
                }
            }

            // (Hàm helper 4: Tự động cuộn)
            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // 3. XỬ LÝ GỬI FORM
            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const messageText = messageInput.value.trim();
                if (messageText) {
                    sendMessage(messageText);
                    messageInput.value = '';
                }
            });

            // 4. TẢI LỊCH SỬ CHAT (Khi mở trang)
            fetch(FETCH_URL)
                .then(response => response.json())
                .then(messages => renderMessages(messages));

            // 5. LẮNG NGHE PUSHER (Tin nhắn mới từ Admin)
            window.Echo.channel('public-chat-channel')
                .listen('.MessageSent', (e) => {

                    const ADMIN_ID = 1;

                    if (e.message.receiver_id == currentUserId && e.message.sender_id == ADMIN_ID) {
                        console.log('Tin nhắn mới từ Admin:', e.message);
                        appendMessage(e.message);
                        scrollToBottom();
                    }
                });

        });
    </script>
@endpush