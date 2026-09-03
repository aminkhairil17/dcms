<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use App\Models\Document;
use Filament\Forms\Components\Radio;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->withCount([
                'bookmarks as is_bookmarked' => fn(Builder $bookmarkQuery): Builder => $bookmarkQuery
                    ->where('user_id', Auth::id() ?? 0),
            ]))
            ->columns([
                IconColumn::make('is_bookmarked')
                    ->label('Simpan')
                    ->alignCenter()
                    ->boolean()
                    ->trueIcon('heroicon-s-bookmark')
                    ->falseIcon('heroicon-o-bookmark')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->tooltip(fn(Document $record): string => (int) ($record->is_bookmarked ?? 0) > 0
                        ? 'Dokumen ini ada di daftar tersimpan Anda'
                        : 'Simpan dokumen ini untuk akses cepat')
                    ->width('56px')
                    ->visibleFrom('md'),

                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('48px')
                    ->visibleFrom('md'),

                TextColumn::make('title')
                    ->label('Dokumen')
                    ->view('filament.tables.columns.document-mobile-card')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->fullTextSearch($search);
                    })
                    ->sortable()
                    ->grow(),

                TextColumn::make('document_type')
                    ->label('Tipe')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'file'   => 'heroicon-o-paper-clip',
                        'form'   => 'heroicon-o-pencil-square',
                        'hybrid' => 'heroicon-o-squares-plus',
                        default  => 'heroicon-o-document',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'file'   => 'success',
                        'form'   => 'info',
                        'hybrid' => 'warning',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'file'   => 'Berkas',
                        'form'   => 'Formulir',
                        'hybrid' => 'Gabungan',
                        default  => $state,
                    })
                    ->visibleFrom('md'),

                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->icon('heroicon-o-building-office-2')
                    ->iconColor('gray')
                    ->searchable()
                    ->sortable()
                    ->limit(22)
                    ->wrap()
                    ->placeholder('—')
                    ->visibleFrom('md'),

                TextColumn::make('unit.name')
                    ->label('Unit')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('gray')
                    ->searchable()
                    ->sortable()
                    ->limit(22)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—')
                    ->visibleFrom('lg'),

                TextColumn::make('allowedUnits.name')
                    ->label('Akses Unit Granular')
                    ->badge()
                    ->color('success')
                    ->separator(', ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'draft'            => 'heroicon-o-pencil',
                        'pending_kabid'    => 'heroicon-o-clock',
                        'pending_direktur' => 'heroicon-o-clock',
                        'approved'         => 'heroicon-o-check-circle',
                        'rejected'         => 'heroicon-o-x-circle',
                        'archived'         => 'heroicon-o-archive-box',
                        default            => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending_kabid'    => 'warning',
                        'pending_direktur' => 'info',
                        'approved'         => 'success',
                        'rejected'         => 'danger',
                        'archived'         => 'gray',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft'            => 'Pending',
                        'pending_kabid'    => 'Menunggu Kabid',
                        'pending_direktur' => 'Menunggu Direktur',
                        'approved'         => 'Disetujui',
                        'rejected'         => 'Ditolak',
                        'archived'         => 'Diarsipkan',
                        default            => $state,
                    })
                    ->description(fn(Document $record): string => match ($record->document_type) {
                        'file'   => 'Berkas',
                        'form'   => 'Formulir',
                        'hybrid' => 'Gabungan',
                        default  => '',
                    })
                    ->visibleFrom('md'),

                TextColumn::make('expires_at')
                    ->label('Masa Berlaku')
                    ->badge()
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Tidak dibatasi')
                    ->icon(fn(Document $record): string => match (true) {
                        blank($record->expires_at) => 'heroicon-o-infinity',
                        $record->is_expired => 'heroicon-o-exclamation-triangle',
                        $record->is_expiring_soon => 'heroicon-o-clock',
                        default => 'heroicon-o-shield-check',
                    })
                    ->color(fn(Document $record): string => match (true) {
                        blank($record->expires_at) => 'gray',
                        $record->is_expired => 'danger',
                        $record->is_expiring_soon => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn($state, Document $record): string => match (true) {
                        blank($state) => 'Tidak dibatasi',
                        $record->is_expired => 'Kedaluwarsa',
                        $record->is_expiring_soon => 'Segera berakhir',
                        default => 'Masih berlaku',
                    })
                    ->description(function (Document $record): string {
                        if (! $record->expires_at) {
                            return 'Tanpa tanggal akhir';
                        }

                        if ($record->is_expired) {
                            return 'Berakhir ' . $record->expires_at->format('d M Y');
                        }

                        $daysLeft = today()->diffInDays($record->expires_at, false);

                        return $daysLeft <= 30
                            ? "{$daysLeft} hari lagi · " . $record->expires_at->format('d M Y')
                            : $record->expires_at->format('d M Y');
                    }),

                TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->sortable()
                    ->limit(20)
                    ->wrap()
                    ->description(
                        fn(Document $record): ?string => $record->updated_by && $record->updated_by !== $record->user_id
                            ? 'Diedit: ' . ($record->updatedByUser?->name ?? '—')
                            : null
                    )
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—')
                    ->visibleFrom('lg'),

                TextColumn::make('version')
                    ->label('Versi')
                    ->badge()
                    ->color(fn(Document $record): string => $record->rejections()->exists() ? 'warning' : 'gray')
                    ->icon('heroicon-o-document-duplicate')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('lg'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->icon('heroicon-o-calendar')
                    ->iconColor('gray')
                    ->dateTime('d M Y')
                    ->description(fn(Document $record): string => $record->created_at->diffForHumans())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Document::getStatuses())
                    ->native(false),

                SelectFilter::make('document_type')
                    ->label('Tipe Dokumen')
                    ->options([
                        'file'   => 'Berkas',
                        'form'   => 'Formulir',
                        'hybrid' => 'Gabungan',
                    ])
                    ->native(false),

                SelectFilter::make('company')
                    ->label('Perusahaan')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('department')
                    ->label('Departemen')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('unit')
                    ->label('Unit')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Filter::make('bookmarked')
                    ->label('Tersimpan Saya')
                    ->query(fn(Builder $query): Builder => $query->whereHas(
                        'bookmarks',
                        fn(Builder $bookmarkQuery): Builder => $bookmarkQuery->where('user_id', Auth::id() ?? 0),
                    )),

                Filter::make('critical_expiry')
                    ->label('Kritis 7 Hari')
                    ->query(fn(Builder $query): Builder => $query->expiringSoon(7)),

                Filter::make('expiring_soon')
                    ->label('Segera Kedaluwarsa')
                    ->query(fn(Builder $query): Builder => $query->expiringSoon(30)),

                Filter::make('expired')
                    ->label('Sudah Kedaluwarsa')
                    ->query(fn(Builder $query): Builder => $query->expired()),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label('Detail')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->color('gray')
                    ->extraAttributes(['class' => 'hidden md:inline-flex']),

                Action::make('toggle_bookmark')
                    ->icon(fn(Document $record): string => (int) ($record->is_bookmarked ?? 0) > 0
                        ? 'heroicon-s-bookmark'
                        : 'heroicon-o-bookmark')
                    ->label(fn(Document $record): string => (int) ($record->is_bookmarked ?? 0) > 0
                        ? 'Tersimpan'
                        : 'Simpan')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->color(fn(Document $record): string => (int) ($record->is_bookmarked ?? 0) > 0 ? 'warning' : 'gray')
                    ->action(function (Document $record): void {
                        $isSaved = $record->toggleBookmark();

                        Notification::make()
                            ->title($isSaved ? 'Dokumen disimpan' : 'Bookmark dihapus')
                            ->body($isSaved
                                ? 'Dokumen masuk ke daftar tersimpan Anda untuk akses cepat.'
                                : 'Dokumen dihapus dari daftar tersimpan Anda.')
                            ->success()
                            ->send();
                    })
                    ->extraAttributes(['class' => 'hidden md:inline-flex']),

                Action::make('convert')
                    ->label('Konversi Format')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('info')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->modalWidth('md')
                    ->modalIcon('heroicon-o-arrow-path-rounded-square')
                    ->modalIconColor('info')
                    ->modalHeading('Konversi & Unduh Dokumen')
                    ->modalDescription('Pilih format berkas yang ingin Anda unduh.')
                    ->modalSubmitAction(fn($action) => $action->color('info'))
                    ->modalSubmitActionLabel('Unduh Dokumen')
                    ->modalCancelActionLabel('Batal')
                    ->form(function (Document $record): array {
                        $canWord = in_array($record->document_type, ['form', 'hybrid']) || in_array(strtolower(pathinfo($record->file_path ?? '', PATHINFO_EXTENSION)), ['doc', 'docx']);

                        $options = ['pdf' => 'Unduh sebagai PDF (.pdf)'];
                        $descriptions = ['pdf' => 'Konversi dan unduh dokumen dalam format PDF'];

                        if ($canWord) {
                            $options['word'] = 'Unduh sebagai Word (.docx)';
                            $descriptions['word'] = 'Konversi dan unduh dokumen dalam format Word Docx';
                        }

                        return [
                            Radio::make('format')
                                ->label('Pilih Format')
                                ->options($options)
                                ->descriptions($descriptions)
                                ->default('pdf')
                                ->required(),
                        ];
                    })
                    ->action(function (array $data, Document $record) {
                        if (($data['format'] ?? 'pdf') === 'word') {
                            return \App\Services\DocumentConversionService::downloadAsWord($record);
                        }
                        return \App\Services\DocumentConversionService::downloadAsPdf($record);
                    })
                    ->extraAttributes(['class' => 'hidden md:inline-flex']),

                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->label('Edit')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->color('primary')
                    ->extraAttributes(['class' => 'hidden md:inline-flex']),

                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->label('Unduh Asli')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->color('info')
                    ->url(function (Document $record): string {
                        /** @var FilesystemAdapter $disk */
                        $disk = Storage::disk('documents');
                        return $disk->url((string) $record->file_path);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn(Document $record) => !empty($record->file_path))
                    ->extraAttributes(['class' => 'hidden md:inline-flex']),

                Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-bolt')
                    ->button()
                    ->size('xs')
                    ->color('primary')
                    ->modalWidth('lg')
                    ->modalIcon('heroicon-o-bolt')
                    ->modalHeading('Proses Dokumen')
                    ->modalDescription('Pilih tindakan yang ingin dijalankan untuk dokumen ini.')
                    ->modalSubmitActionLabel('Jalankan')
                    ->modalCancelActionLabel('Batal')
                    ->form(fn(Document $record): array => [
                        Radio::make('process_action')
                            ->label('Tindakan')
                            ->options(self::getProcessOptions($record))
                            ->descriptions(self::getProcessDescriptions($record))
                            ->required(),
                    ])
                    ->action(function (array $data, Document $record): void {
                        self::handleProcessAction($record, $data['process_action']);
                    })
                    ->visible(fn(Document $record): bool => self::hasProcessActions($record))
                    ->extraAttributes(['class' => 'hidden md:inline-flex', 'data-process-action' => 'true']),
            ])



            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('Belum ada dokumen')
            ->emptyStateDescription('Mulai dengan menambahkan dokumen pertama Anda.')
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public static function hasProcessActions(Document $record): bool
    {
        return self::getProcessOptions($record) !== [];
    }

    public static function getProcessOptions(Document $record): array
    {
        $options = [];
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) return $options;

        // Dokumen rejected/draft bisa diajukan untuk review oleh pemiliknya
        if ($record->status === 'draft' && $record->user_id === $user->id) {
            $wasRejected = $record->rejections()->exists();
            $options['submit_review'] = $wasRejected ? 'Ajukan Ulang Review' : 'Ajukan ke Review';
        }

        // Kabid / Super Admin bisa ACC atau Tolak dokumen yang pending_kabid
        if ($record->status === Document::STATUS_PENDING_KABID && ($user->hasRole(['kabid', 'super_admin']))) {
            $options['approve_kabid'] = 'Setujui Dokumen (Teruskan ke Direktur)';
            $options['reject_kabid'] = 'Tolak Dokumen';
        }

        // Direktur / Super Admin bisa ACC atau Tolak dokumen yang pending_direktur maupun pending_kabid
        if (in_array($record->status, [Document::STATUS_PENDING_KABID, Document::STATUS_PENDING_DIREKTUR]) && ($user->hasRole(['direktur', 'super_admin']))) {
            $options['approve_direktur'] = 'Setujui Dokumen (Final)';
            $options['reject_direktur'] = 'Tolak Dokumen';
        }

        // Super admin bisa setujui/tolak langsung draft
        if ($user->hasRole('super_admin') && $record->status === 'draft') {
            $options['approve'] = 'Setujui Langsung';
            $options['reject'] = 'Tolak Dokumen';
        }

        return $options;
    }

    protected static function getProcessDescriptions(Document $record): array
    {
        $descriptions = [];
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($record->status === 'draft') {
            $wasRejected = $record->rejections()->exists();
            $descriptions['submit_review'] = $wasRejected
                ? 'Kirim kembali dokumen yang telah diperbaiki ke Kepala Bidang untuk direview ulang.'
                : 'Kirim dokumen ke Kepala Bidang untuk direview sesuai alur.';
        }

        if ($record->status === Document::STATUS_PENDING_KABID) {
            $descriptions['approve_kabid'] = 'Setujui dokumen ini dan teruskan ke Direktur untuk persetujuan akhir.';
            $descriptions['reject_kabid'] = 'Tolak dokumen ini dan berikan alasan penolakan kepada pembuat dokumen.';
        }

        if ($record->status === Document::STATUS_PENDING_DIREKTUR) {
            $descriptions['approve_direktur'] = 'Berikan persetujuan akhir untuk mengaktifkan/menerbitkan dokumen ini.';
            $descriptions['reject_direktur'] = 'Tolak dokumen ini pada tahap persetujuan akhir.';
        }

        if ($user?->hasRole('super_admin') && $record->status === 'draft') {
            $descriptions['approve'] = 'Lewati antrean review dan langsung tandai dokumen sebagai disetujui.';
            $descriptions['reject'] = 'Tandai dokumen sebagai ditolak tanpa membuka dropdown tambahan.';
        }

        return $descriptions;
    }

    protected static function handleProcessAction(Document $record, string $processAction): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        match ($processAction) {
            'submit_review' => $record->submitForReview(),
            'approve_kabid' => $record->approveByKabid($user),
            'reject_kabid' => $record->rejectByKabid($user, 'Ditolak dari menu proses'),
            'approve_direktur' => $record->approveByDirektur($user),
            'reject_direktur' => $record->rejectByDirektur($user, 'Ditolak dari menu proses'),
            'approve' => $record->update([
                'status' => 'approved',
                'reviewed_by_direktur' => Auth::id(),
                'direktur_reviewed_at' => now(),
            ]),
            'reject' => $record->update([
                'status' => 'rejected',
            ]),
            default => null,
        };

        Notification::make()
            ->title(match ($processAction) {
                'submit_review' => 'Dokumen diajukan untuk review',
                'approve_kabid' => 'Dokumen disetujui Kabid & diteruskan ke Direktur',
                'reject_kabid' => 'Dokumen ditolak Kabid',
                'approve_direktur' => 'Dokumen disetujui final oleh Direktur',
                'reject_direktur' => 'Dokumen ditolak oleh Direktur',
                'approve' => 'Dokumen disetujui',
                'reject' => 'Dokumen ditolak',
                default => 'Proses dokumen selesai',
            })
            ->success()
            ->send();
    }
}
