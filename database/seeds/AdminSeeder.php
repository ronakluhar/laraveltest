<?php

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
        if(DB::table('users')->get()->count() == 0){
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'ronak.luhar@test.com', 
                'password' => bcrypt('admin@123'),
                'phone' => '123456',
                'unique_id' => str_random(20),
                'is_admin' => 1,
            ]);
        }
    }
}
