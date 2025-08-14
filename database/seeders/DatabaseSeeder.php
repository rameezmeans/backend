<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Admin Tsichlakidis Kostas',
        //     'email' => 'admin@ecutech.gr',
        //     'password' => Hash::make('asdf@1234'),
        //     'phone' => '1234',
        //     'Language' => 'English',
        //     'address' => 'somewhere',
        //     'zip' => 'somewhere',
        //     'city' => 'somewhere',
        //     'country' => 'somewhere',
        //     'status' => 'active',
        //     'company_name' => 'active',
        //     'company_id' => 'active',
        //     'slave_tools_flag' => 'active',
        //     'master_tools' => 'active',
        //     'slave_tools' => 'active',
        //     'is_admin' => true,
        // ]);

        $this->call([
            ChatgptPromptSeeder::class,
        ]);
    }
}
