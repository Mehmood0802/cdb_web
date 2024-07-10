<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Member extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $table = $this->table('member', ['collation'=>'utf8mb4_general_ci'] );
        $table->addColumn('sort', 'integer', ['limit'=>4,'default'=>0 , 'comment'=>'排序' ]  )
              ->addColumn('status', 'integer', ['limit'=>4,'default'=>0 , 'comment'=>'状态 0-不显示 1-显示' ] )
              ->addColumn('create_time', 'integer',[ 'default'=>0 , 'comment'=>'创建时间' ])
              ->addColumn('update_time', 'integer',[ 'default'=>0 , 'comment'=>'更新时间' ])
              ->addColumn('delete_time', 'integer',[ 'null'=>true , 'comment'=>'删除时间' ])

              ->addColumn('avatar', 'string',[ 'default'=>'' , 'comment'=>'头像' ])
              ->addColumn('nickname', 'string',[ 'default'=>'' , 'comment'=>'昵称' ])
              ->addColumn('money', 'integer',[ 'default'=>0 , 'comment'=>'余额' ])
              ->addColumn('score', 'integer',[ 'default'=>0 , 'comment'=>'积分' ])
              ->addColumn('sex', 'integer',[ 'default'=>0 , 'comment'=>'性别' ])
              ->addColumn('birthday', 'integer',[ 'default'=>0 , 'comment'=>'生日' ])
              ->addColumn('mobile', 'string',[ 'default'=>'' , 'comment'=>'手机' ])
              ->addColumn('userpass', 'string',[ 'default'=>'' , 'comment'=>'密码' ])
              ->addColumn('usercode', 'string',[ 'default'=>'' , 'comment'=>'字符串' ])
              
              ->create();
    }
}
