<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sinistre;
use Illuminate\Support\Facades\DB;

class SinistreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
        
        // Récupérer les sinistres liés à la police de l'utilisateur
        $sinistres = Sinistre::whereHas('police', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest('date_sinistre')->get();

        // Calcul des statistiques
        $totalRembourse = $sinistres->where('statut', 'approuve')->sum('montant_total'); 
        $totalEnAttente = $sinistres->whereIn('statut', ['en_attente', 'en_analyse'])->sum('montant_total');
        $nombreTraites = $sinistres->whereIn('statut', ['valide', 'rejete'])->count();

        // Vérifier si la police est encore valide
        $police = $user->polices()->latest()->first();
        $canDeclareSinistre = $police && $police->dateFin >= now()->startOfDay();

        return view('sinistres.index', compact('sinistres', 'totalRembourse', 'totalEnAttente', 'nombreTraites', 'canDeclareSinistre'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // On récupère la police active
        $police = request()->user()->polices()->with('beneficiaires')->where('statut', 'actif')->latest()->first();
        
        if (!$police) {
             return redirect()->route('polices.create')->with('warning', 'Vous devez avoir une police active pour déclarer un sinistre.');
        }

        return view('sinistres.create', compact('police'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'police_id' => 'required|exists:police,id',
            'beneficiaire_id' => 'nullable|exists:beneficiaires,id',
            'type_sinistre' => 'required|in:maladie,accident,hospitalisation,maternite,chirurgie',
            'lieu_sinistre' => 'required|string|max:255',
            'ville_pays' => 'required|string|max:255',
            'date_sinistre' => 'required|date|before_or_equal:today',
            'premiere_consultation' => 'boolean', 
            'gravite' => 'required|in:leger,moyen,grave',
            'description' => 'required|string|min:10',
            'diagnostic' => 'nullable|string',
            'medecin_traitant' => 'nullable|string',
            'traitement_prescrit' => 'nullable|string',
            'montant_total' => 'required|numeric|min:0',

            'is_declarant_different' => 'boolean',
            'declarant_nom' => 'required_if:is_declarant_different,1,true|nullable|string',
            'declarant_relation' => 'required_if:is_declarant_different,1,true|nullable|string',
            'commentaires' => 'nullable|string',
            'consentement' => 'accepted',
            'documents' => 'required|array|min:1',
            'documents.*.type' => 'required|in:certificat_medical,facture,examen,ordonnance,rapport_medical,identite,autre',
            'documents.*.file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $sinistre = Sinistre::create([
            'police_id' => $validated['police_id'],
            'beneficiaire_id' => $validated['beneficiaire_id'] ?? null,
            'reference' => 'SIN-' . strtoupper(uniqid()),
            'type_sinistre' => $validated['type_sinistre'],
                'lieu_sinistre' => $validated['lieu_sinistre'],
                'ville_pays' => $validated['ville_pays'],
                'date_sinistre' => $validated['date_sinistre'],
                'premiere_consultation' => $request->has('premiere_consultation') ? $request->boolean('premiere_consultation') : true,
                'gravite' => $validated['gravite'],
                'description' => $validated['description'],
                'diagnostic' => $validated['diagnostic'] ?? null,
                'medecin_traitant' => $validated['medecin_traitant'] ?? null,
                'traitement_prescrit' => $validated['traitement_prescrit'] ?? null,
                'montant_total' => $validated['montant_total'],

                'is_declarant_different' => $request->boolean('is_declarant_different'),
                'declarant_nom' => $validated['declarant_nom'] ?? null,
                'declarant_relation' => $validated['declarant_relation'] ?? null,
                'commentaires' => $validated['commentaires'] ?? null,
                'consentement' => true,
                'statut' => 'en_attente',
            ]);

            // Re-approach for file loop:
            $files = $request->file('documents');
            $types = $request->input('documents');

            foreach ($files as $index => $fileWrapper) {
                
                $uploadedFile = $fileWrapper['file'];
                $docType = $types[$index]['type'];

                $path = $uploadedFile->store('documents/' . date('Y/m'), 'public');

                $sinistre->documents()->create([
                    'police_id' => $sinistre->police_id,
                    'user_id' => $request->user()->id,
                    'typeDocument' => $docType,
                    'cheminDocument' => $path,
                    'tailleDocument' => $uploadedFile->getSize(),
                    'statutDocument' => 'actif',
                ]);
            }

            return redirect()->route('sinistres.index')->with('success', 'Votre sinistre a été déclaré avec succès et est en attente d\'analyse.');

         
    }

    /**
     * Display the specified resource.
     */
    public function show(Sinistre $sinistre)
    {
        // Vérifier que le sinistre appartient à une police de l'utilisateur
        if ($sinistre->police->user_id !== request()->user()->id) {
            abort(403);
        }

        return view('sinistres.show', compact('sinistre'));
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
