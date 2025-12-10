<?php
// Debug payment proof path
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$reservation = \App\Models\Reservation::where('queue_number', 'LX20251210LPVY')->first();

if ($reservation) {
    echo "Invoice: " . $reservation->queue_number . "\n";
    echo "Payment Proof Path: " . $reservation->payment_proof . "\n";
    echo "Full URL: " . url('/' . $reservation->payment_proof) . "\n";

    // Check if file exists
    $publicPath = public_path($reservation->payment_proof);
    echo "File exists in public: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";

    if (file_exists($publicPath)) {
        echo "File size: " . filesize($publicPath) . " bytes\n";
    }
} else {
    echo "Reservation not found\n";
}
