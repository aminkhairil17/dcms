@php
    $record        = $getRecord();
    $name          = $record->name ?? '—';
    $address       = $record->address ?? null;
    $capacity      = $record->capacity ?? null;
    $isInUse       = method_exists($record, 'isCurrentlyInUse') ? $record->isCurrentlyInUse() : false;
    $meeting       = method_exists($record, 'getCurrentMeeting') ? $record->getCurrentMeeting() : null;
    $meetingTitle  = $meeting?->title ?? null;
    $startTime     = $meeting?->date_time?->format('H:i') ?? null;
    $endTime       = $meeting?->end_time?->format('H:i') ?? null;
    $totalMeetings = $record->meetings_count ?? $record->meetings()->count();
@endphp

<style>
    @media (min-width: 768px) {
        .dcms-mobile-card {
            display: none !important;
        }
        .dcms-desktop-title-view {
            display: flex !important;
        }
    }
    @media (max-width: 767.98px) {
        .dcms-desktop-title-view {
            display: none !important;
        }
        .dcms-mobile-card {
            display: block !important;
        }
    }
</style>

{{-- TAMPILAN DESKTOP (Sembunyi di mobile) --}}
<div class="dcms-desktop-title-view hidden md:flex flex-col gap-0.5 py-0.5">
    <span style="font-size:13px;font-weight:700;color:#0f172a;">
        {{ $name }}
    </span>
</div>

{{-- TAMPILAN MOBILE (Sembunyi di desktop) --}}
<div class="dcms-mobile-card md:hidden" style="width:100%;margin:4px 0;">
    <div style="width:100%;background:#ffffff;border:1px solid {{ $isInUse ? '#fca5a5' : '#bfdbfe' }};border-radius:14px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.06);box-sizing:border-box;">
        <div style="background:{{ $isInUse ? 'linear-gradient(135deg,#fff1f2 0%,#ffe4e6 100%)' : 'linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%)' }};padding:10px 14px;display:flex;align-items:center;gap:10px;border-bottom:1px solid {{ $isInUse ? '#fca5a5' : '#bfdbfe' }};">
            <div style="width:40px;height:40px;border-radius:10px;background:{{ $isInUse ? '#ef4444' : '#1e40af' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:800;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $name }}</div>
                @if($address)<div style="font-size:11px;color:#64748b;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $address }}</div>@endif
            </div>
            @if($isInUse)
                <span style="background:#fff1f2;color:#be123c;border:1px solid #fca5a5;border-radius:20px;padding:2px 8px;font-size:10px;font-weight:700;flex-shrink:0;display:inline-flex;align-items:center;gap:4px;">
                    <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Dipakai</span>
                </span>
            @else
                <span style="background:#ecfdf5;color:#047857;border:1px solid #6ee7b7;border-radius:20px;padding:2px 8px;font-size:10px;font-weight:700;flex-shrink:0;display:inline-flex;align-items:center;gap:4px;">
                    <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Tersedia</span>
                </span>
            @endif
        </div>
        <div style="padding:8px 14px;display:grid;grid-template-columns:1fr 1fr;gap:6px;">
            @if($capacity)<div><div style="font-size:9.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:2px;">Kapasitas</div><div style="font-size:12px;font-weight:600;color:#1e293b;">{{ $capacity }} orang</div></div>@endif
            <div><div style="font-size:9.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:2px;">Total Rapat</div><div style="font-size:12px;font-weight:600;color:#1e293b;">{{ $totalMeetings }} rapat</div></div>
            @if($isInUse && $meetingTitle)
            <div style="grid-column:span 2;">
                <div style="font-size:9.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:2px;">Rapat Berlangsung</div>
                <div style="font-size:11.5px;font-weight:600;color:#be123c;line-height:1.3;">{{ $meetingTitle }}</div>
                @if($startTime)
                    <div style="font-size:10.5px;color:#64748b;margin-top:2px;display:flex;align-items:center;gap:4px;">
                        <svg style="width:11px;height:11px;color:#64748b;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $startTime }}{{ $endTime ? ' – '.$endTime : '' }}</span>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
