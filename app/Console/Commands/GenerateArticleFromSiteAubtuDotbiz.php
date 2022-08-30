<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CanGenerateArticleFromDataArray;
use App\Models\Article;
use Faker\Generator as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use spekulatius\phpscraper as Scraper;

class GenerateArticleFromSiteAubtuDotbiz extends Command
{
    use CanGenerateArticleFromDataArray;

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
        $urls = [
            'https://aubtu.biz/81303/',
            'https://aubtu.biz/4905/',
            // 'https://aubtu.biz/8969/', // blacklist
            'https://aubtu.biz/14248/',
            'https://aubtu.biz/18039/',
            'https://aubtu.biz/31770/',
            'https://aubtu.biz/37455/',
            'https://aubtu.biz/17267/',
            'https://aubtu.biz/17756/',
            'https://aubtu.biz/18628/',
            'https://aubtu.biz/18602/',
            'https://aubtu.biz/7612/',
            'https://aubtu.biz/30587/',
            'https://aubtu.biz/31825/',
            'https://aubtu.biz/23742/',
            'https://aubtu.biz/2960/',
            'https://aubtu.biz/82819/',
            'https://aubtu.biz/82603/',
            'https://aubtu.biz/24138/',
            'https://aubtu.biz/6684/',
            'https://aubtu.biz/18307/',
            'https://aubtu.biz/2844/',
            'https://aubtu.biz/17261/',
            'https://aubtu.biz/14598/',
            // 'https://aubtu.biz/3970/', // Good article, but multipage!
            'https://aubtu.biz/2016/',
            // 'https://aubtu.biz/1457/', // cant access images!
            'https://aubtu.biz/12862/',
            'https://aubtu.biz/8675/',
            'https://aubtu.biz/3813/',
            'https://aubtu.biz/25499/',
            'https://aubtu.biz/85593/',
            'https://aubtu.biz/3310/',
            'https://aubtu.biz/23556/',
        ];

        $urls = [ $urls[count($urls) - 1] ];

        foreach ($urls as $url) {
            $data = $this->fetchHtmlAsDataArray($url);
            // $article = $this->convertDataArrayToArticleArray($data);
            // $html = $this->convertArticleArrayToHtml($article);
    
            $this->generate($data, $data['outline']);
            // $this->generateArticleModel($data, $html);

            sleep(4);
        }

        return 0;
    }

    private function fetchHtmlAsDataArray($url)
    {
        $web = new Scraper();

        $web->go($url);

        $data = [
            'title' => $web->title,
            'author' => 'James Ball',
            // 'author' => $web->author, // Maybe generate this?
            'description' => isset($web->cleanParagraphs[0]) ? $web->cleanParagraphs[0] : $web->title,
            'outline' => collect($web->cleanOutlineWithParagraphs)
                ->reject(fn($value, $key) => empty($value['tag']))
                ->reject(fn($value, $key) => $value['tag'] === 'h1')
                ->reject(fn($value, $key) => $value['tag'] === 'h2')
                ->reject(fn($value, $key) => $value['tag'] === 'h4')
                ->values()
                ->toArray(),
            'imagesWithDetails' => collect($web->imagesWithDetails)
                ->reject(fn($value, $key) => Str::startsWith($value['url'], ['data:image']))
                ->filter(fn($value, $key) => empty($value['alt']))
                ->filter(fn($value, $key) => Str::startsWith($value['url'], ['https://cdn3s.com', 'https://i.imgur.com', 'http://aubtu.biz/wp-content', 'https://aubtu.biz/wp-content']))
                ->values()
                ->toArray(),
            // --------------------------------------------
            // 'description' => $web->description, // Appears empty, maybe use first paragraph expert instead.
            // 'keywords' => $web->contentKeywords, // Results are pretty messy
            'keywords' => Str::of(implode(',', $web->contentKeywords))->replaceMatches('/ {2,}/', ',')->value(),
            'category' => 'animals',
        ];

        return $data;
    }
}
