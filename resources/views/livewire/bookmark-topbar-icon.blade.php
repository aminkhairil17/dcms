<div class="fi-topbar-item" style="display: flex; align-items: center; margin-right: 0.25rem;">
    <a
        href="{{ route('filament.admin.pages.bookmarks') }}"
        title="{{ $count > 0 ? $count . ' dokumen tersimpan' : 'Dokumen Tersimpan' }}"
        style="
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            transition: background-color 0.2s ease, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        "
        onmouseenter="this.style.backgroundColor='rgba(255,255,255,0.12)'; this.style.transform='scale(1.1)'; this.querySelector('svg').style.transform='scale(1.15) rotate(-5deg)'"
        onmouseleave="this.style.backgroundColor='transparent'; this.style.transform='scale(1)'; this.querySelector('svg').style.transform='scale(1) rotate(0deg)'"
    >
        {{-- Bookmark icon --}}
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="{{ $count > 0 ? 'rgba(245,158,11,0.9)' : 'none' }}"
            viewBox="0 0 24 24"
            stroke-width="1.75"
            stroke="{{ $count > 0 ? 'rgb(245,158,11)' : 'rgba(255,255,255,0.7)' }}"
            @php $svgFilter = $count > 0 ? 'filter: drop-shadow(0 0 5px rgba(245,158,11,0.5));' : ''; @endphp
            style="{{ "width: 1.2rem; height: 1.2rem; transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), stroke 0.2s ease, fill 0.2s ease; {$svgFilter}" }}"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
        </svg>

        {{-- Badge counter --}}
        @if($count > 0)
            <span style="
                position: absolute;
                top: -2px;
                right: -2px;
                min-width: 16px;
                height: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 4px;
                font-size: 9px;
                font-weight: 800;
                line-height: 1;
                color: #ffffff;
                background: linear-gradient(135deg, #f59e0b, #d97706);
                border-radius: 999px;
                border: 1.5px solid #0B2545;
                box-shadow: 0 2px 6px rgba(245, 158, 11, 0.5);
                letter-spacing: 0.2px;
                animation: bookmark-badge-pop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            ">
                {{ $count > 99 ? '99+' : $count }}
            </span>
        @endif
    </a>

    <style>
        @keyframes bookmark-badge-pop {
            0%   { transform: scale(0); opacity: 0; }
            60%  { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</div>
