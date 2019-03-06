<?php

namespace Code16\Formoj\Console;

use Code16\Formoj\Job\SendDailyNotifications;
use Illuminate\Console\Command;

class SendFormojNotificationsForYesterday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'formoj:send_grouped_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send yesterday’s notifications for configured forms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(SendDailyNotifications::class)->handle(today()->subDay());

        $this->info("Finished.");
    }
}