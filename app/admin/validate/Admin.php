<?php
namespace app\admin\validate;

use think\Validate;
 
class Admin extends Validate
{
    protected $rule =   [
        'id' => 'require' ,
        'account'  => 'require|chsAlphaNum|length:2,20',
        'password'   => 'require|length:6,20',
        'password_new'   => 'require|length:6,20',
        'role_id'   => 'require',
    ];
    
    protected $message  =   [
        'id.require'  => 'id不能为空',
        'account.require'  => '用户名必须填写',
        'account.chsAlphaNum'   => '用户名只能是汉字、字母和数字',
        'account.length'     => '用户名长度范围5-20个字符',
        'password.require'   => '密码必须填写',
        'password.length'   => '密码必须6-20个字符',

        'password_new.require'   => '密码必须填写',
        'password_new.length'   => '密码必须6-20个字符',

        'role_id.require'   => '角色必须选择',
    ];
    
    protected $scene = [
        'login'  =>  ['account','password'],
        'password'  =>  ['password','password_new'],
        'add'  =>  ['account','password','role_id'],
        'edit'  =>  ['id','account','password','role_id'],
        'del'  =>  ['id'],
    ];


}