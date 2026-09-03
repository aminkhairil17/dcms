@php
    $hasAlerts = $hasAlerts ?? false;
    $expiredCount = $expiredCount ?? 0;
    $expiringSoonCount = $expiringSoonCount ?? 0;
    $criticalCount = $criticalCount ?? 0;
@endphp

<div>
@if($hasAlerts)
<div class="dcms-expiry-widget" style="
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 12px -2px rgba(0,0,0,0.06);
    overflow: hidden;
    margin-bottom: 8px;
">
    {{-- Header --}}
    <div style="
        padding: 20px 24px 16px;
        background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%);
        border-bottom: 1px solid rgba(239,68,68,0.1);
        display: flex;
        align-items: center;
        gap: 14px;
    ">
        <div style="
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #ef4444, #f97316);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:22px;height:22px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <div>
            <div style="font-size:15px; font-weight:800; color:#1e293b; letter-spacing:-0.01em;">
                Peringatan Masa Berlaku Dokumen
            </div>
            <div style="font-size:12px; color:#64748b; margin-top:2px;">
                @if($expiredCount > 0)
                    <span style="color:#ef4444; font-weight:600;">{{ $expiredCount }} dokumen kedaluwarsa</span>
                @endif
                @if($expiredCount > 0 && $expiringSoonCount > 0) &nbsp;·&nbsp; @endif
                @if($expiringSoonCount > 0)
                    <span style="color:#f97316; font-weight:600;">{{ $expiringSoonCount }} akan kedaluwarsa dalam 30 hari</span>
                @endif
            </div>
        </div>

        {{-- Stats pills --}}
        <div style="margin-left:auto; display:flex; gap:8px; flex-shrink:0;">
            @if($criticalCount > 0)
            <span style="
                background: linear-gradient(135deg, #ef4444, #dc2626);
                color: white;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 700;
                box-shadow: 0 2px 8px rgba(239,68,68,0.3);
                display: inline-flex;
                align-items: center;
                gap: 4px;
            ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                <span>{{ $criticalCount }} kritis</span>
            </span>
            @endif
        </div>
    </div>

    {{-- Tabs content --}}
    <div style="padding: 16px 24px 20px;">
        @if(count($expiredDocs) > 0)
        <div style="margin-bottom:16px;">
            <div style="font-size:11px; font-weight:700; color:#ef4444; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <div style="width:6px; height:6px; background:#ef4444; border-radius:50%;"></div>
                SUDAH KEDALUWARSA
            </div>
            <div style="display:flex; flex-direction:column; gap:6px;">
                @foreach($expiredDocs as $doc)
                <a href="/admin/documents/{{ $doc->id }}" style="
                    display:flex; align-items:center; gap:12px;
                    padding: 10px 14px;
                    background: linear-gradient(135deg, #fef2f2, #fff5f5);
                    border: 1px solid rgba(239,68,68,0.15);
                    border-radius: 10px;
                    text-decoration: none;
                    transition: all 0.2s ease;
                " onmouseover="this.style.transform='translateX(4px)'; this.style.boxShadow='0 4px 12px rgba(239,68,68,0.15)';"
                   onmouseout="this.style.transform=''; this.style.boxShadow='';">
                    <div style="width:32px; height:32px; background:rgba(239,68,68,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ef4444" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:13px; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $doc->title }}</div>
                        <div style="font-size:11px; color:#64748b; margin-top:1px;">{{ $doc->code_number }} · {{ $doc->department?->name }}</div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <div style="font-size:11px; font-weight:700; color:#ef4444; white-space:nowrap; display:flex; align-items:center; justify-content:flex-end; gap:3px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                            <span>{{ $doc->expires_at?->format('d M Y') }}</span>
                        </div>
                        <div style="font-size:10px; color:#94a3b8; margin-top:1px;">{{ $doc->expires_at?->diffForHumans() }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if(count($expiringSoonDocs) > 0)
        <div>
            <div style="font-size:11px; font-weight:700; color:#f97316; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <div style="width:6px; height:6px; background:#f97316; border-radius:50%;"></div>
                SEGERA KEDALUWARSA (30 HARI)
            </div>
            <div style="display:flex; flex-direction:column; gap:6px;">
                @foreach($expiringSoonDocs as $doc)
                @php $daysLeft = today()->diffInDays($doc->expires_at); @endphp
                <a href="/admin/documents/{{ $doc->id }}" style="
                    display:flex; align-items:center; gap:12px;
                    padding: 10px 14px;
                    background: linear-gradient(135deg, #fff7ed, #fffbeb);
                    border: 1px solid rgba(249,115,22,0.15);
                    border-radius: 10px;
                    text-decoration: none;
                    transition: all 0.2s ease;
                " onmouseover="this.style.transform='translateX(4px)'; this.style.boxShadow='0 4px 12px rgba(249,115,22,0.12)';"
                   onmouseout="this.style.transform=''; this.style.boxShadow='';">
                    <div style="width:32px; height:32px; background:rgba(249,115,22,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#f97316" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:13px; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $doc->title }}</div>
                        <div style="font-size:11px; color:#64748b; margin-top:1px;">{{ $doc->code_number }} · {{ $doc->department?->name }}</div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        @php $badgeTextColor = $daysLeft <= 7 ? '#ef4444' : '#f97316'; @endphp
                        <div style="{{ "font-size:11px; font-weight:700; color:{$badgeTextColor}; white-space:nowrap; display:flex; align-items:center; justify-content:flex-end; gap:3px;" }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <span>{{ $daysLeft }} hari lagi</span>
                        </div>
                        <div style="font-size:10px; color:#94a3b8; margin-top:1px;">{{ $doc->expires_at?->format('d M Y') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif
</div>
