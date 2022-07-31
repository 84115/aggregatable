<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRecommendedArticles()
    {
        return Article::orderByDesc('created_at')
            ->where('id', '!=', $this->id)
            ->limit(10)
            ->get();
    }

    public function getUrl()
    {
        return url($this->category . '/' . $this->slug);
    }
}
