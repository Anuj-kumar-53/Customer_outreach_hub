<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AdminActivityLog::query()->with('admin');

        if ($search = trim((string) $request->query('q', ''))) {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('action', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($adminId = $request->query('admin_id')) {
            if (ctype_digit((string) $adminId)) {
                $query->where('admin_id', (int) $adminId);
            }
        }

        $logs = $query->orderByDesc('id')->paginate(40)->withQueryString();

        return view('admin.activity.index', compact('logs'));
    }
}
