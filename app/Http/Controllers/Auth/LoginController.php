<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginOtpChallenge;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(
        Request $request,
        TwoFactorService $twoFactor
    ) {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $credentials['login'];

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $authCredentials = [
            $field => $login,
            'password' => $credentials['password'],
        ];

        // Validate credentials without logging in.
        if (!Auth::validate($authCredentials)) {
            return back()
                ->withErrors([
                    'login' => 'The email/phone or password is incorrect.',
                ])
                ->onlyInput('login');
        }

        $user = User::where($field, $login)->first();

        if (!$user || empty($user->phone)) {
            return back()
                ->withErrors([
                    'login' => 'A registered mobile number is required for OTP verification.',
                ])
                ->onlyInput('login');
        }

        // Remove any previous pending challenge from this session.
        $previousChallengeId = $request->session()->get(
            'pending_login_challenge'
        );

        if ($previousChallengeId) {
            LoginOtpChallenge::where(
                'challenge_id',
                $previousChallengeId
            )->delete();
        }

        $request->session()->forget([
            'pending_login_challenge',
            'pending_login_remember',
        ]);

        try {
            // Send SMS OTP through 2Factor.in.
            $providerSessionId = $twoFactor->sendOtp(
                (string) $user->phone
            );

            $challenge = LoginOtpChallenge::create([
                'user_id' => $user->id,
                'challenge_id' => (string) Str::uuid(),
                'provider_session_id' => $providerSessionId,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(5),
                'last_sent_at' => now(),
            ]);

            // Store only the pending challenge, not an authenticated user.
            $request->session()->put([
                'pending_login_challenge' => $challenge->challenge_id,
                'pending_login_remember' => $request->boolean('remember'),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'login' => 'Unable to send OTP right now. Please try again.',
                ])
                ->onlyInput('login');
        }

        return redirect()->route('login.otp')
            ->with('status', 'An OTP has been sent to your registered mobile number.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
