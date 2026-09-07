<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use App\Models\DocumentReadAcknowledgment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QuickAccessHubWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-access-hub-widget';

    protected int|string|array $columnSpan = 1;

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
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user) {
            return [];
        }

        // Bookmarks Count
        $bookmarkCount = Cache::remember(
            'widget_user_bookmarks_'.$user->id,
            30,
            fn () => DB::table('document_bookmarks')->where('user_id', $user->id)->count()
        );

        // Unread Mandatory Docs Count
        $unreadComplianceCount = Cache::remember(
            'widget_unread_compliance_'.$user->id,
            30,
            function () use ($user) {
                $readIds = DocumentReadAcknowledgment::where('user_id', $user->id)->pluck('document_id');

                return Document::query()
                    ->mandatoryForUser($user)
                    ->whereNotIn('id', $readIds)
                    ->count();
            }
        );

        // My Recent Submissions (4 latest)
        $myRecentDocs = Document::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(4)
            ->get();

        // Expiry Alerts
        $baseQuery = Document::query()->access();
        $expiredCount = (clone $baseQuery)->expired()->count();
        $expiringSoonCount = (clone $baseQuery)->expiringSoon(30)->count();
        $expiringSoonDocs = (clone $baseQuery)->expiringSoon(30)->orderBy('expires_at')->limit(3)->get();

        return [
            'bookmarkCount' => $bookmarkCount,
            'unreadComplianceCount' => $unreadComplianceCount,
            'myRecentDocs' => $myRecentDocs,
            'expiredCount' => $expiredCount,
            'expiringSoonCount' => $expiringSoonCount,
            'expiringSoonDocs' => $expiringSoonDocs,
            'hasExpiryAlerts' => $expiredCount > 0 || $expiringSoonCount > 0,
        ];
    }
}
