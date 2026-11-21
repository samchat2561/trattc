<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Post;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // รับค่า {slug} จาก URL (เช่น 'laravel', 'php')
    public string $slug;

    #[Computed]
    public function posts()
    {
        // ค้นหา Post ทั้งหมดที่มี Tag ตรงกับ $slug ที่ส่งมา
        return Post::withAnyTags([$this->slug])
            ->with('categories') // โหลดหมวดหมู่มาด้วย (ถ้ามี)
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(3); // แบ่งหน้าละ 3 โพสต์
    }
};
?>

<div class="max-w-7xl mx-auto mt-36 bg-white shadow-2xl rounded-lg mb-10 pb-4">
    <!-- นำหน้า bloglist-page มาใช้แสดงผลซ้ำได้เลย -->
    @include('components.⚡bloglist-page', [
        'sectionTitle' => 'บทความที่ติดแท็ก: #' . ucwords(str_replace('-', ' ', $this->slug)),
        'posts' => $this->posts,
    ])
</div>
