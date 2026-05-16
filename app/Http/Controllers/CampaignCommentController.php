<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignComment;
use App\Services\CommentSpamAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignCommentController extends Controller
{
    public function store(Request $request, Campaign $campaign, CommentSpamAnalyzer $spamAnalyzer)
    {
        if ($campaign->moderation_status !== 'active') {
            abort(404);
        }

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:500'],
        ]);

        $eval = $spamAnalyzer->evaluate($validated['comment']);

        CampaignComment::create([
            'user_id' => Auth::id(),
            'campaign_id' => $campaign->id,
            'comment' => $validated['comment'],
            'moderation_status' => $eval['status'],
            'spam_score' => $eval['score'],
        ]);

        $message = $eval['status'] === 'pending_review'
            ? __('Comment received and is pending review before it appears publicly.')
            : __('Comment added.');

        return back()->with('success', $message);
    }

    public function destroy(CampaignComment $comment)
    {
        if ((int) $comment->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', __('Comment deleted.'));
    }
}
