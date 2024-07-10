<?php
namespace app\admin\validate;

use think\Validate;
 
class SiteCate extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require|length:2,100',
    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',
        'title.length'     => '标题名长度范围2-100个字符',



    ];
    
    protected $scene = [
        'add'  =>  ['title'],
        'edit'  =>  ['id','title'],
        'del'  =>  ['id'],
    ];


}