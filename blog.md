<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
    <!-- Blog Post 1 -->
    @foreach ($this->posts as $post)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <div class="bg-gray-50">
                <img src="{{ asset('storage/' . $post->feature_image) }}" alt="Blog Post 1" class="w-full h-48 cursor-pointer object-cover object-top" />
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
                    @foreach ($post->categories as $category)
                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                            <a href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
                        </span>
                    @endforeach
                    {{-- <span>{{ $post->published_at->format('d-m-Y') }}</span> --}}
                    <span
                        class="text-[12px] text-slate-400 font-medium">{{ $post->published_at->format('M d, Y') }}</span>
                </div>
                <h3 class="text-base font-semibold text-slate-900">
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h3>
                <hr class="my-4 border-slate-300" />
                <p class="text-slate-600 text-[15px] mb-4 line-clamp-3 leading-relaxed">
                    {{ $post->exercpt }}</p>
                <a href="{{ route('posts.show', $post) }}"
                    class="text-blue-600 font-semibold hover:text-blue-700">Read More →</a>
            </div>
        </div>
    @endforeach
</div>
=======
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
    <!-- Blog Post 1 -->
    @foreach ($this->posts as $post)
        <div class="group bg-white/70 backdrop-blur-md border border-white/20 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-500 ease-out">
            <div class="relative overflow-hidden">
                <img src="{{ asset('storage/' . $post->feature_image) }}" alt="{{ $post->title }}"
                    class="w-full h-52 object-cover object-top transform group-hover:scale-105 transition-transform duration-500" />
                <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($post->categories as $category)
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                                <a href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
                            </span>
                        @endforeach
                    </div>
                    <span class="text-[12px] text-slate-400 font-medium">{{ $post->published_at->format('M d, Y') }}</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 leading-snug group-hover:text-blue-600 transition-colors">
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h3>
                {{-- <hr class="my-4 border-slate-300" /> --}}
                <p class="text-slate-600 text-[15px] mb-4 line-clamp-3 leading-relaxed">
                    {{ $post->exercpt }}
                </p>
                <div class="mt-5 pt-4 border-t border-slate-300/60 flex justify-end">
                    <a href="{{ route('posts.show', $post) }}"
                        class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-700 transition-all">
                        Read More
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
