<?php

namespace App\Livewire;

use App\Models\Document;
use App\Models\DocumentDiscussion;
use App\Notifications\DiscussionPinnedNotification;
use App\Notifications\DiscussionRepliedNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocumentDiscussionThread extends Component
{
    public int $documentId;

    public string $newComment = '';

    public ?int $replyingTo = null;

    public string $replyingToName = '';

    protected $rules = [
        'newComment' => 'required|string|min:3|max:2000',
    ];

    protected $listeners = ['$refresh'];

    public function submitComment(): void
    {
        $this->validate();

        $discussion = DocumentDiscussion::create([
            'document_id' => $this->documentId,
            'user_id' => Auth::id(),
            'parent_id' => $this->replyingTo,
            'content' => $this->newComment,
            'is_pinned' => false,
        ]);

        // If this is a reply, notify the parent comment's author
        if ($this->replyingTo) {
            $parent = DocumentDiscussion::find($this->replyingTo);
            if ($parent && $parent->user_id !== Auth::id()) {
                $parent->user->notify(new DiscussionRepliedNotification($discussion));
            }
        }

        // Also notify document owner
        $document = Document::find($this->documentId);
        if ($document && $document->user_id !== Auth::id()) {
            $document->user?->notify(new DiscussionRepliedNotification($discussion));
        }

        $this->newComment = '';
        $this->replyingTo = null;
        $this->replyingToName = '';

        $this->dispatch('$refresh');
    }

    public function startReply(int $discussionId, string $authorName): void
    {
        $this->replyingTo = $discussionId;
        $this->replyingToName = $authorName;
        $this->dispatch('focus-comment-input');
    }

    public function cancelReply(): void
    {
        $this->replyingTo = null;
        $this->replyingToName = '';
    }

    public function togglePin(int $discussionId): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user?->hasAnyRole(['super_admin', 'kabid', 'direktur'])) {
            return;
        }

        $discussion = DocumentDiscussion::find($discussionId);
        if (! $discussion) {
            return;
        }

        // If we are pinning, first unpin all others for this doc
        if (! $discussion->is_pinned) {
            DocumentDiscussion::where('document_id', $this->documentId)
                ->update(['is_pinned' => false]);
        }

        $discussion->update(['is_pinned' => ! $discussion->is_pinned]);

        // Notify author when their comment is pinned
        if ($discussion->is_pinned && $discussion->user_id !== Auth::id()) {
            $discussion->user->notify(new DiscussionPinnedNotification($discussion));
        }

        $this->dispatch('$refresh');
    }

    public function deleteComment(int $discussionId): void
    {
        $discussion = DocumentDiscussion::find($discussionId);
        if (! $discussion) {
            return;
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $canDelete = $discussion->user_id === Auth::id()
            || ($user?->hasAnyRole(['super_admin', 'kabid']) ?? false);

        if ($canDelete) {
            $discussion->delete();
        }

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $pinnedComments = DocumentDiscussion::with(['user', 'replies.user'])
            ->where('document_id', $this->documentId)
            ->whereNull('parent_id')
            ->where('is_pinned', true)
            ->latest()
            ->get();

        $comments = DocumentDiscussion::with(['user', 'replies.user'])
            ->where('document_id', $this->documentId)
            ->whereNull('parent_id')
            ->where('is_pinned', false)
            ->latest()
            ->get();

        $totalCount = DocumentDiscussion::where('document_id', $this->documentId)->count();

        return view('livewire.document-discussion-thread', [
            'pinnedComments' => $pinnedComments,
            'comments' => $comments,
            'totalCount' => $totalCount,
        ]);
    }
}
