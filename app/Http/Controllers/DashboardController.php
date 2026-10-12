<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Loan;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isEncargado()) {
            $stats = [
                'categories' => Category::count(),
                'equipment' => Equipment::count(),
                'available' => Equipment::where('status', 'activo')
                    ->sum('available_quantity'),
                'pending_loans' => Loan::where('status', 'pendiente')
                    ->count(),
                'active_loans' => Loan::whereIn('status', [
                    'aprobado',
                    'entregado'
                ])->count(),
                'open_incidents' => Incident::whereIn('status', [
                    'abierta',
                    'en_proceso'
                ])->count(),
            ];

            $recentLoans = Loan::with('user')
                ->latest()
                ->take(5)
                ->get();

            $recentIncidents = Incident::with([
                'user',
                'equipment'
            ])
                ->latest()
                ->take(5)
                ->get();
        } else {
            $stats = [
                'equipment' => Equipment::where('status', 'activo')
                    ->count(),
                'available' => Equipment::where('status', 'activo')
                    ->sum('available_quantity'),
                'pending_loans' => $user->loans()
                    ->where('status', 'pendiente')
                    ->count(),
                'active_loans' => $user->loans()
                    ->whereIn('status', [
                        'aprobado',
                        'entregado'
                    ])->count(),
                'open_incidents' => $user->incidents()
                    ->whereIn('status', [
                        'abierta',
                        'en_proceso'
                    ])->count(),
            ];

            $recentLoans = $user->loans()
                ->latest()
                ->take(5)
                ->get();

            $recentIncidents = $user->incidents()
                ->with('equipment')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'user',
            'stats',
            'recentLoans',
            'recentIncidents'
        ));
    }
}
