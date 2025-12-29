<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Police;
use Illuminate\Support\Facades\Auth;
use App\Models\Formule;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PoliceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $police = request()->user()->police;
        
        $daysRemaining = null;
        $isExpired = false;
        $isExpiringSoon = false;
        
        if ($police && $police->dateFin) {
            // Calculer le nombre de jours restants avec Carbon
            $dateFin = Carbon::parse($police->dateFin);
            $now = Carbon::now();
            
            $daysRemaining = ceil($now->diffInDays($dateFin, false)); 
            
            $isExpired = $daysRemaining < 0;
            $isExpiringSoon = !$isExpired && $daysRemaining >= 0 && $daysRemaining <= 7;
        }

        return view('polices.index', compact('police', 'isExpired', 'isExpiringSoon', 'daysRemaining'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $police = request()->user()->police;
        $isRenewal = false;
        
        if ($police && $police->dateFin) {
            // Calculer avec Carbon pour vérifier si la police est expirée
            $dateFin = Carbon::parse($police->dateFin)->startOfDay();
            $now = Carbon::now()->startOfDay();
            $daysRemainingRaw = $now->diffInDays($dateFin, false);
            $isExpired = $daysRemainingRaw < 0;
            
            // Si la police n'est pas expirée, on ne peut pas renouveler
            if (!$isExpired) {
                return redirect()->route('polices.index')->with('info', 'Vous avez déjà une police active.');
            }
            
            // Si la police est expirée, c'est un renouvellement
            $isRenewal = true;
        }
        
        $formules = Formule::all();
        return view('polices.create', compact('formules', 'isRenewal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = request()->user();
        if ($user->police && $user->police->dateFin) {
            // Vérifier avec Carbon si la police est expirée pour permettre le renouvellement
            $dateFin = Carbon::parse($user->police->dateFin)->startOfDay();
            $now = Carbon::now()->startOfDay();
            $daysRemainingRaw = $now->diffInDays($dateFin, false);
            $isExpired = $daysRemainingRaw < 0;

            // Si la police n'est pas expirée, on ne peut pas renouveler
            if (!$isExpired) {
                return redirect()->route('polices.index')->with('error', 'Vous avez déjà une police active. Vous ne pouvez renouveler que si votre police est expirée.');
            }
            
            // Supprimer l'ancienne police expirée pour créer la nouvelle
            $user->police->delete();
        }

        // 1. Validation de tous les champs
        $validated = $request->validate([
            // Infos User
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'photo_profil' => 'required|image|max:2048',
            'type_piece' => 'required|string',
            'numero_piece' => 'required|string',
            'date_expiration_piece' => 'required|date',
            'telephone' => 'required|string',
            'email' => 'required|email',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'quartier' => 'required|string',
            'statut_matrimonial' => 'required|in:celibataire,marie,divorce,veuf',
            'nombre_enfants' => 'required|integer|min:0',
            'profession' => 'required|string',
            'employeur' => 'nullable|string',
            'revenu_mensuel' => 'nullable|numeric',

            // Infos Police
            'formule' => 'required|exists:formules,nom',
            'antecedents_medicaux' => 'nullable|string',
            'medicaments_actuels' => 'nullable|string',
            'allergies' => 'nullable|string',
            'habitudes_vie' => 'nullable|string',
            'consentement_conditions' => 'accepted',

            // Bénéficiaires
            'beneficiaires' => ['nullable', 'array', function ($attribute, $value, $fail) use ($request) {
                // Limites strictes par formule
                $limits = [
                    'Basique' => 0,
                    'Standard' => 2,
                    'Confort' => 4,
                    'Premium' => 8,
                ];
                $formule = $request->input('formule');
                if (isset($limits[$formule]) && count($value) > $limits[$formule]) {
                    $fail("La formule {$formule} ne permet pas plus de {$limits[$formule]} bénéficiaire(s).");
                }
            }],
            'beneficiaires.*.nom' => 'required_with:beneficiaires|string',
            'beneficiaires.*.prenom' => 'required_with:beneficiaires|string',
            'beneficiaires.*.relation' => 'required_with:beneficiaires|string',
            'beneficiaires.*.date_naissance' => 'required_with:beneficiaires|date',
            'beneficiaires.*.sexe' => 'required_with:beneficiaires|in:masculin,feminin',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Traitement de la photo
        if ($request->hasFile('photo_profil')) {
            $path = $request->file('photo_profil')->store('profils', 'public');
            $user->photo_profil = $path;
        }

        // 3. Mise à jour du profil utilisateur
        $user->update([
            'name' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'telephone' => $validated['telephone'],
            'date_naissance' => $validated['date_naissance'],
            'sexe' => $validated['sexe'],
            'type_piece' => $validated['type_piece'],
            'numero_piece' => $validated['numero_piece'],
            'date_expiration_piece' => $validated['date_expiration_piece'],
            'adresse' => $validated['adresse'],
            'ville' => $validated['ville'],
            'quartier' => $validated['quartier'],
            'statut_matrimonial' => $validated['statut_matrimonial'],
            'nombre_enfants' => $validated['nombre_enfants'],
            'profession' => $validated['profession'],
            'employeur' => $validated['employeur'],
            'revenu_mensuel' => $validated['revenu_mensuel'],
        ]);

        // 4. Calcul du prix
        $formule = Formule::where('nom', $validated['formule'])->first();
        $prime = $formule->prix_mensuel;
        
        // 5. Création de la Police
        $police = $user->police()->create([
            'numeroPolice' => 'POL-' . strtoupper(uniqid()),
            'typePolice' => 'Assurance Santé ' . $validated['formule'], // Description
            'formule_id' => $formule->id,
            'couverture' => 'Gamme ' . $validated['formule'],
            'dateDebut' => now(),
            'dateFin' => now()->copy()->addMonth(),
            'primeMensuelle' => $prime,
            'antecedents_medicaux' => $validated['antecedents_medicaux'],
            'medicaments_actuels' => $validated['medicaments_actuels'],
            'allergies' => $validated['allergies'],
            'habitudes_vie' => $validated['habitudes_vie'],
            'statut' => 'en_attente',

        ]);

        // 6. Enregistrement des bénéficiaires
        if (isset($validated['beneficiaires'])) {
            foreach ($validated['beneficiaires'] as $b) {
                $police->beneficiaires()->create([
                    'nomBeneficiaire' => $b['nom'],
                    'prenomBeneficiaire' => $b['prenom'],
                    'relationBeneficiaire' => $b['relation'],
                    'dateNaissanceBeneficiaire' => $b['date_naissance'],
                    'genreBeneficiaire' => $b['sexe'], // attention à l'enum dans la migration benef
                    'statutBeneficiaire' => 'actif',
                    'telephoneBeneficiaire' => 'N/A', // Champ requis dans la migration précédente ? Vérifions.
                ]);
            }
        }

        /*
        // Role update logic disabled as per previous instruction
        */

        return redirect()->route('polices.confirmation');
    }

    /**
     * Affiche la page de confirmation après souscription.
     */
    public function confirmation()
    {
        return view('polices.confirmation');
    }

    /**
     * Display the specified resource.
     */
    public function show(Police $police)
    {
        // Vérifier que la police appartient à l'utilisateur connecté
        if ($police->user_id !== Auth::id()) {
            abort(403);
        }

        $police->load(['beneficiaires', 'sinistres']);

        return view('polices.show', compact('police'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
