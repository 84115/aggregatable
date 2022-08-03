<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $article->title }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8615852714164072" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    </head>
    <body class="antialiased">
        <header>
            <div class="tagline"><a href="{{ url($article->category) }}">{{ Str::title($article->category) }} Daily</a></div>

            <h1>{{ $article->title }}</h1>
        </header>

        <main>
            <div class="author"><em>By {{ $article->author }} - {{ $article->created_at->format('Y/m/d') }}</em></div>
            <p>{{ $article->description }}</p>

            <x-googlead />

            {!! $article->content !!}

            <x-googlead />

            @if($recommendedArticles->count())
            <hr>

            <h6 class="recommended">Recommended Articles</h6>

            <ul>
            @foreach ($recommendedArticles as $recommended)
                <li>
                    <a href="{{ $recommended->getUrl() }}">
                        <img style="margin-bottom: 0" src="{{ $recommended->hero }}" alt="{{ $recommended->title }}">
                        <h4 style="margin-top: 0">{{ $recommended->title }}</h4>
                    </a>
                </li>
            @endforeach
            </ul>
            @endif

            <x-about />
        </main>

        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </body>
</html>
