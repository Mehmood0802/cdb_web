<?php

namespace app\admin\model ;

use app\common\Model ;
use app\admin\validate\Admin as ValidateModel ;
use think\exception\ValidateException;


use think\Config ;
use think\facade\Session ;
use think\facade\Cookie ;


class Admin extends Model{

	//登录
	static public function login($params){
		$m = new self() ;  //静态方法实例化
		try {
			//验证表单
			validate(ValidateModel::class)->scene('login')->check($params);
			
			$data = $m->where('account', $params['account'] ) -> find() ;
			if(!$data){
				return errMsg('账号不存在！') ;
			}
			if($data['password'] != md5(md5($params['password']).$data['code']) ){
				return errMsg('密码不正确！') ;
			}else{
				Cookie::set('admin_id' , $data['id'] , 360000);
				Session::set('admin_id',$data['id']);
				return trueMsg('登录成功！', $data ) ;
			}

		} catch (ValidateException $e) {
			return errMsg( 'validate:'.$e->getError() ) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;

		}

	}


	//修改密码
	static public function changePassword($params){
		try {
			//验证表单
			validate(ValidateModel::class)->scene('password')->check($params);
			
			//验证原密码
			$id = Session::get('admin_id');
			$data = static::getOne($id) ;
			if($data['password'] != passMd5($params['password'], $data['code']) ){
				return errMsg('原密码不正确！') ;
			}
			
			//修改
			$options = [
				'id' => Session::get('admin_id') ,
				'password' => passMd5( $params['password_new'] , $data['code'] ) 
			];

			$res = static::editData($options);

			if($res){
				return trueMsg('修改成功！');
			}else{
				return errMsg('修改失败！');
			}

		} catch (ValidateException $e) {
			return errMsg( 'validate:'.$e->getError() ) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;

		}


	}





	//增加
	static public function add($params){
		try {
			//验证表单
			validate(ValidateModel::class)->scene('add')->check($params);

			$params['code'] = createNonceStr(8);
			$params['password'] = passMd5($params['password'],$params['code']) ;

			//添加
			$res = static::addData($params) ;
			return $res ? trueMsg('添加成功！', $res) : errMsg('添加失败！', $res) ;
		} catch (ValidateException $e) {
			return errMsg( 'validate:'.$e->getError() ) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}
	}


	//编辑
	static public function edit($params){
		try {
			//验证表单
			validate(ValidateModel::class)->scene('edit')->check($params);

			$params['code'] = createNonceStr(8);
			$params['password'] = passMd5($params['password'],$params['code']) ;
			//添加
			$res = static::editData($params) ;
			return $res ? trueMsg('编辑成功！', $res) : errMsg('编辑失败！', $res) ;
		} catch (ValidateException $e) {
			return errMsg( 'validate:'.$e->getError() ) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}
	}

	//删除
	static public function del($params){
		try {
			$res = static::delData($params) ;
			return $res ? trueMsg('删除成功！', $res) : errMsg('删除失败！', $res) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}
	}

	//修改状态
	static public function status($id){
		try {
			$data = static::getOne($id) ;
			$update=[
				'id'=>$id,
				'status'=> $data['status'] ? 0 : 1 
			];
			$res = static::editData($update);
			return $res ? trueMsg('提交成功！', $res) : errMsg('提交失败！', $res) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}

	}


}