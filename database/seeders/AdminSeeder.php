<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            // 'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $admin->syncRoles(['Admin']);

        $admin->profile()->create([
            "username" => "admin",
            "first_name" => "Admin",
            "last_name" => "Aja",
            "phone_number" => "098301928"
        ]);
    }
}
