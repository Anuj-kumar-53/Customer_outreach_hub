<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignComment;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    public function index(Request $request): View
    {
        $query = CampaignComment::query()->with(['user', 'campaign']);

        if ($status = $request->query('status')) {
            if (in_array($status, ['approved', 'pending_review', 'hidden', 'spam'], true)) {
                $query->where('moderation_status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $term = '%'.$search.'%';
            $query->where('comment', 'like', $term);
        }

        $comments = $query->orderByDesc('id')->paginate(25)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(CampaignComment $comment): RedirectResponse
    {
        $comment->update([
            'moderation_status' => 'approved',
            'moderated_at' => now(),
            'moderated_by' => Auth::id(),
        ]);

        AdminActivityLogger::log(Auth::user(), 'comment.approved', $comment, 'Comment approved');

        return back()->with('success', __('Comment approved.'));
    }

    public function markSpam(CampaignComment $comment): RedirectResponse
    {
        $comment->update([
            'moderation_status' => 'spam',
            'spam_score' => max($comment->spam_score, 90),
            'moderated_at' => now(),
            'moderated_by' => Auth::id(),
        ]);

        AdminActivityLogger::log(Auth::user(), 'comment.spam', $comment, 'Comment marked spam');

        return back()->with('success', __('Comment marked as spam.'));
    }

    public function hide(CampaignComment $comment): RedirectResponse
    {
        $comment->update([
            'moderation_status' => 'hidden',
            'moderated_at' => now(),
            'moderated_by' => Auth::id(),
        ]);

        AdminActivityLogger::log(Auth::user(), 'comment.hidden', $comment, 'Comment hidden');

        return back()->with('success', __('Comment hidden.'));
    }

    public function destroy(CampaignComment $comment): RedirectResponse
    {
        $commentId = $comment->id;
        $comment->delete();

        AdminActivityLogger::log(Auth::user(), 'comment.deleted', null, 'Comment deleted permanently', ['comment_id' => $commentId]);

        return back()->with('success', __('Comment deleted permanently.'));
    }
}
