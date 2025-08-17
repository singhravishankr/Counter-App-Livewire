<div> <!-- This is the single root element -->
    <h1 class="text-2xl font-bold mb-4">Livewire To-Do List</h1>

    <!-- Form -->
    <form wire:submit.prevent="{{ $editingTodoId ? 'updateTodo' : 'addTodo' }}" class="mb-4 space-y-2">
        <input type="text" wire:model="title" placeholder="Task title" class="w-full px-3 py-2 border rounded">
        <input type="date" wire:model="due_date" class="w-full px-3 py-2 border rounded">
        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                {{ $editingTodoId ? 'Update' : 'Add' }}
            </button>
            @if($editingTodoId)
                <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-gray-500 text-white rounded">
                    Cancel
                </button>
            @endif
        </div>
    </form>

    <!-- Filter Buttons -->
    <div class="flex gap-2 mb-4">
        <button wire:click="setFilter('all')" class="px-2 py-1 rounded {{ $filter == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">All</button>
        <button wire:click="setFilter('completed')" class="px-2 py-1 rounded {{ $filter == 'completed' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">Completed</button>
        <button wire:click="setFilter('pending')" class="px-2 py-1 rounded {{ $filter == 'pending' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">Pending</button>
    </div>

    <!-- Task List -->
    <ul class="space-y-2">
        @forelse($todos as $todo)
            <li class="flex items-center justify-between p-2 bg-gray-100 rounded">
                <div>
                    <input type="checkbox" wire:click="toggle({{ $todo->id }})" {{ $todo->completed ? 'checked' : '' }}>
                    <span class="{{ $todo->completed ? 'line-through text-gray-500' : '' }}">
                        {{ $todo->title }}
                    </span>
                    @if($todo->due_date)
                        <span class="text-sm text-gray-600">– Due: {{ \Carbon\Carbon::parse($todo->due_date)->format('M d, Y') }}</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button wire:click="edit({{ $todo->id }})" class="text-blue-600">Edit</button>
                    <button wire:click="delete({{ $todo->id }})" class="text-red-600">Delete</button>
                </div>
            </li>
        @empty
            <p class="text-gray-500">No tasks found.</p>
        @endforelse
    </ul>
</div>
