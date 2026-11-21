<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component {
    //
};
?>

<div>
    <!-- Blog Posts Grid -->
    <section class="mx-auto sm:p-5 md:p-3 mb-2 relative font-[sarabun]">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
            <div class="sm:col-span-6 lg:col-span-9 p-2 shadow-2xl rounded-2xl dark:bg-slate-900">
                <h2 class="text-xl font-extrabold text-[#7a1a40] mb-6 flex items-center">
                    <span class="w-1.5 h-6 bg-[#7a1a40] rounded-full mr-3"></span>
                    {{-- โพสล่าสุด (Latest Posts) --}}
                    {{ $sectionTitle }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Blog Post Card -->
                    @foreach ($posts as $post)
                        <div class="group flex flex-col bg-white/70 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/80 shadow-[0_8px_30px_rgba(0,0,0,0.02)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.15)] rounded-2xl overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.06)] dark:hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:-translate-y-1.5 transition-all duration-500 ease-out relative">
                            <!-- Image Section -->
                            <div class="relative h-56 overflow-hidden">
                                @php $cardImages = $post->feature_images; @endphp
                                @if(count($cardImages) > 0)
                                    <img src="{{ asset('storage/' . $cardImages[0]) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out" alt="{{ $post->title }}" />
                                @else
                                    <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <x-heroicon-o-photo class="w-8 h-8" />
                                    </div>
                                @endif

                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-linear-to-t from-slate-950/50 via-transparent to-transparent opacity-80 group-hover:opacity-65 transition-opacity duration-500"></div>

                                <!-- Photo Count Badge -->
                                @if(count($cardImages) > 1)
                                    <div class="absolute top-4 right-4 z-10 flex items-center gap-1 px-2.5 py-1 bg-black/60 dark:bg-slate-950/60 backdrop-blur-md text-white text-[11px] font-black tracking-wider rounded-xl border border-white/10 shadow-xs">
                                        <x-heroicon-s-camera class="w-3.5 h-3.5 text-amber-500" />
                                        <span>{{ count($cardImages) }}</span>
                                    </div>
                                @endif

                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4 flex flex-wrap gap-2 z-10">
                                    @foreach ($post->categories as $category)
                                        <a wire:navigate href="{{ route('category.show', $category) }}" class="px-3 py-1.5 bg-slate-900/60 dark:bg-black/40 backdrop-blur-md text-white text-xs font-bold tracking-wider rounded-xl border border-white/10 hover:bg-[#7a1a40] hover:border-[#7a1a40] hover:text-white transition-all duration-300 shadow-sm">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="p-6 flex flex-col flex-1">
                                <!-- Title -->
                                <h3 class="text-[17px] font-black text-slate-800 dark:text-slate-100 leading-snug mb-3 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors duration-300 line-clamp-2">
                                    <a wire:navigate href="{{ route('posts.show', $post) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 line-clamp-3 leading-relaxed flex-1">
                                    {{ $post->excerpt }}
                                </p>

                                <!-- Footer (Author & Read More) -->
                                <div class="mt-auto pt-5 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                                    <!-- Author Info -->
                                    <div class="flex items-center gap-3">
                                        <div class="shrink-0 relative">
                                            @if ($post->author && $post->author->profile_photo)
                                                <img src="{{ asset('storage/' . $post->author->profile_photo) }}" alt="{{ $post->author->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-100 dark:ring-amber-500/20 shadow-xs">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'ผู้ใช้ไม่ระบุชื่อ') }}&color=d97706&background=fef3c7" alt="Author" class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-100 dark:ring-amber-500/20 shadow-xs">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 leading-none">
                                                {{ $post->author ? $post->author->name : 'ผู้ใช้ไม่ระบุชื่อ' }}
                                            </p>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1.5 text-[10px] text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider">
                                                <span class="flex items-center gap-1">
                                                    <x-heroicon-o-calendar-days class="w-3.5 h-3.5 text-[#7a1a40]" />
                                                    {{ $post->published_at->format('j M Y') }}
                                                </span>
                                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                                <span class="flex items-center gap-1">
                                                    <x-heroicon-o-clock class="w-3.5 h-3.5 text-[#7a1a40]" />
                                                    {{ $post->published_at?->diffForHumans() ?? 'ไม่ได้ระบุวันที่' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Read More Button -->
                                    <a wire:navigate href="{{ route('posts.show', $post) }}" class="w-10 h-10 shrink-0 rounded-full bg-rose-50 dark:bg-rose-950/40 flex items-center justify-center text-[#7a1a40] dark:text-rose-400 group-hover:bg-[#7a1a40] group-hover:text-white dark:group-hover:bg-[#7a1a40] transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 -rotate-45 group-hover:rotate-0 transition-transform duration-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
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
                        {{ $posts->links(data: ['scrollTo' => '#latest-posts']) }}
                    </div>
                @endif

            </div>

            {{-- Category Sidebar --}}
            <div class="sm:col-span-6 lg:col-span-3 p-2">
                <div class="bg-white/80 backdrop-blur-xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.08)] rounded-2xl p-2 sticky top-6 dark:bg-slate-900">
                    <h2 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center">
                        <span class="w-2 h-8 bg-linear-to-b from-[#7a1a40] to-rose-700 rounded-full mr-4 shadow-sm"></span>
                        หมวดหมู่บทความ
                    </h2>

                    <div class="space-y-3">
                        @php
                            $sidebarCategories = $categories ?? Category::all();
                        @endphp
                        @if ($sidebarCategories->isNotEmpty())
                            @foreach ($sidebarCategories as $category)
                                <a wire:navigate href="{{ route('category.show', $category) }}"
                                   class="group flex items-center p-3 bg-white/40 border border-gray-100 rounded-2xl hover:bg-white hover:shadow-[0_4px_20px_rgb(0,0,0,0.05)] hover:-translate-y-0.5 hover:border-rose-200 transition-all duration-300 relative overflow-hidden">

                                    <!-- Decorative Hover Background -->
                                    <div class="absolute inset-0 bg-linear-to-r from-rose-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                    @if ($category->image != null)
                                        <div class="relative w-16 h-16 shrink-0 overflow-hidden rounded-xl shadow-sm mr-4">
                                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                        </div>
                                    @else
                                        <div class="relative w-16 h-16 shrink-0 flex items-center justify-center bg-linear-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl shadow-sm mr-4 group-hover:border-rose-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover:text-[#7a1a40] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0 relative z-10">
                                        <h3 class="text-gray-800 font-bold text-sm truncate group-hover:text-[#7a1a40] transition-colors">
                                            {{ $category->name }}
                                        </h3>
                                        <h5 class="text-gray-800 text-sm truncate group-hover:text-[#7a1a40] transition-colors">
                                            {!! Str::limit($category->description, 20, '...') !!}
                                        </h5>
                                        <div class="flex items-center text-[13px] text-gray-500 mt-1.5 gap-1.5">
                                            <x-heroicon-o-calendar-days class="w-3.5 h-3.5" />
                                            <span class="tracking-wide">{{ $category->created_at->format('F j, Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="ml-2 bg-white rounded-full p-1.5 shadow-sm border border-gray-100 text-gray-400 group-hover:text-[#7a1a40] group-hover:border-rose-200 group-hover:bg-rose-50 transition-all duration-300 relative z-10 group-hover:translate-x-1">
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
