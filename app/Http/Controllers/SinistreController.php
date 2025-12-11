<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sinistre;

class SinistreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
        
        // Récupérer les sinistres liés aux polices de l'utilisateur
        $sinistres = Sinistre::whereHas('police', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest('date_sinistre')->get();

        // Calcul des statistiques
        $totalRembourse = $sinistres->where('statut', 'valide')->sum('montant_estime'); // Ou montant_rembourse si dispo
        $totalEnAttente = $sinistres->where('statut', 'en_cours')->sum('montant_estime');
        $nombreTraites = $sinistres->whereIn('statut', ['valide', 'rejete'])->count();

        return view('sinistres.index', compact('sinistres', 'totalRembourse', 'totalEnAttente', 'nombreTraites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polices = request()->user()->polices()->where('etat', 'Actif')->get();
        return view('sinistres.create', compact('polices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'police_id' => 'required|exists:police,id',
            'date_sinistre' => 'required|date|before_or_equal:today',
            'description' => 'required|string|min:10',
            'montant_estime' => 'required|numeric|min:0',
            'fichier_preuve' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('fichier_preuve')) {
            $path = $request->file('fichier_preuve')->store('proofs', 'public');
            $validated['fichier_preuve'] = $path;
        }

        Sinistre::create(
            [
                'police_id' => $validated['police_id'],
                'date_sinistre' => $validated['date_sinistre'],
                'description' => $validated['description'],
                'montant_estime' => $validated['montant_estime'],
                'fichier_preuve' => $validated['fichier_preuve'],
                'reference' => 'SIN-' . strtoupper(uniqid()),
                'statut' => 'en_cours',
            ]
        );

        return redirect()->route('sinistres.index')->with('success', 'Votre sinistre a été déclaré avec succès.');
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
