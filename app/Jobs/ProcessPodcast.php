<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessPodcast implements ShouldQueue
{
    use Queueable;

    private mixed $id;

    /**
     * 入队列时就判断是否有相同的任务在队列（不管他的状态 未运行/运行中），如果有，就不入队列
     * 如果不设置 uniqueFor/uniqueId,注意删除ShouldBeUnique实现声明 默认是当前队列名全局的，即同一个队列名的任务只能同时执行一个。与入参无关。
     * 当前任务运行uniqueFor秒后，如果还没结束，就会释放唯一锁，同一个uniqueId任务可以执行了
     * 设置 QUEUE_FAILED_DRIVER=null 失败的/超过maxAttempt的/ 不存储到导数据库。会自动记录到文件日志
     * 源码位置：
     * src/Illuminate/Queue/Worker.php
     * src/Illuminate/Queue/Jobs/Job.php
     * vendor/laravel/framework/src/Illuminate/Queue/Jobs/DatabaseJob.php
     */
//    public $uniqueFor = 100;
//
//    public function uniqueId()
//    {
//        return $this->id;
//    }

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

//    public $timeout = 20;
//    public $failOnTimeout = true;


    public function retryUntil()
    {
        return now()->addSeconds(10);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing podcast with ID: ' . $this->id);
        echo 'Processing podcast with ID: ' . $this->id, PHP_EOL;
//        sleep(4);
//        echo 'Podcast processed with ID: ' . $this->id, PHP_EOL;
        $this->release(2);
        echo 'Podcast run at next 2s with ID: ' . $this->id, PHP_EOL;
    }
}
