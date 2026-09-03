@php
    $record = $getRecord();
    $filePath = $record?->file_path;
@endphp

@if(filled($filePath))
    @php
        $filename = basename($filePath);
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $cleanPath = ltrim($filePath, '/');
        
        if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
            $url = $filePath;
        } else {
            $url = route('documents.serve', ['path' => $cleanPath]);
        }

        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
        $isPdf   = ($ext === 'pdf');
        $isWord  = in_array($ext, ['doc', 'docx']);
        $isExcel = in_array($ext, ['xls', 'xlsx', 'csv']);

        $badgeStyle = match (true) {
            $isImage => 'background-color:#d1fae5; color:#065f46; border-color:#a7f3d0;',
            $isPdf   => 'background-color:#fee2e2; color:#991b1b; border-color:#fca5a5;',
            $isWord  => 'background-color:#dbeafe; color:#1e40af; border-color:#bfdbfe;',
            $isExcel => 'background-color:#dcfce7; color:#166534; border-color:#bbf7d0;',
            default  => 'background-color:#f3f4f6; color:#374151; border-color:#e5e7eb;',
        };

        $extLabel = strtoupper($ext ?: 'FILE');
    @endphp

    <div x-data="{ open: false }" style="width: 100%; padding-top: 4px; padding-bottom: 4px;">
        <!-- Header Info Bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1;">
                <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; border: 1px solid; flex-shrink: 0; {{ $badgeStyle }}">
                    {{ $extLabel }}
                </span>
                <p style="font-size: 13px; font-weight: 600; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0;" title="{{ $filename }}">
                    {{ $filename }}
                </p>
            </div>
            <a href="{{ $url }}" target="_blank" download style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: #047857; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; text-decoration: none; transition: background 0.15s;">
                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh
            </a>
        </div>

        <!-- Preview Container -->
        @if($isImage)
            <!-- Image Card -->
            <div @click="open = true" 
                 style="cursor: zoom-in; width: 100%; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; padding: 8px; text-align: center; position: relative;">
                <div style="position: relative; overflow: hidden; border-radius: 8px;">
                    <img src="{{ $url }}" alt="{{ $filename }}" style="max-height: 240px; width: auto; max-width: 100%; margin: 0 auto; display: block; object-fit: contain; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.08);" />
                    <div style="margin-top: 8px; font-size: 11px; font-weight: 600; color: #64748b; display: flex; align-items: center; justify-content: center; gap: 4px;">
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        Klik untuk lihat ukuran penuh
                    </div>
                </div>
            </div>

            <!-- Full-Screen Lightbox Modal Teleported to Body -->
            <template x-teleport="body">
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click.self="open = false"
                     @keydown.escape.window="open = false"
                     style="position: fixed; inset: 0; z-index: 99999; display: flex; flex-direction: column; align-items: center; justify-content: space-between; background-color: rgba(15, 23, 42, 0.95); padding: 12px; backdrop-filter: blur(10px); box-sizing: border-box;">
                    
                    <!-- Top Bar with File Name and Close Button -->
                    <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 4px 12px;">
                        <span style="color: #f8fafc; font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 70vw;" title="{{ $filename }}">
                            {{ $filename }}
                        </span>
                        <button @click="open = false" 
                                title="Tutup (Esc)"
                                style="display: flex; width: 36px; height: 36px; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); cursor: pointer; font-size: 18px; flex-shrink: 0;">
                            ✕
                        </button>
                    </div>

                    <!-- Full Resolution Image -->
                    <div style="flex: 1; min-height: 0; width: 100%; display: flex; align-items: center; justify-content: center; padding: 6px; overflow: hidden;">
                        <img src="{{ $url }}" alt="{{ $filename }}" style="max-width: 95vw; max-height: 72vh; object-fit: contain; border-radius: 8px; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.8));" />
                    </div>

                    <!-- Floating Action Toolbar -->
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 6px; padding-bottom: 6px; flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 12px; background: rgba(30, 41, 59, 0.9); padding: 6px 14px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
                            <a href="{{ $url }}" download target="_blank" @click.stop style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background-color: #10b981; color: #ffffff; border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh Dokumen
                            </a>
                            <a href="{{ $url }}" target="_blank" @click.stop style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 20px; font-size: 12px; font-weight: 700; text-decoration: none;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Buka di Tab Baru
                            </a>
                        </div>
                        <p style="color: #cbd5e1; font-size: 11px; margin: 0; line-height: 1.4;">Tekan <kbd style="padding: 2px 6px; background: #334155; border-radius: 4px; color: #e2e8f0; font-family: monospace;">Esc</kbd> atau klik area gelap untuk menutup</p>
                    </div>
                </div>
            </template>
        @elseif($isPdf)
            <!-- PDF Card -->
            <div style="width: 100%; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                <div style="background-color: #fee2e2; padding: 10px 14px; border-bottom: 1px solid #fca5a5; display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 12px; font-weight: 700; color: #991b1b; display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Pratinjau Dokumen PDF
                    </span>
                    <a href="{{ $url }}" target="_blank" style="font-size: 11px; font-weight: 700; color: #991b1b; text-decoration: none;">
                        Buka PDF Penuh &rarr;
                    </a>
                </div>
                <iframe src="{{ $url }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH" style="width: 100%; height: 320px; border: none; display: block;"></iframe>
            </div>
        @else
            <!-- Other Documents Card -->
            <div style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px 16px; text-align: center; background-color: #f8fafc;">
                <div style="margin-bottom: 8px;">
                    <svg style="width: 36px; height: 36px; margin: 0 auto; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0 0 4px;">Dokumen {{ $extLabel }}</h4>
                <p style="font-size: 11px; color: #64748b; margin: 0 0 16px; word-break: break-all;">{{ $filename }}</p>
                <a href="{{ $url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background-color: #2563eb; color: #ffffff; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none;">
                    Buka / Unduh Dokumen
                </a>
            </div>
        @endif
    </div>
@endif
