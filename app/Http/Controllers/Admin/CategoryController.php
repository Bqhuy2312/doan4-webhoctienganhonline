<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);
        return back()->with('success', 'Tạo danh mục thành công!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);
        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category)
    {
        if ($category->courses()->count() > 0) {
            return back()->withErrors(['error' => 'Không thể xóa danh mục vì vẫn còn khóa học.']);
        }
        $category->delete();
        return back()->with('success', 'Xóa danh mục thành công!');
    }
}