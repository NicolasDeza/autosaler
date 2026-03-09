<x-mail::message>
# Nouvelle demande d'abonnement Pro

Un professionnel souhaite souscrire à un abonnement.

**Entreprise :** {{ $inquiry['company_name'] }}

**Contact :** {{ $inquiry['contact_name'] }}

**Email :** {{ $inquiry['email'] }}

**Téléphone :** {{ $inquiry['phone'] }}

**Plan demandé :** {{ $inquiry['plan_listings'] }} annonces/mois

@if(!empty($inquiry['message']))
**Message :**

{{ $inquiry['message'] }}
@endif

<x-mail::button :url="config('app.url')">
Accéder au site
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
