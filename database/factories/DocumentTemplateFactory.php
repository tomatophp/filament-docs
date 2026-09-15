<?php

namespace TomatoPHP\FilamentDocs\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

/**
 * @extends Factory<DocumentTemplate>
 */
class DocumentTemplateFactory extends Factory
{
    protected $model = DocumentTemplate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'body' => '<h2>' . fake()->sentence() . '</h2><p>' . fake()->paragraph() . '</p>',
            'is_active' => true,
            'icon' => 'heroicon-o-document-text',
            'color' => 'primary',
        ];
    }
}
