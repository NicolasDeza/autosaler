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
use Illuminate\Support\Facades\DB;
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
            'catalog' => $this->catalogTab($request),
            'users' => $this->usersTab($request),
            default => $this->statsTab($request),
        };
    }

    protected function statsTab(Request $request): Response
    {
        $last7Days = collect(range(6, 0))->map(function ($days) {
            return now()->subDays($days)->format('Y-m-d');
        });

        $adsCount = VehicleAd::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        $usersCount = User::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        $adsEvolution = $last7Days->map(fn ($date) => [
            'date' => $date,
            'count' => (int) $adsCount->get($date, 0),
        ]);

        $usersEvolution = $last7Days->map(fn ($date) => [
            'date' => $date,
            'count' => (int) $usersCount->get($date, 0),
        ]);

        $stats = [
            'vehicles' => [
                'total' => VehicleAd::count(),
                'active' => VehicleAd::where('status', 'active')->count(),
                'draft' => VehicleAd::where('status', 'draft')->count(),
                'pending' => VehicleAd::where('status', 'pending')->count(),
            ],
            'users' => [
                'total' => User::count(),
                'total_non_admin' => User::whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                })->count(),
                'users' => User::role('user')->count(),
                'dealers' => User::role('dealer')->count(),
                'admins' => User::role('admin')->count(),
            ],
            'evolution' => [
                'ads' => $adsEvolution,
                'users' => $usersEvolution,
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

    protected function catalogTab(Request $request): Response
    {
        $brands = VehicleBrand::withCount('models')
            ->orderBy('name')
            ->get();

        return Inertia::render('AdminDashboard/Index', [
            'tab' => 'catalog',
            'brands' => $brands,
            'models' => $request->filled('brand_id')
                ? VehicleModel::where('brand_id', $request->brand_id)->orderBy('name')->get()
                : [],
            'filters' => $request->only(['brand_id']),
        ]);
    }

    protected function usersTab(Request $request): Response
    {
        $query = User::with(['roles', 'company', 'subscriptions.plan']);

        $showAdmins = $request->has('show_admins') && ($request->show_admins === 'true' || $request->show_admins === '1' || $request->show_admins === true);

        if ($showAdmins) {
            $query->role('admin');
        } else {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            });

            if ($request->filled('role')) {
                $query->role($request->role);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting logic
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        match ($sort) {
            'name' => $query->orderBy('last_name', $direction)->orderBy('first_name', $direction),
            'role' => $query->orderBy(
                Role::select('name')
                    ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', User::class)
                    ->limit(1),
                $direction
            ),
            'subscription' => $query->orderBy(
                SubscriptionPlan::select('key')
                    ->join('subscriptions', 'subscription_plans.id', '=', 'subscriptions.subscription_plan_id')
                    ->whereColumn('subscriptions.user_id', 'users.id')
                    ->where('subscriptions.status', 'active')
                    ->limit(1),
                $direction
            ),
            default => $query->orderBy($sort, $direction),
        };

        $users = $query->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('AdminDashboard/Index', [
            'tab' => 'users',
            'users' => $users,
            'filters' => array_merge($request->only(['search', 'role', 'per_page', 'sort', 'direction']), [
                'show_admins' => $showAdmins,
            ]),
            'roles' => Role::where('name', '!=', 'admin')->get(['id', 'name']),
            'subscription_plans' => SubscriptionPlan::all(),
        ]);
    }

    public function updateSubscription(Request $request, User $user)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        DB::transaction(function () use ($user, $plan) {
            $user->subscriptions()->where('status', 'active')->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            $user->subscriptions()->create([
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days ?: 30),
                'auto_renew' => false,
            ]);
        });

        return to_route('admin.dashboard', ['tab' => 'users'])->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function cancelSubscription(User $user)
    {
        $user->subscriptions()->where('status', 'active')->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return to_route('admin.dashboard', ['tab' => 'users'])->with('success', 'Abonnement annulé avec succès.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => ['required', Rule::enum(UserStatus::class)],
        ]);

        $user->update(['status' => $request->status]);

        return to_route('admin.dashboard', ['tab' => 'users'])->with('success', 'Statut de l\'utilisateur mis à jour avec succès.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return to_route('admin.dashboard', ['tab' => 'users'])->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return to_route('admin.dashboard', ['tab' => 'users'])->with('success', 'Utilisateur supprimé avec succès.');
    }
}
