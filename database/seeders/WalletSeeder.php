<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Wallet::create([
                'user_id' => $user->id,
                'crypto_symbol' => fake()->randomElement(['BTC', 'ETH', 'XRP', 'DOGE', 'ADA']),
                'amount' => fake()->randomFloat(2, 0, 10000),
            ]);
        }
    }
}
