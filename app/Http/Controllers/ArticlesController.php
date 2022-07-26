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
            echo '<a href="' . url('article/' . $article->slug) . '">' . $article->slug . '</a><br>';
        });

        exit;
    }

    public function show(Article $article)
    {
        return view('article', [
            'title' => $article->title,
            'description' => $article->description,
            'content' => $article->content,
        ]);
    }
}
