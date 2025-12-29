<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the user's documents.
     */
    public function index()
    {
        $documents = Document::where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('documents.index', compact('documents'));
    }
    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $path = storage_path('app/public/' . $document->cheminDocument);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Download the specified document.
     */
    public function download(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $path = storage_path('app/public/' . $document->cheminDocument);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $document->nom . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document)
    {
        // Vérification des droits
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($document->cheminDocument);

        // Suppression de l’enregistrement en base
        $document->delete();

        return back()->with('success', 'Document supprimé avec succès.');
    }
}
