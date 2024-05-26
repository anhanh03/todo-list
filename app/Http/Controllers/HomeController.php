<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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


}
