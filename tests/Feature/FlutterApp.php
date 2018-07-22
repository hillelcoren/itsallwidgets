<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Faker\Generator as Faker;

class FlutterApp extends TestCase
{
    //use RefreshDatabase;
    use WithFaker;

    protected $title;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->title = $this->faker->streetName;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSubmitApp()
    {
        // fail to submit if not logged in
        $response = $this->get('flutter-apps/submit');
        $response->assertStatus(302);


        $response = $this->actingAs($this->user)
                         ->get('flutter-apps/submit');
        $response->assertStatus(200);

        // submit an app
        $response = $this->actingAs($this->user)
                         ->post('flutter-apps/submit', [
                             'title' => $this->title,
                             'short_description' => $this->faker->text(100),
                             'long_description' => $this->faker->text(500),
                             'screenshot' => UploadedFile::fake()->image('screenshot.png', 1080, 1920)->size(100),
                         ]);

        $this->assertDatabaseHas('flutter_apps', [
            'title' => $this->title,
        ]);

        $response = $this->get('flutter-app/' . str_slug($this->title));
        $response->assertSeeText($this->title);
    }

    public function testEditApp()
    {
        // fail to submit if not logged in
        $response = $this->get('flutter-app/' . str_slug($this->title) . '/edit');
        $response->assertStatus(302);
    }
}
