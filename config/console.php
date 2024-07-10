<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        // 'workerman' => \crmeb\command\Workerman::class ,
        'timer'=>\app\command\Timer::class,
        'order'=>\app\command\Order::class,
    ],
];
