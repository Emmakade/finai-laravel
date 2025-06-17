<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'crypto_symbol' => $this->faker->randomElement(['BTC', 'ETH', 'XRP', 'DOGE', 'ADA']),
            'balance' => $this->faker->randomFloat(2, 2000, 10000), // Between 2000 and 10,000
        ];
    }
}
