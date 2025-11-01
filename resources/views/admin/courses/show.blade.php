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
        <button type="button" class="action-btn" onclick="openModal('addSectionModal')">
            <i class="fa-solid fa-plus"></i> Thêm chương mới
        </button>
    </div>

    @forelse ($course->sections as $section)
        <div class="content-section">
            <div class="section-header">
                <h3>{{ $section->title }}</h3>
                <div class="lesson-actions">
                    <a href="#" title="Sửa tên chương" onclick="openEditModal({{ $section->id }}, '{{ e($section->title) }}')">
                        <i class="fa-solid fa-pencil"></i>
                    </a>

                    <form action="{{ route('admin.sections.destroy', $section->id) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa chương này không? Toàn bộ bài học bên trong cũng sẽ bị xóa.');"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-icon-btn" title="Xóa chương">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
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
                            <i class="fa-solid fa-question-circle lesson-icon icon-quiz"></i>
                        @endif
                        <span class="lesson-title">{{ $lesson->title }}</span>
                        <div class="lesson-actions">
                            <a href="#" title="Sửa" onclick="openEditLessonModal({{ json_encode($lesson) }})">
                                <i class="fa-solid fa-pencil"></i>
                            </a>

                            <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này không?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-icon-btn" title="Xóa bài học">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="lesson-item" style="justify-content: center;">Chương này chưa có bài học nào.</li>
                @endforelse
            </ul>

            <div class="add-lesson-buttons">
                <button class="add-lesson-btn" onclick="openAddLessonModal('addVideoModal', {{ $section->id }})"><i
                        class="fa-solid fa-video"></i> Thêm Video</button>
                <button class="add-lesson-btn" onclick="openAddLessonModal('addPdfModal', {{ $section->id }})"><i
                        class="fa-solid fa-file-pdf"></i> Thêm PDF</button>
                <button class="add-lesson-btn" onclick="openAddLessonModal('addQuizModal', {{ $section->id }})"><i
                        class="fa-solid fa-square-check"></i> Thêm Quiz</button>
            </div>
        </div>
    @empty
        <div class="content-section" style="text-align: center; padding: 2rem;">
            <p>Khóa học này chưa có chương nào.</p>
        </div>
    @endforelse

    <div id="addSectionModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm chương mới</h3>
                <button class="close-modal" onclick="closeModal('addSectionModal')">&times;</button>
            </div>
            <form action="{{ route('admin.courses.sections.store', $course->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="section_title">Tên chương</label>
                    <input type="text" name="title" id="section_title" placeholder="Ví dụ: Chương 1: Giới thiệu" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addSectionModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu chương</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addVideoModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm bài học Video mới</h3>
                <button class="close-modal" onclick="closeModal('addVideoModal')">&times;</button>
            </div>
            <form action="{{ route('admin.lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="video">
                <input type="hidden" name="section_id" id="addVideo_section_id">
                <div class="form-group">
                    <label for="video_title">Tên bài học</label>
                    <input type="text" name="title" id="video_title" required>
                </div>
                <div class="form-group">
                    <label>Tải lên file Video</label>
                    <input type="file" name="video_file" accept="video/*" required>
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
                <input type="hidden" name="section_id" id="addPdf_section_id">
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
                <input type="hidden" name="section_id" id="addQuiz_section_id">
                <div class="form-group">
                    <label for="quiz_title">Tên hiển thị trong khóa học</label>
                    <input type="text" name="title" id="quiz_title" required>
                </div>
                <div class="form-group">
                    <label for="quiz_id">Chọn bài Quiz có sẵn</label>
                    <select name="quiz_id" id="quiz_id" required>
                        <option value="" disabled selected>-- Chọn một bài quiz --</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addQuizModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editSectionModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sửa tên chương</h3>
                <button class="close-modal" onclick="closeModal('editSectionModal')">&times;</button>
            </div>
            <form id="editSectionForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_section_title">Tên chương mới</label>
                    <input type="text" name="title" id="edit_section_title" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editSectionModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editVideoModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sửa bài học Video</h3><button class="close-modal"
                    onclick="closeModal('editVideoModal')">&times;</button>
            </div>
            <form id="editVideoForm" action="" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Tên bài học</label>
                    <input type="text" name="title" id="edit_video_title" required>
                </div>
                <div class="form-group">
                    <label>Tải lên file Video mới (tùy chọn)</label>
                    <input type="file" name="video_file" id="edit_video_path" accept="video/*">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editVideoModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editPdfModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sửa bài học PDF</h3><button class="close-modal" onclick="closeModal('editPdfModal')">&times;</button>
            </div>
            <form id="editPdfForm" action="" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-group"><label>Tên bài học</label><input type="text" name="title" id="edit_pdf_title"
                        required></div>
                <div class="form-group"><label>Tải lên file PDF mới (tùy chọn)</label><input type="file" name="pdf_file"
                        accept=".pdf"></div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editPdfModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editQuizModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sửa bài học Quiz</h3><button class="close-modal" onclick="closeModal('editQuizModal')">&times;</button>
            </div>
            <form id="editQuizForm" action="" method="POST">
                @csrf @method('PUT')
                <div class="form-group"><label>Tên hiển thị</label><input type="text" name="title" id="edit_quiz_title"
                        required></div>
                <div class="form-group">
                    <label>Chọn lại bài Quiz</label>
                    <select name="quiz_id" id="edit_quiz_id" required>
                        <option value="" disabled>-- Chọn một bài quiz --</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editQuizModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin_course_show.js') }}"></script>
@endpush