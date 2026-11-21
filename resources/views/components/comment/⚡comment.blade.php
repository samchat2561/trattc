<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="group relative mb-8 transition-all duration-500 {{ $comment->parent_id ? 'ml-6 md:ml-16 mt-4' : 'border-b border-slate-100 dark:border-slate-800/60 pb-8 last:border-0' }}">
    {{-- เส้นเชื่อมกรณีเป็น Reply --}}
    @if ($comment->parent_id)
        <div class="absolute -left-6 top-0 bottom-1/2 w-px bg-slate-200 dark:bg-slate-700 rounded-full"></div>
        <div class="absolute -left-6 top-1/2 w-4 h-px bg-slate-200 dark:bg-slate-700"></div>
    @endif

    <div class="flex items-start gap-4">
        {{-- Avatar Section --}}
        <div class="relative shrink-0">
            <img src="{{ $comment->user && $comment->user->profile_photo
                ? asset('storage/' . $comment->user->profile_photo)
                : 'https://ui-avatars.com/api/?name=' .
                    urlencode($comment->user ? $comment->user->name : $comment->guest_name) .
                    '&color=FFFFFF&background=d97706' }}"
                alt="Avatar"
                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl object-cover shadow-xs ring-2 ring-white dark:ring-slate-800 transition-transform duration-300 group-hover:scale-105">
        </div>

        {{-- Content Section --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h4 class="font-extrabold text-slate-800 dark:text-slate-100 text-base flex items-center gap-2">
                        {{ $comment->user ? $comment->user->name : $comment->guest_name }}
                        @if ($comment->user_id === $post->user_id)
                            <span
                                class="text-[10px] bg-amber-100 dark:bg-amber-950/45 text-amber-700 dark:text-amber-400 px-2 py-0.5 rounded-full uppercase tracking-wider font-black">Author</span>
                        @endif
                    </h4>
                    <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500 font-semibold">
                        <x-heroicon-o-clock class="w-3 h-3 text-slate-350 dark:text-slate-655" />
                        {{ $comment->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    {{-- ปุ่ม Like --}}
                    <button wire:click="toggleLike({{ $comment->id }})"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl transition-all duration-300 {{ $comment->isLikedBy(auth()->id()) ? 'bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-450' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 dark:text-slate-500' }}">
                        <x-heroicon-o-heart class="w-4 h-4 {{ $comment->isLikedBy(auth()->id()) ? 'hidden' : '' }}" />
                        <x-heroicon-s-heart class="w-4 h-4 {{ $comment->isLikedBy(auth()->id()) ? '' : 'hidden' }}" />
                        <span class="text-xs font-bold">{{ $comment->likes_count ?? $comment->likes()->count() }}</span>
                    </button>

                    {{-- ปุ่ม Reply --}}
                    <button
                        onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')"
                        class="p-2 text-slate-400 dark:text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30 rounded-xl transition-all duration-300">
                        <x-heroicon-m-arrow-uturn-left class="w-4 h-4" />
                    </button>
                </div>
            </div>

            {{-- Message Bubble --}}
            <div
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 p-5 rounded-3xl rounded-tl-none shadow-[0_4px_20px_rgba(0,0,0,0.02)] text-[15px] mb-4">
                {{ $comment->body }}
            </div>

            {{-- Reply Form --}}
            <div>
               <form id="reply-form-{{ $comment->id }}" wire:submit.prevent="store({{ $comment->id }})"
                class="mt-4 space-y-4 hidden bg-white dark:bg-slate-900 p-6 rounded-2xl border border-amber-250/40 dark:border-slate-800 shadow-[0_8px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.25)] animate-in fade-in slide-in-from-top-2 duration-300">

                <h5 class="font-bold text-slate-800 dark:text-slate-100 text-sm flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-amber-450 rounded-full"></span>
                    ตอบกลับความคิดเห็นของ {{ $comment->user ? $comment->user->name : $comment->guest_name }}
                </h5>

                @guest
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-655 dark:text-slate-400 mb-1 uppercase tracking-wider">ชื่อของคุณ</label>
                            <input type="text" wire:model="guest_name" placeholder="ระบุชื่อ..."
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 outline-none text-slate-850 dark:text-slate-200 transition-all">
                            @error('guest_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-655 dark:text-slate-400 mb-1 uppercase tracking-wider">อีเมล (จะไม่แสดงผล)</label>
                            <input type="email" wire:model="guest_email" placeholder="example@mail.com"
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 outline-none text-slate-850 dark:text-slate-200 transition-all">
                            @error('guest_email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-350 bg-slate-50 dark:bg-slate-950 py-2 px-3 rounded-lg border border-slate-100 dark:border-slate-800/80">
                        <x-heroicon-s-user class="w-4 h-4 text-amber-500" />
                        ตอบกลับในชื่อ <strong>{{ auth()->user()->name }}</strong>
                    </div>
                @endguest

                <div>
                    <label class="block text-xs font-bold text-slate-655 dark:text-slate-400 mb-1 uppercase tracking-wider">ข้อความ</label>
                    <textarea
                    rows="3"
                    wire:model="body"
                    placeholder="พิมพ์ข้อความตอบกลับที่นี่..."
                    class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 outline-none text-slate-850 dark:text-slate-200 transition-all"></textarea>
                    @error('body') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button"
                        onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.add('hidden')"
                        class="px-5 py-2 text-sm font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-850 rounded-xl transition-all">
                        ยกเลิก
                    </button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-amber-500 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition-all duration-300 flex items-center gap-2">
                        <span wire:loading.remove wire:target="store({{ $comment->id }})">ส่งคำตอบ</span>
                        <span wire:loading wire:target="store({{ $comment->id }})">กำลังส่ง...</span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

{{-- Recursive Replies Section --}}
@if ($comment->replies->where('is_approved', true)->count() > 0)
    <div class="ml-6 md:ml-12 mt-2 space-y-6 border-l-2 border-slate-100 dark:border-slate-800 pl-4 md:pl-8">
        @foreach ($comment->replies->where('is_approved', true) as $reply)
            @include('components.comment.⚡comment', [
                'comment' => $reply,
                'post' => $post,
            ])
        @endforeach
    </div>
@endif
