<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (is_null(User::where('email', 'admin@admin.com')->first())) {
            User::factory()->admin()->withEmail('admin@admin.com')->create();
        }
        User::factory(10)->create();
    }
}
