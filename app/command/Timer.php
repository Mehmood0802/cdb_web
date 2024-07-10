<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

use think\facade\Log;

use Workerman\Worker;
use Workerman\Lib\Timer as TimerService ;


use app\api\service\FomoPay as FomoPayService ;

use app\api\model\CabinetOrderTemp ;


use app\api\service\Task as TaskService ;


//php think timer start --d  

class Timer extends Command
{
    protected $timer;
    protected $interval =1;

    protected function configure()
    {
        // 指令配置
        $this->setName('timer')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（守护进程）方式启动')
            ->addOption('i', null, Option::VALUE_OPTIONAL, '多长时间执行一次')
            ->setDescription('开启/关闭/重启 定时任务');
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;
        if ($input->hasOption('i'))
            $this->interval = floatval($input->getOption('i'));
        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }

    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);
        //创建定时器任务  new Worker('websocket://0.0.0.0:2346');
        $task = new Worker();
        $task->count = 1;
        //每个子进程启动时都会执行$this->start方法
        $task->onWorkerStart = [$this, 'start'];
        //每个子进程连接时都会执行，浏览器127.0.0.1:2346,就能调用方法
        $task->onConnect = function ($connection) {
            echo "nihao\n";
        };
        $task->runAll();
    }

    public function stop()
    {
        //手动暂停定时器
        TimerService::del($this->timer);
    }
    public function start()
    {
//        workerman的Timer定时器类 add ，$time_interval是多长时间执行一次
        // $time_interval = 86400;  //一天执行一次
        // TimerService::add($time_interval, function()
        // {
        //     MemberService::bonus();
        //     Log::channel('worker')->write(date('Y-m-d H:i:s',time()).'分红执行成功！');

        // });


        $time_interval = 10 ;  //30s 一次
        TimerService::add($time_interval, function(){

            FomoPayService::check_withdrawal_temp();   //退款

            CabinetOrderTemp::changeOrderStatus();  //清除扫码后未租借的临时订单

            //TaskService::autoCheckMemberRecharge() ;  //取消未充值的订单

            TaskService::CabinetOrderOutTime() ;  //48小时订单处理

            TaskService::autoCheckCabinetOnline() ; //每分钟检查一次 设备 是否在线

            TaskService::autoCheckMemberWithdrawal();   //退款检查，没有退款回调导致充值订单，以及退款订单未修改状态问题

            // TaskService::autoCheckCabinetBattery() ;  //故障电池的处理

            TaskService::pending_orders();   //普通心跳结算订单队列处理

            // Log::channel('worker')->write( date('Y-m-d H:i:s',time()).'轮询提现结果：'.json_decode($res) );
        });


    }

}