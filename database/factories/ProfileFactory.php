<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Storage::disk('public')->makeDirectory('profiles');

        $file = UploadedFile::fake()->image(fake()->uuid() . '.jpg');

        $path = Storage::disk('public')->put('profiles', $file);

        return [
            'firstName' => fake()->firstName(),
            'lastName'  => fake()->lastName(),
            'image'     => '/storage/' . $path,
            'status'    => fake()->randomElement(array_keys(Profile::STATUSES)),
        ];
    }

    public function waiting(): static
    {
        return $this->state(fn() => ['status' => Profile::STATUS_WAITING]);
    }

    public function active(): static
    {
        return $this->state(fn() => ['status' => Profile::STATUS_ACTIVE]);
    }

    public function inactive(): static
    {
        return $this->state(fn() => ['status' => Profile::STATUS_INACTIVE]);
    }
}
