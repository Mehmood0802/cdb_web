<?php
namespace app\admin\validate;

use think\Validate;
 
class AdminParameter extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'title'  => 'require|length:2,100',
        'tag'   => 'require',
        'value'   => 'require|max:100',
    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'title.require'  => '标题必须填写',
        'title.length'     => '标题名长度范围2-100个字符',

        'tag.require'   => 'tag必须填写',

        'value.require'   => '参数必须填写',
        'value.max'   => '参数最大100个字符',

    ];
    
    protected $scene = [
        'add'  =>  ['title','tag','value'],
        'edit'  =>  ['id','title','tag','value'],
        'del'  =>  ['id'],
    ];


}