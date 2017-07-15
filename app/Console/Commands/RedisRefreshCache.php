<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CacheController;

class RedisRefreshCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:refreshCache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshed the redis cache used for the application';

    /**
     * Create a new command instance.
     */
    public function __construct(CacheController $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cache->refresh();
    }
}
