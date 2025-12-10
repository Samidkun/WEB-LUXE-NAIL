<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigratePaymentProofs extends Command
{
    protected $signature = 'migrate:payment-proofs';
    protected $description = 'Migrate payment proof images from storage to public folder';

    public function handle()
    {
        $this->info('Starting payment proof migration...');

        $storagePath = storage_path('app/public/payment_proofs');
        $publicPath = public_path('payment_proofs');

        // Check if storage directory exists
        if (!File::exists($storagePath)) {
            $this->warn('No payment proofs found in storage. Nothing to migrate.');
            return 0;
        }

        // Create public directory if not exists
        if (!File::exists($publicPath)) {
            File::makeDirectory($publicPath, 0755, true);
            $this->info('Created public/payment_proofs directory');
        }

        // Get all files from storage
        $files = File::files($storagePath);
        $count = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $destination = $publicPath . '/' . $filename;

            // Copy file (not move, to keep backup in storage)
            if (!File::exists($destination)) {
                File::copy($file->getPathname(), $destination);
                $count++;
                $this->line("Copied: {$filename}");
            } else {
                $this->line("Skipped (already exists): {$filename}");
            }
        }

        $this->info("Migration complete! Copied {$count} files.");
        $this->info('Payment proofs are now in public/payment_proofs/');
        $this->warn('You can now remove the storage symlink if desired.');

        return 0;
    }
}
