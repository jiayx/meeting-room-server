<?php

namespace App\Console\Commands;

use App\Models\SuperUser;
use Illuminate\Console\Command;

class SuperUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superUser {wanxin} {--D|delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '操作超级用户';

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
     * @return mixed
     */
    public function handle()
    {
        $wanxin = $this->argument('wanxin');

        if ($this->option('delete')) {
            SuperUser::query()->where('wanxin', $wanxin)->delete();
        } else {
            SuperUser::query()->create([
                'wanxin' => $wanxin
            ]);
        }
    }
}
