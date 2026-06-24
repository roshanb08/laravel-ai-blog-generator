<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateBlogPost extends Command
{
    protected $signature = 'blog:generate
                            {--category=technology : NewsAPI category (business|entertainment|general|health|science|sports|technology)}
                            {--country=us          : ISO 3166-1 alpha-2 country code}
                            {--limit=3             : Number of news articles to include (1-10)}
                            {--q=                  : Keyword filter for headlines}';

    protected $description = 'Generate a blog post by calling the ai-blog-generator API service';

    private array $fallbacks = [
        ['category' => 'technology', 'country' => 'gb', 'limit' => 3],
        ['category' => 'technology', 'country' => null, 'limit' => 3],
        ['category' => 'general',    'country' => 'us', 'limit' => 3],
    ];

    public function handle(): int
    {
        $apiBase = rtrim(config('services.blog_api.url', 'http://api:8000'), '/');

        $this->info("Blog API: {$apiBase}");

        $primary = [
            'category' => $this->option('category'),
            'country'  => $this->option('country') ?: null,
            'limit'    => (int) $this->option('limit'),
            'q'        => $this->option('q') ?: null,
        ];

        $data = $this->callApi($apiBase, $primary);

        if (! $data) {
            foreach ($this->fallbacks as $fb) {
                $label = "category={$fb['category']} country=" . ($fb['country'] ?? 'global');
                $this->warn("  Trying fallback: {$label}");
                $data = $this->callApi($apiBase, $fb);
                if ($data) {
                    break;
                }
            }
        }

        if (! $data) {
            $this->error('All attempts failed. Check BLOG_API_URL and that the api service is running.');
            return self::FAILURE;
        }

        $this->info("Generated: \"{$data['title']}\"");

        return $this->savePost($data);
    }

    private function callApi(string $apiBase, array $job): ?array
    {
        $payload = array_filter([
            'category'  => $job['category'] ?? 'technology',
            'country'   => $job['country'] ?? null,
            'limit'     => $job['limit'] ?? 3,
            'q'         => $job['q'] ?? null,
            'full_html' => false,
        ], fn ($v) => $v !== null);

        try {
            $response = Http::timeout(300)
                ->post("{$apiBase}/generate-blog", $payload);

            if (! $response->successful()) {
                $detail = $response->json('detail') ?? "HTTP {$response->status()}";
                $this->warn("  API error: {$detail}");
                return null;
            }

            $data = $response->json();

            if (empty($data['title']) || empty($data['html'])) {
                $this->warn('  API returned incomplete data.');
                return null;
            }

            return $data;

        } catch (\Throwable $e) {
            $this->warn("  Request failed: {$e->getMessage()}");
            Log::warning('blog:generate API call failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function savePost(array $data): int
    {
        $slug = $this->uniqueSlug(Str::slug($data['title']));
        $wordCount = str_word_count(strip_tags($data['html']));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        try {
            $post = BlogPost::create([
                'title'           => $data['title'],
                'slug'            => $slug,
                'meta_description'=> $data['meta_description'] ?? null,
                'content'         => $data['html'],
                'keywords'        => $data['keywords'] ?? null,
                'og_title'        => $data['og_title'] ?? null,
                'og_description'  => $data['og_description'] ?? null,
                'og_image'        => $data['og_image'] ?? null,
                'sources'         => $data['sources'] ?? [],
                'articles_used'   => (int) ($data['articles_used'] ?? 0),
                'category'        => $data['category'] ?? null,
                'country'         => $data['country'] ?? null,
                'reading_time'    => $readingTime,
                'published_at'    => now(),
            ]);

            $this->info("Saved: blog #{$post->id} → /blog/{$slug}");
            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('DB save failed: ' . $e->getMessage());
            Log::error('blog:generate DB save failed', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }
    }

    private function uniqueSlug(string $slug): string
    {
        $original = $slug;
        $count = 1;

        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
