<?php

namespace App\Console\Commands;

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
                ->toArray(),
            // 'imagesWithDetails' => $web->imagesWithDetails,
            // --------------------------------------------
            // 'description' => $web->description, // Appears empty, maybe use first paragraph expert instead.
            // 'keywords' => $web->contentKeywords, // Results are pretty messy
        ];

        print_r($data);

        // Cache::rememberForever($url, function () use ($links) {
            // return serialize($links);
        // });

        return 0;
    }
}
