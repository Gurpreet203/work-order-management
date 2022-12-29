<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'role_id' => 4,
            'first_name' => 'Gurpreet',
            'last_name' => 'Singh',
            'email' => 'test2@gmail.com',
            'Password' => Hash::make('123456789')
        ]);

        WorkOrder::factory(10)->create([
            'user_id' => $user->id
        ]);
    }
}
