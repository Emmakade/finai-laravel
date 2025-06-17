<?php

namespace Database\Factories;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'crypto_symbol' => $this->faker->randomElement(['BTC', 'ETH', 'XRP', 'DOGE', 'ADA']),
            'amount_invested' => $this->faker->randomFloat(2, 100, 5000), // Between 100 and 5,000
            'current_value' => $this->faker->randomFloat(2, 100, 5000),  // Between 100 and 5,000
        ];
    }
}
