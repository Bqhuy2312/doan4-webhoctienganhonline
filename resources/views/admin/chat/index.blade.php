@extends('admin.layout')

@section('title', 'Hỗ trợ trực tuyến')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_chat.css') }}">
@endpush

@section('content')

    @php
        $conversations = [
            (object) ['id' => 1, 'name' => 'Nguyễn Thu Trang', 'avatar' => 'https://i.pravatar.cc/150?img=1', 'last_message' => 'Dạ em cảm ơn admin ạ!', 'active' => true],
            (object) ['id' => 2, 'name' => 'Trần Minh Hoàng', 'avatar' => 'https://i.pravatar.cc/150?img=2', 'last_message' => 'Khóa học IELTS Foundation có giới hạn số lượng học viên không ạ?', 'active' => false],
            (object) ['id' => 3, 'name' => 'Lê Thị Kim Anh', 'avatar' => 'https://i.pravatar.cc/150?img=3', 'last_message' => 'Mình muốn hỏi về lịch khai giảng...', 'active' => false],
        ];
        $activeChatPartner = "Nguyễn Thu Trang";
        $messages = [
            (object) ['type' => 'user', 'text' => 'Chào admin, em muốn hỏi về cách làm bài quiz ạ.'],
            (object) ['type' => 'admin', 'text' => 'Chào bạn, bạn có thắc mắc cụ thể ở phần nào không?'],
            (object) ['type' => 'user', 'text' => 'Dạ em không thấy nút nộp bài ở đâu ạ.'],
            (object) ['type' => 'admin', 'text' => 'Nút "Nộp bài" sẽ hiện ra ở góc trên bên phải màn hình sau khi bạn trả lời hết các câu hỏi nhé.'],
            (object) ['type' => 'user', 'text' => 'Dạ em cảm ơn admin ạ!'],
        ];
    @endphp

    <div class="chat-container">
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <input type="text" placeholder="Tìm kiếm cuộc trò chuyện...">
            </div>
            <div class="conversation-list">
                @foreach ($conversations as $convo)
                    <div class="conversation-item {{ $convo->active ? 'active' : '' }}">
                        <img src="{{ $convo->avatar }}" alt="Avatar">
                        <div class="conversation-details">
                            <div class="name">{{ $convo->name }}</div>
                            <p class="last-message">{{ $convo->last_message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        <main class="chat-window">
            <header class="chat-header">
                Đang trò chuyện với {{ $activeChatPartner }}
            </header>
            <div class="chat-messages">
                @foreach ($messages as $message)
                    <div class="message {{ $message->type === 'admin' ? 'admin-message' : 'user-message' }}">
                        <div class="message-bubble">
                            {{ $message->text }}
                        </div>
                    </div>
                @endforeach
            </div>
            <footer class="chat-input">
                <input type="text" placeholder="Nhập tin nhắn...">
                <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
            </footer>
        </main>
    </div>
@endsection