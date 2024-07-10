<?php
use think\migration\Seeder;
use think\facade\Db ;

class Admin extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // $faker = Faker\Factory::create('zh_CN');
        // $rows = [];
        // for ($i = 0; $i < 50; $i++) {
        //     $rows[] = [
        //         'username'      => $faker->name,
        //         'password'      => md5($faker->password),
        //          'phone'         => $faker->phoneNumber,
        //     ];
        // }
 

        $rows[] = [
            'account' => 'admin' ,
            'code' => 'iH2KF0kV' ,
            'password' => '5898a4ea5110597ca7beee0d200b1029' ,
            'create_time' => time() 

        ];


        Db::table('tp_admin')->insertAll($rows);

    }
}