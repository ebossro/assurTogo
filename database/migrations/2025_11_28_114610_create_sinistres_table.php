<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_id')->constrained('police')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->text('description');
            $table->string('fichier_preuve');
            $table->date('date_sinistre');
            $table->decimal('montant_estime', 15, 2)->nullable();
            $table->enum('statut', ['en_cours', 'valide', 'rejete'])->default('en_cours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinistres');
    }
};
