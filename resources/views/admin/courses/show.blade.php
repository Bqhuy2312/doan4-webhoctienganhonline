@extends('admin.layout')

@section('title', 'Nội dung khóa học')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_course_show.css') }}">
@endpush

@section('content')

    <div class="page-header">
        <h1>Quản lý nội dung</h1>
        <h2 class="course-subtitle">{{ $course->title }}</h2>
    </div>

    <div class="page-actions">
        <button type="button" class="action-btn" onclick="openModal('addVideoModal')"><i class="fa-solid fa-video"></i>
            Thêm Video
        </button>
        <button type="button" class="action-btn" onclick="openModal('addPdfModal')"><i class="fa-solid fa-file-pdf"></i>
            Thêm PDF
        </button>
        <button type="button" class="action-btn" onclick="openModal('addQuizModal')"><i class="fa-solid fa-square-check"></i>
            Thêm Quiz
        </button>
    </div>

    @forelse ($course->sections as $section)
        <div class="content-section">
            <div class="section-header">
                <h3>{{ $section->title }}</h3>
                <div class="lesson-actions">
                    <a href="#" title="Sửa tên chương"><i class="fa-solid fa-pencil"></i></a>
                    <a href="#" title="Xóa chương"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
            <ul class="lesson-list">
                @forelse ($section->lessons as $lesson)
                    <li class="lesson-item">
                        <span class="drag-handle" title="Kéo thả để sắp xếp"><i class="fa-solid fa-grip-vertical"></i></span>
                        @if ($lesson->type == 'video')
                            <i class="fa-solid fa-circle-play lesson-icon icon-video"></i>
                        @elseif ($lesson->type == 'pdf')
                            <i class="fa-solid fa-file-lines lesson-icon icon-pdf"></i>
                        @elseif ($lesson->type == 'quiz')
                            <i class="fa-solid fa-square-check lesson-icon icon-quiz"></i>
                        @endif
                        <span class="lesson-title">{{ $lesson->title }}</span>
                        <div class="lesson-actions">
                            <a href="#" title="Sửa"><i class="fa-solid fa-pencil"></i></a>
                            <a href="#" title="Xóa"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </li>
                @empty
                    <li class="lesson-item" style="justify-content: center;">Chương này chưa có bài học nào.</li>
                @endforelse
            </ul>
        </div>
    @empty
        <div class="content-section" style="text-align: center; padding: 2rem;">
            <p>Khóa học này chưa có chương nào.</p>
        </div>
    @endforelse

    <div id="addVideoModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm bài học Video mới</h3>
                <button class="close-modal" onclick="closeModal('addVideoModal')">&times;</button>
            </div>
            <form action="{{ route('admin.lessons.store', $course->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="video">
                <div class="form-group">
                    <label for="video_title">Tên bài học</label>
                    <input type="text" name="title" id="video_title" required>
                </div>
                <div class="form-group">
                    <label for="video_url">Đường dẫn Video (URL)</label>
                    <input type="url" name="video_url" id="video_url" placeholder="https://youtube.com/..." required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addVideoModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addPdfModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm bài học PDF mới</h3>
                <button class="close-modal" onclick="closeModal('addPdfModal')">&times;</button>
            </div>
            <form action="{{ route('admin.lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="pdf">
                <div class="form-group">
                    <label for="pdf_title">Tên bài học</label>
                    <input type="text" name="title" id="pdf_title" required>
                </div>
                <div class="form-group">
                    <label for="pdf_file">Chọn file PDF</label>
                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addPdfModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addQuizModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm bài Quiz vào khóa học</h3>
                <button class="close-modal" onclick="closeModal('addQuizModal')">&times;</button>
            </div>
            <form action="{{ route('admin.lessons.store', $course->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="quiz">
                <div class="form-group">
                    <label for="quiz_title">Tên hiển thị trong khóa học</label>
                    <input type="text" name="title" id="quiz_title" required>
                </div>
                <div class="form-group">
                    <label for="quiz_id">Chọn bài Quiz có sẵn</label>
                    <select name="quiz_id" id="quiz_id" required>
                        <option value="" disabled selected>-- Chọn một bài quiz --</option>
                        {{-- Vòng lặp này sẽ lấy quiz từ CSDL --}}
                        {{-- @foreach($quizzes as $quiz)
                        <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                        @endforeach --}}
                        <option value="1">Bài kiểm tra cuối khóa IELTS</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addQuizModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin_course_show.js') }}"></script>
@endpush