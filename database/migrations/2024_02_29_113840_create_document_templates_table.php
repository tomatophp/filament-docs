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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');

            $table->longText('body');

            $table->boolean('is_active')->default(1)->nullable();

            $table->string('icon')->default('bx bx-file')->nullable();
            $table->string('color')->default('primary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
