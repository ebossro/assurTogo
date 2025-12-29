<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Police;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer la police de l'utilisateur avec relations
        $police = Police::where('user_id', $user->id)->with(['paiements', 'sinistres'])->first();
        
        // Statistiques
        $activePolicesCount = ($police && $police->statut === 'actif') ? 1 : 0;
        $totalMonthlyPremium = ($police && $police->statut === 'actif') ? $police->primeMensuelle : 0;
        
        // Prochaine échéance 
        $nextRenewalPolicy = ($police && $police->statut === 'actif') ? $police : null;
        $daysUntilRenewal = ceil($nextRenewalPolicy ? Carbon::now()->diffInDays(Carbon::parse($police->dateFin), false) : null);

        // Sinistres récents
        $recentClaims = $police ? $police->sinistres->sortByDesc('created_at')->take(3) : collect();

        return view('dashboard.index', compact(
            'user', 
            'police', 
            'activePolicesCount', 
            'totalMonthlyPremium', 
            'nextRenewalPolicy',
            'daysUntilRenewal',
            'recentClaims'
        ));
    }
}
