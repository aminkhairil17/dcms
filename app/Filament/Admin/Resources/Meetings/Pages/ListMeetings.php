<?php

namespace App\Filament\Admin\Resources\Meetings\Pages;

use App\Filament\Admin\Resources\Meetings\MeetingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListMeetings extends ListRecords
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'terjadwal' => Tab::make()->query(fn($query) => $query->where('status', 'scheduled')),
            'Selesai' => Tab::make()->query(fn($query) => $query->where('status', 'completed')),
            'dibatalkan' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
    public function getDefaultActiveTab(): string|int|null
    {
        return 'terjadwal';
    }
}
