<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class RegenerateDatesOfAllPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:regenerate_dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = today();

        $articles = Article::query()
            ->orderByDesc('ID')
            ->where('category', "!=", 'zodiacs')
            ->get();

        $counter = 0;

        foreach ($articles as $article) {
            $article->created_at = $date->subDays($counter);

            $article->save();

            $counter++;
        }

        return 0;
    }
}
