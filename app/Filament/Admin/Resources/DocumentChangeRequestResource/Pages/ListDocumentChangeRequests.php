<?php

namespace App\Filament\Admin\Resources\DocumentChangeRequestResource\Pages;

use App\Filament\Admin\Resources\DocumentChangeRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentChangeRequests extends ListRecords
{
    protected static string $resource = DocumentChangeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('guide')
                ->label('Panduan Usulan Revisi')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('Panduan Cara Mengajukan Usulan Revisi')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalWidth('3xl')
                ->modalContent(view('filament.admin.resources.document-change-requests.change-request-guide-modal'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
            CreateAction::make()
                ->label('+ Ajukan Usulan Revisi')
                ->icon('heroicon-o-pencil-square'),
        ];
    }
}
