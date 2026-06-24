<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Daily AI-generated insights on software engineering, cloud architecture, and the technologies shaping tomorrow.">
    <title>{{ config('app.name') }} — AI-Powered Tech Blog</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Lora', 'Georgia', 'serif'],
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        ink: {
                            DEFAULT: '#1a1a1a',
                            light: '#292929',
                            muted: '#6b6b6b',
                            faint: '#9b9b9b',
                        },
                        accent: '#1a8917',
                        surface: '#fafafa',
                        border: '#e6e6e6',
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .font-serif { font-family: 'Lora', Georgia, serif; }
        .tag-pill {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: #f0faf0;
            color: #1a8917;
            border: 1px solid #c3e6c3;
        }
        .card-hover {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .divider {
            width: 40px;
            height: 3px;
            background: #1a8917;
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-white text-ink antialiased">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-border">
        <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ route('blog.index') }}" class="flex items-center gap-2.5">
                <svg class="w-7 h-7 text-accent" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                </svg>
                <span class="font-serif font-semibold text-xl text-ink tracking-tight">{{ config('app.name') }}</span>
            </a>

            <div class="flex items-center gap-6">
                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-ink-muted bg-surface border border-border rounded-full px-3 py-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                    New post every night
                </span>
                <a href="https://github.com/roshanb08/ai-blog-generator" target="_blank" rel="noopener" class="text-ink-muted hover:text-ink transition-colors" aria-label="GitHub">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero / Site Header --}}
    <header class="border-b border-border bg-surface">
        <div class="max-w-6xl mx-auto px-6 py-14 md:py-20 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-4">AI-Generated · Daily</p>
            <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-ink leading-tight mb-5">
                Ideas worth<br class="hidden md:block"> thinking about
            </h1>
            <p class="text-ink-muted text-lg max-w-xl mx-auto leading-relaxed">
                Deep-dives on software engineering, cloud architecture, and the technologies shaping tomorrow — written by AI, curated for builders.
            </p>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-14">

        @if($posts->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-24">
                <div class="w-16 h-16 rounded-2xl bg-surface border border-border flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-ink-faint" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h2 class="font-serif text-2xl font-semibold text-ink mb-3">No posts yet</h2>
                <p class="text-ink-muted max-w-sm mx-auto">The first post will be generated tonight. Come back soon — or run <code class="text-sm bg-surface border border-border rounded px-1.5 py-0.5">php artisan blog:generate</code> to create one now.</p>
            </div>

        @else

            {{-- Featured Post --}}
            @php $featured = $posts->first() @endphp
            @if($posts->currentPage() === 1)
            <section class="mb-14">
                <div class="flex items-center gap-3 mb-8">
                    <div class="divider"></div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-ink-muted">Featured Story</span>
                </div>

                <a href="{{ route('blog.show', $featured->slug) }}" class="group block">
                    <div class="grid md:grid-cols-5 gap-8 p-8 rounded-2xl border border-border bg-surface card-hover">
                        <div class="md:col-span-3 flex flex-col justify-center">
                            @if($featured->tags)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($featured->tags, 0, 3) as $tag)
                                        <span class="tag-pill">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <h2 class="font-serif text-2xl md:text-3xl font-bold text-ink group-hover:text-ink-light leading-snug mb-4 transition-colors">
                                {{ $featured->title }}
                            </h2>
                            <p class="text-ink-muted leading-relaxed mb-6 text-base">{{ $featured->excerpt }}</p>
                            <div class="flex items-center gap-4 text-sm text-ink-faint">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $featured->published_at->format('M j, Y') }}
                                </span>
                                <span>·</span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $featured->reading_time }} min read
                                </span>
                            </div>
                        </div>
                        <div class="md:col-span-2 flex items-center justify-center">
                            @if($featured->og_image)
                                <img src="{{ $featured->og_image }}" alt="{{ $featured->title }}"
                                     class="w-full aspect-video rounded-xl object-cover border border-border">
                            @else
                                <div class="w-full aspect-video rounded-xl bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 border border-green-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-accent opacity-20" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            </section>
            @endif

            {{-- Post Grid --}}
            @php $gridPosts = $posts->currentPage() === 1 ? $posts->slice(1) : $posts @endphp
            @if($gridPosts->isNotEmpty())
            <section>
                <div class="flex items-center gap-3 mb-8">
                    <div class="divider"></div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-ink-muted">
                        {{ $posts->currentPage() === 1 ? 'Latest Stories' : 'All Stories' }}
                    </span>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($gridPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group flex flex-col p-6 rounded-2xl border border-border bg-white card-hover">
                        <div class="flex-1">
                            @if($post->tags)
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @foreach(array_slice($post->tags, 0, 2) as $tag)
                                        <span class="tag-pill">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <h3 class="font-serif text-lg font-semibold text-ink group-hover:text-ink-light leading-snug mb-3 transition-colors line-clamp-3">
                                {{ $post->title }}
                            </h3>
                            <p class="text-sm text-ink-muted leading-relaxed line-clamp-3">{{ $post->excerpt }}</p>
                        </div>
                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-border">
                            <span class="text-xs text-ink-faint">{{ $post->published_at->format('M j, Y') }}</span>
                            <span class="text-xs text-ink-faint flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $post->reading_time }} min
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Pagination --}}
            @if($posts->hasPages())
            <div class="mt-14 flex items-center justify-center gap-2">
                @if($posts->onFirstPage())
                    <span class="px-4 py-2 rounded-lg text-sm text-ink-faint cursor-not-allowed select-none">&larr; Prev</span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}" class="px-4 py-2 rounded-lg text-sm font-medium text-ink-muted hover:text-ink border border-border hover:border-ink-muted transition-colors">&larr; Prev</a>
                @endif

                <span class="px-4 py-2 text-sm text-ink-muted">
                    Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
                </span>

                @if($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}" class="px-4 py-2 rounded-lg text-sm font-medium text-ink-muted hover:text-ink border border-border hover:border-ink-muted transition-colors">Next &rarr;</a>
                @else
                    <span class="px-4 py-2 rounded-lg text-sm text-ink-faint cursor-not-allowed select-none">Next &rarr;</span>
                @endif
            </div>
            @endif

        @endif
    </main>

    {{-- Footer --}}
    <footer class="border-t border-border mt-20">
        <div class="max-w-6xl mx-auto px-6 py-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-ink-faint">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-accent" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                </svg>
                <span class="font-serif font-medium text-ink-muted">{{ config('app.name') }}</span>
            </div>
            <p>Powered by <a href="https://openrouter.ai" target="_blank" rel="noopener" class="text-accent hover:underline">OpenRouter</a> · Built with <a href="https://laravel.com" target="_blank" rel="noopener" class="hover:text-ink transition-colors">Laravel</a></p>
            <a href="https://github.com/roshanb08/ai-blog-generator" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:text-ink transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/></svg>
                Open Source
            </a>
        </div>
    </footer>

</body>
</html>
