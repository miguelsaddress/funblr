<?php

use Illuminate\Database\Seeder;
use Funblr\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $u = User::create([
            'username'  => "mamoreno", 
            'password'  => bcrypt("123456"),
        ]);
        $u->assignApiKey();
        
        $u = User::create([
            'username'  => "insided", 
            'password'  => bcrypt("123456"),
        ]);
        $u->assignApiKey();
    }
}
