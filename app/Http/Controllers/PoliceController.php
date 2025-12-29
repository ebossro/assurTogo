<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Police;
use Illuminate\Support\Facades\Auth;
use App\Models\Formule;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PoliceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer la dernière police (active ou en attente)
        $police = request()->user()->polices()->latest()->first();

        $daysRemaining = null;
        $isExpired = false;
        $isExpiringSoon = false;

        if ($police && $police->dateFin) {
            // Calculer le nombre de jours restants avec Carbon
            $dateFin = Carbon::parse($police->dateFin);
            $now = Carbon::now();

            $daysRemaining = ceil($now->diffInDays($dateFin, false));

            $isExpired = $daysRemaining < 0;
            $isExpiringSoon = ceil($daysRemaining >= 0 && $daysRemaining <= 7);

            if ($isExpired && $police->statut === 'actif') {
                $police->update(['statut' => 'expire']);
            }
        }

        return view('polices.index', compact('police', 'isExpired', 'isExpiringSoon', 'daysRemaining'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérification d'une police
        $activePolice = request()->user()->polices()->whereIn('statut', ['actif', 'en_attente'])->first();
        $latestPolice = request()->user()->polices()->latest()->first();

        $isRenewal = false;

        // Si une police active existe, on bloque
        if ($activePolice) {
            return redirect()->route('polices.index')->with('info', 'Vous avez déjà une police active ou en cours de validation.');
        }

        // Si la dernière police est expirée/résiliée, c'est un renouvellement
        if ($latestPolice && in_array($latestPolice->statut, ['expire', 'resilie', 'suspendu'])) {
            $isRenewal = true;
            $police = $latestPolice;
        } else {
            $police = null;
        }

        // Charger les formules et afficher le formulaire 
        $formules = Formule::all();
        return view('polices.create', compact('formules', 'isRenewal', 'police'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = request()->user();

        // pas de police active ou en attente
        $activePolice = $user->polices()->whereIn('statut', ['actif', 'en_attente'])->first();
        if ($activePolice) {
            return redirect()->route('polices.index')->with('error', 'Vous avez déjà une police active ou en cours de validation.');
        }

        // 1. Validation de tous les champs
        $photoValidation = $user->photo_profil ? 'nullable|image|max:2048' : 'required|image|max:2048';

        $validated = $request->validate([
            // Infos User
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'photo_profil' => $photoValidation,
            'type_piece' => 'required|string',
            'numero_piece' => 'required|string|digits:11',
            'date_expiration_piece' => 'required|date',
            'telephone' => 'required|string|digits:8',
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
            'beneficiaires' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) use ($request) {
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
                }
            ],
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
        $police = $user->polices()->create([
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
                    'genreBeneficiaire' => $b['sexe'],
                    'statutBeneficiaire' => 'actif',
                    'telephoneBeneficiaire' => 'N/A', 
                ]);
            }
        }

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

        $police->load(['beneficiaires', 'sinistres', 'formule']);

        return view('polices.show', compact('police'));
    }

    /**
     * Afficher l'historique des polices de l'utilisateur.
     */
    public function history()
    {
        // Récupérer la police de l'utilisateur suspendue, resilie ou expire
        $user = Auth::user();
        $polices = $user->polices()->whereIn('statut', ['suspendu', 'resilie', 'expire'])->get();

        return view('polices.history', compact('polices'));
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

    /**
     * Télécharger l'attestation d'assurance.
     */
    public function downloadAttestation(Police $police)
    {
        // Vérification que l'utilisateur est bien le propriétaire
        if ($police->user_id !== Auth::id()) {
            abort(403);
        }

        // Vérification que la police est active
        if ($police->statut !== 'actif') {
            return back()->with('error', 'L\'attestation n\'est disponible que pour les polices actives.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.attestation', compact('police'));

        return $pdf->download('Attestation_Assurance_' . $police->numeroPolice . '.pdf');
    }
}
