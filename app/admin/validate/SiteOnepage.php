<?php
namespace app\admin\validate;

use think\Validate;
 
class SiteOnepage extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require|length:2,100',
        'content'   => 'require',
    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',
        'title.length'     => '标题名长度范围2-100个字符',

        'content.require'   => '内容必须填写',


    ];
    
    protected $scene = [
        'add'  =>  ['title','content'],
        'edit'  =>  ['id','title','content'],
        'del'  =>  ['id'],
    ];


}