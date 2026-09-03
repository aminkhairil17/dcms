<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PriorityActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.priority-actions-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 20;

    public static function canView(): bool
    {
        return Auth::check();
    }

    protected function getViewData(): array
    {
        /** @var User $user */
        $user = Auth::user();

        $documentsNeedActionQuery = $this->getDocumentsNeedActionQuery($user);
        $documentsNeedAction = (clone $documentsNeedActionQuery)
            ->limit(5)
            ->get()
            ->map(fn(Document $document) => $this->transformDocument($document))
            ->all();

        $quickActions = $this->buildQuickActions($user);

        return [
            'documentsNeedAction' => $documentsNeedAction,
            'quickActions'        => $quickActions,
        ];
    }

    private function getDocumentsNeedActionQuery(User $user)
    {
        $query = Document::query()->access()->latest();

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

    private function transformDocument(Document $document): array
    {
        return [
            'title'     => $document->title,
            'code'      => $document->code_number ?: 'Tanpa nomor',
            'url'       => '/admin/documents/' . $document->id,
            'meta'      => $document->category?->name ?: 'Dokumen internal',
            'badge'     => match ($document->status) {
                Document::STATUS_DRAFT             => 'Perlu dilengkapi',
                Document::STATUS_REJECTED          => 'Perlu revisi',
                Document::STATUS_PENDING_KABID     => 'Menunggu Kabid',
                Document::STATUS_PENDING_DIREKTUR  => 'Menunggu Direktur',
                default                            => ucfirst($document->status),
            },
            'badgeColor' => match ($document->status) {
                Document::STATUS_DRAFT             => 'slate',
                Document::STATUS_REJECTED          => 'rose',
                Document::STATUS_PENDING_KABID     => 'amber',
                Document::STATUS_PENDING_DIREKTUR  => 'sky',
                default                            => 'gray',
            },
            'updatedAt' => optional($document->updated_at)->diffForHumans() ?: 'Baru saja',
        ];
    }

    private function buildQuickActions(User $user): array
    {
        return [
            [
                'label'       => 'Buat Reminder',
                'description' => 'Pusat pengirim notifikasi pengingat pegawai & reviewer',
                'url'         => route('filament.admin.pages.reminders'),
                'icon'        => 'heroicon-o-bell-alert',
                'accent'      => 'blue',
            ],
            [
                'label'       => 'Compliance Hub',
                'description' => 'Lihat & konfirmasi dokumen wajib baca Anda',
                'url'         => route('filament.admin.pages.compliance-hub'),
                'icon'        => 'heroicon-o-shield-check',
                'accent'      => 'blue',
            ],
        ];
    }
}
