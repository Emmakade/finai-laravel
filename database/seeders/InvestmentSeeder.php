<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Investment;

class InvestmentSeeder extends Seeder
{
    public function run()
    {
        Investment::factory()->count(10)->create();
    }
}
