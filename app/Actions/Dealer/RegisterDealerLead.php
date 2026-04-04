<?php

namespace App\Actions\Dealer;

use App\Models\City;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Utils\PhoneUtils;
use Illuminate\Support\Facades\DB;

class RegisterDealerLead
{
    /**
     * Register a professional lead with both company and user accounts.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function handle(array $payload): array
    {
        return DB::transaction(function () use ($payload): array {
            $city = City::query()
                ->select(['id', 'country_id'])
                ->with(['country:id,iso2'])
                ->findOrFail((int) $payload['city_id']);

            $subscriptionPlan = SubscriptionPlan::query()
                ->select(['id', 'key', 'price', 'listing_limit'])
                ->findOrFail((int) $payload['subscription_plan_id']);

            $company = Company::query()->create([
                'name' => (string) $payload['company_name'],
                'email' => (string) $payload['company_email'],
                'tva_number' => $payload['tva_number'] ?: null,
                'phone' => PhoneUtils::format($payload['company_phone'] ?: null, $city->country->iso2),
                'address' => (string) $payload['company_address'],
                'country_id' => $city->country_id,
                'city_id' => $city->id,
            ]);

            $user = User::query()->create([
                'company_id' => $company->id,
                'first_name' => (string) $payload['first_name'],
                'last_name' => (string) $payload['last_name'],
                'email' => (string) $payload['email'],
                'password' => (string) $payload['password'],
            ]);

            $user->assignRole('dealer');

            return [
                'company' => $company,
                'user' => $user,
                'subscription_plan' => $subscriptionPlan,
                'message' => $payload['message'] ?? null,
            ];
        });
    }
}
