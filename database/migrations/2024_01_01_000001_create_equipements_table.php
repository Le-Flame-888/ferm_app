<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     */
    public function up(): void
    {
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('code_ref')->nullable()->unique();
            $table->string('categorie', 50); // Moteur, Pompe...
            $table->string('type_emplacement', 50); // Extracteur, Tapis...
            $table->string('site', 50); // Ain Aouda PS, Zhiliga...
            $table->string('emplacement', 50); // Batiment, Magasin...
            $table->string('lot', 20); // C C C, Z1...
            $table->string('code_ref', 50); // auto-généré
            $table->string('photo', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('type_huile', 50)->nullable();
            $table->integer('duree_controle')->nullable(); // en jours
            $table->string('ref_courroie', 50)->nullable();
            $table->string('ref_roulement', 50)->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->string('marque', 50)->nullable();
            $table->date('date_achat')->nullable();
            $table->unsignedBigInteger('fournisseur_id')->nullable();
            $table->date('date_mise_service')->nullable();
            $table->decimal('prix_achat', 10, 2)->nullable();
            $table->timestamps();
        });

        // Add index after table creation to avoid key length issues
        Schema::table('equipements', function (Blueprint $table) {
            $table->unique('code_ref');
            $table->index(['categorie', 'site']);
        });
        
        // Foreign key will be added in a separate migration after fournisseurs table is created
    }

    /**
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
