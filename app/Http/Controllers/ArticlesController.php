<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Article::all();

        $articles->each(function($article) {
            echo '<a href="' . url($article->category . '/' . $article->slug) . '">' . $article->slug . '</a><br>';
        });

        exit;
    }

    public function latest()
    {
        $article = Article::query()->OrderByDesc('created_at')->limit(1)->first();

        return redirect()->to(url($article->category . '/' . $article->slug));
    }

    public function show(Article $article)
    {
        return view('article', [
            'article' => $article,
            'recommendedArticles' => $article->getRecommendedArticles(),
        ]);
    }
}
