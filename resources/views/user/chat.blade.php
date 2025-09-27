@extends('user.layout')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Chat hỗ trợ</h1>
    <div class="bg-white shadow p-4 rounded-lg space-y-2">
        @foreach($messages as $msg)
            <div>
                <strong>{{ $msg->user }}:</strong> {{ $msg->message }}
            </div>
        @endforeach
    </div>
</div>
@endsection
