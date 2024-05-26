<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = [];

        if (auth()->check()) {
            // Lấy id của user hiện tại đang đăng nhập
            $userId = auth()->user()->id;

            // Lấy danh sách các todo của user hiện tại
            $todos = Todo::where('user_id', $userId)->get();
        }

        return view('home', compact('todos'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|max:255',
        ], [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
        ]);
        
        
       // Thêm cột 'user_id' vào mảng dữ liệu trước khi tạo mới todo
        $todoData = [
            'title' => $request->title,
            'completed' => false, // Mặc định công việc mới chưa hoàn thành
            'user_id' => $request->user_id, // Lấy giá trị 'user_id' từ request
        ];
        
        Todo::create($todoData);

        return redirect()->route('dashboard')->with('status', 'Todo added successfully!');
    }

    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        return view('edit', compact('todo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $todo = Todo::findOrFail($id);
        $todo->update([
            'title' => $request->title,
        ]);

        return redirect()->route('dashboard')->with('status', 'Todo updated successfully!');
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect()->route('dashboard')->with('status', 'Todo deleted successfully!');
    }

    public function complete(Todo $todo)
    {
        // Kiểm tra xem todo có thuộc về người dùng hiện tại không
        if ($todo->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Lấy giá trị trạng thái mới từ request
        $completed = request()->input('completed');

        // Cập nhật trạng thái của todo dựa trên giá trị mới
        $todo->update(['completed' => $completed]);

        // Chuẩn bị thông báo
        $statusMessage = $completed ? 'Todo marked as completed.' : 'Todo marked as not completed.';

        // Chuyển hướng trở lại trang trước với thông báo
        return redirect()->back()->with('status', $statusMessage);
    }
}
