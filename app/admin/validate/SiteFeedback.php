<?php
namespace app\admin\validate;

use think\Validate;
 
class SiteFeedback extends Validate
{
    protected $rule =   [
        'id' => 'require|number',
        'truename'  => 'require',
        'mobile'  => 'require|mobile',
        'remark'  => 'require',

    ];
    
    protected $message  =   [
        'id.require' => 'id必须' ,
        'id.number' => 'id只能是数字' ,

        'truename.require'  => '姓名必须填写',
        'mobile.require'     => '手机号必须填写',
        'mobile.mobile'     => '手机号格式不对',
        'remark.require'  => '反馈内容必须填写',

    ];
    
    protected $scene = [
        'add'  =>  ['truename','mobile','remark'],
        'edit'  =>  ['id','truename','mobile','remark'],
        'del'  =>  ['id'],
    ];


}