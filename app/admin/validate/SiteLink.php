<?php
namespace app\admin\validate;

use think\Validate;
 
class SiteLink extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require',
    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',



    ];
    
    protected $scene = [
        'add'  =>  ['title'],
        'edit'  =>  ['id'],
        'del'  =>  ['id'],
    ];


}