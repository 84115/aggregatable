<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CanGenerateArticleFromDataArray;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use spekulatius\phpscraper as Scraper;

class GenerateArticleFromSiteDemilked extends Command
{
    use CanGenerateArticleFromDataArray;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:demilked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an article from the site https://www.demilked.com';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $urls = [
            'https://www.demilked.com/interesting-creative-maps/',
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

        $title = str_replace(' | DeMilked', '', $web->title);

        $data = [
            'title' => $title,
            'author' => 'James Ball',
            // 'author' => $web->author, // Maybe generate this?
            'description' => isset($web->cleanParagraphs[1]) ? $web->cleanParagraphs[0] : $title,
            'outline' => collect($web->cleanOutlineWithParagraphs)
                ->reject(fn($value, $key) => empty($value['tag']))
                ->reject(fn($value, $key) => $value['tag'] === 'p' && $value['content'] == "Cookies help us deliver our services. By using our services, you agree to our use of cookies.")
                ->reject(fn($value, $key) => $value['tag'] === 'p' && $value['content'] == "Cookies help us deliver our services. By using our services, you agree to our use of cookies.")
                ->reject(fn($value, $key) => $value['tag'] === 'h1')
                ->reject(fn($value, $key) => $value['tag'] === 'h3')
                ->reject(fn($value, $key) => $value['tag'] === 'h4')
                ->reject(fn($value, $key) => $value['tag'] === 'h5')
                ->reject(fn($value, $key) => $value['tag'] === 'h6')
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
            'category' => 'learn',
        ];

        dd($data);

        return $data;
    }
}
