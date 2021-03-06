<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncLastLoginTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:sync-last-login-time
                            {--now : 同步现在的数据}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步用户最后登录时间';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('开始同步');
        $date = null;
        if($this->option('now')) {
            $date = now()->toDateString();
        }
        $successNum = (new User)->syncLastLoginTimeToDatabase($date);
        $this->info("总共同步了 $successNum 条数据");
    }
}
