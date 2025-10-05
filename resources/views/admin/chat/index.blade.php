@extends('admin.layout')

@section('title', 'Hỗ trợ trực tuyến')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_chat.css') }}">
@endpush

@section('content')

    {{-- @php
    $conversations = [
    (object) ['id' => 1, 'name' => 'Nguyễn Thu Trang', 'avatar' => 'https://i.pravatar.cc/150?img=1', 'last_message' => 'Dạ
    em cảm ơn admin ạ!', 'active' => true],
    (object) ['id' => 2, 'name' => 'Trần Minh Hoàng', 'avatar' => 'https://i.pravatar.cc/150?img=2', 'last_message' => 'Khóa
    học IELTS Foundation có giới hạn số lượng học viên không ạ?', 'active' => false],
    (object) ['id' => 3, 'name' => 'Lê Thị Kim Anh', 'avatar' => 'https://i.pravatar.cc/150?img=3', 'last_message' => 'Mình
    muốn hỏi về lịch khai giảng...', 'active' => false],
    ];
    $activeChatPartner = "Nguyễn Thu Trang";
    $messages = [
    (object) ['type' => 'user', 'text' => 'Chào admin, em muốn hỏi về cách làm bài quiz ạ.'],
    (object) ['type' => 'admin', 'text' => 'Chào bạn, bạn có thắc mắc cụ thể ở phần nào không?'],
    (object) ['type' => 'user', 'text' => 'Dạ em không thấy nút nộp bài ở đâu ạ.'],
    (object) ['type' => 'admin', 'text' => 'Nút "Nộp bài" sẽ hiện ra ở góc trên bên phải màn hình sau khi bạn trả lời hết
    các câu hỏi nhé.'],
    (object) ['type' => 'user', 'text' => 'Dạ em cảm ơn admin ạ!'],
    ];
    @endphp --}}

    <div class="chat-container">
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <input type="text" placeholder="Tìm kiếm cuộc trò chuyện...">
            </div>
            <div class="conversation-list">
                @forelse ($conversations as $convo)
                        <a href="{{ route('admin.chat.index', ['user_id' => $convo->id]) }}" class="conversation-item-link">
                            <div class="conversation-item @if($activeChatPartner && $activeChatPartner->id == $convo->id) active @endif"
                                data-id="{{ $convo->id }}">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($convo->name) }}&background=random"
                                    alt="Avatar">
                                <div class="conversation-details">
                                    <div class="name">{{ $convo->name }}</div>
                                    {{-- Phần tin nhắn cuối cùng là một tính năng nâng cao, tạm ẩn đi --}}
                                    {{-- <p class="last-message">Bấm để xem trò chuyện...</p> --}}
                                </div>
                            </div>
                        </a>
                @empty
                        <p style="text-align:center; padding: 1rem; color: #6c757d;">Chưa có cuộc trò chuyện nào.</p>
                    @endforelse
            </div>
        </aside>

        <main class="chat-window">
            @if ($activeChatPartner)
                <header class="chat-header">
                    Đang trò chuyện với {{ $activeChatPartner->name }}
                </header>
                <div class="chat-messages">
                    @foreach ($messages as $message)
                        <div class="message {{ $message->sender_id === Auth::id() ? 'admin-message' : 'user-message' }}">
                            <div class="message-bubble">
                            </div>
                        </div>
                    @endforeach
                </div>
                <footer class="chat-input">
                    <form id="chat-form">
                        <input type="text" placeholder="Nhập tin nhắn..." autocomplete="off">
                        <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </footer>
            @else
                <div class="no-chat-selected">
                    <i class="fa-regular fa-comments"></i>
                    <p>Chọn một cuộc trò chuyện để bắt đầu</p>
                </div>
            @endif
        </main>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/admin/admin_chat.js') }}"></script>
@endpush