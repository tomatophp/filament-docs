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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string('ref')->nullable()->index();

            $table->string('model_type')->nullable();
            $table->string('model_id')->nullable();

            $table->foreignId('document_template_id')
                ->constrained('document_templates')
                ->cascadeOnDelete();

            $table->longText('body');

            $table->boolean('is_send')
                ->default(false)
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
