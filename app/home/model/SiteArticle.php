<?php

namespace app\home\model ;

use app\common\Model ;

use app\home\validate\SiteArticle as ValidateModel ;
use think\exception\ValidateException;



class SiteArticle extends Model{







	//定义一个获取器
    protected function getCreateTimeAttr($value){
    	return $value > 0 ? date('Y-m-d',$value) : '' ;
    }

}