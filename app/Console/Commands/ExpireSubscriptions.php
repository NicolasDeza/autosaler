<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire les abonnements expirés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Subscription::where('ends_at', '<', now())->update(['status' => 'expired']);

        return 0;
    }
}
