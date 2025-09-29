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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')->constrained()->onDelete('cascade');
            $table->foreignId('technicien_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date_controle')->nullable();
            $table->date('date_maintenance')->nullable();
            $table->date('date_retour')->nullable();
            $table->text('description_panne')->nullable();
            $table->string('photo_avant')->nullable();
            $table->string('photo_apres')->nullable();
            $table->decimal('cout', 10, 2)->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'attente'])->default('attente');
            $table->timestamps();

            $table->index(['equipement_id', 'statut']);
            $table->index(['technicien_id', 'statut']);
            $table->index('date_controle');
            $table->index('date_maintenance');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
