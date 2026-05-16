<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AdminNotificationRead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationCenterController extends Controller
{
    public function index(): View
    {
        $notifications = AdminNotification::query()
            ->with(['reads' => fn ($q) => $q->where('admin_user_id', Auth::id())])
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function read(AdminNotification $notification): RedirectResponse
    {
        AdminNotificationRead::query()->updateOrCreate(
            [
                'admin_notification_id' => $notification->id,
                'admin_user_id' => Auth::id(),
            ],
            ['read_at' => now()]
        );

        return back();
    }

    public function readAll(): RedirectResponse
    {
        $ids = AdminNotification::query()->pluck('id');

        foreach ($ids as $nid) {
            AdminNotificationRead::query()->updateOrCreate(
                [
                    'admin_notification_id' => $nid,
                    'admin_user_id' => Auth::id(),
                ],
                ['read_at' => now()]
            );
        }

        return back()->with('success', __('All notifications marked read.'));
    }
}
