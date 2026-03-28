<?php

use App\Models\Company;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$company = Company::first();
if ($company) {
    echo 'Found Company: '.$company->name."\n";
    $media = $company->getMedia('logo');
    echo "Media count in 'logo': ".count($media)."\n";
    foreach ($media as $m) {
        echo 'Media ID: '.$m->id."\n";
        echo 'File name: '.$m->file_name."\n";
        echo 'URL: '.$m->getUrl()."\n";
        echo 'Thumb URL: '.$m->getUrl('thumb')."\n";
    }
} else {
    echo "No company found\n";
}
