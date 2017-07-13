<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScrapeController;

class KinepolisScrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Kinepolis:scrape';

    protected $scraper;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes data from kinepolisluxembourg.lu';

    /**
     * Create a new command instance.
     */
    public function __construct(ScrapeController $scraper)
    {
        parent::__construct();

        $this->scraper = $scraper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->scraper->execute();
    }
}
