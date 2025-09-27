@extends('user.layout')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Danh sách Quiz</h1>
    <ul class="list-disc pl-6">
        @foreach($quizzes as $quiz)
            <li>{{ $quiz->title }}</li>
        @endforeach
    </ul>
</div>
@endsection
