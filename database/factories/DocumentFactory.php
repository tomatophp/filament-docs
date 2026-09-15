<?php

namespace TomatoPHP\FilamentDocs\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentDocs\Models\Document;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ref' => strtoupper(fake()->bothify('DOC-####')),
            'document_template_id' => DocumentTemplate::factory(),
            'body' => '<h2>' . fake()->sentence() . '</h2><p>' . fake()->paragraph() . '</p>',
            'is_send' => false,
        ];
    }
}
