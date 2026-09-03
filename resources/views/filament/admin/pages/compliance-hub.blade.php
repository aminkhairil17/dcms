<x-filament-panels::page>
    @php
    $data = $this->getViewData();
    extract($data);
    @endphp

    {{-- ─── HERO PROGRESS CARD ─── --}}
    <div class="compliance-hub-hero">
        <div class="compliance-hero-left">
            <div class="compliance-progress-ring">
                <svg class="compliance-ring-svg" viewBox="0 0 120 120" width="120" height="120">
                    <circle class="ring-track" cx="60" cy="60" r="52" fill="none" stroke-width="10" />
                    <circle class="ring-progress" cx="60" cy="60" r="52" fill="none" stroke-width="10"
                        stroke-dasharray="{{ round(52 * 2 * 3.14159) }}"
                        stroke-dashoffset="{{ round(52 * 2 * 3.14159 * (1 - $percentage / 100)) }}"
                        stroke-linecap="round"
                        transform="rotate(-90 60 60)" />
                </svg>
                <div class="ring-label">
                    <span class="ring-percent">{{ $percentage }}%</span>
                    <span class="ring-sublabel">Patuh</span>
                </div>
            </div>
        </div>
        <div class="compliance-hero-right">
            <h2 class="hero-heading">Kepatuhan Dokumen Saya</h2>
            <p class="hero-sub">Berdasarkan dokumen SOP wajib baca untuk divisi Anda.</p>
            <div class="hero-stats">
                <div class="stat-chip read">
                    <span class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg></span>
                    <span><strong>{{ $readCount }}</strong> Sudah Dibaca</span>
                </div>
                <div class="stat-chip unread">
                    <span class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg></span>
                    <span><strong>{{ $totalCount - $readCount }}</strong> Belum Dibaca</span>
                </div>
                <div class="stat-chip total">
                    <span class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                        </svg></span>
                    <span><strong>{{ $totalCount }}</strong> Total Dokumen</span>
                </div>
            </div>
            @if($percentage >= 100)
            <div class="hero-badge complete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg> Luar biasa! Semua dokumen wajib baca telah selesai.</div>
            @elseif($percentage >= 80)
            <div class="hero-badge almost"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                </svg> Hampir selesai! Segera baca sisa dokumen.</div>
            @else
            <div class="hero-badge pending"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg> Harap selesaikan semua dokumen wajib baca.</div>
            @endif
        </div>
    </div>

    {{-- ─── BELUM DIBACA ─── --}}
    @if($unreadDocs->isNotEmpty())
    <div class="compliance-section">
        <div class="section-header danger">
            <span class="section-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="margin-top:2px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg></span>
            <div>
                <h3 class="section-title">Belum Dibaca <span class="badge-count danger">{{ $unreadDocs->count() }}</span></h3>
                <p class="section-desc">Baca dan tandai dokumen berikut sebagai sudah dibaca.</p>
            </div>
        </div>
        <div class="doc-list">
            @foreach($unreadDocs as $doc)
            @php
                $isOpened = !empty($openedDocuments[$doc->id]);
                $fileUrl  = $doc->file_url ?? route('filament.admin.resources.documents.view', $doc);
            @endphp
            <div class="doc-card unread"
                 x-data="{
                     opened: {{ $isOpened ? 'true' : 'false' }},
                     timer: 10,
                     ready: {{ $isOpened ? 'false' : 'false' }},
                     showModal: false,
                     agreeChecked: false,
                     startTimer() {
                         this.opened = true;
                         this.timer = 10;
                         this.ready = false;
                         let interval = setInterval(() => {
                             if (this.timer > 0) {
                                 this.timer--;
                             } else {
                                 this.ready = true;
                                 clearInterval(interval);
                             }
                         }, 1000);
                     }
                 }"
                 x-init="if (opened) { startTimer(); }">
                <div class="doc-icon-wrap unread">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="doc-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <div class="doc-info">
                    <div class="doc-title">{{ $doc->title }}</div>
                    <div class="doc-meta">
                        @if($doc->code_number)<span class="meta-chip" style="display:inline-flex;align-items:center;gap:4px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                            </svg> {{ $doc->code_number }}</span>@endif
                        @if($doc->department)<span class="meta-chip" style="display:inline-flex;align-items:center;gap:4px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg> {{ $doc->department->name }}</span>@endif
                    </div>
                    <div class="doc-desc">{{ $doc->description ?? 'Tidak ada deskripsi.' }}</div>
                </div>
                <div class="doc-actions">
                    <a href="{{ $fileUrl }}" target="_blank"
                        @click="startTimer()"
                        class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="btn-text-desktop">Buka & Baca Dokumen</span>
                        <span class="btn-text-mobile">Buka Dokumen</span>
                    </a>

                    <!-- State 1: Belum dibuka -->
                    <template x-if="!opened">
                        <button type="button" disabled class="btn-disabled" title="Harap buka dan baca dokumen terlebih dahulu">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <span class="btn-text-desktop">Tandai Telah Dibaca (Buka Dulu)</span>
                            <span class="btn-text-mobile">Tandai Dibaca (Buka Dulu)</span>
                        </button>
                    </template>

                    <!-- State 2: Sedang membaca (Timer berjalan) -->
                    <template x-if="opened && !ready">
                        <button type="button" disabled class="btn-timer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14" class="animate-spin">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Harap Baca... (<span x-text="timer"></span>s)
                        </button>
                    </template>

                    <!-- State 3: Siap konfirmasi -->
                    <template x-if="opened && ready">
                        <button type="button" @click="showModal = true" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span class="btn-text-desktop">Konfirmasi Telah Membaca</span>
                            <span class="btn-text-mobile">Konfirmasi Baca</span>
                        </button>
                    </template>
                </div>

                <!-- Anti-Cheat Confirmation Modal -->
                <div x-show="showModal" class="compliance-modal-backdrop" x-cloak style="display: none;">
                    <div class="compliance-modal-content" @click.away="showModal = false">
                        <div class="modal-header">
                            <div class="modal-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="modal-title">Pernyataan Kepatuhan Membaca</h4>
                                <p class="modal-subtitle">Konfirmasi integritas membaca dokumen resmi SOP</p>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p style="margin-bottom: 12px; font-size: 13px; color: #334155; line-height: 1.5;">
                                Dokumen: <strong>{{ $doc->title }}</strong> ({{ $doc->code_number ?? 'SOP' }})
                            </p>
                            <div class="declaration-box">
                                <label class="declaration-checkbox-label">
                                    <input type="checkbox" x-model="agreeChecked" class="modal-checkbox">
                                    <span>Saya menyatakan dengan sesungguhnya bahwa saya telah <strong>membuka, membaca, memahami</strong>, dan siap <strong>menerapkan</strong> isi dari dokumen ini dalam menjalankan tugas.</span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="showModal = false" class="btn-modal-cancel">Batal</button>
                            <button type="button"
                                    :disabled="!agreeChecked"
                                    @click="showModal = false; $wire.acknowledge({{ $doc->id }})"
                                    class="btn-modal-submit">
                                Kirim Konfirmasi Kepatuhan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ─── SUDAH DIBACA ─── --}}
    @if($readDocs->isNotEmpty())
    <div class="compliance-section">
        <div class="section-header success">
            <span class="section-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="margin-top:2px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg></span>
            <div>
                <h3 class="section-title">Sudah Dibaca <span class="badge-count success">{{ $readDocs->count() }}</span></h3>
                <p class="section-desc">Dokumen-dokumen berikut telah Anda baca dan akui.</p>
            </div>
        </div>
        <div class="doc-list">
            @foreach($readDocs as $doc)
            <div class="doc-card read">
                <div class="doc-icon-wrap read">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="doc-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="doc-info">
                    <div class="doc-title read">{{ $doc->title }}</div>
                    <div class="doc-meta">
                        @if($doc->code_number)<span class="meta-chip" style="display:inline-flex;align-items:center;gap:4px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                            </svg> {{ $doc->code_number }}</span>@endif
                        @if($doc->department)<span class="meta-chip" style="display:inline-flex;align-items:center;gap:4px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg> {{ $doc->department->name }}</span>@endif
                    </div>
                </div>
                <div class="doc-actions" style="display: flex; align-items: center; gap: 8px;">
                    @php
                        $fileUrl = $doc->file_url ?? route('filament.admin.resources.documents.view', $doc);
                    @endphp
                    <a href="{{ $fileUrl }}" target="_blank" class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        Buka Dokumen
                    </a>
                    <span class="read-badge"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg> Telah Dibaca</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($totalCount === 0)
    <div class="empty-state">
        <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="48" height="48" style="color:#CBD5E1; margin:0 auto;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="empty-title">Tidak ada dokumen wajib baca saat ini</h3>
        <p class="empty-desc">Dokumen SOP yang diwajibkan untuk divisi Anda akan muncul di sini.</p>
    </div>
    @endif

    <style>
        .compliance-hub-hero {
            display: flex;
            align-items: center;
            gap: 40px;
            background: linear-gradient(135deg, #0B2545 0%, #133B6B 50%, #1E40AF 100%);
            border-radius: 20px;
            padding: 36px 40px;
            margin-bottom: 28px;
            color: white;
            box-shadow: 0 10px 30px -5px rgba(11, 37, 69, 0.4);
            position: relative;
            overflow: hidden;
        }

        .compliance-hub-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .compliance-progress-ring {
            position: relative;
            flex-shrink: 0;
        }

        .compliance-ring-svg {
            display: block;
        }

        .ring-track {
            stroke: rgba(255, 255, 255, 0.15);
        }

        .ring-progress {
            stroke: #34D399;
            transition: stroke-dashoffset 1s ease;
        }

        .ring-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .ring-percent {
            display: block;
            font-size: 22px;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .ring-sublabel {
            display: block;
            font-size: 10px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .compliance-hero-right {
            flex: 1;
        }

        .hero-heading {
            font-size: 22px;
            font-weight: 800;
            color: white;
            margin: 0 0 6px 0;
        }

        .hero-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 0 20px 0;
        }

        .hero-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .stat-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .hero-badge.complete {
            background: rgba(52, 211, 153, 0.2);
            color: #6EF5C0;
            border: 1px solid rgba(52, 211, 153, 0.3);
        }

        .hero-badge.almost {
            background: rgba(251, 191, 36, 0.2);
            color: #FDE68A;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .hero-badge.pending {
            background: rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .compliance-section {
            margin-bottom: 28px;
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 14px 14px 0 0;
            border-bottom: 2px solid transparent;
        }

        .section-header.danger {
            background: #FFF5F5;
            border-color: #FCA5A5;
        }

        .section-header.success {
            background: #F0FDF4;
            border-color: #86EFAC;
        }

        .section-icon {
            font-size: 22px;
            line-height: 1;
        }

        .section-title {
            font-size: 15px;
            font-weight: 800;
            color: #0B2545;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-desc {
            font-size: 12px;
            color: #64748B;
            margin: 3px 0 0 0;
        }

        .badge-count {
            display: inline-block;
            border-radius: 999px;
            padding: 1px 9px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-count.danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .badge-count.success {
            background: #DCFCE7;
            color: #16A34A;
        }

        .doc-list {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 0 0 14px 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .doc-card {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: background 0.2s;
        }

        .doc-card:last-child {
            border-bottom: none;
        }

        .doc-card:hover {
            background: #F8FAFF;
        }

        .doc-card.read {
            opacity: 0.75;
        }

        .doc-icon-wrap {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .doc-icon-wrap.unread {
            background: #EEF2FF;
        }

        .doc-icon-wrap.read {
            background: #F0FDF4;
        }

        .doc-icon {
            width: 20px;
            height: 20px;
        }

        .doc-icon-wrap.unread .doc-icon {
            color: #4F46E5;
        }

        .doc-icon-wrap.read .doc-icon {
            color: #16A34A;
        }

        .doc-info {
            flex: 1;
            min-width: 0;
        }

        .doc-title {
            font-size: 14px;
            font-weight: 700;
            color: #0B2545;
            margin-bottom: 5px;
        }

        .doc-title.read {
            color: #64748B;
        }

        .doc-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 6px;
        }

        .meta-chip {
            background: #F1F5F9;
            color: #475569;
            border-radius: 5px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .doc-desc {
            font-size: 12px;
            color: #94A3B8;
            line-height: 1.5;
        }

        .doc-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, #1E40AF, #3B82F6);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 3px 8px rgba(30, 64, 175, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(30, 64, 175, 0.4);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: transparent;
            color: #1E40AF;
            border: 1.5px solid #93C5FD;
            border-radius: 8px;
            padding: 7px 13px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: #EFF6FF;
            border-color: #1E40AF;
        }

        .read-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #F0FDF4;
            color: #16A34A;
            border: 1.5px solid #86EFAC;
            border-radius: 8px;
            padding: 7px 13px;
            font-size: 12px;
            font-weight: 700;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 800;
            color: #0B2545;
            margin-bottom: 8px;
        }

        .empty-desc {
            font-size: 13px;
            color: #64748B;
        }

        /* Anti-Cheat Styles */
        .btn-disabled {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #F1F5F9;
            color: #94A3B8;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: not-allowed;
            white-space: nowrap;
        }

        .btn-timer {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #FEF3C7;
            color: #D97706;
            border: 1px solid #FCD34D;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: wait;
            white-space: nowrap;
        }

        .compliance-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .compliance-modal-content {
            background: white;
            border-radius: 16px;
            max-width: 480px;
            width: 100%;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
            text-align: left;
        }

        .modal-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #EFF6FF;
            color: #1E40AF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 800;
            color: #0F172A;
            margin: 0;
        }

        .modal-subtitle {
            font-size: 12px;
            color: #64748B;
            margin: 2px 0 0 0;
        }

        .declaration-box {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 14px;
            margin-top: 10px;
            text-align: left;
        }

        .declaration-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            cursor: pointer;
            font-size: 12px;
            color: #334155;
            line-height: 1.5;
        }

        .modal-checkbox {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border-radius: 4px;
            accent-color: #1E40AF;
            cursor: pointer;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-modal-cancel {
            background: #F1F5F9;
            color: #475569;
            border: none;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-modal-cancel:hover {
            background: #E2E8F0;
        }

        .btn-modal-submit {
            background: linear-gradient(135deg, #16A34A, #15803D);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.3);
            transition: all 0.2s;
        }

        .btn-modal-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
            background: #CBD5E1;
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .btn-text-mobile {
            display: none;
        }

        .btn-text-desktop {
            display: inline;
        }

        /* ── Compact Mobile Layout for Hero Progress Card & Document List (< 768px) ── */
        @media (max-width: 767.98px) {
            .btn-text-desktop {
                display: none !important;
            }

            .btn-text-mobile {
                display: inline !important;
            }

            .doc-card {
                flex-wrap: wrap !important;
                padding: 12px 14px !important;
                gap: 10px !important;
            }

            .doc-icon-wrap {
                width: 34px !important;
                height: 34px !important;
                border-radius: 8px !important;
            }

            .doc-icon {
                width: 16px !important;
                height: 16px !important;
            }

            .doc-info {
                flex: 1 1 calc(100% - 50px) !important;
                min-width: 0 !important;
            }

            .doc-title {
                font-size: 13px !important;
                margin-bottom: 4px !important;
            }

            .doc-desc {
                font-size: 11px !important;
            }

            .doc-actions {
                width: 100% !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 6px !important;
                margin-top: 6px !important;
            }

            .doc-actions .btn-outline,
            .doc-actions .btn-disabled,
            .doc-actions .btn-timer,
            .doc-actions .btn-primary,
            .doc-actions .read-badge {
                width: 100% !important;
                justify-content: center !important;
                padding: 7px 10px !important;
                font-size: 11.5px !important;
                box-sizing: border-box !important;
                text-align: center !important;
            }

            .doc-meta {
                gap: 4px !important;
            }

            .meta-chip {
                font-size: 10px !important;
                padding: 2px 6px !important;
                max-width: 100% !important;
                word-break: break-word !important;
            }

            .section-header {
                padding: 12px 14px !important;
                gap: 10px !important;
            }

            .section-title {
                font-size: 14px !important;
            }

            .section-desc {
                font-size: 11px !important;
            }

            .compliance-hub-hero {
                flex-direction: row !important;
                align-items: flex-start !important;
                gap: 12px !important;
                padding: 14px 12px !important;
                border-radius: 14px !important;
                margin-bottom: 16px !important;
            }

            .compliance-progress-ring {
                width: 64px !important;
                height: 64px !important;
            }

            .compliance-ring-svg {
                width: 64px !important;
                height: 64px !important;
            }

            .ring-percent {
                font-size: 14px !important;
            }

            .ring-sublabel {
                font-size: 8px !important;
                margin-top: 1px !important;
            }

            .compliance-hero-right {
                min-width: 0 !important;
                flex: 1 !important;
            }

            .hero-heading {
                font-size: 14px !important;
                margin-bottom: 2px !important;
                line-height: 1.25 !important;
            }

            .hero-sub {
                font-size: 10.5px !important;
                margin-bottom: 8px !important;
                line-height: 1.3 !important;
            }

            .hero-stats {
                display: flex !important;
                flex-direction: column !important;
                gap: 4px !important;
                margin-bottom: 8px !important;
            }

            .stat-chip {
                padding: 3px 8px !important;
                font-size: 10px !important;
                border-radius: 6px !important;
                gap: 4px !important;
                width: fit-content !important;
            }

            .stat-chip .stat-icon svg {
                width: 13px !important;
                height: 13px !important;
            }

            .hero-badge {
                padding: 4px 8px !important;
                font-size: 10px !important;
                border-radius: 6px !important;
                gap: 4px !important;
                line-height: 1.3 !important;
                box-sizing: border-box !important;
            }

            .hero-badge svg {
                width: 13px !important;
                height: 13px !important;
                flex-shrink: 0 !important;
            }
        }
    </style>
</x-filament-panels::page>