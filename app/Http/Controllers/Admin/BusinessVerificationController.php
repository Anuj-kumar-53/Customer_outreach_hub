<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BusinessVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Business::query()->with('user');

        if ($search = trim((string) $request->query('q', ''))) {
            $term = '%'.$search.'%';
            $query->where('business_name', 'like', $term);
        }

        if ($request->query('verified') === '1') {
            $query->whereNotNull('verified_at');
        } elseif ($request->query('verified') === '0') {
            $query->whereNull('verified_at');
        }

        $businesses = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.businesses.index', compact('businesses'));
    }

    public function verify(Business $business): RedirectResponse
    {
        $business->update([
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        AdminActivityLogger::log(Auth::user(), 'business.verified', $business, 'Business verified');

        return back()->with('success', __('Business marked as verified.'));
    }

    public function unverify(Business $business): RedirectResponse
    {
        $business->update([
            'verified_at' => null,
            'verified_by' => null,
        ]);

        AdminActivityLogger::log(Auth::user(), 'business.unverified', $business, 'Business verification removed');

        return back()->with('success', __('Verification removed.'));
    }
}
