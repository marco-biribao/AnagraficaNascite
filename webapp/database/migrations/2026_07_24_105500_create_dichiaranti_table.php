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
        Schema::create('dichiaranti', function (Blueprint $table) {
            $table->id();
            $table->string('codice')->unique(); // PADRE, MADRE, ALTRO
            $table->string('descrizione');
            $table->unsignedInteger('ordine')->default(0);
            $table->boolean('attivo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dichiaranti');
    }
};
