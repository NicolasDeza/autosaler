<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\FeatureCategory;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {

        $categories = [

            'airbag' => [
                'Airbag arriere',
                'Airbag avant',
                'Airbag conducteur',
                'Airbag passager',
                'Airbags lateraux',
            ],

            'assistant_stationnement' => [
                '360deg camera',
                "Camera d'aide au stationnement",
                "Capteurs d'aide au stationnement arriere",
                "Capteurs d'aide au stationnement avant",
                "Systeme d'aide au stationnement automatique",
            ],

            'autres' => [
                'Attache remorque',
                'Auvent',
                'Certificat de batterie',
                'Charge bidirectionnelle',
                'Compatible E-10',
                'Conduite a droite',
                'Coupe vent (pour cabriolet)',
                'Deflecteur',
                "Eclairage d'ambiance",
                'Equipement handicape',
                'Fonctionne au biodiesel',
                'Frein de stationnement electronique',
                'Jantes acier',
                'Jantes alliage',
                'Kit de depannage',
                'Kit fumeur',
                'Pack hiver',
                'Pack Sport',
                'Pneus ete',
                'Pneus neige',
                'Pneus tout temps saisons',
                'Pompe a chaleur',
                'Porte-bagages',
                'Pot catalytique',
                "Prolongateur d'autonomie",
                "Reglage electrique du siege arriere",
                "Retroviseur interieur anti-eblouissement automatique",
                'Roue de secours',
                'Roue de urgence',
                'Separateur pour coffre',
                'Sieges arriere chauffant',
                'Suspension sport',
                'Systeme de nettoyage des phares',
                'Trappe a ski',
                'Tuning',
                'Vitres teintees',
            ],

            'climatisation' => [
                'Climatisation',
                'Climatisation automatique',
                'Climatisation automatique, 3 zones',
                'Climatisation automatique, 4 zones',
                'Climatisation automatique, bi-zone',
            ],

            'confort' => [
                'Accoudoir',
                'Affichage tete haute',
                'Chauffage auxiliaire',
                'Detecteur de lumiere',
                'Detecteur de pluie',
                'Direction assistee',
                'Hayon arriere electrique',
                'Palettes de changement de vitesses',
                'Pare-brise chauffant',
                'Porte coulissante',
                'Porte coulissante droite',
                'Porte coulissante gauche',
                'Retroviseurs lateraux electriques',
                'Start/Stop automatique',
                'Suspension pneumatique',
                'Toit ouvrant',
                'Toit panoramique',
                'Vitres electriques',
                'Volant chauffant',
                'Volant en cuir',
            ],

            'divertissement' => [
                'Android Auto',
                'Apple CarPlay',
                'Chargeur smartphone a induction',
                'Commande vocale',
                'Ecran multifonction entierement numerique',
                'Ecran tactile',
                'Fonction TV',
                'Hotspot Wi-Fi',
                'Radio numerique',
                'Soundsystem',
                'Streaming audio integre',
            ],

            'divertissement_medias' => [
                'Bluetooth',
                'CD',
                'Dispositif mains libres',
                'MP3',
                'Ordinateur de bord',
                'Radio',
                'Systeme de navigation',
                'Volant multifonctions',
            ],

            'lumiere' => [
                'Feux anti-brouillard',
                'Feux de route non eblouissants',
                'LED phare de jour',
                'Phares au LED',
                'Phares au Xenon',
                'Phares bi-xenon',
                'Phares de jour',
                'Phares directionnels',
                'Phares Full LED',
                'Phares laser',
            ],

            'regulateur_vitesse' => [
                'Regulateur de distance',
                'Regulateur de vitesse',
            ],

            'securite' => [
                'ABS',
                'Alarme',
                'Anti-demarrage',
                'Anti-patinage',
                'ESP',
                'Isofix',
                "Systeme d'appel d'urgence",
                'Systeme de controle de la pression pneus',
                'Systeme de detection de la somnolence',
            ],

            'sieges' => [
                'Siege a reglage lombaire',
                'Siege passager repliable',
                'Sieges arrieres 1/3 - 2/3',
                'Sieges chauffants',
                'Sieges electriques',
                'Sieges massants',
                'Sieges sport',
                'Sieges ventiles',
            ],

            'assistances' => [
                'Alerte de franchissement involontaire de lignes',
                "Assistant au freinage d'urgence",
                'Assistant de demarrage en cote',
                'Assistant de vision nocturne',
                'Assistant feux de route',
                "Avertisseur d'angle mort",
                'Detection des panneaux routiers',
                'Limiteur de vitesse',
                "Systeme d'avertissement de distance",
            ],

            'verrouillage' => [
                'Verrouillage centralise',
                'Verrouillage centralise avec telecommande',
                'Verrouillage centralise sans cle',
            ],

        ];

        $categoryOrder = 1;

        foreach ($categories as $categoryCode => $features) {

            $category = FeatureCategory::firstOrCreate(
                ['code' => $categoryCode],
                [
                    'sort_order' => $categoryOrder++,
                    'is_active' => true
                ]
            );

            $featureOrder = 1;

            foreach ($features as $featureName) {

                Feature::firstOrCreate(
                    ['code' => $featureName],
                    [
                        'feature_category_id' => $category->id,
                        'sort_order' => $featureOrder++,
                        'is_active' => true
                    ]
                );
            }
        }
    }
}
