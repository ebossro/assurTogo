<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Police;
use App\Models\Sinistre;
use App\Models\Paiement;
use App\Models\Role;
use App\Mail\MeetingInvitationEmail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class AdminController extends Controller
{
    public function index()
    {
        // Statistiques globales pour la compagnie
        $stats = [

            'users_count' => User::where('role_id', Role::where('typeRole', 'assure')->value('id'))->count(),
            'polices_actives' => Police::where('statut', 'actif')->count(),
            'sinistres_en_attente' => Sinistre::whereIn('statut', ['en_attente', 'en_analyse'])->count(),
            'revenu_total' => Paiement::sum('montant'),
        ];

        // Revenu total de la prime mensuelle des polices actives
        $revenu_total = Police::where('statut', 'actif')->sum('primeMensuelle');

        $recentPolices = Police::with('user')->where('statut', 'en_attente')->latest()->take(10)->get();

        $recentSinistres = Sinistre::with(['police.user'])->where('statut', 'en_attente')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPolices', 'recentSinistres', 'revenu_total'));
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


        $polices = $query->latest()->paginate(10);

        return view('admin.polices.index', compact('polices'));
    }

    public function showPolice(Police $police)
    {
        $police->load(['user.role', 'beneficiaires']);

        // si l'utilisateur a déjà le rôle "assure", 
        $isRenewal = false;
        if ($police->user->role && $police->statut === 'en_attente') {
            $isRenewal = $police->user->role->typeRole === 'assure';
        }

        return view('admin.polices.show', compact('police', 'isRenewal'));
    }

    public function scheduleMeeting(Request $request, Police $police)
    {
        $request->validate([
            'date_rendez_vous' => 'required|date|after:now',
        ]);

        $police->update([
            'statut' => 'rendez_vous_planifie',
            'date_rendez_vous' => $request->date_rendez_vous,
        ]);

        // Send Email
        Mail::to($police->user)->send(new MeetingInvitationEmail($police));

        return back()->with('success', "Le rendez-vous a été planifié et l'invitation envoyée.");
    }

    public function validateSubscription(Police $police)
    {
        $dateDebut = now();
        $dateFin = $dateDebut->copy()->addMonth();

        $police->update([
            'statut' => 'actif',
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);

        // Update User Role to 'assure'
        $assureRole = Role::where('typeRole', 'assure')->first();
        if ($assureRole) {
            $police->user->update(['role_id' => $assureRole->id]);
        }

        // Send Welcome Email
        Mail::to($police->user)->send(new WelcomeEmail($police));

        return back()->with('success', 'La souscription a été validée, le rôle mis à jour et le compte est actif.');
    }

    public function rejectPolice(Police $police)
    {
        $police->update(['statut' => 'resilie']);
        return back()->with('success', 'La demande a été rejetée.');
    }

    public function suspendPolice(Police $police)
    {
        $police->update(['statut' => 'suspendu']);
        return back()->with('success', 'La police a été suspendue.');
    }

    public function reactivatePolice(Police $police)
    {
        $police->update(['statut' => 'actif']);
        return back()->with('success', 'La police a été réactivée.');
    }

    public function resiliatePolice(Police $police)
    {
        $police->update(['statut' => 'resilie']);
        return back()->with('success', 'La police a été résiliée définitivement.');
    }

    public function sinistres(Request $request)
    {
        // Stats for Tabs
        $countEnAttente = Sinistre::where('statut', 'en_attente')->count();
        $countEnAnalyse = Sinistre::where('statut', 'en_analyse')->count();
        $countApprouve = Sinistre::where('statut', 'approuve')->count();
        $countRejete = Sinistre::where('statut', 'rejete')->count();

        // KPI Stats
        $pendingCount = $countEnAttente + $countEnAnalyse;
        $approvedCount = Sinistre::where('statut', 'approuve')
            ->whereMonth('updated_at', now()->month)
            ->count();
        $totalAmount = Sinistre::where('statut', 'approuve')
            ->whereMonth('updated_at', now()->month)
            ->sum('montant_total');

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

        return view('admin.sinistres.index', compact(
            'sinistres',
            'pendingCount',
            'approvedCount',
            'totalAmount',
            'countEnAttente',
            'countEnAnalyse',
            'countApprouve',
            'countRejete'
        ));
    }
    public function showSinistre(Sinistre $sinistre)
    {
        $sinistre->load(['police', 'police.user', 'documents']);
        return view('admin.sinistres.show', compact('sinistre'));
    }
    public function analyzeSinistre(Sinistre $sinistre)
    {
        $sinistre->update(['statut' => 'en_analyse']);
        return back()->with('success', 'Le sinistre est maintenant en cours d\'analyse.');
    }

    public function approveSinistre(Sinistre $sinistre)
    {
        $sinistre->update(['statut' => 'approuve']);
        return back()->with('success', 'Le sinistre a été approuvé.');
    }
    public function rejectSinistre(Sinistre $sinistre)
    {
        $sinistre->update(['statut' => 'rejete']);
        return back()->with('success', 'Le sinistre a été rejeté.');
    }
    public function users(Request $request)
    {
        $query = User::withCount('polices');

        // Statistics
        $totalUsers = User::count();
        $activeUsers = User::whereHas('polices', function ($q) {
            $q->where('statut', 'actif');
        })->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Search and Filter
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'newUsersThisMonth'));
    }

    public function showUser(User $user)
    {
        $user->load(['polices', 'polices.sinistres']);
        return view('admin.users.show', compact('user'));
    }

    public function destroyUser(User $user)
    {
        $role = Role::where('id', $user->role_id)->first();
        if ($role->typeRole === 'admin') {
            return back()->with('error', 'Vous ne pouvez pas supprimer un admin.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users')->with('success', 'Utilisateur et toutes ses données associées supprimés avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }

    public function history()
    {
        // Polices archivées (expirées, résiliées, suspendues)
        $archivedPolices = Police::with('user')
            ->whereIn('statut', ['expire', 'resilie', 'suspendu'])
            ->latest()
            ->paginate(10, ['*'], 'polices_page');

        // Sinistres archivés (approuvés, rejetés)
        $archivedSinistres = Sinistre::with(['police.user', 'beneficiaire'])
            ->whereIn('statut', ['approuve', 'rejete', 'termine'])
            ->latest()
            ->paginate(10, ['*'], 'sinistres_page');

        return view('admin.history.index', compact('archivedPolices', 'archivedSinistres'));
    }

    public function analytics()
    {
        // Chart 1: Users by Month (Bar)
        $users_chart = new LaravelChart([
            'chart_title' => 'Nouveaux Utilisateurs par Mois',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\User',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'filter_field' => 'created_at',
            'filter_days' => 365,
        ]);

        // Chart 2: Policies by Formula (Pie)
        $polices_chart = new LaravelChart([
            'chart_title' => 'Répartition par Formule',
            'report_type' => 'group_by_relationship',
            'model' => 'App\Models\Police',
            'relationship_name' => 'formule', 
            'group_by_field' => 'nom', // formule.nom
            'chart_type' => 'pie',
        ]);

        // Chart 3: Claims by Status (Doughnut)
        $sinistres_chart = new LaravelChart([
            'chart_title' => 'Statuts des Sinistres',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\Sinistre',
            'group_by_field' => 'statut',
            'chart_type' => 'pie',
            'chart_color' => [
                "239, 68, 68", // Rouge (Rejeté)
                "59, 130, 246", // Bleu (Attente)
                "245, 158, 11", // Jaune (Analyse) 
                "34, 197, 94", // Vert (Approuvé)
            ],
        ]);

        return view('admin.analytics', compact('users_chart', 'polices_chart', 'sinistres_chart'));
    }
}
