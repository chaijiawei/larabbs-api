<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateJWTToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:jwt-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '为用户生成 jwt token';

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
        $userId = $this->ask('请输入用户ID');

        $user = User::query()->find($userId);

        if(! $user) {
            return $this->error('用户不存在');
        }

        $minutesInOneYear = 365 * 24 * 60;
        $token = auth('api')->setTTL($minutesInOneYear)->login($user);
        $this->info($token);
    }
}
