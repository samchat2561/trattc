<?php

use Livewire\Component;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

new class extends Component {
    public $post_id;
    public $parent_id = null;
    public $guest_name;
    public $guest_email;
    public $body;

    public function mount($post_id)
    {
        $this->post_id = $post_id;
    }

    public function store()
    {
        $formRequest = new StoreCommentRequest();
        // รัน Validation โดยดึงกฎจาก FormRequest
        $validated = $this->validate($formRequest->rules(), $formRequest->messages());
        // dd($validated);

        $data = $validated;
        Comment::create(
            $data + [
                'user_id' => auth()->id(),
                'guest_name' => auth()->check() ? null : $data['guest_name'],
                'guest_email' => auth()->check() ? null : $data['guest_email'],
            ],
        );

        $this->reset(['body', 'guest_name', 'guest_email', 'parent_id']);
        session()->flash('success', 'Comment Submitted Successfully!');
    }
};
?>

<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-200 text-green-800 px-4 py-3 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <form class="space-y-4" wire:submit.prevent="store">
        @guest
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Name</label>
                    <input type="text" wire:model="guest_name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    @error('guest_name')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" wire:model="guest_email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    @error('guest_email')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @else
            <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-600 mb-2">
                Commenting as <strong>{{ auth()->user()->name }}</strong>
            </div>
        @endguest
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Comment</label>
            <textarea rows="5" wire:model="body" placeholder="Share your thoughts..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"></textarea>
            @error('body')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled"
            class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center">
            <span wire:loading.remove>Post Comment</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>
</div>
