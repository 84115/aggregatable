<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CanGenerateArticleFromDataArray;

class HoroscopesYesterday extends HoroscopesCommand
{
    use CanGenerateArticleFromDataArray;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horoscopes:yesterday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate yesterdays horoscopes.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateHoroscopes('yesterday', today()->subDays(1));

        return 0;
    }
}
