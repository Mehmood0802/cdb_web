<?php
namespace app\admin\validate;

use think\Validate;
 
class AdminPower extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require|length:2,100',
        'controller'   => 'require|alpha|length:4,20',
        'action'   => 'require|alpha|length:4,20',
    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',
        'title.length'     => '标题名长度范围2-100个字符',

        'controller.require'   => '控制器名必须填写',
        'controller.alpha'   => '控制器名只能是字母',
        'controller.length'   => '控制器名必须4-20个字符',

        'action.require'   => '方法名必须填写',
        'action.alpha'   => '方法名只能是字母',
        'action.length'   => '方法名必须4-20个字符',

    ];
    
    protected $scene = [
        'add'  =>  ['title','controller','action'],
        'edit'  =>  ['id','title','controller','action'],
        'del'  =>  ['id'],
    ];


}