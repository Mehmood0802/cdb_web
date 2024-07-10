<?php
namespace app\admin\validate;

use think\Validate;
 
class AdminRole extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require|length:2,10',
        'power_ids'   => 'require',
    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',
        'title.length'     => '标题名长度范围2-10个字符',

        'power_ids.require'   => '权限必须填写',


    ];
    
    protected $scene = [
        'add'  =>  ['title','power_ids'],
        'edit'  =>  ['id','title','power_ids'],
        'del'  =>  ['id'],
    ];


}