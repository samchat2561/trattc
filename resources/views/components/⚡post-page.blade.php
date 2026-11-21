<?php

use Livewire\Component;

use Illuminate\Support\Facades\RateLimiter; // 1. เพิ่มการเรียกใช้ RateLimiter
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Filament\Resources\Comments\CommentResource;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment; // เพิ่ม Model Comment
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // รับค่า Model Injection จาก Route อัตโนมัติ
    public Post $post;

    public function mount()
    {
        $this->post->increment('view_count');
        $this->refreshComments();
    }

    // --- เพิ่ม Properties สำหรับระบบ Comment/Reply ---
    public $body;
    public $guest_name;
    public $guest_email;

    public function store($parentId = null)
    {
        // --- 2. ป้องกันการส่งรัว (Rate Limiting) ---
        // สร้าง Key เฉพาะตัวจาก IP และ ID ของโพสต์
        $throttleKey = 'submit-comment:' . request()->ip() . ':' . $this->post->id;

        // จำกัดให้ส่งได้ 1 ครั้ง ต่อ 30 วินาที
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "คุณส่งความเห็นบ่อยเกินไป กรุณารออีก {$seconds} วินาที");
            return;
        }

        // --- 3. ตรวจสอบข้อมูลซ้ำ (Duplicate Check) ---
        $lastComment = Comment::where('post_id', $this->post->id)
            ->where(function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('guest_email', $this->guest_email);
                }
            })
            ->latest()
            ->first();

        if ($lastComment && $lastComment->body === $this->body) {
            $this->addError('body', 'คุณเพิ่งส่งข้อความที่เหมือนกันนี้ไปเมื่อสักครู่');
            return;
        }

        // 4. Validation
        $rules = ['body' => 'required|string|max:500'];
        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:50';
            $rules['guest_email'] = 'required|email|max:75';
        }
        $this->validate($rules);

        // 5. บันทึกข้อมูล
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'parent_id' => $parentId, // ถ้ารับค่ามาจะเป็นการ Reply
            'user_id' => auth()->id(),
            'guest_name' => $this->guest_name,
            'guest_email' => $this->guest_email,
            'body' => $this->body,
            'is_approved' => false,
        ]);

        // 6. บันทึกประวัติการส่ง (Hit) เพื่อเริ่มนับถอยหลัง Rate Limit
        RateLimiter::hit($throttleKey, 30); // ล็อคไว้ 30 วินาที

        // --- ส่วนการส่ง Notification ไปยัง Dashboard ---
        $users = User::permission('SendNotification:Comment')->get();

        Notification::make()
            ->title('มีความคิดเห็นใหม่')
            ->body(str($this->body)->limit(50))
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->success()
            ->actions([
                Action::make('view')
                    ->label('ดูความคิดเห็น')
                    ->button()
                    ->outlined()
                    ->url(CommentResource::getUrl('edit', ['record' => $comment])),
            ])
            ->sendToDatabase($users);

        // 7. ล้างค่าเฉพาะ Property ที่เกี่ยวข้อง
        $this->reset(['body', 'guest_name', 'guest_email']);

        // 8. โหลด/refresh ข้อมูล Comment ใหม่ ในตัวแปร $post เพื่อความสดใหม่ของข้อมูล
        $this->refreshComments();

        // 9.แจ้งเตือนผู้ใช้
        session()->flash('message', 'ส่งความคิดเห็นเรียบร้อยแล้ว! ความคิดเห็นของคุณจะปรากฏหลังจากได้รับการตรวจสอบจากทีมงาน');
    }
    // ------------------------------------------

    #[Computed]
    public function relatedPosts()
    {
        // 1. ดึงชื่อ Tag ออกมา
        $tags = $this->post->tags->pluck('name')->toArray();

        // ถ้าไม่มี Tag เลย ให้ดึงโพสต์ในหมวดหมู่เดียวกันแทน
        if (empty($tags)) {
            $category = $this->post->categories->first();
            if (!$category) {
                return collect();
            }

            return $category
                ->posts()
                ->where('posts.id', '!=', $this->post->id)
                ->with('categories')
                ->published()
                ->orderBy('published_at', 'desc')
                ->paginate(3);
        }

        // 2. ดึงบทความที่เกี่ยวข้องตาม Tag
        return Post::with('categories')
            ->withAnyTags($tags)
            ->where('posts.id', '!=', $this->post->id) // ไม่เอาโพสต์ที่กำลังอ่านมาโชว์ซ้ำ
            ->post()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(3); // ใช้ paginate จะได้ไม่ Error ตอนเรียก $posts->links()
    }

    #[Computed]
    public function posts()
    {
        $category = $this->post->categories->first();
        if (!$category) {
            return collect();
        }

        return $category
            ->posts()
            ->where('posts.id', '!=', $this->post->id)
            ->with('categories')
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(3);
    }

    #[Computed]
    public function latestPosts()
    {
        return Post::with('categories')
            ->post()
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
    }

    // ภายในฟังก์ชัน toggleLike ในไฟล์ post-page.blade.php
    public function toggleLike(Comment $comment)
    {
        if (!auth()->check()) {
            session()->flash('error', 'กรุณาเข้าสู่ระบบเพื่อกดถูกใจ');
            return;
        }

        // ป้องกันการกดย้ำๆ (Throttle Like) 5 ครั้งต่อนาที
        $likeThrottleKey = 'like-comment:' . auth()->id();
        if (RateLimiter::tooManyAttempts($likeThrottleKey, 10)) {
            // ให้กดได้ 10 ครั้งต่อนาที
            return;
        }

        $like = $comment
            ->likes()
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            $comment->likes()->create(['user_id' => auth()->id()]);

            // ส่ง Notification (เฉพาะกรณีไม่ใช่เจ้าของคอมเมนต์กดเอง)
            if ($comment->user_id && $comment->user_id !== auth()->id()) {
                Notification::make()->title('มีคนถูกใจความคิดเห็นของคุณ')->icon('heroicon-s-heart')->iconColor('danger')->sendToDatabase($comment->user);
            }
        }

        RateLimiter::hit($likeThrottleKey, 60);

        // Refresh เฉพาะความสัมพันธ์คอมเมนต์ ไม่ต้องโหลดโพสต์ใหม่ทั้งหมด
        $this->refreshComments();
    }

    // แยกฟังก์ชัน Refresh ออกมาเพื่อให้โค้ดสะอาดขึ้น
    private function refreshComments()
    {
        $this->post->load([
            'comments' => function ($query) {
                return $query
                    ->whereNull('parent_id')
                    ->where('is_approved', true)
                    ->with([
                        'user',
                        'likes',
                        'replies' => function ($q) {
                            $q->where('is_approved', true)->with(['user', 'likes']);
                        },
                    ]);
            },
        ]);
    }
};
?>

<div class="w-full min-h-screen pt-36 pb-16 bg-gray-100 dark:bg-slate-900 font-[sarabun]">
    <x-slot:title>{{ $post->title }}</x-slot>

    <!-- Breadcrumbs Navigation -->
    <div class="w-full bg-gray-100 dark:bg-slate-900 border-b border-gray-200/80 dark:border-slate-800 pb-5 mb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm font-medium text-slate-500 dark:text-slate-400">
                <a wire:navigate href="{{ route('home') }}" class="flex items-center gap-1.5 hover:text-amber-600 transition-colors">
                    <x-heroicon-s-home class="w-4 h-4" />
                    <span>หน้าหลัก</span>
                </a>
                <x-heroicon-s-chevron-right class="w-4 h-4 text-slate-300 dark:text-slate-600" />
                @if ($post->categories->first())
                    <a wire:navigate href="{{ route('category.show', $post->categories->first()) }}" class="hover:text-amber-600 transition-colors">
                        {{ $post->categories->first()->name }}
                    </a>
                    <x-heroicon-s-chevron-right class="w-4 h-4 text-slate-300 dark:text-slate-600" />
                @endif
                <span class="text-slate-800 dark:text-slate-200 truncate max-w-xs sm:max-w-md font-semibold">{{ $post->title }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Article Section -->
    <article class="w-full bg-gray-100 dark:bg-slate-900 border-b border-gray-200/80 dark:border-slate-800 py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xl rounded-2xl px-4 py-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Content & Gallery -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Main Post Header Card (หัวข้อหลัก) -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 md:p-8 shadow-xs">
                        <!-- Category and Date -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400 mb-6 font-medium">
                            <div class="flex flex-wrap gap-2">
                                @forelse ($post->categories as $cat)
                                    <a wire:navigate href="{{ route('category.show', $cat) }}"
                                        class="inline-flex items-center px-3.5 py-1.5 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 rounded-full font-bold tracking-wider text-[12px] uppercase transition-all duration-300 shadow-xs border border-amber-100 dark:border-amber-900/30">
                                        {{ $cat->name }}
                                    </a>
                                @empty
                                    <span
                                        class="inline-flex items-center px-3.5 py-1.5 bg-slate-50 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 rounded-full font-medium text-[12px] border border-slate-100 dark:border-slate-800/30">ไม่มีหมวดหมู่</span>
                                @endforelse
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                <div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-350">
                                    <x-heroicon-o-calendar-days class="w-4 h-4 text-amber-500" />
                                    <span>{{ $post->published_at?->format('j M Y') ?? 'ไม่ได้ระบุวันที่' }}</span>
                                </div>

                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-clock class="w-4 h-4 text-amber-500" />
                                    <span>{{ $post->published_at?->diffForHumans() ?? 'ไม่ได้ระบุวันที่' }}</span>
                                </div>

                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-eye class="w-4 h-4 text-amber-500" />
                                    <span>{{ number_format($post->view_count) }} Views</span>
                                </div>
                            </div>
                        </div>

                        <!-- Post Header Container -->
                        <div class="relative group mb-8">
                            <!-- Accent Decorative Element -->
                            <div
                                class="absolute -left-4 top-0 h-full w-1.5 bg-linear-to-b from-amber-500 to-red-500 rounded-full opacity-80 group-hover:w-2 transition-all duration-300">
                            </div>

                            <!-- Title -->
                            <h1
                                class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white leading-[1.2] tracking-tight pl-6 relative transition-all duration-300 group-hover:translate-x-1">
                                <span
                                    class="bg-clip-text text-transparent bg-linear-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 group-hover:from-amber-600 group-hover:to-red-500 dark:group-hover:from-amber-400 dark:group-hover:to-amber-300 transition-colors duration-500">
                                    {{ $post->title }}
                                </span>
                                <span
                                    class="block mt-4 h-1.5 w-20 bg-amber-500 rounded-full transition-all duration-500 ease-out group-hover:w-40 group-hover:bg-amber-400"></span>
                            </h1>
                        </div>

                        <!-- Author Info (Modern Card Style) -->
                        <div
                            class="group flex items-center gap-4 p-2 pl-2 pr-6 w-fit bg-slate-50/80 dark:bg-slate-800/60 rounded-full border border-slate-200/60 dark:border-slate-700/60 shadow-xs hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-300">
                            @if ($post->author)
                                <div class="relative">
                                    <img src="{{ $post->author->profile_photo
                                        ? asset('storage/' . $post->author->profile_photo)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) . '&color=FFFFFF&background=d97706' }}"
                                        alt="{{ $post->author->name }}"
                                        class="w-12 h-12 rounded-full ring-2 ring-white dark:ring-slate-800 shadow-xs object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full shadow-xs">
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-[0.15em] mb-0.5 opacity-80">Author</span>
                                    <h3 class="font-black text-slate-800 dark:text-slate-100 text-sm leading-tight">{{ $post->author->name }}</h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-[12px] font-medium">
                                        {{ $post->author->tagline ?? 'Expert Instructor' }}</p>
                                </div>
                            @else
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name=U&color=71717a&background=f4f4f5" alt="Unknown"
                                        class="w-12 h-12 rounded-full ring-2 ring-white dark:ring-slate-800 shadow-xs object-cover grayscale">
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mb-0.5">Guest</span>
                                    <h3 class="font-bold text-slate-600 dark:text-slate-350 text-sm leading-tight">Unknown User</h3>
                                    <p class="text-slate-400 dark:text-slate-500 text-[12px]">Contributor</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Article Content -->
                    <div class="prose prose-lg dark:prose-invert max-w-none text-slate-700 dark:text-slate-350 leading-relaxed italic-quotes shadow-xs bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 md:p-10 lg:p-12">
                        {!! $post->content !!}
                    </div>

                    <!-- Attachments -->
                    @if ($post->attachments && count($post->attachments) > 0)
                        <div class="p-6 md:p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
                            <h3 class="text-lg font-bold text-slate-850 dark:text-slate-100 mb-4 flex items-center gap-2">
                                <x-heroicon-s-paper-clip class="w-5 h-5 text-amber-500" />
                                เอกสารแนบ (Attachments)
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($post->attachments as $file)
                                    @php
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $fileConfig = match ($ext) {
                                            'pdf' => [
                                                'color' => 'bg-red-50 dark:bg-red-950/20',
                                                'text' => 'text-red-600 dark:text-red-400',
                                                'icon' => 'heroicon-o-document-text',
                                            ],
                                            'doc', 'docx' => [
                                                'color' => 'bg-blue-50 dark:bg-blue-950/20',
                                                'text' => 'text-blue-600 dark:text-blue-400',
                                                'icon' => 'heroicon-o-document',
                                            ],
                                            'xls', 'xlsx' => [
                                                'color' => 'bg-emerald-50 dark:bg-emerald-950/20',
                                                'text' => 'text-emerald-600 dark:text-emerald-400',
                                                'icon' => 'heroicon-o-table-cells',
                                            ],
                                            'ppt', 'pptx' => [
                                                'color' => 'bg-orange-50 dark:bg-orange-950/20',
                                                'text' => 'text-orange-600 dark:text-orange-400',
                                                'icon' => 'heroicon-o-presentation-chart-bar',
                                            ],
                                            default => [
                                                'color' => 'bg-slate-100 dark:bg-slate-800/40',
                                                'text' => 'text-slate-600 dark:text-slate-400',
                                                'icon' => 'heroicon-o-document-arrow-down',
                                            ],
                                        };
                                    @endphp

                                    <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                        class="flex items-center p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 rounded-xl hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                                        <div
                                            class="p-2.5 {{ $fileConfig['color'] }} rounded-lg mr-3 group-hover:bg-amber-500 transition-colors duration-300">
                                            <x-dynamic-component :component="$fileConfig['icon']"
                                                class="w-6 h-6 {{ $fileConfig['text'] }} group-hover:text-white" />
                                        </div>
                                        <div class="flex-1 overflow-hidden">
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-355 truncate group-hover:text-amber-700 dark:group-hover:text-amber-400">
                                                {{ basename($file) }}
                                            </p>
                                            <p class="text-[10px] font-bold {{ $fileConfig['text'] }} uppercase tracking-wider mt-0.5">
                                                {{ $ext }} file
                                            </p>
                                        </div>
                                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <x-heroicon-s-eye class="w-4 h-4 text-slate-400 dark:text-slate-500" />
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tags Section (หัวข้อ tag) -->
                    @if ($post->tags && $post->tags->isNotEmpty())
                        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="w-2 h-7 bg-linear-to-b from-amber-500 to-amber-700 rounded-full shadow-sm"></span>
                                <h3 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">แท็ก (Tags)</h3>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach ($post->tags as $tag)
                                    <a wire:navigate href="{{ route('posts.bytag', $tag->slug) }}" class="group">
                                        <span
                                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300
                                            bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/70 dark:border-slate-700/60
                                            group-hover:bg-amber-500 group-hover:text-white group-hover:border-amber-500 dark:group-hover:bg-amber-500 dark:group-hover:text-white
                                            group-hover:shadow-[0_4px_12px_rgba(245,158,11,0.25)]
                                            group-hover:-translate-y-0.5">
                                            <span class="text-amber-500 group-hover:text-white mr-1.5 font-bold transition-colors">#</span>
                                            {{ ucwords($tag->name) }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Image Gallery Box (แกลเลอรีภาพกิจกรรม) -->
                    @php $images = $post->feature_images; @endphp
                    @if(count($images) > 0)
                        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs rounded-3xl p-6 md:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2.5 bg-red-500/10 dark:bg-red-500/20 text-red-500 dark:text-red-400 rounded-2xl flex items-center justify-center">
                                    <x-heroicon-s-photo class="w-6 h-6" />
                                </div>
                                <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">แกลเลอรีภาพกิจกรรม</h3>
                            </div>

                            <!-- Image Gallery & Lightbox Section (AlpineJS) -->
                            <div x-data="{ 
                                isOpen: false, 
                                activeImage: '', 
                                images: {{ json_encode(collect($post->feature_images)->map(fn($img) => asset('storage/' . $img))->toArray()) }},
                                currentIndex: 0,
                                openLightbox(index) {
                                    this.currentIndex = index;
                                    this.activeImage = this.images[index];
                                    this.isOpen = true;
                                },
                                next() {
                                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                    this.activeImage = this.images[this.currentIndex];
                                },
                                prev() {
                                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                    this.activeImage = this.images[this.currentIndex];
                                }
                            }" 
                            @keydown.escape.window="isOpen = false" 
                            @keydown.arrow-right.window="next()" 
                            @keydown.arrow-left.window="prev()"
                            class="relative">

                                @php 
                                    $isPortrait = false;
                                    if (count($images) > 0) {
                                        $filePath = storage_path('app/public/' . $images[0]);
                                        if (file_exists($filePath)) {
                                            $size = @getimagesize($filePath);
                                            if ($size && $size[1] > $size[0]) {
                                                $isPortrait = true;
                                            }
                                        }
                                    }
                                @endphp

                                @if($isPortrait)
                                    <!-- Portrait Images Layout (แนวตั้ง) -->
                                    @if(count($images) === 1)
                                        <div class="relative group overflow-hidden rounded-2xl shadow-md border border-white/10 dark:border-slate-800/80 max-w-sm mx-auto">
                                            <img src="{{ asset('storage/' . $images[0]) }}" 
                                                 @click="openLightbox(0)"
                                                 class="relative w-full cursor-zoom-in transition-transform duration-700 group-hover:scale-103 object-cover aspect-[3/4]" 
                                                 alt="{{ $post->title }}">
                                        </div>
                                    @elseif(count($images) === 2)
                                        <div class="grid grid-cols-2 gap-4 max-w-2xl mx-auto">
                                            @foreach($images as $index => $img)
                                                <div class="relative group overflow-hidden rounded-2xl shadow-sm border border-white/10 dark:border-slate-800/80">
                                                    <img src="{{ asset('storage/' . $img) }}" 
                                                         @click="openLightbox({{ $index }})"
                                                         class="w-full aspect-[3/4] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(count($images) === 3)
                                        <div class="grid grid-cols-3 gap-4">
                                            @foreach($images as $index => $img)
                                                <div class="relative group overflow-hidden rounded-2xl shadow-sm border border-white/10 dark:border-slate-800/80">
                                                    <img src="{{ asset('storage/' . $img) }}" 
                                                         @click="openLightbox({{ $index }})"
                                                         class="w-full aspect-[3/4] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- 4+ Portrait Images -->
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                            @foreach(array_slice($images, 0, 4) as $index => $img)
                                                <div class="relative group overflow-hidden rounded-2xl shadow-xs border border-white/10 dark:border-slate-800/80">
                                                    <img src="{{ asset('storage/' . $img) }}" 
                                                         @click="openLightbox({{ $index }})"
                                                         class="w-full aspect-[3/4] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                                    
                                                    @if($index === 3 && count($images) > 4)
                                                        <div @click.stop="openLightbox(3)" 
                                                             class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center cursor-pointer hover:bg-slate-950/80 transition-colors">
                                                            <span class="text-white text-base font-black tracking-wide">+{{ count($images) - 4 }} รูป</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <!-- Landscape Images Layout (แนวนอน) -->
                                    @if(count($images) === 1)
                                        <div class="relative group overflow-hidden rounded-2xl shadow-md border border-white/10 dark:border-slate-800/80">
                                            <img src="{{ asset('storage/' . $images[0]) }}" 
                                                 @click="openLightbox(0)"
                                                 class="relative w-full cursor-zoom-in transition-transform duration-700 group-hover:scale-103 object-cover h-[350px]" 
                                                 alt="{{ $post->title }}">
                                        </div>
                                    @elseif(count($images) === 2)
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach($images as $index => $img)
                                                <div class="relative group overflow-hidden rounded-2xl shadow-sm border border-white/10 dark:border-slate-800/80">
                                                    <img src="{{ asset('storage/' . $img) }}" 
                                                         @click="openLightbox({{ $index }})"
                                                         class="w-full h-[220px] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(count($images) === 3)
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="md:col-span-2 relative group overflow-hidden rounded-2xl shadow-sm border border-white/10 dark:border-slate-800/80">
                                                <img src="{{ asset('storage/' . $images[0]) }}" 
                                                     @click="openLightbox(0)"
                                                     class="w-full h-[280px] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                            </div>
                                            <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
                                                @foreach(array_slice($images, 1, 2) as $subIndex => $img)
                                                    <div class="relative group overflow-hidden rounded-xl shadow-xs border border-white/10 dark:border-slate-800/80">
                                                        <img src="{{ asset('storage/' . $img) }}" 
                                                             @click="openLightbox({{ $subIndex + 1 }})"
                                                             class="w-full h-[132px] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif(count($images) >= 4)
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <div class="md:col-span-8 relative group overflow-hidden rounded-2xl shadow-sm border border-white/10 dark:border-slate-800/80">
                                                <img src="{{ asset('storage/' . $images[0]) }}" 
                                                     @click="openLightbox(0)"
                                                     class="w-full h-[320px] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-103">
                                            </div>
                                            <div class="md:col-span-4 grid grid-cols-3 md:grid-cols-1 gap-4">
                                                @foreach(array_slice($images, 1, 3) as $subIndex => $img)
                                                    @php $index = $subIndex + 1; @endphp
                                                    <div class="relative group overflow-hidden rounded-xl shadow-xs border border-white/10 dark:border-slate-800/80">
                                                        <img src="{{ asset('storage/' . $img) }}" 
                                                             @click="openLightbox({{ $index }})"
                                                             class="w-full h-[98px] object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-105">
                                                        
                                                        @if($index === 3 && count($images) > 4)
                                                            <div @click.stop="openLightbox(3)" 
                                                                 class="absolute inset-0 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center cursor-pointer hover:bg-slate-950/80 transition-colors">
                                                                <span class="text-white text-base font-black tracking-wide">+{{ count($images) - 4 }} รูป</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Lightbox Modal -->
                                <div x-show="isOpen" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4 md:p-10" 
                                     style="display: none;">
                                    
                                    <button @click="isOpen = false" 
                                            class="absolute top-6 right-6 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all duration-300">
                                        <x-heroicon-s-x-mark class="w-6 h-6" />
                                    </button>

                                    <button x-show="images.length > 1"
                                            @click="prev()" 
                                            class="absolute left-6 top-1/2 -translate-y-1/2 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-4 rounded-full transition-all duration-300">
                                        <x-heroicon-s-chevron-left class="w-6 h-6" />
                                    </button>

                                    <button x-show="images.length > 1"
                                            @click="next()" 
                                            class="absolute right-6 top-1/2 -translate-y-1/2 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-4 rounded-full transition-all duration-300">
                                        <x-heroicon-s-chevron-right class="w-6 h-6" />
                                    </button>

                                    <div class="max-w-5xl max-h-[85vh] flex flex-col items-center">
                                        <img :src="activeImage" 
                                             class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl border border-white/10 animate-in zoom-in-95 duration-300">
                                        <p class="text-white/60 text-sm font-bold mt-4 tracking-widest uppercase">
                                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span> รูปภาพ
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Sidebar ( Latest Posts / กิจกรรมล่าสุด ) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- กิจกรรมล่าสุด Sidebar Card -->
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-white/50 dark:border-slate-800/80 shadow-[0_8px_30px_rgb(0,0,0,0.08)] rounded-2xl p-4 dark:bg-slate-900">
                        <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center">
                            <span class="w-2 h-8 bg-linear-to-b from-amber-500 to-amber-700 rounded-full mr-4 shadow-sm"></span>
                            กิจกรรมล่าสุด
                        </h2>

                        <div class="space-y-3">
                            @forelse($this->latestPosts as $lPost)
                                @php $lImages = $lPost->feature_images; @endphp
                                <a wire:navigate href="{{ route('posts.show', $lPost->slug) }}"
                                   class="group flex items-center p-3 bg-white/40 dark:bg-slate-800/40 border border-gray-100 dark:border-slate-800/80 rounded-2xl hover:bg-white dark:hover:bg-slate-800 hover:shadow-[0_4px_20px_rgb(0,0,0,0.05)] hover:-translate-y-0.5 hover:border-amber-200/60 dark:hover:border-amber-500/30 transition-all duration-300 relative overflow-hidden">

                                    <!-- Decorative Hover Background -->
                                    <div class="absolute inset-0 bg-linear-to-r from-amber-50/50 dark:from-amber-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                    @if(count($lImages) > 0)
                                        <div class="relative w-16 h-16 shrink-0 overflow-hidden rounded-xl shadow-sm mr-4">
                                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                src="{{ asset('storage/' . $lImages[0]) }}" alt="{{ $lPost->title }}">
                                        </div>
                                    @else
                                        <div class="relative w-16 h-16 shrink-0 flex items-center justify-center bg-linear-to-br from-gray-50 to-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm mr-4 group-hover:border-amber-200 transition-colors">
                                            <x-heroicon-o-photo class="w-6 h-6 text-gray-400 group-hover:text-amber-500 transition-colors" />
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0 relative z-10">
                                        <h3 class="text-gray-800 dark:text-slate-100 font-bold text-sm truncate group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                                            {{ $lPost->title }}
                                        </h3>
                                        <h5 class="text-gray-600 dark:text-slate-400 text-xs truncate group-hover:text-amber-700 dark:group-hover:text-amber-300 transition-colors mt-0.5">
                                            {!! Str::limit(strip_tags($lPost->excerpt ?? $lPost->content), 25, '...') !!}
                                        </h5>
                                        <div class="flex items-center text-[12px] text-gray-500 dark:text-slate-400 mt-1.5 gap-1.5">
                                            <x-heroicon-o-calendar-days class="w-3.5 h-3.5 text-amber-500" />
                                            <span class="tracking-wide">{{ $lPost->published_at?->format('F j, Y') ?? 'ไม่ได้ระบุวันที่' }}</span>
                                        </div>
                                    </div>

                                    <div class="ml-2 bg-white dark:bg-slate-900 rounded-full p-1.5 shadow-sm border border-gray-100 dark:border-slate-800 text-gray-400 group-hover:text-amber-600 group-hover:border-amber-200 group-hover:bg-amber-50 dark:group-hover:bg-slate-850 transition-all duration-300 relative z-10 group-hover:translate-x-1 shrink-0">
                                        <x-heroicon-m-chevron-right class="w-4 h-4" />
                                    </div>
                                </a>
                            @empty
                                <p class="text-slate-400 dark:text-slate-500 text-xs text-center py-4">ไม่มีกิจกรรมล่าสุด</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Share Card (แชร์บทความ) -->
                    <div class="w-full bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/80 rounded-3xl p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]"
                        x-data="{ copied: false }">
                        <div class="flex items-center gap-2.5 mb-4 text-slate-900 dark:text-white font-bold text-base">
                            <div class="p-1.5 bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-xl">
                                <x-heroicon-s-share class="w-5 h-5" />
                            </div>
                            <span>แชร์บทความ</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                            แบ่งปันบทความหรือข่าวสารนี้ไปยังช่องทางต่างๆ หรือคัดลอกลิงก์
                        </p>
                        <button type="button"
                            @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white text-slate-700 dark:text-slate-300 font-bold text-sm rounded-2xl transition-all duration-300 cursor-pointer shadow-xs active:scale-98">
                            <x-heroicon-s-link class="w-4 h-4" />
                            <span x-text="copied ? 'คัดลอกสำเร็จ!' : 'Copy Link'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related Posts Section -->
            @if ($this->relatedPosts->isNotEmpty())
                <section class="w-full bg-white dark:bg-white border-b border-gray-200/80 dark:border-slate-800 py-2">
                    <div class="max-w-7xl mx-auto ">
                        @include('components.⚡bloglist-page', [
                            'sectionTitle' => 'บทความที่เกี่ยวข้อง',
                            'posts' => $this->relatedPosts,
                            'showPagination' => false,
                        ])
                    </div>
                </section>
            @endif
        </div>
    </article>



    <!-- Comments Section -->
    <section class="w-full bg-gray-100 dark:bg-slate-900 border-b border-gray-200/80 dark:border-slate-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="shadow-sm bg-white dark:bg-slate-900 border border-gray-200/80 dark:border-slate-800 rounded-3xl py-10 px-6 sm:px-8 md:px-10">
                <div class="mb-12 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <!-- แสดงสถานะการ Login ด้วย Icon กระพริบ -->
                        <div class="flex items-center gap-2 mb-1">
                            @auth
                                <span class="relative flex h-3 w-3">
                                    <!-- วงแสงฟุ้งที่ขยายตัวออกมา -->
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <!-- จุดวงกลมหลักสีเขียวสว่าง -->
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.8)]"></span>
                                </span>
                                <span class="text-green-600 dark:text-green-400 font-bold text-xs uppercase tracking-widest">Logged In</span>
                            @else
                                <span class="text-amber-600 dark:text-amber-400 font-bold text-xs uppercase tracking-widest">Public Discussion</span>
                            @endauth
                        </div>

                        <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center">
                            <!-- Icon Section -->
                            <div class="mr-3 p-2 bg-amber-50 dark:bg-amber-950/40 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-6 h-6 text-amber-600 dark:text-amber-450">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 8.25h9m-9 3h9m-9 3h3m-6.75 4.125l-3 3m0 0l-3-3m3 3V1.5h3v19.5" />
                                </svg>
                            </div>
                            <!-- Vertical Line Indicator -->
                            <span class="w-1 h-8 bg-amber-500/30 rounded-full mr-3"></span>
                            ความคิดเห็นล่าสุด
                        </h2>
                    </div>

                    <!-- Total Counter Badge -->
                    <div
                        class="flex items-center gap-3 bg-white dark:bg-slate-900 border border-amber-100 dark:border-slate-800/80 shadow-xs px-5 py-2.5 rounded-2xl w-fit">
                        <span class="text-3xl font-black text-amber-600 dark:text-amber-450">
                            {{ $post->comments->where('parent_id', null)->count() }}
                        </span>
                        <div class="leading-tight border-l border-slate-200 dark:border-slate-800 pl-3">
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total</p>
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Comments</p>
                        </div>
                    </div>
                </div>

                <!-- Comment List -->
                @foreach ($post->comments->whereNull('parent_id')->where('is_approved', true) as $comment)
                    @include('components.comment.⚡comment', [
                        'post' => $post,
                        'comment' => $comment,
                    ])
                @endforeach

                {{-- 2. ฟอร์มสำหรับส่งคอมเมนต์ใหม่ --}}
                <div class="mt-16 pt-8 border-t border-slate-200/50 dark:border-slate-800/60">
                    <h3 class="text-xl font-bold text-slate-855 dark:text-white mb-8 flex items-center gap-3">
                        <span class="p-2 bg-amber-50 dark:bg-amber-950/40 rounded-xl">
                            <x-heroicon-s-chat-bubble-left-right class="w-5 h-5 text-amber-650" />
                        </span>
                        ร่วมแสดงความคิดเห็น
                    </h3>

                    @if (session()->has('message'))
                        <div
                            class="p-4 mb-6 text-sm text-green-800 dark:text-green-300 rounded-2xl bg-green-50 dark:bg-green-950/20 border border-green-100 dark:border-green-900/30 flex items-center gap-2">
                            <x-heroicon-s-check-circle class="w-5 h-5 text-green-500" />
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div
                            class="p-4 mb-4 text-sm text-red-800 dark:text-red-300 rounded-2xl bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/30 flex items-center gap-2">
                            <x-heroicon-s-exclamation-circle class="w-5 h-5 text-red-500" />
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex gap-4">
                        <!-- User Avatar -->
                        <div class="hidden sm:block shrink-0">
                            @auth
                                <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=FFFFFF&background=d97706' }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 dark:ring-slate-800">
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                                    <x-heroicon-s-user class="w-6 h-6 text-slate-400 dark:text-slate-500" />
                                </div>
                            @endauth
                        </div>

                        <!-- Form Body -->
                        <form wire:submit.prevent="store()" class="flex-1 space-y-4">
                            @guest
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative group">
                                        <x-heroicon-o-user
                                            class="w-5 h-5 absolute left-3 top-3 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors" />
                                        <input type="text" wire:model="guest_name" placeholder="ชื่อของคุณ"
                                            class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-855 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none text-slate-800 dark:text-slate-200 transition-all">
                                        @error('guest_name')
                                            <span class="text-red-550 text-xs mt-1 block ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="relative group">
                                        <x-heroicon-o-envelope
                                            class="w-5 h-5 absolute left-3 top-3 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors" />
                                        <input type="email" wire:model="guest_email" placeholder="อีเมลของคุณ"
                                            class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-855 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none text-slate-800 dark:text-slate-200 transition-all">
                                        @error('guest_email')
                                            <span class="text-red-550 text-xs mt-1 block ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endguest

                            <div class="relative group">
                                <textarea rows="3" wire:model="body" placeholder="เขียนความคิดเห็นที่นี่..."
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-855 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none text-slate-800 dark:text-slate-200 transition-all resize-none"></textarea>
                                @error('body')
                                    <span class="text-red-550 text-xs mt-1 block ml-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-500 text-white font-semibold rounded-xl shadow-sm transition-all duration-300 group">
                                    <span wire:loading.remove class="flex items-center gap-2">
                                        ส่งความคิดเห็น
                                        <x-heroicon-s-paper-airplane
                                            class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                    </span>
                                    <span wire:loading class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        กำลังส่ง...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
