<?php

namespace App\Console\Commands;

use App\Models\Article;
use Faker\Generator as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use spekulatius\phpscraper as Scraper;

class generateArticleFromSiteAubtuDotbiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:aubtu_dot_biz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an article from the site https://aubtu.biz';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = $this->fetchHtmlAsDataArray();
        $article = $this->convertDataArrayToArticleArray($data);
        $html = $this->convertArticleArrayToHtml($article);

        $this->generateArticleModel($data, $html);

        return 0;
    }

    private function fetchHtmlAsDataArray()
    {
        $url = 'https://aubtu.biz/81303/';

        // if (Cache::has($url)) {
        //     return unserialize(Cache::get($url));
        // }

        $web = new Scraper();

        $web->go($url);

        $data = [
            'title' => $web->title,
            'author' => $web->author, // Maybe generate this?
            'description' => $web->cleanParagraphs[0],
            'outline' => collect($web->cleanOutlineWithParagraphs)
                ->reject(fn($value, $key) => empty($value['tag']))
                ->reject(fn($value, $key) => $value['tag'] === 'h1')
                ->reject(fn($value, $key) => $value['tag'] === 'h2')
                ->reject(fn($value, $key) => $value['tag'] === 'h4')
                ->values()
                ->toArray(),
            'imagesWithDetails' => collect($web->imagesWithDetails)
                ->reject(fn($value, $key) => Str::startsWith($value['url'], ['data:image', 'https://aubtu.biz/wp-content/uploads']))
                ->filter(fn($value, $key) => Str::startsWith($value['url'], ['https://cdn3s.com']))
                ->values()
                ->toArray(),
            // --------------------------------------------
            // 'description' => $web->description, // Appears empty, maybe use first paragraph expert instead.
            // 'keywords' => $web->contentKeywords, // Results are pretty messy
            'keywords' => Str::of(implode(',', $web->contentKeywords))->replaceMatches('/ {2,}/', ',')->value(),
        ];

        return $data;

        // Cache::rememberForever($url, function () use ($links) {
            // return serialize($links);
        // });
    }

    private function convertDataArrayToArticleArray($data)
    {
        $imageIndex = 0;

        $article = [
            [
                'tag' => 'img',
                'src' => $data['imagesWithDetails'][$imageIndex]['url'],
            ],
        ];

        $imageIndex++;

        foreach ($data['outline'] as $key => $line)
        {
            if ($key === 0) {
                continue;
            }

            if ($line['tag'] === 'h3') {
                $article[] = $line;

                if (!empty($data['imagesWithDetails'][$imageIndex])) {
                    $article[] = [
                        'tag' => 'img',
                        'src' => $data['imagesWithDetails'][$imageIndex]['url'],
                    ];
                
                    $imageIndex++;
                }
            } else {
                $article[] = $line;
            }
        }

        return $article;
    }

    private function convertArticleArrayToHtml($article)
    {
        $article = collect($article)
            ->map(function($row) {
                $tag = $row['tag'] ?? '';
                $src = $row['src'] ?? '';
                $content = $row['content'] ?? '';

                if ($tag === 'img') {
                    return "<$tag src=\"$src\"/>";
                }

                return "<$tag>$content</$tag>";
            })
            ->join('');

        return $article;
    }

    private function generateArticleModel($data, $html)
    {
        $article = new Article;
        $article->title = $data['title'];
        $article->author = $data['author'];
        $article->description = $data['description'];
        $article->keywords = $data['keywords'];
        $article->content = $html;
        $article->save();

        return $article;
    }
}
