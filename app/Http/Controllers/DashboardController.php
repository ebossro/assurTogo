<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Police;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les polices de l'utilisateur avec relations
        $polices = Police::where('user_id', $user->id)->with(['paiements', 'sinistres'])->latest()->get();
        
        // Statistiques
        $activePolicesCount = $polices->where('statut', 'actif')->count();
        $totalMonthlyPremium = $polices->where('statut', 'actif')->sum('primeMensuelle');
        
        // Prochaine échéance (la police qui expire le plus tôt parmi les actives)
        $nextRenewalPolicy = $polices->where('statut', 'actif')->sortBy('dateFin')->first();
        $daysUntilRenewal = $nextRenewalPolicy ? now()->diffInDays($nextRenewalPolicy->dateFin, false) : null;

        // Sinistres récents
        $recentClaims = $user->polices->flatMap->sinistres->sortByDesc('created_at')->take(3);

        return view('dashboard.index', compact(
            'user', 
            'polices', 
            'activePolicesCount', 
            'totalMonthlyPremium', 
            'nextRenewalPolicy',
            'daysUntilRenewal',
            'recentClaims'
        ));
    }
}
