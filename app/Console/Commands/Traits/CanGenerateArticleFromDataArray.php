<?php

namespace App\Console\Commands\Traits;

use App\Models\Article;
use Illuminate\Support\Str;

trait CanGenerateArticleFromDataArray
{
    public function generate($data, $html)
    {
        $article = $this->convertDataArrayToArticleArray($data);
        $html = $this->convertArticleArrayToHtml($article);

        $output = $this->generateArticleModel($data, $html);

        return $output;
    }

    // Make this reusable!
    public function convertDataArrayToArticleArray($data)
    {
        $imageIndex = 0;

        $article = [];

        if (! empty($data['imagesWithDetails'][$imageIndex])) {
            $article[] =             [
                'tag' => 'img',
                'src' => $data['imagesWithDetails'][$imageIndex]['url'],
            ];
        }

        $imageIndex++;

        foreach ($data['outline'] as $key => $line)
        {
            if ($key === 0) {
                continue;
            }

            if ($line['tag'] === 'h3') {
                $article[] = $line;

                if (! empty($data['imagesWithDetails'][$imageIndex])) {
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

    public function convertArticleArrayToHtml($article)
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

    public function generateArticleModel($data, $html)
    {
        $article = new Article;
        $article->slug = Str::slug($data['title']);
        $article->title = $data['title'];
        $article->author = $data['author'];
        $article->description = $data['description'];
        $article->hero = isset($data['hero']) ? $data['hero'] : $data['imagesWithDetails'][0]['url'];
        $article->keywords = $data['keywords'];
        $article->content = $html;
        $article->category = $data['category'];
        $article->save();

        return $article;
    }
}
