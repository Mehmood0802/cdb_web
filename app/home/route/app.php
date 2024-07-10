<?php

use think\facade\Route;


/* ============= 基础模块 ===================*/
         

//反馈
Route::get('feedback', 'index/feedback' ) -> middleware(\app\home\middleware\Cors::class);             
Route::post('feedback', 'index/feedback' ) -> middleware(\app\home\middleware\Cors::class);             





