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
                'rear_airbag',
                'front_airbag',
                'driver_airbag',
                'passenger_airbag',
                'side_airbags',
            ],

            'parking_assistance' => [
                'camera_360',
                'parking_camera',
                'rear_parking_sensors',
                'front_parking_sensors',
                'automatic_parking_assist',
            ],

            'other' => [
                'tow_hitch',
                'awning',
                'battery_certificate',
                'bidirectional_charging',
                'e10_compatible',
                'right_hand_drive',
                'wind_deflector_convertible',
                'deflector',
                'ambient_lighting',
                'disability_equipment',
                'biodiesel_compatible',
                'electronic_parking_brake',
                'steel_wheels',
                'alloy_wheels',
                'breakdown_kit',
                'smoker_kit',
                'winter_package',
                'sport_package',
                'summer_tires',
                'winter_tires',
                'all_season_tires',
                'heat_pump',
                'luggage_rack',
                'catalytic_converter',
                'range_extender',
                'electric_rear_seat_adjustment',
                'auto_dimming_rearview_mirror',
                'spare_wheel',
                'emergency_wheel',
                'trunk_separator',
                'heated_rear_seats',
                'sport_suspension',
                'headlight_washer_system',
                'ski_hatch',
                'tuning',
                'tinted_windows',
            ],

            'air_conditioning' => [
                'air_conditioning',
                'automatic_air_conditioning',
                'automatic_air_conditioning_3_zones',
                'automatic_air_conditioning_4_zones',
                'automatic_air_conditioning_dual_zone',
            ],

            'comfort' => [
                'armrest',
                'head_up_display',
                'auxiliary_heating',
                'light_sensor',
                'rain_sensor',
                'power_steering',
                'power_tailgate',
                'paddle_shifters',
                'heated_windshield',
                'sliding_door',
                'right_sliding_door',
                'left_sliding_door',
                'power_side_mirrors',
                'start_stop_system',
                'air_suspension',
                'sunroof',
                'panoramic_roof',
                'power_windows',
                'heated_steering_wheel',
                'leather_steering_wheel',
            ],

            'infotainment' => [
                'android_auto',
                'apple_carplay',
                'wireless_phone_charger',
                'voice_control',
                'fully_digital_instrument_cluster',
                'touchscreen',
                'tv_function',
                'wifi_hotspot',
                'digital_radio',
                'sound_system',
                'integrated_audio_streaming',
            ],

            'media' => [
                'bluetooth',
                'cd_player',
                'hands_free_kit',
                'mp3',
                'trip_computer',
                'radio',
                'navigation_system',
                'multifunction_steering_wheel',
            ],

            'lighting' => [
                'fog_lights',
                'glare_free_high_beam',
                'led_daytime_running_lights',
                'led_headlights',
                'xenon_headlights',
                'bi_xenon_headlights',
                'daytime_running_lights',
                'directional_headlights',
                'full_led_headlights',
                'laser_headlights',
            ],

            'cruise_control' => [
                'adaptive_cruise_control',
                'cruise_control',
            ],

            'safety' => [
                'abs',
                'alarm_system',
                'immobilizer',
                'traction_control',
                'esp',
                'isofix',
                'emergency_call_system',
                'tire_pressure_monitoring_system',
                'driver_drowsiness_detection',
            ],

            'seats' => [
                'lumbar_adjustable_seat',
                'foldable_passenger_seat',
                'split_folding_rear_seats',
                'heated_seats',
                'power_seats',
                'massage_seats',
                'sport_seats',
                'ventilated_seats',
            ],

            'driver_assistance' => [
                'lane_departure_warning',
                'emergency_brake_assist',
                'hill_start_assist',
                'night_vision_assist',
                'high_beam_assist',
                'blind_spot_warning',
                'traffic_sign_recognition',
                'speed_limiter',
                'distance_warning_system',
            ],

            'locking' => [
                'central_locking',
                'remote_central_locking',
                'keyless_entry',
            ],

        ];

        $categoryOrder = 1;

        foreach ($categories as $categoryCode => $features) {

            $category = FeatureCategory::firstOrCreate(
                ['code' => $categoryCode],
                [
                    'sort_order' => $categoryOrder++,
                    'is_active' => true,
                ]
            );

            $featureOrder = 1;

            foreach ($features as $featureCode) {

                Feature::firstOrCreate(
                    ['code' => $featureCode],
                    [
                        'feature_category_id' => $category->id,
                        'sort_order' => $featureOrder++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
