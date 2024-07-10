<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------



return [
    // 应用地址
    'app_host'         => env('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // //默认语言
    // 'default_lang' => 'zh-cn', 
    // //开启多语言切换
    // 'lang_switch_on' => true

    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'home',
    // 默认时区
    'default_timezone' => env('app.default_timezone','Asia/Shanghai'),

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    // 'exception_tmpl'   => app()->getRootPath()  . 'public/404.html',
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '404',
    // 显示错误信息
    'show_error_msg'   => false,

    // 是否启用事件
    'with_event'       => true,
    //多例模式
    'auto_multi_app'   => true ,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'trim,strip_tags,htmlspecialchars',


];


