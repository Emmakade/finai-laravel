<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => $faker->name,
        //     'email' => $faker->unique()->safeEmail,
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('password'),
        //     'remember_token' => Str::random(10),
        // ]);
        $this->call([
            WalletSeeder::class,
            InvestmentSeeder::class,
        ]);
    }
}