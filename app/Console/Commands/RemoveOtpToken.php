<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RemoveOtpToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OTP:RemoveTenMin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove temp token otp';

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
        User::whereBetween('updated_at', [now()->subMinutes(120), now()])
        ->update(['password' => ""]);
        
        return Command::SUCCESS;
    }
}
