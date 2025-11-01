@extends('user.layout')

@section('title', 'Vào học: ' . $course->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/course_detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/learn_page.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
    <div class="learn-container">

        <aside class="learn-sidebar">
            <h3 class="sidebar-title">Nội dung khóa học</h3>
            <div class="curriculum-list">
                @foreach($course->sections as $section)
                    <div class="section-item">
                        <div class="section-header">
                            <h3>{{ $section->title }}</h3>
                        </div>

                        <ul class="lesson-list show">
                            @foreach ($section->lessons as $lesson_item)
                                <li class="lesson-item {{ $lesson_item->id == $currentLesson->id ? 'active' : '' }}">

                                    <a href="{{ route('user.learn', ['course' => $course->id, 'lesson' => $lesson_item->id]) }}">
                                        @if ($lesson_item->type == 'video')
                                            <i class="fa-solid fa-circle-play lesson-icon icon-video"></i>
                                        @elseif ($lesson_item->type == 'pdf')
                                            <i class="fa-solid fa-file-lines lesson-icon icon-pdf"></i>
                                        @elseif ($lesson_item->type == 'quiz')
                                            <i class="fa-solid fa-square-check lesson-icon icon-quiz"></i>
                                        @endif
                                        <span class="lesson-title">{{ $lesson_item->title }}</span>

                                        {{-- TODO: Thêm icon "đã hoàn thành" --}}
                                        {{-- @if(isset($completedLessons[$lesson_item->id]))
                                        <i class="fa-solid fa-check-circle completed-icon"></i>
                                        @endif --}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </aside>

        {{-- NỘI DUNG CHÍNH (VIDEO/PDF/QUIZ) --}}
        <main class="learn-content">
            <div class="lesson-header">
                <h1>{{ $currentLesson->title }}</h1>
                {{-- TODO: Thêm nút "Đánh dấu hoàn thành" --}}
                {{-- <form action="#" method="POST">
                    <button class="complete-button">Hoàn thành bài học</button>
                </form> --}}
            </div>

            <div class="lesson-body">
                @if ($currentLesson->type == 'video')
                    <div class="video-container">
                        <video controls width="100%" src="{{ asset('storage/' . $currentLesson->video_path) }}"
                            id="lesson-player">
                            Trình duyệt của bạn không hỗ trợ video.
                        </video>
                    </div>

                @elseif ($currentLesson->type == 'pdf')
                    <div class="pdf-container">
                        <iframe src="{{ asset('storage/' . $currentLesson->pdf_path) }}" width="100%" height="800px"></iframe>
                        <a href="{{ asset('storage/' . $currentLesson->pdf_path) }}" download class="download-pdf">Tải PDF</a>
                    </div>

                @elseif ($currentLesson->type == 'quiz')
                        <div class="quiz-container">

                            @if ($latestAttempt && !$isRetry)

                                    <h3>Kết quả lần làm bài trước</h3>
                                    <p>Bạn đã trả lời đúng:
                                        <strong>{{ $latestAttempt->correct_answers }} / {{ $latestAttempt->total_questions }}</strong>
                                    </p>
                                    <p class="score">Điểm số: {{ $latestAttempt->score }}%</p>

                                    <a href="{{ route('user.learn', ['course' => $course->id, 'lesson' => $currentLesson->id, 'retry' => true]) }}"
                                        class="retry-quiz-btn">
                                        Làm lại bài
                                    </a>
                                </div>

                            @else

                            @if ($currentLesson->quiz)
                                <form action="{{ route('user.quiz.submit', $currentLesson->quiz->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">

                                    @foreach ($currentLesson->quiz->questions as $index => $question)
                                        <div class="quiz-question">
                                            <p class="question-text">
                                                <strong>Câu {{ $index + 1 }}:</strong> {{ $question->question_text }}
                                            </p>

                                            <ul class="quiz-options">
                                                @foreach ($question->options as $option)
                                                    <li class="quiz-option">
                                                        <input type="radio" name="question[{{ $question->id }}]" value="{{ $option->id }}"
                                                            id="option-{{ $option->id }}" required>
                                                        <label for="option-{{ $option->id }}">{{ $option->option_text }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach

                                    <button type="submit" class="submit-quiz-btn">Nộp bài</button>
                                </form>
                            @else
                                <p>Không thể tải được nội dung bài Quiz.</p>
                            @endif
                        @endif
                    </div>
                @endif
    </div>
    </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var sectionHeaders = document.querySelectorAll('.section-header');
            sectionHeaders.forEach(function (header) {
                header.addEventListener('click', function () {
                    this.classList.toggle('active');
                    var lessonList = this.nextElementSibling;
                    if (lessonList && lessonList.classList.contains('lesson-list')) {
                        lessonList.classList.toggle('show');
                    }
                });
            });


            const isCompleted = {{ isset($completedLessons[$currentLesson->id]) ? 'true' : 'false' }};

            if (!isCompleted) {

                function markLessonComplete() {
                    fetch("{{ route('user.lessons.complete', $currentLesson->id) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Đã tự động đánh dấu hoàn thành!');
                            }
                        })
                        .catch(error => console.error('Lỗi:', error));
                }

                @if ($currentLesson->type == 'video')
                    const videoPlayer = document.getElementById('lesson-player');
                    if (videoPlayer) {
                        videoPlayer.addEventListener('ended', function () {
                            console.log('Video đã kết thúc.');
                            markLessonComplete();
                        });
                    }

                @elseif ($currentLesson->type == 'pdf')

                    console.log('Trang PDF đã tải.');
                    markLessonComplete();
                @endif
            }

        });
    </script>
@endpush