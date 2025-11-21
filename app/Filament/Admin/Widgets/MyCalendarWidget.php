<?php

namespace App\Filament\Admin\Widgets;

use \Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Guava\Calendar\Enums\CalendarViewType;

class MyCalendarWidget extends CalendarWidget

{
    //protected string $view = 'filament-widgets::filament.admin.widgets.my-calendar-widget';
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    protected function getEvents(FetchInfo $info): Collection | array | Builder
    {
        return [
            CalendarEvent::make()
                ->title('My first calendar')
                ->start(now())
                ->end(now()->addHours(2)),
        ];
    }
}
