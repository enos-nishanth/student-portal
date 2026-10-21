<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginOtpChallenge;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class OtpVerificationController extends Controller
{
    public function create(Request $request)
    {
        $challengeId = $request->session()->get(
            'pending_login_challenge'
        );

        if (!$challengeId) {
            return redirect()->route('login')
                ->withErrors([
                    'login' => 'Please log in again.',
                ]);
        }

        $challenge = LoginOtpChallenge::where(
            'challenge_id',
            $challengeId
        )
            ->whereNull('verified_at')
            ->first();

        if (
            !$challenge ||
            $challenge->expires_at->isPast() ||
            $challenge->attempts >= 5
        ) {
            $request->session()->forget([
                'pending_login_challenge',
                'pending_login_remember',
            ]);

            return redirect()->route('login')
                ->withErrors([
                    'login' => 'Your OTP session expired. Please log in again.',
                ]);
        }

        return view('auth.verify-otp');
    }

    public function store(
        Request $request,
        TwoFactorService $twoFactor
    ) {
        $request->validate([
            'otp' => ['required', 'digits_between:4,6'],
        ]);

        $challengeId = $request->session()->get(
            'pending_login_challenge'
        );

        if (!$challengeId) {
            return redirect()->route('login')
                ->withErrors([
                    'login' => 'Please log in again.',
                ]);
        }

        $challenge = LoginOtpChallenge::where(
            'challenge_id',
            $challengeId
        )
            ->whereNull('verified_at')
            ->first();

        if (
            !$challenge ||
            $challenge->expires_at->isPast() ||
            $challenge->attempts >= 5
        ) {
            $request->session()->forget([
                'pending_login_challenge',
                'pending_login_remember',
            ]);

            return redirect()->route('login')
                ->withErrors([
                    'login' => 'Your OTP session expired. Please log in again.',
                ]);
        }

        try {
            $verified = $twoFactor->verifyOtp(
                $challenge->provider_session_id,
                $request->input('otp')
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'otp' => 'OTP verification is temporarily unavailable. Please try again.',
            ]);
        }

        if (!$verified) {
            $challenge->increment('attempts');
            $challenge->refresh();

            if ($challenge->attempts >= 5) {
                $request->session()->forget([
                    'pending_login_challenge',
                    'pending_login_remember',
                ]);

                return redirect()->route('login')
                    ->withErrors([
                        'login' => 'Too many incorrect attempts. Please log in again.',
                    ]);
            }

            return back()->withErrors([
                'otp' => 'The OTP is incorrect. Please try again.',
            ]);
        }

        $user = DB::transaction(function () use ($challengeId) {
            $lockedChallenge = LoginOtpChallenge::where(
                'challenge_id',
                $challengeId
            )
                ->whereNull('verified_at')
                ->lockForUpdate()
                ->first();

            if (
                !$lockedChallenge ||
                $lockedChallenge->expires_at->isPast() ||
                $lockedChallenge->attempts >= 5
            ) {
                return null;
            }

            $lockedChallenge->update([
                'verified_at' => now(),
            ]);

            return $lockedChallenge->user;
        });

        if (!$user) {
            $request->session()->forget([
                'pending_login_challenge',
                'pending_login_remember',
            ]);

            return redirect()->route('login')
                ->withErrors([
                    'login' => 'This OTP session is no longer valid. Please log in again.',
                ]);
        }

        $remember = (bool) $request->session()->get('pending_login_remember',false);

        Auth::login($user, $remember);

        $request->session()->regenerate();

        $request->session()->forget([
            'pending_login_challenge',
            'pending_login_remember',
        ]);

        if ($user->role === 'admin') {
            return redirect()->intended(
                route('admin.dashboard')
            );
        }

        return redirect()->intended(
            route('dashboard')
        );
    }
}
