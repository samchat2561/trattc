<?php

use Livewire\Component;
use App\Models\Image;
use Livewire\Attributes\Title;

new class extends Component {
    public Image $imageRecord;

    public function mount(Image $image)
    {
        $this->imageRecord = $image;
        $this->imageRecord->increment('view_count');
    }

    public function getTitleProperty()
    {
        return $this->imageRecord->title . ' - คลังภาพ';
    }
};
?>

<div class="max-w-7xl mx-auto bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/50
 dark:border-slate-700/50 shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-2xl mt-36 mb-10 overflow-hidden"
    x-data="{
        lightboxOpen: false,
        activeImage: '',
        openLightbox(src) {
            this.activeImage = src;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            setTimeout(() => { document.body.style.overflow = ''; }, 300);
        }
    }" @keydown.escape.window="closeLightbox()">

    <!-- Title Meta -->
    <x-slot:title>{{ $this->title }}</x-slot>

    <!-- Breadcrumb -->
    <nav
        class="bg-slate-50/50 dark:bg-slate-800/50 backdrop-blur-md border-b border-slate-100 dark:border-slate-700/50 px-6 py-4 md:px-10">
        <ol class="flex items-center space-x-2 md:space-x-3 text-sm font-medium text-slate-500 dark:text-slate-400">
            <li>
                <a wire:navigate href="{{ route('home') }}"
                    class="flex items-center gap-1.5 hover:text-blue-600 transition-colors duration-300">
                    <x-heroicon-s-home class="w-4 h-4" />
                    <span class="hidden sm:inline">หน้าหลัก</span>
                </a>
            </li>
            <li><x-heroicon-s-chevron-right class="w-4 h-4 text-slate-300 dark:text-slate-600" /></li>
            <li>
                <span
                    class="text-slate-800 dark:text-slate-200 font-semibold max-w-50 md:max-w-md lg:max-w-lg truncate block"
                    aria-current="page">
                    คลังภาพ
                </span>
            </li>
        </ol>
    </nav>

    <!-- Article Header -->
    <article class="bg-white dark:bg-slate-900 p-8 md:p-12">

        <div class="mb-10 text-center max-w-4xl mx-auto">
            <h1
                class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white leading-[1.2] tracking-tight mb-6">
                <span class="bg-clip-text text-transparent bg-linear-to-r from-blue-600 to-indigo-600">
                    {{ $imageRecord->title }}
                </span>
            </h1>
            <div class="flex items-center justify-center gap-3 text-slate-500 dark:text-slate-400 text-sm font-medium">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-500" />
                <span>{{ $imageRecord->published_at?->format('j M Y') ?? 'ไม่ได้ระบุวันที่' }}</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600 mx-2"></span>
                <x-heroicon-o-eye class="w-5 h-5" />
                <span>{{ $imageRecord->view_count }} Views</span>
            </div>

            @if ($imageRecord->excerpt)
                <p class="mt-6 text-lg text-slate-600 dark:text-slate-300 font-medium">
                    {{ $imageRecord->excerpt }}
                </p>
            @endif
        </div>

        @php
            $mediaItems = $imageRecord->getMedia('images');
        @endphp

        <!-- Image Slider Section -->
        @if ($mediaItems->count() > 0)
            <div class="max-w-5xl mx-auto mb-16">
                <div x-data="{
                    activeSlide: 0,
                    slides: {{ $mediaItems->count() }},
                    next() { this.activeSlide = (this.activeSlide + 1) % this.slides; },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides; }
                }" class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-900 group">

                    <!-- Main Slider Container -->
                    <div class="relative h-100 md:h-150 w-full flex transition-transform duration-700 ease-out"
                        :style="`transform: translateX(-${activeSlide * 100}%)`">

                        @foreach ($mediaItems as $index => $media)
                            <div class="min-w-full h-full relative cursor-zoom-in"
                                @click="openLightbox('{{ $media->getUrl() }}')">
                                <img src="{{ $media->getUrl() }}" alt="Image {{ $index + 1 }}"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent pointer-events-none">
                                </div>
                                <div
                                    class="absolute bottom-6 left-6 text-white font-medium bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 pointer-events-none">
                                    <span class="flex items-center gap-2">
                                        <x-heroicon-o-magnifying-glass-plus class="w-5 h-5 text-blue-400" />
                                        คลิกเพื่อขยายรูปภาพ
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Controls -->
                    @if ($mediaItems->count() > 1)
                        <button @click="prev()"
                            class="absolute top-1/2 left-4 -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/30 backdrop-blur-xl border
                             border-white/20 rounded-full text-white transition-all opacity-0 group-hover:opacity-100 hover:scale-110 shadow-lg">
                            <x-heroicon-o-chevron-left class="w-7 h-7 stroke-2" />
                        </button>
                        <button @click="next()"
                            class="absolute top-1/2 right-4 -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/30 backdrop-blur-xl border
                             border-white/20 rounded-full text-white transition-all opacity-0 group-hover:opacity-100 hover:scale-110 shadow-lg">
                            <x-heroicon-o-chevron-right class="w-7 h-7 stroke-2" />
                        </button>

                        <!-- Thumbnails -->
                        <div
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 bg-black/40 backdrop-blur-md px-4 py-2 rounded-2xl">
                            @foreach ($mediaItems as $index => $media)
                                <button @click="activeSlide = {{ $index }}"
                                    class="w-16 h-12 rounded-lg overflow-hidden border-2 transition-all shadow-md focus:outline-none"
                                    :class="activeSlide === {{ $index }} ? 'border-blue-400 scale-110 opacity-100' :
                                        'border-transparent opacity-50 hover:opacity-100'">
                                    <img src="{{ $media->getUrl() }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Content -->
        @if ($imageRecord->content)
            <div
                class="prose prose-lg max-w-4xl mx-auto text-slate-700 dark:text-slate-300 leading-relaxed p-8 bg-slate-50
                 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-inner">
                {!! $imageRecord->content !!}
            </div>
        @endif

    </article>

    <!-- Lightbox Modal -->
    <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-100 flex items-center justify-center bg-black/95 backdrop-blur-2xl"
        style="display: none;" @click="closeLightbox()">

        <button @click.stop="closeLightbox()"
            class="absolute top-6 right-6 z-50 p-3 bg-white/10 hover:bg-red-500/80 rounded-full text-white transition-all shadow-lg hover:rotate-90 duration-300">
            <x-heroicon-o-x-mark class="w-8 h-8" />
        </button>

        <!-- The clicked image -->
        <img :src="activeImage"
            class="w-full h-full object-cover cursor-zoom-out" @click.stop="closeLightbox()">
    </div>
</div>
