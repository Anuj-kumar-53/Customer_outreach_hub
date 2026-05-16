<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomerReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'in:'.implode(',', [
                Report::CATEGORY_SPAM,
                Report::CATEGORY_ABUSE,
                Report::CATEGORY_FAKE_BUSINESS,
                Report::CATEGORY_INAPPROPRIATE,
                Report::CATEGORY_OTHER,
            ])],
            'description'          => ['required', 'string', 'max:5000'],
            'reported_user_id'     => ['nullable', 'integer', 'exists:users,id'],
            'reported_campaign_id' => ['nullable', 'integer', 'exists:campaigns,id'],
            'reported_comment_id'  => ['nullable', 'integer', 'exists:campaign_comments,id'],
        ]);

        if (! ($data['reported_user_id'] ?? null) && ! ($data['reported_campaign_id'] ?? null) && ! ($data['reported_comment_id'] ?? null)) {
            throw ValidationException::withMessages([
                'target' => __('Please choose something to report.'),
            ]);
        }

        if ((int) ($data['reported_user_id'] ?? 0) === (int) Auth::id()) {
            throw ValidationException::withMessages([
                'reported_user_id' => __('You cannot report yourself.'),
            ]);
        }

        $report = Report::create([
            'reporter_id'          => Auth::id(),
            'reported_user_id'     => $data['reported_user_id'] ?? null,
            'reported_campaign_id' => $data['reported_campaign_id'] ?? null,
            'reported_comment_id'  => $data['reported_comment_id'] ?? null,
            'category'             => $data['category'],
            'description'          => $data['description'],
            'status'               => Report::STATUS_OPEN,
        ]);

        AdminNotificationService::notifyAdmins(
            'report_created',
            __('New user report'),
            __('Category: :cat — #:id', ['cat' => $data['category'], 'id' => $report->id]),
            ['report_id' => $report->id]
        );

        return back()->with('success', __('Thanks — your report was submitted. Our team will review it.'));
    }
}