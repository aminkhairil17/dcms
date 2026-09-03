<?php

namespace App\Filament\Admin\Resources\Documents\Pages;

use App\Filament\Admin\Resources\Documents\DocumentResource;
use App\Filament\Admin\Resources\Documents\Widgets\MyWidget;
use App\Filament\Admin\Widgets\DocumentExpiryWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('guide')
                ->label('Panduan Manajemen Dokumen')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('Panduan Manajemen Dokumen')
                ->modalIcon('heroicon-o-document-text')
                ->modalWidth('3xl')
                ->modalContent(view('filament.admin.resources.documents.partials.document-management-guide-modal'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
            CreateAction::make()
                ->label('Tambah Dokumen')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MyWidget::class,
            DocumentExpiryWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}


