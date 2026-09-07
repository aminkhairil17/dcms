<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use App\Models\Meeting;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class EmployeeActivityHubWidget extends Widget
{
    protected string $view = 'filament.widgets.employee-activity-hub';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    protected function getViewData(): array
    {
        /** @var User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        $todayMeetingsQuery = Meeting::query()
            ->access()
            ->whereDate('date_time', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date_time');

        $todayMeetingsCount = (clone $todayMeetingsQuery)->count();
        $todayMeetings = (clone $todayMeetingsQuery)
            ->limit(4)
            ->get()
            ->map(fn (Meeting $meeting) => $this->transformMeeting($meeting))
            ->all();

        $upcomingMeetingsQuery = Meeting::query()
            ->access()
            ->where('date_time', '>=', now())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('date_time');

        $nextMeeting = ($meeting = (clone $upcomingMeetingsQuery)->first())
            ? $this->transformMeeting($meeting)
            : null;

        $documentsNeedActionQuery = $this->getDocumentsNeedActionQuery($user);
        $documentsNeedActionCount = (clone $documentsNeedActionQuery)->count();
        $documentsNeedAction = (clone $documentsNeedActionQuery)
            ->limit(4)
            ->get()
            ->map(fn (Document $document) => $this->transformDocument($document))
            ->all();

        $unreadNotificationsCount = (int) $user->unreadNotifications()->count();

        $todayLoad = $todayMeetingsCount + $documentsNeedActionCount;
        $subtitle = $todayLoad > 0
            ? "Ada {$todayLoad} dokumen & agenda yang menunggu tindakan Anda hari ini. Mari kita selesaikan!"
            : 'Semua pekerjaan Anda hari ini sudah terkendali dengan baik. Selamat melanjutkan aktivitas!';

        return [
            'greeting' => $this->getGreeting(),
            'userName' => $user->name,
            'subtitle' => $subtitle,
            'organizationPath' => $user->organization_path ?: 'Pegawai aktif',
            'nextMeeting' => $nextMeeting,
            'todayMeetings' => $todayMeetings,
            'todayMeetingsCount' => $todayMeetingsCount,
            'documentsNeedAction' => $documentsNeedAction,
            'documentsNeedActionCount' => $documentsNeedActionCount,
            'unreadNotificationsCount' => $unreadNotificationsCount,
            'quickActions' => $this->buildQuickActions($user, $documentsNeedActionCount, $todayMeetingsCount),
        ];
    }

    private function getGreeting(): string
    {
        $hour = (int) now()->format('H');

        return match (true) {
            $hour < 11 => 'Selamat pagi',
            $hour < 15 => 'Selamat siang',
            $hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };
    }

    private function getDocumentsNeedActionQuery(User $user): Builder
    {
        $query = Document::query()
            ->access()
            ->latest();

        if ($user->hasRole('super_admin')) {
            return $query->whereIn('status', [
                Document::STATUS_PENDING_KABID,
                Document::STATUS_PENDING_DIREKTUR,
            ]);
        }

        if ($user->hasRole('kabid')) {
            return $query
                ->where('department_id', $user->department_id)
                ->where('status', Document::STATUS_PENDING_KABID);
        }

        if ($user->hasRole('direktur')) {
            return $query
                ->where('company_id', $user->company_id)
                ->where('status', Document::STATUS_PENDING_DIREKTUR);
        }

        return $query
            ->where('user_id', $user->id)
            ->whereIn('status', [
                Document::STATUS_DRAFT,
                Document::STATUS_REJECTED,
                Document::STATUS_PENDING_KABID,
                Document::STATUS_PENDING_DIREKTUR,
            ]);
    }

    private function transformMeeting(Meeting $meeting): array
    {
        /** @var Carbon $dateTime */
        $dateTime = $meeting->date_time;

        return [
            'title' => $meeting->title,
            'url' => '/admin/meetings/'.$meeting->id,
            'when' => $this->formatMeetingWhen($dateTime),
            'time' => $dateTime->format('H:i'),
            'location' => $meeting->location ?: 'Lokasi belum diisi',
            'badge' => match ($meeting->status) {
                'pending' => 'Menunggu',
                'ongoing' => 'Berlangsung',
                'completed' => 'Selesai',
                default => ucfirst((string) $meeting->status),
            },
            'badgeColor' => match ($meeting->status) {
                'pending' => 'warning',
                'ongoing' => 'info',
                'completed' => 'gray',
                default => 'gray',
            },
        ];
    }

    private function transformDocument(Document $document): array
    {
        return [
            'title' => $document->title,
            'code' => $document->code_number ?: 'Tanpa nomor',
            'url' => '/admin/documents/'.$document->id,
            'meta' => $document->category?->name ?: 'Dokumen internal',
            'badge' => match ($document->status) {
                Document::STATUS_DRAFT => 'Perlu dilengkapi',
                Document::STATUS_REJECTED => 'Perlu revisi',
                Document::STATUS_PENDING_KABID => 'Menunggu Kabid',
                Document::STATUS_PENDING_DIREKTUR => 'Menunggu Direktur',
                default => ucfirst($document->status),
            },
            'badgeColor' => match ($document->status) {
                Document::STATUS_DRAFT => 'slate',
                Document::STATUS_REJECTED => 'rose',
                Document::STATUS_PENDING_KABID => 'amber',
                Document::STATUS_PENDING_DIREKTUR => 'sky',
                default => 'gray',
            },
            'updatedAt' => optional($document->updated_at)->diffForHumans() ?: 'Baru saja',
        ];
    }

    private function buildQuickActions(User $user, int $documentsNeedActionCount, int $todayMeetingsCount): array
    {
        return [
            [
                'label' => 'Buat Reminder',
                'description' => 'Pusat pengirim notifikasi pengingat pegawai & reviewer',
                'url' => route('filament.admin.pages.reminders'),
                'icon' => 'heroicon-o-bell-alert',
                'accent' => 'blue',
            ],
            [
                'label' => 'Compliance Hub',
                'description' => 'Lihat & konfirmasi dokumen wajib baca Anda',
                'url' => route('filament.admin.pages.compliance-hub'),
                'icon' => 'heroicon-o-shield-check',
                'accent' => 'blue',
            ],
        ];
    }

    private function formatMeetingWhen(Carbon $dateTime): string
    {
        if ($dateTime->isToday()) {
            return 'Hari ini, '.$dateTime->format('H:i');
        }

        if ($dateTime->isTomorrow()) {
            return 'Besok, '.$dateTime->format('H:i');
        }

        return $dateTime->format('d M Y, H:i');
    }
}
