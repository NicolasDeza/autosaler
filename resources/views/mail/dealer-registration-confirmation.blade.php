@php
    $logoPath = null;

    $publicLogoPath = public_path('images/autosaler-logo.png');
    if (file_exists($publicLogoPath)) {
        $logoPath = $publicLogoPath;
    }

    $resourceLogoPath = resource_path('assets/images/Logo-AutoSaler.png');
    if ($logoPath === null && file_exists($resourceLogoPath)) {
        $logoPath = $resourceLogoPath;
    }

    $logoSrc = isset($message) && $logoPath !== null ? $message->embed($logoPath) : null;
    $fullName = trim(sprintf('%s %s', (string) $user->first_name, (string) $user->last_name));
    $planTranslationKey = 'dealerRegistrationMail.plan_names.' . $subscriptionPlan->key;
    $translatedPlanName = __($planTranslationKey);
    $planName = str_starts_with($translatedPlanName, 'dealerRegistrationMail.plan_names.')
        ? ucfirst((string) $subscriptionPlan->key)
        : $translatedPlanName;
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('dealerRegistrationMail.subject') }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="background:#1c2631;padding:14px 24px 12px 24px;border-bottom:3px solid #f43f5e;" align="center">
                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto;">
                            <tr>
                                @if ($logoSrc)
                                    <td style="padding-right:8px;vertical-align:middle;">
                                        <img src="{{ $logoSrc }}" alt="" style="display:block;height:26px;width:auto;">
                                    </td>
                                @endif
                                <td style="vertical-align:middle;">
                                    <p style="margin:0;font-size:22px;font-weight:700;line-height:1;text-transform:uppercase;letter-spacing:0.3px;">
                                        <span style="color:#f43f5e;">Auto</span><span style="color:#ffffff;">Saler</span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px;">
                        <p style="margin:0 0 8px 0;font-size:30px;font-weight:700;line-height:1.2;">
                            {{ __('dealerRegistrationMail.title') }}
                        </p>
                        <p style="margin:0 0 14px 0;font-size:15px;line-height:1.6;color:#4b5563;">
                            {{ $fullName !== '' ? __('dealerRegistrationMail.greeting_named', ['name' => $fullName]) : __('dealerRegistrationMail.greeting_generic') }}
                        </p>
                        <p style="margin:0 0 14px 0;font-size:14px;line-height:1.7;color:#374151;">
                            {{ __('dealerRegistrationMail.intro', ['company' => $company->name]) }}
                        </p>
                        <p style="margin:0 0 18px 0;font-size:14px;line-height:1.7;color:#374151;">
                            {{ __('dealerRegistrationMail.review_timeline') }}
                        </p>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:10px;margin-bottom:18px;">
                            <tr>
                                <td style="padding:12px 16px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-size:14px;font-weight:700;">
                                    {{ __('dealerRegistrationMail.next_steps_title') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;font-size:14px;line-height:1.7;color:#374151;">
                                    • {{ __('dealerRegistrationMail.step_1') }}<br>
                                    • {{ __('dealerRegistrationMail.step_2') }}<br>
                                    • {{ __('dealerRegistrationMail.step_3') }}
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 18px 0;font-size:14px;line-height:1.7;color:#374151;">
                            {{ __('dealerRegistrationMail.plan_summary', ['plan' => $planName, 'listings' => (int) $subscriptionPlan->listing_limit]) }}
                        </p>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:4px;">
                            <tr>
                                <td align="center">
                                    <a
                                        href="{{ route('vehicles.index') }}"
                                        style="display:inline-block;padding:11px 18px;background:#1c2631;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:700;"
                                    >
                                        {{ __('dealerRegistrationMail.cta') }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background:#1c2631;border-top:3px solid #f43f5e;padding:14px 24px;text-align:center;">
                        <p style="margin:0;font-size:12px;line-height:1.5;color:#cbd5e1;">
                            {{ __('dealerRegistrationMail.copyright', ['year' => now()->year]) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
