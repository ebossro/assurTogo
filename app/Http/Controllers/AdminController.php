<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Police;
use App\Models\Sinistre;
use App\Models\Paiement;
use App\Models\Role;
use App\Mail\PaymentLinkEmail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        // Statistiques globales pour la compagnie
        $stats = [
            // compter le nombres d'assure actifs de typeRole 'assure'
            'users_count' => User::where('role_id', Role::where('typeRole', 'assure')->value('id'))->count(),
            'polices_actives' => Police::where('etat', 'Actif')->count(),
            'sinistres_en_cours' => Sinistre::where('statut', 'en_cours')->count(),
            'revenu_mensuel' => Paiement::whereMonth('date_paiement', now()->month)->sum('montant'),
        ];

        $recentPolices = Police::with('user')->latest()->take(5)->get();
        $recentSinistres = Sinistre::with(['police.user'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPolices', 'recentSinistres'));
    }

    public function polices(Request $request)
    {
        $query = Police::with('user')->withCount('beneficiaires');

        // Simple Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('numeroPolice', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('etat') && $request->etat != '') {
            $query->where('etat', $request->etat);
        }

        $polices = $query->latest()->paginate(10);
        
        return view('admin.polices.index', compact('polices'));
    }

    public function showPolice(Police $police)
    {
        $police->load(['user', 'beneficiaires']);
        return view('admin.polices.show', compact('police'));
    }

    public function validatePolice(Police $police)
    {
        $police->update(['statut' => 'attente_paiement']);

        // Send Email
        Mail::to($police->user)->send(new PaymentLinkEmail($police));

        return back()->with('success', 'La demande a été validée et le lien de paiement a été envoyé à l\'assuré.');
    }

    public function rejectPolice(Police $police)
    {
        $police->update(['statut' => 'rejete']);

        return back()->with('success', 'La demande a été rejetée.');
    }

    public function sinistres(Request $request)
    {
        // Stats
        $pendingCount = Sinistre::where('statut', 'en_cours')->count();
        $approvedCount = Sinistre::where('statut', 'valide')
            ->whereMonth('updated_at', now()->month)
            ->count();
        $totalAmount = Sinistre::where('statut', 'valide')
            ->whereMonth('updated_at', now()->month)
            ->sum('montant_estime');
            
        $query = Sinistre::with(['police', 'police.user']);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('police', function ($q) use ($search) {
                      $q->where('numeroPolice', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('prenom', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        $sinistres = $query->latest()->paginate(10);

        return view('admin.sinistres.index', compact('sinistres', 'pendingCount', 'approvedCount', 'totalAmount'));
    }
    public function showSinistre(Sinistre $sinistre)
    {
        $sinistre->load(['police', 'police.user', 'documents']);
        return view('admin.sinistres.show', compact('sinistre'));
    }
    public function approveSinistre(Sinistre $sinistre)
    {
        $sinistre->update(['statut' => 'valide']);
        return back()->with('success', 'Le sinistre a été approuvé.');
    }
    public function rejectSinistre(Sinistre $sinistre)
    {
        $sinistre->update(['statut' => 'rejete']);
        return back()->with('success', 'Le sinistre a été rejeté.');
    }
}
