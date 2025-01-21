<?php

namespace Database\Seeders;

use Database\Factories\ViewsFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ViewsFactory::new()->count(1)->create();
    }
}
