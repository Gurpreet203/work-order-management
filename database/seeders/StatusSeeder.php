<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'name' => 'Open'
        ]);

        Status::create([
            'name' => 'In Progress'
        ]);

        Status::create([
            'name' => 'Resolve'
        ]);

        Status::create([
            'name' => 'Close'
        ]);

        Status::create([
            'name' => 'Re Open'
        ]);
    }
}
