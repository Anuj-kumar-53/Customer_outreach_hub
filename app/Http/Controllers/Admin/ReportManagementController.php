<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::query()->with(['reporter', 'reportedUser', 'reportedCampaign', 'reportedComment']);

        if ($status = $request->query('status')) {
            if (in_array($status, [Report::STATUS_OPEN, Report::STATUS_REVIEWING, Report::STATUS_RESOLVED, Report::STATUS_DISMISSED], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $term = '%'.$search.'%';
            $query->where('description', 'like', $term);
        }

        $reports = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report): View
    {
        $report->load(['reporter', 'reportedUser', 'reportedCampaign.business', 'reportedComment.user']);

        return view('admin.reports.show', compact('report'));
    }

    public function status(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Report::STATUS_OPEN,
                Report::STATUS_REVIEWING,
                Report::STATUS_RESOLVED,
                Report::STATUS_DISMISSED,
            ])],
            'resolution_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $report->update([
            'status' => $data['status'],
            'resolution_notes' => $data['resolution_notes'] ?? $report->resolution_notes,
            'resolved_by' => in_array($data['status'], [Report::STATUS_RESOLVED, Report::STATUS_DISMISSED], true)
                ? Auth::id()
                : null,
        ]);

        AdminActivityLogger::log(Auth::user(), 'report.updated', $report, 'Report status updated', ['status' => $data['status']]);

        return back()->with('success', __('Report updated.'));
    }
}
