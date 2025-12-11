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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_id')->constrained('police')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->string('reference')->unique();
            $table->string('type_paiement'); // prime, frais, etc.
            $table->string('mode_paiement'); // mobile money, virement, etc.
            $table->date('date_paiement');
            $table->date('date_echeance')->nullable();
            $table->enum('statut', ['en_attente', 'paye', 'echoue'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
