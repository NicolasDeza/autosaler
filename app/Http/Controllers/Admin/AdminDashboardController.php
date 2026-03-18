<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\VehicleAd;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $tab = $request->query('tab', 'stats');

        return match ($tab) {
            'vehicles' => $this->vehiclesTab($request),
            'users' => $this->usersTab($request),
            default => $this->statsTab($request),
        };
    }

    protected function statsTab(Request $request): Response
    {
        $stats = [
            'vehicles' => [
                'total' => VehicleAd::count(),
                'active' => VehicleAd::where('status', 'active')->count(),
                'draft' => VehicleAd::where('status', 'draft')->count(),
                'pending' => VehicleAd::where('status', 'pending')->count(),
            ],
            'users' => [
                'total' => User::count(),
                'users' => User::role('user')->count(),
                'dealers' => User::role('dealer')->count(),
                'admins' => User::role('admin')->count(),
            ],
            'companies' => [
                'total' => Company::count(),
            ],
            'recent_activity' => [
                'new_ads_week' => VehicleAd::where('created_at', '>=', now()->subWeek())->count(),
                'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            ],
        ];

        return Inertia::render('AdminDashboard/Index', [
            'tab' => 'stats',
            'stats' => $stats,
        ]);
    }

    protected function vehiclesTab(Request $request): Response
    {
        $query = VehicleAd::with(['brand', 'model', 'vehicleVersion', 'stat', 'user.company']);

        if ($request->filled('company_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ads = $query->search($request->search)
            ->sort($request->sort ?? 'latest')
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('AdminDashboard/Index', [
            'tab' => 'vehicles',
            'ads' => $ads,
            'filters' => $request->only(['search', 'sort', 'per_page', 'brand_id', 'model_id', 'status', 'company_id']),
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'brands' => VehicleBrand::orderBy('name')->get(['id', 'name']),
            'models' => $request->filled('brand_id')
                ? VehicleModel::where('brand_id', $request->brand_id)->orderBy('name')->get(['id', 'name', 'brand_id'])
                : [],
        ]);
    }

    protected function usersTab(Request $request): Response
    {
        $query = User::with(['roles', 'company', 'subscriptions.plan']);

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('AdminDashboard/Index', [
            'tab' => 'users',
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'per_page']),
            'roles' => Role::all(['id', 'name']),
            'subscription_plans' => SubscriptionPlan::all(),
        ]);
    }

    public function updateSubscription(Request $request, User $user)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        $user->subscriptions()->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $user->subscriptions()->create([
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
        ]);

        return back()->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function cancelSubscription(User $user)
    {
        $user->subscriptions()->where('status', 'active')->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Abonnement annulé avec succès.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => ['required', Rule::enum(UserStatus::class)],
        ]);

        $user->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'utilisateur mis à jour avec succès.');
    }
}
