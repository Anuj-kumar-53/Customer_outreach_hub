<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReferralRegistrationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request, ReferralRegistrationService $referralRegistration): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:customer,business'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'reward_points' => 0,
            'account_status' => User::ACCOUNT_ACTIVE,
        ]);

        // Phase 7: referral credit happens exactly once at registration time.
        $referralRegistration->processForNewUser($user);

        event(new Registered($user));

        Auth::login($user);

        $redirectRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'business' => 'business.dashboard',
            'customer' => 'customer.dashboard',
            default => 'dashboard',
        };

        return redirect()->route($redirectRoute);
    }
}
