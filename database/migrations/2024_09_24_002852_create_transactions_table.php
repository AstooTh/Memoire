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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->string('type');
            $table->unsignedBigInteger('produit_id');
            $table->integer('qte');
            $table->unsignedBigInteger('origine'); 
            $table->unsignedBigInteger('destination');
            $table->timestamps();
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('origine')->references('id')->on('emplacements')->onDelete('cascade');
            $table->foreign('destination')->references('id')->on('emplacements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
