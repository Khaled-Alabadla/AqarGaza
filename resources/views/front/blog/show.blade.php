@extends('layouts.front')

@section('title', $blog->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/blog_info_styles.css') }}">
@endpush

@section('content')
    <main class="main-content">
        <section class="post-content-section section-padding">
            <div class="container post-grid-container">
                <article class="post-main-article">
                    <div class="post-meta-info">
                        <span><i class="fas fa-calendar-alt"></i>
                            {{ $blog->date ? \App\Helpers\ArabicDate::format($blog->date) : 'غير متوفر' }}</span>
                    </div>
                    <h1>{{ $blog->title }}</h1>
                    {!! $blog->content !!}
                </article>

                <aside class="post-sidebar">
                    <div class="sidebar-block main-card">
                        <h3>مقالات شائعة</h3>
                        <ul class="popular-posts-list">
                            @forelse ($popularPosts as $post)
                                <li>
                                    <a href="{{ route('front.blog.show', $post->id) }}">
                                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                                        <div class="post-details">
                                            <h4>{{ $post->title }}</h4>
                                            <span><i class="fas fa-calendar-alt"></i>
                                                {{ $post->date ? \App\Helpers\ArabicDate::format($post->date) : 'غير متوفر' }}</span>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li>لا توجد مقالات شائعة متاحة.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="sidebar-block main-card">
                        <h3>مقالات ذات صلة</h3>
                        <ul class="related-posts-list">
                            @forelse ($relatedPosts as $post)
                                <li><a href="{{ route('front.blog.show', $post->id) }}">{{ $post->title }}</a></li>
                            @empty
                                <li>لا توجد مقالات ذات صلة متاحة.</li>
                            @endforelse
                        </ul>
                    </div>
                </aside>
            </div>
        </section>

        <section class="post-navigation-section section-padding">
            <div class="container post-nav-container">
                @if ($previousPost)
                    <a href="{{ route('front.blog.show', $previousPost->id) }}" class="nav-post-btn prev-post">
                        <i class="fas fa-arrow-right"></i> {{ Str::limit($previousPost->title, 30) }}
                    </a>
                @else
                    <span class="nav-post-btn prev-post disabled"><i class="fas fa-arrow-right"></i> لا يوجد مقال
                        سابق</span>
                @endif

                <a href="{{ route('front.blog.index') }}" class="nav-post-btn back-to-all-posts">
                    <i class="fas fa-th-large"></i> كل المقالات
                </a>

                @if ($nextPost)
                    <a href="{{ route('front.blog.show', $nextPost->id) }}" class="nav-post-btn next-post">
                        {{ Str::limit($nextPost->title, 30) }} <i class="fas fa-arrow-left"></i>
                    </a>
                @else
                    <span class="nav-post-btn next-post disabled"><i class="fas fa-arrow-left"></i> لا يوجد مقال تالي</span>
                @endif
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/blog.js') }}"></script>
@endpush
