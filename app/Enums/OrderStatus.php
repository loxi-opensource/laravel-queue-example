<?php

namespace App\Enums;

enum OrderStatus: int
{
    case DEFAULT = 0;  // 未支付
    case PAYING = 1;  // 支付中
    case CLOSED = 2; // 已关闭
    case SUCCESS = 3;  // 支付成功
    case FAIL = 4; // 支付失败
    case TIMEOUT = 5;  // 超时
}
