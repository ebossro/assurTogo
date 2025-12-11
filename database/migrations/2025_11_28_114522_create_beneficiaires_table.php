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
        Schema::create('beneficiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_id')->constrained('police')->onDelete('cascade');
            $table->string('nomBeneficiaire');
            $table->string('prenomBeneficiaire');
            $table->string('relationBeneficiaire');
            $table->date('dateNaissanceBeneficiaire');
            $table->string('telephoneBeneficiaire')->nullable();
            $table->enum('genreBeneficiaire', ['masculin', 'feminin', 'autre'])->default('masculin');
            $table->enum('statutBeneficiaire', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaires');
    }
};
