<?php
use think\migration\Seeder;
use think\facade\Db ;

class SiteCate extends Seeder
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
            'title' => '新闻动态' ,
            'create_time' => time() 
        ];
        $rows[] = [
            'title' => '通知公告' ,
            'create_time' => time() 
        ];


        Db::table('tp_site_cate')->insertAll($rows);

    }
}