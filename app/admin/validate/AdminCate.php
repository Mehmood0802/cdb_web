<?php
namespace app\admin\validate;

use think\Validate;
 
class AdminCate extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require|length:2,30',
        'controller'   => 'require|alpha|length:4,20',
        'group'   => 'require|length:4,30',
        'icon'  => 'require',
        'action'   => 'require',

    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',
        'title.length'     => '标题名长度范围2-30个字符',

        'controller.require'   => '控制器名必须填写',
        'controller.alpha'   => '控制器名只能是字母',
        'controller.length'   => '控制器名必须4-20个字符',

        'group.require'   => '方法前缀名必须填写',
        // 'group.alpha'   => '方法前缀名只能是字母',
        'group.length'   => '方法前缀名必须4-20个字符',

        'action.require'   => '方法名必须填写',

        'icon.require'   => 'icon必须选择',


    ];
    
    protected $scene = [
        'add'  =>  ['title','icon'],
        'edit'  =>  ['id','title','icon'],
        'add_c'  =>  ['title','controller','group','action'],
        'edit_c'  =>  ['id','title','controller','group','action'],
        'del'  =>  ['id'],
    ];


}