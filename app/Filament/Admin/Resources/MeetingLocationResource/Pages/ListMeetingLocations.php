<?php

namespace App\Filament\Admin\Resources\MeetingLocationResource\Pages;

use App\Filament\Admin\Resources\MeetingLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMeetingLocations extends ListRecords
{
    protected static string $resource = MeetingLocationResource::class;

    public function getTitle(): string
    {
        return 'Lokasi Rapat';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('+ Tambah Lokasi')
                ->icon('heroicon-o-map-pin')
                ->modalHeading('Tambah Lokasi Rapat')
                ->createAnother(false),
        ];
    }
}
