<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component {
    //
};
?>

<div>
    <!-- Blog Posts Grid -->
    <section class="mx-auto p-2 sm:p-5 md:p-3 mb-15 relative shadow-2xl rounded-2xl font-[sarabun]">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 shadow rounded-2xl">
            <div class="sm:col-span-6 lg:col-span-9 p-2 shadow-2xl rounded-2xl">
                <h2 class="text-lg font-bold text-amber-900 mb-6 flex items-center">
                    <span class="w-1.5 h-6 bg-amber-600 rounded-full mr-3"></span>
                    {{-- โพสล่าสุด (Latest Posts) --}}
                    {{ $sectionTitle }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    <!-- Blog Post 1 -->
                    @foreach ($posts as $post)
                        <div class="group bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl shadow-2xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-500">
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('storage/' . $post->feature_image) }}" class="w-full h-56 object-cover transform group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-linear-to-t from-white/20 to-transparent"></div>
                            </div>

                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($post->categories as $category)
                                            <span class="bg-blue-50 text-blue-600 text-sm font-bold tracking-wider px-3 py-1 rounded-2xl border border-blue-100">
                                                <a wire:navigate href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
                                            </span>
                                        @endforeach
                                    </div>
                                    <span class="text-[13px] text-slate-500 font-medium flex items-center gap-1">
                                        <x-heroicon-o-calendar-days class="w-3.5 h-3.5" />
                                        {{ $post->published_at->format('F j, Y') }}
                                    </span>
                                </div>

                                <h3 class="text-md font-bold text-slate-800 leading-snug group-hover:text-blue-600 transition-colors">
                                    <a wire:navigate href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                </h3>
                                {{-- <hr class="my-4 border-slate-300" /> --}}
                                <p class="text-slate-600 text-[14px] mb-4 line-clamp-3 leading-relaxed">
                                    {{ $post->excerpt }}
                                </p>

                                <div class="mt-6 flex items-center">
                                    <div class="shrink-0">
                                        @if ($post->author && $post->author->profile_photo)
                                            <img src="{{ asset('storage/' . $post->author->profile_photo) }}" alt="{{ $post->author->name }}" class="w-10 h-10 rounded-full mx-auto object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'ผู้ใช้ไม่ระบุชื่อ') }}&color=7F9CF5&background=EBF4FF" alt="Author" class="w-10 h-10 rounded-full mx-auto object-cover">
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            เขียนโดย
                                            @if ($post->author)
                                                <span>{{ $post->author->name }}</span>
                                            @else
                                                <span>ผู้ใช้ไม่ระบุชื่อ</span>
                                            @endif
                                        </p>
                                        <div class="flex-cols-2 space-x-1 text-sm text-gray-500">
                                            <div>อัปเดตเมื่อ {{ $post->published_at->format('F j, Y') }}</div>
                                            <div>{{ $post->published_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 pt-4 border-t border-slate-300/60 flex justify-end">
                                    <a wire:navigate href="{{ route('posts.show', $post) }}"
                                        class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-700 transition-all">
                                        อ่านต่อ
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- pagination --}}
                @if ($showPagination ?? true)
                    <div class="mt-8 flex justify-end py-4 border-t border-slate-200/50">
                        {{ $posts->links() }}
                    </div>
                @endif

            </div>

            {{-- Category Sidebar --}}
            <div class="sm:col-span-6 lg:col-span-3 p-2">
                <div class="bg-white/80 backdrop-blur-xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.08)] rounded-2xl p-2 sticky top-6">
                    <h2 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center">
                        <span class="w-2 h-8 bg-linear-to-b from-amber-500 to-amber-700 rounded-full mr-4 shadow-sm"></span>
                        หมวดหมู่บทความ
                    </h2>

                    <div class="space-y-3">
                        @php
                            $sidebarCategories = $categories ?? Category::all();
                        @endphp
                        @if ($sidebarCategories->isNotEmpty())
                            @foreach ($sidebarCategories as $category)
                                <a wire:navigate href="{{ route('category.show', $category) }}"
                                   class="group flex items-center p-3 bg-white/40 border border-gray-100 rounded-2xl hover:bg-white hover:shadow-[0_4px_20px_rgb(0,0,0,0.05)] hover:-translate-y-0.5 hover:border-amber-200/60 transition-all duration-300 relative overflow-hidden">

                                    <!-- Decorative Hover Background -->
                                    <div class="absolute inset-0 bg-linear-to-r from-amber-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                    @if ($category->image != null)
                                        <div class="relative w-16 h-16 shrink-0 overflow-hidden rounded-xl shadow-sm mr-4">
                                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                        </div>
                                    @else
                                        <div class="relative w-16 h-16 shrink-0 flex items-center justify-center bg-linear-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl shadow-sm mr-4 group-hover:border-amber-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0 relative z-10">
                                        <h3 class="text-gray-800 font-bold text-sm truncate group-hover:text-amber-700 transition-colors">
                                            {{ $category->name }}
                                        </h3>
                                        <h5 class="text-gray-800 text-sm truncate group-hover:text-amber-700 transition-colors">
                                            {!! Str::limit($category->description, 20, '...') !!}
                                        </h5>
                                        <div class="flex items-center text-[13px] text-gray-500 mt-1.5 gap-1.5">
                                            <x-heroicon-o-calendar-days class="w-3.5 h-3.5" />
                                            <span class="tracking-wide">{{ $category->created_at->format('F j, Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="ml-2 bg-white rounded-full p-1.5 shadow-sm border border-gray-100 text-gray-400 group-hover:text-amber-600 group-hover:border-amber-200 group-hover:bg-amber-50 transition-all duration-300 relative z-10 group-hover:translate-x-1">
                                        <x-heroicon-m-chevron-right class="w-4 h-4" />
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
    </section>
</div>
