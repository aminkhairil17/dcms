<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schema = new \Filament\Schemas\Schema();
echo method_exists($schema, 'columns') ? 'yes' : 'no';
echo "\n";
echo method_exists($schema, 'components') ? 'yes' : 'no';
echo "\n";
