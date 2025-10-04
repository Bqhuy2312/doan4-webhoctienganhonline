@extends('admin.layout')

@section('title', 'Quản lý danh mục')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_categories.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Quản lý danh mục</h1>
        <button type="button" class="add-new-btn" onclick="openModal('addCategoryModal')"><i class="fa-solid fa-plus"></i>
            Thêm danh mục</button>
    </div>

    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tên danh mục</th>
                    <th></th>
                    <th>Số khóa học</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->courses_count }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="action-btn"
                                    onclick="openEditModal({{ $category->id }}, '{{ e($category->name) }}')">
                                    <i class="fa-solid fa-pencil"></i> <span>Sửa</span>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn"><i class="fa-solid fa-trash"></i>
                                        <span>Xóa</span></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Chưa có danh mục nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-container" style="margin-top: 1.5rem;">{{ $categories->links() }}</div>

    <div id="addCategoryModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <h3>Thêm danh mục mới</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group"><label>Tên danh mục</label><input type="text" name="name" required></div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addCategoryModal')">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editCategoryModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content">
            <h3>Sửa danh mục</h3>
            <form id="editCategoryForm" action="" method="POST">
                @csrf @method('PUT')
                <div class="form-group"><label>Tên danh mục</label><input type="text" name="name" id="edit_category_name"
                        required></div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editCategoryModal')" style="color: black">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin_category_index.js') }}"></script>
@endpush