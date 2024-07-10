<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

use Workerman\Worker;
use Workerman\Lib\Timer as TimerService ;


use app\api\service\Member as MemberService ;

use think\facade\Log;


//php think timer start --d  

class Order extends Command
{
    protected $order;
    protected $interval =1;

    protected function configure()
    {
        // 指令配置
        $this->setName('order')
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
        TimerService::del($this->order);
    }
    public function start()
    {
        $time_interval = 1800;  //一天执行一次
        TimerService::add($time_interval, function()
        {
            MemberService::orderDel();
            Log::channel('worker')->write(date('Y-m-d H:i:s',time()).'取消订单执行成功！');

        });
    }

}