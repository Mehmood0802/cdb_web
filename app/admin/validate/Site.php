<?php
namespace app\admin\validate;

use think\Validate;
 
class Site extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require',

    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '店名不能为空',
     



    ];
    
    protected $scene = [
        'add'  =>  ['title'],
        'edit'  =>  ['id','title'],
        'del'  =>  ['id'],
    ];


}