@extends('layouts.front')

@section('title', 'المدونة')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/blog_styles.css') }}">
@endpush

@section('content')
    <main class="main-content">
        <section class="blog-posts-section section-padding">
            <div class="container blog-grid-container">
                <div class="blog-main-content">
                    @if ($mainBlog)
                        <article class="featured-post main-card interactive-card">
                            <div class="featured-post__image">
                                <img src="{{ asset($mainBlog->image) }}" alt="{{ $mainBlog->title }}">
                                <div class="featured-post__overlay"></div>
                                <div class="featured-post__content">
                                    <h3 class="post-title">{{ $mainBlog->title }}</h3>
                                    <p class="post-excerpt">{{ $mainBlog->excerpt }}</p>
                                    <a href="{{ route('front.blog.show', $mainBlog->id) }}"
                                        class="btn-read-more interactive-button new_more">اقرأ المزيد</a>
                                </div>
                            </div>
                        </article>
                    @else
                        <p>لا توجد مقالات مميزة متاحة حاليًا.</p>
                    @endif

                    @if ($blogs->isNotEmpty())
                        <div class="posts-listing-grid">
                            @foreach ($blogs as $blog)
                                <article class="post-card main-card interactive-card">
                                    <div class="post-card__image">
                                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                                    </div>
                                    <div class="post-card__content">
                                        <h4 class="post-card__title">{{ $blog->title }}</h4>
                                        <p class="post-card__excerpt">{{ $blog->excerpt }}</p>
                                        <div class="post-card__meta">
                                            <span><i class="fas fa-calendar-alt"></i>
                                                {{ $blog->date ? \App\Helpers\ArabicDate::format($blog->date) : 'غير متوفر' }}</span>
                                            <span><i class="fas fa-user-edit"></i> فريق المدونة</span>
                                        </div>
                                        <a href="{{ route('front.blog.show', $blog->id) }}"
                                            class="btn-read-more interactive-button">اقرأ المزيد</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p>لا توجد مقالات متاحة حاليًا.</p>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/blog.js') }}"></script>
@endpush
