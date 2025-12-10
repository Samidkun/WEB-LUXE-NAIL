<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class CheckPaymentProofPaths extends Command
{
    protected $signature = 'check:payment-proofs';
    protected $description = 'Check payment proof paths in database';

    public function handle()
    {
        $this->info('Checking payment proof paths...');

        $reservations = Reservation::whereNotNull('payment_proof')
            ->where('payment_proof', '!=', '')
            ->get();

        $this->info("Found {$reservations->count()} reservations with payment proofs");

        foreach ($reservations as $reservation) {
            $this->line("Invoice: {$reservation->queue_number}");
            $this->line("  Path in DB: {$reservation->payment_proof}");

            // Check if file exists in public
            $publicPath = public_path($reservation->payment_proof);
            $exists = file_exists($publicPath);

            $this->line("  File exists in public: " . ($exists ? 'YES' : 'NO'));

            if ($exists) {
                $this->line("  File size: " . filesize($publicPath) . " bytes");
                $this->info("  ✓ OK");
            } else {
                $this->warn("  ✗ FILE NOT FOUND");
            }

            $this->line("");
        }

        return 0;
    }
}
