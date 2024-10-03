<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPodcast;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Test command');
        ProcessPodcast::dispatch(
            "freddy"
        );
        $this->info('Time: ' . now());
//        sleep(3);
//        ProcessPodcast::dispatch(1);
//        ProcessPodcast::dispatch(2);
//        ProcessPodcast::dispatch(2);
    }
}
