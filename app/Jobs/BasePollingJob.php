<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Support\Facades\Log;

abstract class BasePollingJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    protected $jobDesc = '任务轮询队列';

    protected $jobPayload;

    const TIMEOUT_SECOND = 60 * 3;

    const NEXT_STATE_CONTINUE = 'continue';

    public function retryUntil()
    {
        return now()->addSeconds(self::TIMEOUT_SECOND);
    }

    protected function scheduleNextRunning()
    {
        $attempts = $this->job->attempts();
        if ($attempts <= 5) {
            $this->release(1); // 前10秒 每隔1秒执行一次
        } elseif ($attempts <= 10) {
            $this->release(5); // 接下来30秒 每隔3秒一次
        } elseif ($attempts <= 20) {
            $this->release(10); // 接下来50秒 每5秒一次
        } else {
            $this->release(30); // 最后变成10秒一次
        }
    }

    public function afterMaxAttemptsExceeded()
    {
        // todo
    }

    public function failed(\Throwable $exception)
    {
        if ($exception instanceof MaxAttemptsExceededException) {
            // 获取子类的所有构造方法的入参
            Log::info($this->jobDesc . '超时退出执行', [
                'payload' => $this->jobPayload,
                'error' => $exception->getMessage(),
            ]);
            $this->afterMaxAttemptsExceeded();
        } else {
            Log::error($this->jobDesc . '异常失败', [
                'payload' => $this->jobPayload,
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);
        }
    }
}
