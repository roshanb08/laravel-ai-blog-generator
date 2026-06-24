<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $post->meta_description }}">
    <title>{{ $post->title }} — {{ config('app.name') }}</title>

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

        /* Article typography */
        .article-body { font-family: 'Lora', Georgia, serif; color: #1a1a1a; }
        .article-body h2 {
            font-family: 'Lora', Georgia, serif;
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.3;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }
        .article-body h3 {
            font-family: 'Lora', Georgia, serif;
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.4;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            color: #292929;
        }
        .article-body p {
            font-size: 1.1rem;
            line-height: 1.85;
            margin-bottom: 1.5rem;
            color: #292929;
        }
        .article-body ul, .article-body ol {
            margin-bottom: 1.5rem;
            padding-left: 1.75rem;
        }
        .article-body ul { list-style-type: disc; }
        .article-body ol { list-style-type: decimal; }
        .article-body li {
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 0.5rem;
            color: #292929;
        }
        .article-body code {
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 0.875em;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 0.15em 0.45em;
            border-radius: 4px;
            color: #374151;
        }
        .article-body pre {
            background: #1e1e2e;
            border-radius: 10px;
            padding: 1.5rem;
            overflow-x: auto;
            margin-bottom: 1.75rem;
            border: 1px solid #313244;
        }
        .article-body pre code {
            background: transparent;
            border: none;
            padding: 0;
            font-size: 0.9rem;
            color: #cdd6f4;
            font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
        }
        .article-body blockquote {
            border-left: 3px solid #1a8917;
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            background: #f9fffe;
            border-radius: 0 8px 8px 0;
        }
        .article-body blockquote p {
            font-size: 1.1rem;
            font-style: italic;
            color: #374151;
            margin-bottom: 0;
        }
        .article-body a {
            color: #1a8917;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .article-body a:hover { color: #145e11; }
        .article-body strong { font-weight: 700; color: #1a1a1a; }
        .article-body em { font-style: italic; }
    </style>
</head>
<body class="bg-white text-ink antialiased">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-border">
        <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ route('blog.index') }}" class="flex items-center gap-2 text-ink-muted hover:text-ink transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Blog
            </a>

            <a href="{{ route('blog.index') }}" class="flex items-center gap-2">
                <svg class="w-6 h-6 text-accent" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                </svg>
                <span class="font-serif font-semibold text-ink hidden sm:inline">{{ config('app.name') }}</span>
            </a>

            <div class="flex items-center gap-3">
                <button onclick="sharePost()" class="hidden sm:flex items-center gap-1.5 text-xs font-medium text-ink-muted hover:text-ink border border-border hover:border-ink-muted rounded-full px-3 py-1.5 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    Share
                </button>
            </div>
        </div>
    </nav>

    <article class="max-w-3xl mx-auto px-6 py-14">

        {{-- Hero image --}}
        @if($post->og_image)
        <div class="-mx-6 mb-10">
            <img src="{{ $post->og_image }}" alt="{{ $post->title }}"
                 class="w-full max-h-96 object-cover rounded-xl border border-border">
        </div>
        @endif

        {{-- Tags --}}
        @if($post->tags)
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($post->tags as $tag)
                <span class="tag-pill">{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        {{-- Title --}}
        <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-ink leading-tight mb-6">
            {{ $post->title }}
        </h1>

        {{-- Excerpt --}}
        @if($post->meta_description)
        <p class="font-serif text-xl text-ink-muted leading-relaxed mb-8 italic">
            {{ $post->meta_description }}
        </p>
        @endif

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-4 pb-8 border-b border-border text-sm text-ink-faint">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                </div>
                <span class="font-medium text-ink-muted">AI Generated</span>
            </div>
            <span>·</span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $post->published_at->format('F j, Y') }}
            </span>
            <span>·</span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $post->reading_time }} min read
            </span>
        </div>

        {{-- Article Content --}}
        <div class="article-body mt-10 max-w-none">
            {!! $post->content !!}
        </div>

        {{-- End of Article Divider --}}
        <div class="flex items-center gap-4 my-12">
            <div class="flex-1 h-px bg-border"></div>
            <svg class="w-5 h-5 text-ink-faint" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
            <div class="flex-1 h-px bg-border"></div>
        </div>

        {{-- Sources --}}
        @if($post->sources && count($post->sources))
        <div class="rounded-xl border border-border bg-surface p-6 mb-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-ink-muted mb-3">
                Sources ({{ $post->articles_used }} {{ $post->articles_used === 1 ? 'article' : 'articles' }})
            </p>
            <ul class="space-y-1.5">
                @foreach($post->sources as $source)
                <li>
                    <a href="{{ $source }}" target="_blank" rel="noopener noreferrer"
                       class="text-sm text-accent hover:underline underline-offset-2 break-all flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        {{ $source }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- AI Disclosure --}}
        <div class="rounded-xl border border-border bg-surface p-6 mb-10">
            <div class="flex items-start gap-4">
                <div class="w-9 h-9 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-ink mb-1">AI-Generated Content</p>
                    <p class="text-sm text-ink-muted leading-relaxed">This article was autonomously written by an AI as part of an open-source blog experiment. Content is generated nightly and may not reflect the latest developments. Always verify technical claims independently.</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex items-center justify-between pt-4 border-t border-border">
            <a href="{{ route('blog.index') }}" class="flex items-center gap-2 text-sm font-medium text-ink-muted hover:text-ink transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                All Stories
            </a>
            <button onclick="sharePost()" class="flex items-center gap-2 text-sm font-medium text-ink-muted hover:text-ink transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                Share Article
            </button>
        </div>
    </article>

    {{-- Footer --}}
    <footer class="border-t border-border mt-8">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-ink-faint">
            <a href="{{ route('blog.index') }}" class="flex items-center gap-2 hover:text-ink transition-colors">
                <svg class="w-4 h-4 text-accent" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                <span class="font-serif font-medium text-ink-muted">{{ config('app.name') }}</span>
            </a>
            <p>Powered by <a href="https://openrouter.ai" target="_blank" rel="noopener" class="text-accent hover:underline">OpenRouter</a> · Built with Laravel</p>
            <a href="https://github.com/roshanb08/ai-blog-generator" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:text-ink transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/></svg>
                Open Source
            </a>
        </div>
    </footer>

    <script>
        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($post->title) }}',
                    text: '{{ addslashes($post->excerpt) }}',
                    url: window.location.href,
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = event.currentTarget;
                    const original = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
                    setTimeout(() => { btn.innerHTML = original; }, 2000);
                });
            }
        }
    </script>

</body>
</html>
