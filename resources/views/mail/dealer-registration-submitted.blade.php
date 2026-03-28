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

    $company = $registration['company'];
    $user = $registration['user'];
    $subscriptionPlan = $registration['subscription_plan'] ?? null;
    $zipCodeLabel = $company->city?->zip_code ?? '-';
    $cityLabel = $company->city?->code ?? '-';
    $countryLabel = $company->country?->code ?? $company->city?->country?->code ?? '-';
    $planListings = (int) ($subscriptionPlan?->listing_limit ?? 0);
    $planName = match ($subscriptionPlan?->key) {
        'starter' => 'Starter',
        'pro' => 'Pro',
        'business' => 'Business',
        'premium' => 'Premium',
        default => 'Plan',
    };
@endphp

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvelle inscription professionnelle</title>
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
                                        <img
                                            src="{{ $logoSrc }}"
                                            alt=""
                                            style="display:block;height:26px;width:auto;"
                                        >
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
                        <p style="margin:0 0 6px 0;font-size:32px;font-weight:700;line-height:1.2;">Nouvelle inscription professionnelle</p>
                        <p style="margin:0 0 20px 0;font-size:15px;line-height:1.55;color:#4b5563;">Un nouveau professionnel vient de soumettre une demande d'inscription.</p>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:10px;margin-bottom:16px;">
                            <tr>
                                <td style="padding:14px 16px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-weight:700;">Entreprise</td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;font-size:14px;line-height:1.7;">
                                    <strong>Nom :</strong> {{ $company->name }}<br>
                                    <strong>Email :</strong> {{ $company->email }}<br>
                                    <strong>Téléphone :</strong> {{ $company->phone ?? '-' }}<br>
                                    <strong>Code postal :</strong> {{ $zipCodeLabel }}<br>
                                    <strong>Ville :</strong> {{ $cityLabel }}<br>
                                    <strong>Pays :</strong> {{ $countryLabel }}
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:10px;margin-bottom:16px;">
                            <tr>
                                <td style="padding:14px 16px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-weight:700;">Contact utilisateur</td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;font-size:14px;line-height:1.7;">
                                    <strong>Prénom :</strong> {{ $user->first_name }}<br>
                                    <strong>Nom :</strong> {{ $user->last_name }}<br>
                                    <strong>Email :</strong> {{ $user->email }}
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:10px;margin-bottom:16px;">
                            <tr>
                                <td style="padding:14px 16px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-weight:700;">Abonnement souhaité</td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;font-size:14px;line-height:1.7;">
                                    <strong>Plan :</strong> {{ $planName }} - {{ $planListings }} annonces / mois
                                </td>
                            </tr>
                        </table>

                        @if (!empty($registration['message']))
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:10px;margin-bottom:20px;">
                                <tr>
                                    <td style="padding:14px 16px;background:#f9fafb;border-bottom:1px solid #e5e7eb;font-weight:700;">Message</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 16px;font-size:14px;line-height:1.7;color:#374151;">{{ $registration['message'] }}</td>
                                </tr>
                            </table>
                        @endif

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:4px;">
                            <tr>
                                <td align="center">
                                    <a
                                        href="{{ route('admin.dashboard') }}"
                                        style="display:inline-block;padding:11px 18px;background:#1c2631;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:700;"
                                    >
                                        Accéder à l'administration
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="background:#1c2631;border-top:3px solid #f43f5e;padding:14px 24px;text-align:center;">
                        <p style="margin:0;font-size:12px;line-height:1.5;color:#cbd5e1;">
                            © 2026 AutoSaler — Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
