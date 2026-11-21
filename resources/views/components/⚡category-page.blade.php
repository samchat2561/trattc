<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Category;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Category $category;

    #[Computed]
    public function posts()
    {
        return $this->category->posts()
        ->with('categories')
        ->published()
        ->paginate(3);
    }
};
?>

<div class="max-w-7xl mx-auto mt-36 bg-white shadow-2xl rounded-lg">
    @include('components.⚡bloglist-page', [
        'sectionTitle' => $this->category->name,
        'posts' => $this->posts,
    ])
</div>
