<?php

namespace app\admin\model ;

use app\common\Model ;
use think\Config ;
use think\facade\Db;

use app\admin\validate\AdminCate as ValidateModel ;
use think\exception\ValidateException;

use app\admin\model\AdminPower ;

class AdminCate extends Model{



	//增加
	static public function add($params){
		Db::startTrans();
		try {
			//验证表单
			if($params['pid']){
				validate(ValidateModel::class)->scene('add_c')->check($params);
			}else{
				validate(ValidateModel::class)->scene('add')->check($params);
			}
			//添加
			$res = static::addData(static::formatData($params)) ;
			//同步到权限表
			$params['id'] = $res ;
			$res2 = static::_toAddPower($params) ;
			if($res && $res2){
				Db::commit();
				return trueMsg('添加成功！', $res);
			}else{
				Db::rollback();
				return errMsg('添加失败！', $res) ;
			}
		} catch (ValidateException $e) {
			Db::rollback();
			return errMsg( 'validate:'.$e->getError() ) ;
		} catch (\Exception $e) {
			Db::rollback();
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}
	}


	//编辑
	static public function edit($params){
		Db::startTrans();
		try {
			//验证表单
			if($params['pid']){
				validate(ValidateModel::class)->scene('edit_c')->check($params);
			}else{
				validate(ValidateModel::class)->scene('edit')->check($params);
			}
			//添加
			$res = static::editData(static::formatData($params)) ;
			//同步到权限表
			$res2 = static::_toUpdatePower($params) ;
			if($res && $res2){
				Db::commit();
				return trueMsg('编辑成功！', $res);
			}else{
				Db::rollback();
				return errMsg('编辑失败！', $res) ;
			}
		} catch (ValidateException $e) {
			Db::rollback();
			return errMsg( 'validate:'.$e->getError() ) ;
		} catch (\Exception $e) {
			Db::rollback();
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
			return $res ? trueMsg('提交成功！', $res) : errMsg('提交失败！', $res) ;
		} catch (\Exception $e) {
			// 这是进行异常捕获
			return errMsg( 'system:'.$e->getMessage() ) ;
		}

	}

	//数据统一处理
	protected static function formatData($data){

		//子目录
		if($data['pid']){
			$arr = [] ;
			$arr2 = [] ;
			$list = Config('set.action') ;
			foreach($data['action'] as $k => $v){
				$state = false ;
				$tag = '' ;
				foreach($list as $kk => $vv){
					if($v == $vv['id']){
						$state = true ;
						$tag = $vv['tag'] ;
					}
				}
				if($state){
					$arr[] = $data['group'].$tag ;
					$arr2[] = $v;
				}
			}
			//其他方法
			if($data['action_other']){
				$arr_other = explode(',',$data['action_other']) ;
				foreach($arr_other as $k => $v){
					$arr[] = $data['group'].$v ;
				}
			}
			//赋值
			$data['actions'] = implode(',', $arr) ;
			$data['action'] = implode(',', $arr2) ;
		}

		return $data ;
	}


	//二层列表数据
	public static function list(){

		$list = static::getAll(['pid'=>0]) ;
		foreach($list as $k => $v){
			$list[$k]['children'] = static::getAll(['pid'=>$v['id']]) ;
		}
		return $list ;
	}


	//批量插入或更新权限表
	protected static function _toAddPower($data){

		//数据处理
		if($data['pid']){
			$arr = [] ;
			$list = Config('set.action') ;
			foreach($data['action'] as $k => $v){
				$state = false ;
				$title = '' ;
				$tag='';
				foreach($list as $kk => $vv){
					if($v == $vv['id']){
						$state = true ;
						$title = $vv['title'] ;
						$tag = $vv['tag'] ;
					}
				}
				if($state){
					$arr[] = [
						'title' => $title ,
						'cate_id' => $data['id'] ,
						'controller' => $data['controller'] ,
						'action' => $data['group'].$tag ,
						'status' => 1 ,
					];
				}
			}
			//其他方法
			if($data['action_other']){
				$arr_other = explode(',',$data['action_other']) ;
				foreach($arr_other as $k => $v){
					$arr[] = [
						'title' => $v ,
						'cate_id' => $data['id'] ,
						'controller' => $data['controller'] ,
						'action' => $data['group'].$v ,
						'status' => 1 ,
					];
				}
			}

			return AdminPower::addAll($arr) ;
		}else{
			return true ;
		}
	}

	//批量插入或更新权限表
	protected static function _toUpdatePower($data){

		//数据处理
		if($data['pid']){
			$arr = [] ;
			$list = Config('set.action') ;
			foreach($data['action'] as $k => $v){
				$state = false ;
				$title = '' ;
				$tag='';
				foreach($list as $kk => $vv){
					if($v == $vv['id']){
						$state = true ;
						$title = $vv['title'] ;
						$tag = $vv['tag'] ;
					}
				}
				if($state){
					$arr[] = [
						'title' => $title ,
						'cate_id' => $data['id'] ,
						'controller' => $data['controller'] ,
						'action' => $data['group'].$tag ,
						'status' => 1 ,
					];
				}
			}
			//其他方法
			if($data['action_other']){
				$arr_other = explode(',',$data['action_other']) ;
				foreach($arr_other as $k => $v){
					$arr[] = [
						'title' => $v ,
						'cate_id' => $data['id'] ,
						'controller' => $data['controller'] ,
						'action' => $data['group'].$v ,
						'status' => 1 ,
					];
				}
			}


			//
			$list = Db::name('admin_power') -> where( ['cate_id'=>$data['id'] ] ) -> select() -> toArray() ;


			//新旧对比
			foreach($list as $k => $v){
				$ss = 0 ;
				foreach($arr as $kk => $vv){
					if( $v['action'] == $vv['action'] && $v['controller'] == $vv['controller'] ){
						$ss = 1 ;
					}
				}
				if($ss){
					$list[$k]['checked'] = 1 ;
				}else{
					$list[$k]['checked'] = 0 ;
				}
			}


			foreach($arr as $k => $v){
				$ss = 0 ;
				foreach($list as $kk => $vv){
					if( $v['action'] == $vv['action'] && $v['controller'] == $vv['controller'] ){
						$ss = 1 ;
					}
				}
				if($ss){
					$arr[$k]['checked'] = 0 ;
				}else{
					$arr[$k]['checked'] = 1 ;
				}
			}


			//处理过时的
			$list_arr = [] ;
			foreach($list as $k => $v){
				if(!$v['checked']){
					$list_arr[] = $v['id'] ;
				}
			}


			if($list_arr){
				AdminPower::delData($list_arr,1);
			}

			//处理新增
			$arr_arr = [] ;
			foreach($arr as $k => $v){
				if(!$v['checked']){
					$arr_arr[] = $v ;
				}
			}

			if($arr_arr){
				AdminPower::addAll($arr_arr) ;
			}


			return true ;
		}else{
			return true ;
		}
	}

}