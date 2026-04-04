<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute moet worden geaccepteerd.',
    'accepted_if' => ':attribute moet worden geaccepteerd wanneer :other :value is.',
    'active_url' => ':attribute is geen geldige URL.',
    'after' => ':attribute moet een datum na :date zijn.',
    'after_or_equal' => ':attribute moet een datum op of na :date zijn.',
    'alpha' => ':attribute mag alleen letters bevatten.',
    'alpha_dash' => ':attribute mag alleen letters, cijfers, streepjes en underscores bevatten.',
    'alpha_num' => ':attribute mag alleen letters en cijfers bevatten.',
    'any_of' => ':attribute moet een van de volgende waarden bevatten: :values.',
    'array' => ':attribute moet een array zijn.',
    'ascii' => ':attribute mag alleen 1-byte alfanumerieke tekens en symbolen bevatten.',
    'before' => ':attribute moet een datum voor :date zijn.',
    'before_or_equal' => ':attribute moet een datum op of voor :date zijn.',
    'between' => [
        'array' => ':attribute moet tussen :min en :max items bevatten.',
        'file' => ':attribute moet tussen :min en :max kilobytes zijn.',
        'numeric' => ':attribute moet tussen :min en :max zijn.',
        'string' => ':attribute moet tussen :min en :max karakters bevatten.',
    ],
    'boolean' => ':attribute moet ja of nee zijn.',
    'can' => ':attribute bevat een niet-toegestane waarde.',
    'confirmed' => 'De bevestiging van :attribute komt niet overeen.',
    'contains' => 'Een vereiste waarde ontbreekt in :attribute.',
    'current_password' => 'Het wachtwoord is onjuist.',
    'date' => ':attribute is geen geldige datum.',
    'date_equals' => ':attribute moet een datum gelijk aan :date zijn.',
    'date_format' => ':attribute voldoet niet aan het formaat :format.',
    'decimal' => ':attribute moet :decimal decimalen bevatten.',
    'declined' => ':attribute moet worden afgewezen.',
    'declined_if' => ':attribute moet worden afgewezen wanneer :other :value is.',
    'different' => ':attribute en :other moeten verschillend zijn.',
    'digits' => ':attribute moet :digits cijfers bevatten.',
    'digits_between' => ':attribute moet tussen :min en :max cijfers bevatten.',
    'dimensions' => ':attribute heeft ongeldige afmetingen.',
    'distinct' => ':attribute heeft een dubbele waarde.',
    'doesnt_contain' => ':attribute mag geen van de volgende waarden bevatten: :values.',
    'doesnt_end_with' => ':attribute mag niet eindigen op een van de volgende waarden: :values.',
    'doesnt_start_with' => ':attribute mag niet beginnen met een van de volgende waarden: :values.',
    'email' => ':attribute moet een geldig e-mailadres zijn.',
    'encoding' => ':attribute moet een geldige codering zijn.',
    'ends_with' => ':attribute moet eindigen met een van de volgende waarden: :values.',
    'enum' => 'De geselecteerde :attribute is ongeldig.',
    'exists' => 'De geselecteerde :attribute is ongeldig.',
    'extensions' => ':attribute moet een van de volgende extensies hebben: :values.',
    'file' => ':attribute moet een bestand zijn.',
    'filled' => ':attribute moet een waarde hebben.',
    'gt' => [
        'array' => ':attribute moet meer dan :value items bevatten.',
        'file' => ':attribute moet groter zijn dan :value kilobytes.',
        'numeric' => ':attribute moet groter zijn dan :value.',
        'string' => ':attribute moet meer dan :value karakters bevatten.',
    ],
    'gte' => [
        'array' => ':attribute moet :value of meer items bevatten.',
        'file' => ':attribute moet groter of gelijk zijn aan :value kilobytes.',
        'numeric' => ':attribute moet groter of gelijk zijn aan :value.',
        'string' => ':attribute moet minimaal :value karakters bevatten.',
    ],
    'hex_color' => ':attribute moet een geldige hexadecimale kleur zijn.',
    'image' => ':attribute moet een afbeelding zijn.',
    'in' => 'De geselecteerde :attribute is ongeldig.',
    'in_array' => ':attribute komt niet voor in :other.',
    'in_array_keys' => ':attribute moet een van de volgende sleutels bevatten: :values.',
    'integer' => ':attribute moet een geheel getal zijn.',
    'ip' => ':attribute moet een geldig IP-adres zijn.',
    'ipv4' => ':attribute moet een geldig IPv4-adres zijn.',
    'ipv6' => ':attribute moet een geldig IPv6-adres zijn.',
    'json' => ':attribute moet een geldige JSON-string zijn.',
    'list' => ':attribute moet een lijst zijn.',
    'lowercase' => ':attribute moet in kleine letters zijn.',
    'lt' => [
        'array' => ':attribute moet minder dan :value items bevatten.',
        'file' => ':attribute moet kleiner zijn dan :value kilobytes.',
        'numeric' => ':attribute moet kleiner zijn dan :value.',
        'string' => ':attribute moet minder dan :value karakters bevatten.',
    ],
    'lte' => [
        'array' => ':attribute mag niet meer dan :value items bevatten.',
        'file' => ':attribute moet kleiner of gelijk zijn aan :value kilobytes.',
        'numeric' => ':attribute moet kleiner of gelijk zijn aan :value.',
        'string' => ':attribute mag maximaal :value karakters bevatten.',
    ],
    'mac_address' => ':attribute moet een geldig MAC-adres zijn.',
    'max' => [
        'array' => ':attribute mag niet meer dan :max items bevatten.',
        'file' => ':attribute mag niet groter zijn dan :max kilobytes.',
        'numeric' => ':attribute mag niet groter zijn dan :max.',
        'string' => ':attribute mag niet meer dan :max karakters bevatten.',
    ],
    'max_digits' => ':attribute mag niet meer dan :max cijfers bevatten.',
    'mimes' => ':attribute moet een bestand zijn van het type: :values.',
    'mimetypes' => ':attribute moet een bestand zijn van het type: :values.',
    'min' => [
        'array' => ':attribute moet minimaal :min items bevatten.',
        'file' => ':attribute moet minimaal :min kilobytes zijn.',
        'numeric' => ':attribute moet minimaal :min zijn.',
        'string' => ':attribute moet minimaal :min karakters bevatten.',
    ],
    'min_digits' => ':attribute moet minimaal :min cijfers bevatten.',
    'missing' => ':attribute moet ontbreken.',
    'missing_if' => ':attribute moet ontbreken wanneer :other :value is.',
    'missing_unless' => ':attribute moet ontbreken tenzij :other :value is.',
    'missing_with' => ':attribute moet ontbreken wanneer :values aanwezig is.',
    'missing_with_all' => ':attribute moet ontbreken wanneer :values aanwezig zijn.',
    'multiple_of' => ':attribute moet een meervoud van :value zijn.',
    'not_in' => 'De geselecteerde :attribute is ongeldig.',
    'not_regex' => 'Het formaat van :attribute is ongeldig.',
    'numeric' => ':attribute moet een getal zijn.',
    'password' => [
        'letters' => ':attribute moet minimaal één letter bevatten.',
        'mixed' => ':attribute moet minimaal één hoofdletter en één kleine letter bevatten.',
        'numbers' => ':attribute moet minimaal één cijfer bevatten.',
        'symbols' => ':attribute moet minimaal één symbool bevatten.',
        'uncompromised' => 'De opgegeven :attribute is gecompromitteerd in een datalek. Kies een andere :attribute.',
    ],
    'present' => ':attribute moet aanwezig zijn.',
    'present_if' => ':attribute moet aanwezig zijn wanneer :other :value is.',
    'present_unless' => ':attribute moet aanwezig zijn tenzij :other :value is.',
    'present_with' => ':attribute moet aanwezig zijn wanneer :values aanwezig is.',
    'present_with_all' => ':attribute moet aanwezig zijn wanneer :values aanwezig zijn.',
    'prohibited' => ':attribute is verboden.',
    'prohibited_if' => ':attribute is verboden wanneer :other :value is.',
    'prohibited_if_accepted' => ':attribute is verboden wanneer :other is geaccepteerd.',
    'prohibited_if_declined' => ':attribute is verboden wanneer :other is geweigerd.',
    'prohibited_unless' => ':attribute is verboden tenzij :other voorkomt in :values.',
    'prohibits' => ':attribute verbiedt de aanwezigheid van :other.',
    'regex' => 'Het formaat van :attribute is ongeldig.',
    'required' => ':attribute is verplicht.',
    'required_array_keys' => ':attribute moet waarden bevatten voor: :values.',
    'required_if' => ':attribute is verplicht wanneer :other :value is.',
    'required_if_accepted' => ':attribute is verplicht wanneer :other is geaccepteerd.',
    'required_if_declined' => ':attribute is verplicht wanneer :other is geweigerd.',
    'required_unless' => ':attribute is verplicht tenzij :other voorkomt in :values.',
    'required_with' => ':attribute is verplicht wanneer :values aanwezig is.',
    'required_with_all' => ':attribute is verplicht wanneer :values aanwezig zijn.',
    'required_without' => ':attribute is verplicht wanneer :values niet aanwezig is.',
    'required_without_all' => ':attribute is verplicht wanneer geen van :values aanwezig zijn.',
    'same' => ':attribute en :other moeten overeenkomen.',
    'size' => [
        'array' => ':attribute moet :size items bevatten.',
        'file' => ':attribute moet :size kilobytes zijn.',
        'numeric' => ':attribute moet :size zijn.',
        'string' => ':attribute moet :size karakters bevatten.',
    ],
    'starts_with' => ':attribute moet beginnen met een van de volgende waarden: :values.',
    'string' => ':attribute moet een string zijn.',
    'timezone' => ':attribute moet een geldige tijdzone zijn.',
    'unique' => ':attribute is al in gebruik.',
    'uploaded' => 'Het uploaden van :attribute is mislukt.',
    'uppercase' => ':attribute moet in hoofdletters zijn.',
    'url' => ':attribute moet een geldige URL zijn.',
    'ulid' => ':attribute moet een geldige ULID zijn.',
    'uuid' => ':attribute moet een geldige UUID zijn.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'eigen-bericht',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
