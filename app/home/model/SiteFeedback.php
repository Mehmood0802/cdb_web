<?php

namespace app\home\model ;

use app\common\Model ;

use app\home\validate\SiteFeedback as ValidateModel ;
use think\exception\ValidateException;


class SiteFeedback extends Model{



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


	//数据处理
	static private function formatData($data){
		return $data ;
	}




}