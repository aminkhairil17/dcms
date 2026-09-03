<div x-data="{
    confirmModalOpen: false,
    commentIdToDelete: null,
    isReply: false,
    openDeleteModal(id, reply = false) {
        this.commentIdToDelete = id;
        this.isReply = reply;
        this.confirmModalOpen = true;
    },
    confirmDelete() {
        if(this.commentIdToDelete) {
            $wire.deleteComment(this.commentIdToDelete);
            this.confirmModalOpen = false;
        }
    }
}" class="discussion-root" id="discussion-section" wire:poll.30s>
    <style>
        .discussion-root {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.07);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow: hidden;
            margin-top: 24px;
            font-family: inherit;
        }
        .dt-modal-backdrop { position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px; pointer-events: none; }
        .dt-modal { background: white; border-radius: 16px; padding: 24px; max-width: 360px; width: 100%; box-shadow: 0 10px 40px rgba(0,0,0,0.15); text-align: center; pointer-events: auto; }
        .dt-modal-icon { width: 56px; height: 56px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .dt-modal-title { font-size: 18px; font-weight: 700; color: #0F172A; margin: 0 0 8px; }
        .dt-modal-desc { font-size: 14px; color: #64748B; margin: 0 0 24px; line-height: 1.5; }
        .dt-modal-actions { display: flex; gap: 12px; }
        .dt-modal-btn { flex: 1; padding: 10px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; }
        .dt-modal-btn.cancel { background: #F1F5F9; color: #475569; }
        .dt-modal-btn.cancel:hover { background: #E2E8F0; }
        .dt-modal-btn.danger { background: #EF4444; color: white; }
        .dt-modal-btn.danger:hover { background: #DC2626; }
        .dt-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 22px;
            background: linear-gradient(to right, rgba(30,64,175,0.04), transparent);
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .dt-header-left { display: flex; align-items: center; gap: 14px; }
        .dt-icon-wrap {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            display: flex; align-items: center; justify-content: center; color: white;
            box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        }
        .dt-title { font-size: 16px; font-weight: 700; color: #0F172A; margin: 0; line-height: 1.4; }
        .dt-subtitle { font-size: 13px; color: #64748B; margin: 0; }

        @keyframes chatBounce {
            0% { opacity: 0; transform: translateY(20px) scale(0.95); }
            60% { opacity: 1; transform: translateY(-2px) scale(1.02); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-chat {
            animation: chatBounce 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        
        .pinned-banner {
            margin: 16px 22px 0;
            background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
            border: 1px solid #FDE68A;
            border-radius: 12px;
            padding: 16px;
            position: relative;
        }
        .pin-label { font-size: 11px; font-weight: 700; color: #92400E; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px; }
        .pin-content { display: flex; align-items: flex-start; gap: 12px; }
        .pin-body { flex: 1; }
        .pin-badge {
            display: inline-block; background: #F59E0B; color: white;
            font-size: 10px; font-weight: 700; border-radius: 4px;
            padding: 2px 6px; margin-left: 6px; vertical-align: middle;
        }
        .pin-text { font-size: 14px; color: #1E293B; margin: 4px 0; line-height: 1.6; }
        .pin-btn {
            position: absolute; top: 12px; right: 12px;
            background: white; border: 1px solid #FCD34D;
            border-radius: 6px; padding: 4px 10px; font-size: 11px;
            color: #92400E; cursor: pointer; font-weight: 600;
            transition: all 0.2s;
        }
        .pin-btn:hover { background: #FEF3C7; }

        .dt-comments { padding: 16px 22px 22px; background: rgba(248,250,252,0.5); }
        .comment-item { display: flex; align-items: flex-start; gap: 12px; padding: 16px 0; border-bottom: 1px solid rgba(0,0,0,0.04); }
        .comment-item:last-child { border-bottom: none; }
        .comment-item.is-own { flex-direction: row-reverse; }

        .comment-content { flex: 1; display: flex; flex-direction: column; align-items: flex-start; min-width: 0; }
        .comment-item.is-own .comment-content { align-items: flex-end; }

        .comment-avatar {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            color: white; display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; box-shadow: 0 2px 6px rgba(59,130,246,0.2);
            border: 2px solid white;
        }
        .comment-avatar.pinned { background: linear-gradient(135deg, #F59E0B, #FCD34D); box-shadow: 0 2px 6px rgba(245,158,11,0.2); }
        .reply-avatar { width: 32px; height: 32px; font-size: 12px; background: linear-gradient(135deg, #94A3B8, #CBD5E1); box-shadow: none; }
        
        .comment-content { flex: 1; }
        .comment-bubble {
            background: white; border: 1px solid rgba(0,0,0,0.05); border-radius: 16px; border-top-left-radius: 4px;
            padding: 12px 16px; display: inline-block; min-width: 50%; max-width: 90%; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .comment-item.is-own .comment-bubble {
            background: #EFF6FF; border-color: #BFDBFE; border-top-left-radius: 16px; border-top-right-radius: 4px;
        }
        .comment-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; gap: 12px; }
        .comment-item.is-own .comment-header, .reply-item.is-own .comment-header { flex-direction: row-reverse; }
        
        .comment-author { font-size: 14px; font-weight: 600; color: #0F172A; }
        .comment-time { font-size: 12px; color: #94A3B8; }
        .comment-text { font-size: 14px; color: #334155; line-height: 1.6; margin: 0; white-space: pre-wrap; word-break: break-word; }
        
        .comment-actions { display: flex; align-items: center; gap: 12px; margin-top: 8px; margin-left: 8px; }
        .comment-item.is-own .comment-actions { margin-left: 0; margin-right: 8px; flex-direction: row-reverse; }
        
        .ca-btn {
            display: inline-flex; align-items: center; gap: 4px;
            background: transparent; border: none; padding: 0;
            font-size: 12px; font-weight: 500; cursor: pointer; transition: all 0.2s;
        }
        .ca-btn.reply { color: #64748B; } .ca-btn.reply:hover { color: #2563EB; }
        .ca-btn.pin { color: #64748B; } .ca-btn.pin:hover { color: #D97706; }
        .ca-btn.delete { color: #94A3B8; } .ca-btn.delete:hover { color: #DC2626; }

        .replies-thread { margin-top: 12px; padding-left: 20px; border-left: 2px solid #E2E8F0; width: 100%; box-sizing: border-box; }
        .comment-item.is-own .replies-thread { padding-left: 0; padding-right: 20px; border-left: none; border-right: 2px solid #E2E8F0; }

        .reply-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; }
        .reply-item.is-own { flex-direction: row-reverse; }

        .reply-content { flex: 1; display: flex; flex-direction: column; align-items: flex-start; min-width: 0; }
        .reply-item.is-own .reply-content { align-items: flex-end; }

        .reply-bubble {
            background: #F8FAFC; border: 1px solid rgba(0,0,0,0.04); border-radius: 16px; border-top-left-radius: 4px;
            padding: 10px 14px; display: inline-block; max-width: 95%;
        }
        .reply-item.is-own .reply-bubble {
            background: #EFF6FF; border-color: #BFDBFE; border-top-left-radius: 16px; border-top-right-radius: 4px;
        }

        .dt-input-section { padding: 16px 22px; border-top: 1px solid rgba(0,0,0,0.06); background: white; }
        .reply-indicator {
            display: flex; align-items: center; justify-content: space-between;
            background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px;
            padding: 8px 12px; font-size: 12px; color: #1E3A8A; font-weight: 500;
            margin-bottom: 12px;
        }
        .cancel-reply { background: none; border: none; cursor: pointer; color: #60A5FA; padding: 4px; border-radius: 4px; display: flex; align-items: center; }
        .cancel-reply:hover { background: #DBEAFE; color: #1E3A8A; }

        .dt-input-row { display: flex; align-items: flex-end; gap: 12px; position: relative; }
        .dt-textarea {
            width: 100%; border: 1px solid #E2E8F0;
            border-radius: 12px; padding: 12px 14px; padding-right: 48px; font-size: 14px;
            color: #1E293B; resize: none; min-height: 48px;
            transition: all 0.2s; font-family: inherit;
            background: #F8FAFC;
        }
        .dt-textarea:focus { outline: none; border-color: #3B82F6; background: white; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .dt-btn-submit {
            position: absolute; right: 6px; bottom: 6px;
            width: 36px; height: 36px; border-radius: 10px;
            background: #2563EB; color: white; border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
        }
        .dt-btn-submit:hover { background: #1D4ED8; transform: scale(1.05); }
        .dt-error { display: block; color: #EF4444; font-size: 12px; margin-top: 6px; }

        .dt-empty { text-align: center; padding: 40px 20px; }
        .dt-empty-icon { width: 64px; height: 64px; border-radius: 50%; background: #F1F5F9; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: #94A3B8; }
        .dt-empty-text { font-size: 15px; font-weight: 600; color: #475569; margin: 0; }
        .dt-empty-sub { font-size: 13px; color: #94A3B8; margin-top: 4px; }
    </style>

    {{-- ─── HEADER ─── --}}
    <div class="dt-header">
        <div class="dt-header-left">
            <div class="dt-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="22" height="22"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
            </div>
            <div>
                <h4 class="dt-title">Diskusi & Q&A</h4>
                <p class="dt-subtitle">{{ $totalCount }} komentar</p>
            </div>
        </div>
    </div>

    {{-- ─── PINNED COMMENT ─── --}}
    @foreach($pinnedComments as $pinned)
    <div class="pinned-banner animate-chat" style="animation-delay: 0.1s;">
        <div class="pin-label">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09l2.846.813-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM15.5 4.5l-.271-.949a2.25 2.25 0 0 0-1.545-1.545L12.75 1.75l.934.267a2.25 2.25 0 0 0 1.545 1.545l.271.949.271-.949a2.25 2.25 0 0 0 1.545-1.545l.934-.267-.934.267a2.25 2.25 0 0 0-1.545 1.545l-.271.949Z" /></svg>
            Jawaban Resmi
        </div>
        <div class="pin-content">
            <div class="comment-avatar pinned">{{ strtoupper(substr($pinned->user?->name ?? 'A', 0, 1)) }}</div>
            <div class="pin-body">
                <div>
                    <span class="comment-author">{{ $pinned->user?->name ?? 'Anonim' }}</span>
                    <span class="pin-badge">Official</span>
                </div>
                <p class="pin-text">{{ $pinned->content }}</p>
                <div class="comment-time">{{ $pinned->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @can('manage-discussions')
        <button wire:click="togglePin({{ $pinned->id }})" class="pin-btn unpin">Lepas Sematan</button>
        @else
            @if(auth()->user()?->hasAnyRole(['super_admin', 'kabid', 'direktur']))
            <button wire:click="togglePin({{ $pinned->id }})" class="pin-btn unpin">Lepas Sematan</button>
            @endif
        @endcan
    </div>
    @endforeach

    {{-- ─── COMMENT LIST ─── --}}
    <div class="dt-comments">
        @forelse($comments as $index => $comment)
        @php $isOwn = $comment->user_id === auth()->id(); @endphp
        <div class="comment-item animate-chat {{ $isOwn ? 'is-own' : '' }}" style="--delay: {{ 0.05 * $index }}s; animation-delay: var(--delay);" wire:key="comment-{{ $comment->id }}">
            <div class="comment-avatar">{{ strtoupper(substr($comment->user?->name ?? 'A', 0, 1)) }}</div>
            <div class="comment-content">
                <div class="comment-bubble">
                    <div class="comment-header">
                        <span class="comment-author">{{ $comment->user?->name ?? 'Anonim' }}</span>
                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="comment-text">{{ $comment->content }}</p>
                </div>

                <div class="comment-actions">
                    <button wire:click="startReply({{ $comment->id }}, '{{ addslashes($comment->user?->name ?? 'Anonim') }}')" class="ca-btn reply">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                        Balas
                    </button>

                    @if(auth()->user()?->hasAnyRole(['super_admin', 'kabid', 'direktur']))
                    <button wire:click="togglePin({{ $comment->id }})" class="ca-btn pin">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h1m2 0h1m2 0h1m2 0h1m2 0h1m2 0h1M6 21v-8a6 6 0 0 1 12 0v8M12 3v2" /></svg>
                        Sematkan
                    </button>
                    @endif

                    @if($comment->user_id === auth()->id() || auth()->user()?->hasAnyRole(['super_admin', 'kabid']))
                    <button @click="openDeleteModal({{ $comment->id }}, false)" class="ca-btn delete">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Hapus
                    </button>
                    @endif
                </div>

                {{-- Replies --}}
                @if($comment->replies->isNotEmpty())
                <div class="replies-thread">
                    @foreach($comment->replies as $rIndex => $reply)
                    @php $isOwnReply = $reply->user_id === auth()->id(); @endphp
                    <div class="reply-item animate-chat {{ $isOwnReply ? 'is-own' : '' }}" style="--delay: {{ 0.1 + (0.05 * $rIndex) }}s; animation-delay: var(--delay);" wire:key="reply-{{ $reply->id }}">
                        <div class="comment-avatar reply-avatar">{{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}</div>
                        <div class="reply-content">
                            <div class="reply-bubble">
                                <div class="comment-header">
                                    <span class="comment-author" style="font-size:13px;">{{ $reply->user?->name ?? 'Anonim' }}</span>
                                    <span class="comment-time" style="font-size:11px;">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="comment-text" style="font-size:13px;">{{ $reply->content }}</p>
                            </div>
                            @if($reply->user_id === auth()->id() || auth()->user()?->hasAnyRole(['super_admin', 'kabid']))
                            <div class="comment-actions" style="margin-top:4px;">
                                <button @click="openDeleteModal({{ $reply->id }}, true)" class="ca-btn delete">Hapus</button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="dt-empty">
            <div class="dt-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>
            </div>
            <h4 class="dt-empty-text">Belum ada diskusi</h4>
            <p class="dt-empty-sub">Jadilah yang pertama bertanya atau memberikan komentar!</p>
        </div>
        @endforelse
    </div>

    {{-- ─── INPUT FORM ─── --}}
    <div class="dt-input-section" x-data>
        @if($replyingTo)
        <div class="reply-indicator">
            <div style="display:flex; align-items:center; gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                <span>Membalas <strong>{{ $replyingToName }}</strong></span>
            </div>
            <button wire:click="cancelReply" class="cancel-reply">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        @endif

        <div class="dt-input-row">
            <textarea
                wire:model.defer="newComment"
                id="comment-input"
                class="dt-textarea"
                placeholder="{{ $replyingTo ? 'Tulis balasan Anda di sini...' : 'Tulis pertanyaan atau komentar...' }}"
                rows="1"
                x-on:focus-comment-input.window="$el.focus()"
                x-on:keydown.enter.prevent="$wire.submitComment()"
                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
            ></textarea>
            <button wire:click="submitComment" class="dt-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18" style="margin-left:2px;"><path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" /></svg>
            </button>
        </div>
        <div style="font-size:11px; color:#94A3B8; text-align:right; margin-top:4px; padding-right:48px;">Tekan Enter untuk mengirim</div>
        @error('newComment')<span class="dt-error">{{ $message }}</span>@enderror
    </div>

    {{-- ─── DELETE CONFIRMATION MODAL ─── --}}
    <template x-teleport="body">
        <div x-show="confirmModalOpen" class="dt-modal-backdrop" x-transition.opacity.duration.300ms style="display: none;">
            <div class="dt-modal" x-show="confirmModalOpen" @click.away="confirmModalOpen = false" x-transition.scale.90.duration.300ms>
                <div class="dt-modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="dt-modal-title" x-text="isReply ? 'Hapus Balasan?' : 'Hapus Komentar?'"></h3>
                <p class="dt-modal-desc">Tindakan ini tidak dapat dibatalkan. Pesan yang dihapus akan hilang selamanya.</p>
                <div class="dt-modal-actions">
                    <button type="button" class="dt-modal-btn cancel" @click="confirmModalOpen = false">Batal</button>
                    <button type="button" class="dt-modal-btn danger" @click="confirmDelete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </template>
</div>
