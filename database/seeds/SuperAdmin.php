<?php

use Illuminate\Database\Seeder;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->insert([
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin'),
            'nickname' => '超级管理员',
            'supper' => 1,
            'created_at' => format_time(),
            'updated_at' => format_time()
        ]);
    }
}
