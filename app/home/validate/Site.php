<?php
namespace app\home\validate;

use think\Validate;
 
class Site extends Validate
{
    protected $rule =   [
        'id' => 'require' ,
        'title'  => 'require',

    ];
    
    protected $message  =   [
        'id.require'  => 'id不能为空',
        'title.require'  => 'title不能为空',

    ];
    
    protected $scene = [
        'add'  =>  ['title'],
        'edit'  =>  ['id'],
        'del'  =>  ['id'],
    ];


}