<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class TwoFactorService
{
    private string $apiKey;
    private string $template;

    public function __construct()
    {
        $this->apiKey = config('services.twofactor.api_key');
        $this->template = config('services.twofactor.otp_template');

        if (empty($this->apiKey)) {
            throw new RuntimeException(
                '2Factor API key is not configured.'
            );
        }

        if (empty($this->template)) {
            throw new RuntimeException(
                '2Factor OTP template is not configured.'
            );
        }
    }

    public function sendOtp(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        if (!preg_match('/^91[6-9]\d{9}$/', $phone)) {
            throw new RuntimeException(
                'The registered mobile number is invalid.'
            );
        }

        $url = sprintf(
            'https://2factor.in/API/V1/%s/SMS/%s/AUTOGEN/%s',
            $this->apiKey,
            $phone,
            rawurlencode($this->template)
        );

        $response = Http::acceptJson()
            ->timeout(15)
            ->get($url);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Unable to communicate with 2Factor.in.'
            );
        }

        $data = $response->json();

        if (
            !is_array($data) ||
            ($data['Status'] ?? '') !== 'Success' ||
            empty($data['Details'])
        ) {
            throw new RuntimeException(
                'Unable to send SMS OTP. Check your 2Factor account and template configuration.'
            );
        }

        return (string) $data['Details'];
    }

    public function verifyOtp(
        string $sessionId,
        string $otp
    ): bool {
        if (!preg_match('/^\d{6}$/', $otp)) {
            return false;
        }

        $url = sprintf(
            'https://2factor.in/API/V1/%s/SMS/VERIFY/%s/%s',
            $this->apiKey,
            rawurlencode($sessionId),
            rawurlencode($otp)
        );

        $response = Http::acceptJson()
            ->timeout(15)
            ->get($url);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Unable to communicate with 2Factor.in.'
            );
        }

        $data = $response->json();

        return is_array($data)
            && ($data['Status'] ?? '') === 'Success'
            && ($data['Details'] ?? '') === 'OTP Matched';
    }
}
