<?php

namespace TomatoPHP\FilamentDocs\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

class DocumentTemplateFactory extends Factory
{
    protected $model = DocumentTemplate::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'body' => $this->faker->text(),
            'is_active' => $this->faker->boolean(),
            'icon' => 'heroicon-o-document-text',
            'color' => $this->faker->word()
        ];
    }

    public function withTeam($id)
    {
        return $this->state([
            'team_id' => $id,
        ]);
    }
}
