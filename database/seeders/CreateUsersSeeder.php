<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
  
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'cabang_id'=>'1',
                'name'=>'Admin User',
               'email'=>'wariadmin@artha.com',
               'type'=>1,
               'password'=> bcrypt('123456'),
            ],
            [
               'cabang_id'=>'2',
                'name'=>'Manager User',
               'email'=>'warimanager@artha.com',
               'type'=> 2,
               'password'=> bcrypt('123456'),
            ],
            [
               'cabang_id'=>'1',
                'name'=>'karyawan',
               'email'=>'waricabang@artha.com',
               'type'=>0,
               'password'=> bcrypt('123456'),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}