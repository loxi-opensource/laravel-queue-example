<?php

namespace App\Console\Commands;

use App\Jobs\PollingOrderStatusJob;
use App\Models\Order;
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
        $this->info('测试订单轮询任务-入队列');
        $order = Order::first();
        PollingOrderStatusJob::dispatch($order);
    }
}
