@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Todo List') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->has('title'))
                            <div class="alert alert-danger">
                                <strong>{{ $errors->first('title') }}</strong>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('todos.store') }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" placeholder="Add new todo">
                                <!-- Thêm input hidden để lưu user_id -->
                                @if (Auth::check())
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                @endif
                            </div>
                            <button style="margin-top: 5px;" type="submit" class="btn btn-primary">Add</button>
                        </form>

                        <ul class="list-group mt-3">
                            @foreach ($todos as $todo)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="check">
                                        <input type="checkbox" name="completed" id="todo-{{ $todo->id }}" {{ $todo->completed ? 'checked' : '' }}>
                                    </div>
                                    {{ $todo->title }}
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('todos.edit', $todo->id) }}" style="margin-right:5px; border-radius: 5px" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button style="margin-left: 5px;" type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <script>
        document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.addEventListener('click', function () {
                var todoId = this.id.split('-')[1];
                var isCompleted = this.checked;

                // Gửi yêu cầu cập nhật thông qua Ajax
                fetch('/todos/' + todoId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        completed: isCompleted
                    })
                })
                .then(response => response.json())
                .then(data =>  alert(data.message))
                .catch(error => console.error('Error:', error));
                alert(response)
            });
        });
    </script>
@endsection



