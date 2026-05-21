<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$count = DB::table('jobs')->count();
echo "PENDING JOBS COUNT: " . $count . "\n";

// Let's print the first few pending jobs
$jobs = DB::table('jobs')->get();
foreach ($jobs as $job) {
    $payload = json_decode($job->payload, true);
    echo "Job ID: {$job->id}, Display Name: {$payload['displayName']}\n";
}
