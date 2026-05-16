<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with('business');

        if ($search = trim((string) $request->query('q', ''))) {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        if ($role = $request->query('role')) {
            if (in_array($role, ['admin', 'business', 'customer'], true)) {
                $query->where('role', $role);
            }
        }

        if ($status = $request->query('status')) {
            if (in_array($status, ['active', 'suspended', 'banned'], true)) {
                $query->where('account_status', $status);
            }
        }

        $users = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->loadCount(['submittedReports', 'receivedReports', 'referralsGiven', 'comments', 'likedCampaigns', 'savedCampaigns']);

        return view('admin.users.show', compact('user'));
    }

    public function suspend(Request $request, User $user): RedirectResponse
    {
        $this->guardTarget($user);

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $user->update([
            'account_status' => User::ACCOUNT_SUSPENDED,
            'suspended_at' => now(),
            'banned_at' => null,
            'suspension_reason' => $data['reason'] ?? null,
        ]);

        AdminActivityLogger::log(Auth::user(), 'user.suspended', $user, 'User suspended', ['reason' => $data['reason'] ?? null]);

        return back()->with('success', __('User suspended.'));
    }

    public function unsuspend(User $user): RedirectResponse
    {
        $this->guardTarget($user);

        $user->update([
            'account_status' => User::ACCOUNT_ACTIVE,
            'suspended_at' => null,
            'banned_at' => null,
            'suspension_reason' => null,
        ]);

        AdminActivityLogger::log(Auth::user(), 'user.unsuspended', $user, 'User unsuspended');

        return back()->with('success', __('Suspension cleared.'));
    }

    public function ban(Request $request, User $user): RedirectResponse
    {
        $this->guardTarget($user);

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $user->update([
            'account_status' => User::ACCOUNT_BANNED,
            'banned_at' => now(),
            'suspended_at' => null,
            'suspension_reason' => $data['reason'] ?? null,
        ]);

        AdminActivityLogger::log(Auth::user(), 'user.banned', $user, 'User banned', ['reason' => $data['reason'] ?? null]);

        return back()->with('success', __('User banned.'));
    }

    public function unban(User $user): RedirectResponse
    {
        $this->guardTarget($user);

        $user->update([
            'account_status' => User::ACCOUNT_ACTIVE,
            'banned_at' => null,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        AdminActivityLogger::log(Auth::user(), 'user.unbanned', $user, 'User unbanned');

        return back()->with('success', __('Ban removed.'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', __('You cannot delete your own admin account here.'));
        }

        if ($user->isAdmin()) {
            return back()->with('error', __('Deleting administrator accounts is disabled for safety.'));
        }

        $user->delete();

        AdminActivityLogger::log(Auth::user(), 'user.deleted', $user, 'User soft-deleted');

        return redirect()->route('admin.users.index')->with('success', __('User deleted.'));
    }

    private function guardTarget(User $user): void
    {
        if ($user->id === Auth::id()) {
            abort(403, 'Cannot modify your own account with this action.');
        }

        if ($user->isAdmin()) {
            abort(403, 'Administrator accounts cannot be moderated here.');
        }
    }
}
