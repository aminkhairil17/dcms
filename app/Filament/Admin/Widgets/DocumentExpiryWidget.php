<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class DocumentExpiryWidget extends Widget
{
    protected string $view = 'filament.widgets.document-expiry-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getPollingInterval(): ?string
    {
        return '60s';
    }

    public static function canView(): bool
    {
        return Auth::check();
    }

    protected function getViewData(): array
    {
        $baseQuery = Document::query()->access();

        $expiredDocs = (clone $baseQuery)->expired()->orderBy('expires_at')->limit(5)->get();
        $expiredCount = (clone $baseQuery)->expired()->count();

        $expiringSoonDocs = (clone $baseQuery)->expiringSoon(30)->orderBy('expires_at')->limit(5)->get();
        $expiringSoonCount = (clone $baseQuery)->expiringSoon(30)->count();

        $criticalDocs = (clone $baseQuery)->expiringSoon(7)->orderBy('expires_at')->limit(5)->get();
        $criticalCount = (clone $baseQuery)->expiringSoon(7)->count();

        return [
            'expiredDocs'        => $expiredDocs,
            'expiredCount'       => $expiredCount,
            'expiringSoonDocs'   => $expiringSoonDocs,
            'expiringSoonCount'  => $expiringSoonCount,
            'criticalDocs'       => $criticalDocs,
            'criticalCount'      => $criticalCount,
            'hasAlerts'          => $expiredCount > 0 || $expiringSoonCount > 0,
        ];
    }
}
