<?php

namespace App\Filament\Reviewer\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class ReviewerDashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Reviewer';

    protected static string $routePath = '/';

    protected static ?int $navigationSort = 1;
}
