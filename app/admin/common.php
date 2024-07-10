<?php

use think\facade\Config ;


use app\admin\model\StoreGoodsCate ;





//商品类型
function goodsTagName($id){
	$list = Config('set.store_goods_tag') ;
	foreach($list as $k => $v){
		if($v['id'] == $id){
			return $v['title'] ;	
		}
	}
}


//菜品
function goodscateName($ids){
	$html = '' ;
	if($ids){
		$where[] = ['id','in', $ids ] ;
		$list =	StoreGoodsCate::getAll($where);
		if($list){
			$arr = [] ;
			foreach($list as $k => $v){
				$arr[] = $v['title'] ;
			}
			$html = implode(',', $arr) ;
		}
	}
	return $html ;
}