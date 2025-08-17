<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;

class TodoList extends Component
{
    // public $todos, $title;
    public $todos, $title, $due_date, $editingTodoId = null, $filter = 'all';


    public function mount()
{
    $this->loadTodos();
}

public function loadTodos()
{
    $query = Todo::query()->latest();

    if ($this->filter === 'completed') {
        $query->where('completed', true);
    } elseif ($this->filter === 'pending') {
        $query->where('completed', false);
    }

    $this->todos = $query->get();
}

    public function addTodo()
{
    $this->validate([
        'title' => 'required|string|max:255',
        'due_date' => 'nullable|date'
    ]);

    Todo::create([
        'title' => $this->title,
        'due_date' => $this->due_date,
    ]);

    $this->reset(['title', 'due_date']);
    $this->loadTodos();
}

public function edit($id)
{
    $todo = Todo::find($id);
    $this->editingTodoId = $todo->id;
    $this->title = $todo->title;
    $this->due_date = $todo->due_date?->format('Y-m-d');
}

public function updateTodo()
{
    $this->validate([
        'title' => 'required|string|max:255',
        'due_date' => 'nullable|date'
    ]);

    $todo = Todo::find($this->editingTodoId);
    $todo->update([
        'title' => $this->title,
        'due_date' => $this->due_date,
    ]);

    $this->reset(['editingTodoId', 'title', 'due_date']);
    $this->loadTodos();
}

public function cancelEdit()
{
    $this->reset(['editingTodoId', 'title', 'due_date']);
}

public function setFilter($value)
{
    $this->filter = $value;
    $this->loadTodos();
}


    public function toggle($id)
    {
        $todo = Todo::find($id);
        $todo->completed = !$todo->completed;
        $todo->save();

        $this->todos = Todo::latest()->get();
    }

    public function delete($id)
    {
        Todo::find($id)->delete();
        $this->todos = Todo::latest()->get();
    }

    public function render()
    {
        return view('livewire.todo-list');
    }
}
