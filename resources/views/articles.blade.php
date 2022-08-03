<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ Str::title($category) }} Daily</title>

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8615852714164072" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    </head>
    <body class="antialiased">
        <header>
            <h1 class="tagline">{{ Str::title($category) }} Daily</h1>
        </header>

        <main>
            @foreach($articles as $article)
            <article>
                <h1><a href="{{ $article->getUrl() }}">{{ $article->title }}</a></h1>
                <div class="author"><em>By {{ $article->author }} - {{ $article->created_at->format('Y/m/d') }}</em></div>
                <p>{{ $article->description }}</p>
                @if ($article->hero)
                <img src="{{ $article->hero }}" alt="{{ $article->title }}" title="{{ $article->title }}">
                @endif
            </article>
            <x-googlead />
            @endforeach
        </main>

        <x-about />

        <footer>
            {{ $articles->links() }}
        </footer>

        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </body>
</html>
