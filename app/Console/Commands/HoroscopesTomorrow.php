<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\CanGenerateArticleFromDataArray;

class HoroscopesTomorrow extends HoroscopesCommand
{
    use CanGenerateArticleFromDataArray;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horoscopes:tomorrow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tomorrows horoscopes.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateHoroscopes('tomorrow', today()->addDays(1));

        return 0;
    }
}
