<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


class ArticlesController extends Controller
{
    public function index()
    {
        $category = str_replace(url('').'/', '', URL::current());

        if ($category !== url('')) {
            $articles = Article::query()
                ->where('category', '=', $category)
                ->orderByDesc('created_at')
                ->paginate(15);
        } else {
            $articles = Article::query()
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        return view('articles', [
            'category' => "everything",
            'articles' => $articles,
        ]);
    }

    // public function index()
    // {
    //     $articles = Article::all();

    //     $articles->each(function($article) {
    //         echo '<a href="' . url($article->category . '/' . $article->slug) . '">' . $article->slug . '</a><br>';
    //     });

    //     exit;
    // }

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
