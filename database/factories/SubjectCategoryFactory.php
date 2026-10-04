<?php

namespace Database\Factories;

use App\Models\SubjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubjectCategory>
 */
class SubjectCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Geografi', 'Sejarah', 'Bahasa Indonesia', 'Bahasa Inggris', 'Seni Rupa', 'Seni Musik', 'Teknologi Informasi', 'Ekonomi', 'Sosiologi', 'Psikologi']),
            'deskripsi' => fake()->text(),
            'foto' => fake()->randomElement(['📐','📏','🔬','🧪','🧬','🌍','📝','🔢','📖','🎨','🎵','💻','🏛️','⚗️','🌱','🧮','🗺️','✏️']),
            'is_active' => true,
        ];
    }
}
