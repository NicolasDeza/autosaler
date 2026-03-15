<x-mail::message>
# Nouvelle demande de contact

Vous avez recu une nouvelle demande depuis la fiche vehicule.

**Annonce :** {{ $vehicleAd->brand?->name }} {{ $vehicleAd->model?->name }} (ID: {{ $vehicleAd->id }})

**Nom :** {{ trim(($contact['first_name'] ?? '').' '.($contact['last_name'] ?? '')) ?: '-' }}

**Email :** {{ $contact['email'] }}

@if(!empty($contact['phone']))
**Telephone :** {{ $contact['phone'] }}
@endif

**Message :**

{{ $contact['message'] }}

<x-mail::button :url="route('vehicles.show', $vehicleAd)">
Voir l'annonce
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
