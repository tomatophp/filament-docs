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
        Schema::create('document_template_vars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_template_id')
                ->constrained('document_templates')
                ->cascadeOnDelete();

            $table->string('var');
            $table->string('model')->nullable();
            $table->json('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_template_vars');
    }
};
