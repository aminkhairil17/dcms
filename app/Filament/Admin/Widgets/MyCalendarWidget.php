<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Meeting;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;
use Guava\Calendar\Enums\CalendarViewType;

class MyCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected function getEvents(FetchInfo $info): Collection|array
    {
        return Meeting::query()
            ->get()
            ->map(function ($meeting) {
                // START = kolom datetime
                $start = $meeting->date_time;

                // END optional: kalau tidak ada, pakai +1 jam dari start
                $end = $meeting->date_time;

                return CalendarEvent::make()
                    ->title($meeting->title)
                    ->start($start)
                    ->end($end)
                    ->url(route('filament.admin.resources.meetings.edit', $meeting)); // opsional
            });
    }
}
