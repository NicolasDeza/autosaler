<?php

use App\Models\Company;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$company = Company::first();
if ($company) {
    echo 'Logo Thumb: '.$company->getFirstMediaUrl('logo', 'thumb')."\n";
    echo 'Logo Original: '.$company->getFirstMediaUrl('logo')."\n";
} else {
    echo "No company found\n";
}
