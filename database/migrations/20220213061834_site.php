<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Site extends Migrator
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
        $table = $this->table('site', ['collation'=>'utf8mb4_general_ci'] );
        $table->addColumn('sort', 'integer', ['limit'=>4,'default'=>0 , 'comment'=>'排序' ]  )
              ->addColumn('status', 'integer', ['limit'=>4,'default'=>0 , 'comment'=>'状态 0-不显示 1-显示' ] )
              ->addColumn('create_time', 'integer',[ 'default'=>0 , 'comment'=>'创建时间' ])
              ->addColumn('update_time', 'integer',[ 'default'=>0 , 'comment'=>'更新时间' ])
              ->addColumn('delete_time', 'integer',[ 'null'=>true , 'comment'=>'删除时间' ])
              
              ->addColumn('title', 'string',[ 'default'=>'' , 'comment'=>'标题' ])
              ->addColumn('tel', 'string',[ 'default'=>'' , 'comment'=>'电话' ])
              ->addColumn('fax', 'string',[ 'default'=>'' , 'comment'=>'传真' ])
              ->addColumn('email', 'string',[ 'default'=>'' , 'comment'=>'邮箱' ])
              ->addColumn('qq', 'string',[ 'default'=>'' , 'comment'=>'qq' ])
              ->addColumn('address', 'string',[ 'default'=>'' , 'comment'=>'地址' ])
              ->addColumn('copyright', 'string',[ 'default'=>'' , 'comment'=>'版权' ])
              ->addColumn('record', 'string',[ 'default'=>'' , 'comment'=>'备案' ])
              ->addColumn('remark', 'string',[ 'default'=>'' , 'comment'=>'备注' ])
              
              ->create();

    }
}
