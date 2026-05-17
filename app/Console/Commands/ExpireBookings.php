<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class ExpireBookings extends Command
{
    protected $signature   = 'bookings:expire';
    protected $description = 'Auto-cancel expired pending bookings';

    public function handle(): void
    {
        $expired = Booking::whereIn('status', ['pending','waiting_payment'])
            ->where('expired_at', '<', now())
            ->get();

        $count = $expired->count();

        foreach ($expired as $booking) {
            $booking->update(['status' => 'expired']);
        }

        $this->info("Expired {$count} bookings.");
    }
}
