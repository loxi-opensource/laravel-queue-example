<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PollingOrderStatusJob extends BasePollingJob
{
    protected $jobDesc = '订单同步队列';

    private Order $order;

    public function uniqueId()
    {
        return $this->order->trade_no;
    }

    public function retryUntil()
    {
        return now()->addSeconds(10);
    }

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->jobPayload = $order->toArray();
    }

    public function handle()
    {
        try {
            Log::info($this->jobDesc . '开始执行', [$this->order->id, $this->order->trade_no]);
            $order = $this->order;

            // 前置校验
            $shouldPolling = $this->checkOrderBeforePolling($order);
            if (!$shouldPolling) {
                $this->delete();
                return;
            }

            // 开始请求通道同步订单状态
            $result = $this->orderQuery($order);
            if (!$result || $result !== BasePollingJob::NEXT_STATE_CONTINUE) {
                $this->delete();
                return;
            }

            // 下次运行的时机
            $this->scheduleNextRunning();
        } catch (\Throwable $e) {
            $this->fail($e);
        }
    }

    private function orderQuery(Order $order)
    {
        Log::info('订单同步请求开始', [$order->trade_no]);

        // 模拟订单查询请求
        sleep(1);

        // 支付成功返回
//        $result = [
//            'amount' => 100,
//            'payment_no' => 'p123456',
//            'status' => 'SUCCESS'
//        ];

        // 正在支付中返回
        $result = [
            'amount' => null,
            'payment_no' => null,
            'status' => 'PENDING'
        ];

        Log::info('订单同步请求结束', [$order->trade_no, $result]);

        if ($result['status'] == 'PENDING') {
            return BasePollingJob::NEXT_STATE_CONTINUE;
        }
    }

    private function checkOrderBeforePolling(Order $order): bool
    {
        Log::info($this->jobDesc . '前置检测开始', [$order->trade_no]);
        $orderStatus = OrderStatus::from($order->status);
        if (!in_array($orderStatus, [OrderStatus::DEFAULT, OrderStatus::PAYING])) {
            Log::info($this->jobDesc . '前置检测-订单状态已完成-退出队列', [$order->trade_no, $orderStatus->name]);
            return false;
        }

        return true;
    }

    public function afterMaxAttemptsExceeded()
    {
        try {
            $this->order->status = OrderStatus::TIMEOUT->value;
            $this->order->save();
            Log::info($this->jobDesc . '-更新订单状态为超时', [$this->order->trade_no]);
        } catch (\Throwable $e) {
            Log::error($this->jobDesc . '-超时后置逻辑执行异常', [$this->order, $e->getMessage()]);
        }
    }

}
