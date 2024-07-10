<?php

namespace app\admin\model ;

use app\common\Model ;

use app\admin\validate\AdminRole as ValidateModel ;
use think\exception\ValidateException;

use think\facade\Db ;

class AdminRole extends Model{


	//增加
	static public function add($params){
		try {
			//验证表单
			validate(ValidateModel::class)->scene('add')->check($params);
			//添加
			$res = static::addData(static::formatData($params)) ;
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
			//添加
			$res = static::editData(static::formatData($params)) ;
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
			$res = static::delData($params,1) ;
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
			return $res ? trueMsg('修改成功！', $res) : errMsg('修改失败！', $res) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}

	}


	//数据处理
	static private function formatData($data){

		//权限数组转字符串
		$data['power_ids'] = implode(',', $data['power_ids']) ;

		//合并分类ID
		$sql = 'select * from `tp_admin_power` where id in ('.$data['power_ids'].') GROUP BY cate_id';

		$list = Db::query($sql);
		$arr = [] ;
		foreach($list as $k=>$v){
			$arr[] = $v['cate_id'] ;
		}
		$data['cate_ids'] = implode(',',$arr);

		return $data ;

	}





}