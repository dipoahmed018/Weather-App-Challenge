<?php

namespace App\Console\Commands;

use App\Jobs\CrawlWeatherDataJob;
use Illuminate\Console\Command;

class InitialWeatherInfoCrawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl Weather information of every user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CrawlWeatherDataJob::dispatchSync();
    }
}
