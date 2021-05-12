<?php

namespace Code16\Formoj\Console;

use Code16\Formoj\Job\SendDailyNotifications;
use Illuminate\Console\Command;

class SendFormojNotificationsForYesterday extends Command
{
    protected $signature = 'formoj:send_grouped_notifications';
    protected $description = 'Send yesterday’s notifications for configured forms';

    public function handle()
    {
        app(SendDailyNotifications::class)->handle(today()->subDay());

        $this->info("Finished.");
    }
}