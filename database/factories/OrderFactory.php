<?php

namespace Database\Factories;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'trade_no' => $this->faker->unique()->numerify('o###################'),
            'order_amount' => $this->faker->numberBetween(1, 1000),
            'status' => OrderStatus::DEFAULT,
        ];
    }
}
