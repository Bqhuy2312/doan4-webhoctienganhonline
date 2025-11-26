@extends('admin.layout')

@section('title', 'Hỗ trợ trực tuyến')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_chat.css') }}">
@endpush

@section('content')

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
                            @if(Str::startsWith($convo->avatar, 'http'))
                                <img src="{{ $convo->avatar }}" alt="avatar" class="avatar">
                            @else
                                <img src="{{ asset('storage/' . $convo->avatar) }}" alt="avatar" class="avatar">
                            @endif
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
                                {{ $message->message }}
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

@push('scripts')
    <script src="{{ asset('js/admin/admin_chat.js') }}"></script>
@endpush