<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Document;
use App\Models\User;
use App\Policies\DocumentPolicy;
use Illuminate\Support\Facades\Auth;

// Test with kabid user id 283
$kabid = User::find(283);
Auth::login($kabid);

echo "=== KABID: {$kabid->name} (id={$kabid->id}, dept={$kabid->department_id}, company={$kabid->company_id}) ===\n";
echo 'Roles: '.$kabid->getRoleNames()->implode(', ')."\n\n";

// Find a doc uploaded by a different user, same department
$doc = Document::where('status', 'pending_kabid')
    ->where('department_id', $kabid->department_id)
    ->where('user_id', '!=', $kabid->id)
    ->first();

if (! $doc) {
    echo "ERROR: No pending_kabid document found from another user in dept {$kabid->department_id}\n";
    exit(1);
}

echo "=== TEST DOCUMENT: #{$doc->id} '{$doc->title}' ===\n";
echo "  status={$doc->status}, dept={$doc->department_id}, company={$doc->company_id}, user_id={$doc->user_id}\n\n";

$policy = new DocumentPolicy;

echo "--- Policy checks ---\n";
echo 'update():  '.($policy->update($kabid, $doc) ? 'ALLOWED' : 'DENIED')."\n";
echo 'review():  '.($policy->review($kabid, $doc) ? 'ALLOWED' : 'DENIED')."\n";

echo "\n--- Gate/can() checks ---\n";
echo "can('update', doc):  ".($kabid->can('update', $doc) ? 'ALLOWED' : 'DENIED')."\n";
echo "can('review', doc):  ".($kabid->can('review', $doc) ? 'ALLOWED' : 'DENIED')."\n";

echo "\n--- Authorize chain check ---\n";
// This is what ->authorize() in Filament calls
$result = auth()->user()->can('review', $doc);
echo "auth()->user()->can('review', doc): ".($result ? 'ALLOWED' : 'DENIED')."\n";

// Also check if Gate resolves policy
$gateResolve = \Illuminate\Support\Facades\Gate::getPolicyFor($doc);
echo 'Policy class resolved: '.(is_object($gateResolve) ? get_class($gateResolve) : 'NONE')."\n";

echo "\n--- Department match ---\n";
echo "kabid->department_id: {$kabid->department_id}\n";
echo "doc->department_id: {$doc->department_id}\n";
echo 'Match: '.($doc->department_id === $kabid->department_id ? 'YES' : 'NO')."\n";
