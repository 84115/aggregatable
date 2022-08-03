<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CanGenerateArticleFromDataArray;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class HoroscopesToday extends Command
{
    use CanGenerateArticleFromDataArray;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horoscopes:today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $signs = [
        'aries',
        'taurus',
        'gemini',
        'cancer',
        'leo',
        'virgo',
        'libra',
        'scorpio',
        'sagittarius',
        'capricorn',
        'aquarius',
        'pisces',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $prettyDate = today()->format('l \T\h\e jS \O\f F Y');

        $title = "Readings: $prettyDate";
        $summary = "Get caught up on the todays latest horoscopes.";

        // Don't generate duplicate post(s)
        if (Article::where('title', '=', $title)->get()->count()) {
            return 0;
        }

        $data = [
            'title' => $title,
            'author' => 'Mystic Meg',
            'description' => $summary,
            'keywords' => 'zodiac, zodiacs, horoscope, horoscopes, ' . implode(', ', $this->signs),
            'content' => $summary,
            'category' => 'zodiacs',
            'hero' => '/zodiacs.webp',
        ];

        $html = [
            [
                'tag' => 'p',
                'content' => $summary,
            ],
        ];

        // https://aztro.readthedocs.io/en/latest/
        foreach ($this->signs as $sign) {
            $signData = $this->aztro($sign, 'today');
            // $signData['date_range'];
            // $signData['description'];
            // $signData['compatibility'];
            // $signData['mood'];
            // $signData['color'];
            // $signData['lucky_number'];
            // $signData['lucky_time'];

            array_push($html,                 [
                'tag' => 'p',
                'content' => '',
            ]);
            array_push($html, [
                'tag' => 'h3',
                'content' => Str::title($sign),
            ]);
            array_push($html, [
                'tag' => 'p',
                'content' => $signData['description'],
            ]);

            sleep(2);
        }

        $data['outline'] = $html;

        $this->generate($data, $html);

        return 0;
    }

    private function aztro($sign, $day = 'today') {
        $aztro = curl_init('https://aztro.sameerkumar.website/?sign='.$sign.'&day='.$day);

        curl_setopt_array($aztro, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));

        $response = curl_exec($aztro);

        if ($response === FALSE){
            die(curl_error($aztro));
        }

        $responseData = json_decode($response, TRUE);

        return $responseData;
    }
}
