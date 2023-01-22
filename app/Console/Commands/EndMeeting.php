<?php

namespace App\Console\Commands;

use App\Models\BookingRoom;
use Illuminate\Console\Command;

class EndMeeting extends Command
{
    protected $signature = 'endmeeting:cron';
    protected $description = 'Change the meeting status, when it ends';
    public function handle()
    {
        $data = BookingRoom::when([['status', '1'], ['date', '<=', date(now()->format('Y-m-d'))], ['endtime', '<=', date(now()->format('H:i:s'))]])->get();
        // $data = BookingRoom::when('id',2)->get();
        // error_log($data);
        $data->update([
            'status' => '3'
        ]);
    }
}
