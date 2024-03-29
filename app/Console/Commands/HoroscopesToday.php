<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CanGenerateArticleFromDataArray;

class HoroscopesToday extends HoroscopesCommand
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
    protected $description = 'Generate todays horoscopes.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateHoroscopes('today', today());

        return 0;
    }
}
