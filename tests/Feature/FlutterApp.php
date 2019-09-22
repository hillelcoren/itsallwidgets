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
    protected $other_user;
    protected $admin_user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->other_user = factory(User::class)->create();
        $this->admin_user = factory(User::class)->create([
            'is_admin' => true,
        ]);

        $this->title = $this->faker->streetName;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSubmitApp()
    {
        // =======================
        // Create the app
        // =======================

        $response = $this->get('submit');
        $response->assertStatus(302);

        $response = $this->actingAs($this->user)
                         ->get('submit');
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)
                         ->post('submit', [
                             '_token' => csrf_token(),
                             'title' => $this->title,
                             'short_description' => $this->faker->text(100),
                             'long_description' => $this->faker->text(500),
                             'screenshot' => UploadedFile::fake()->image('screenshot.png', 1080, 1920)->size(100),
                             'terms' => true,
                         ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('flutter_apps', [
            'title' => $this->title,
        ]);

        $response = $this->get('/?legacy=true');
        $response->assertDontSee($this->title);

        $response = $this->get(str_slug($this->title));
        $response->assertSeeText($this->title);

        // =======================
        // Edit the app
        // =======================

        auth()->logout();
        $route = 'flutter-app/' . str_slug($this->title) . '/edit';

        $response = $this->get($route);
        $response->assertStatus(302);

        $response = $this->actingAs($this->other_user)
                          ->get($route);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)
                         ->get($route);
        $response->assertStatus(200);

        $newTitle = $this->faker->streetName;
        $newData = [
            '_token' => csrf_token(),
            'title' => $newTitle,
            'short_description' => $this->faker->text(100),
            'long_description' => $this->faker->text(500),
        ];

        auth()->logout();
        $route = 'flutter-app/' . str_slug($this->title);

        $response = $this->put($route, $newData);
        $response->assertStatus(302);

        $response = $this->actingAs($this->other_user)
                          ->put($route, $newData);
        $response->assertStatus(403);

        $response = $this->get(str_slug($this->title));
        $response->assertSeeText($this->title);

        $response = $this->actingAs($this->user)
                         ->put($route, $newData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('flutter_apps', [
            'title' => $newTitle,
            'is_approved' => false,
        ]);

        auth()->logout();

        $response = $this->get($route);
        $response->assertDontSee($this->title);
        $response->assertSee($newTitle);


        // =======================
        // Approve the app
        // =======================

        auth()->logout();
        $route = 'flutter-app/' . str_slug($this->title) . '/approve';

        $response = $this->get($route);
        $response->assertStatus(302);

        $response = $this->actingAs($this->other_user)
                          ->get($route);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)
                          ->get($route);
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin_user)
                          ->get($route);
        $response->assertStatus(302);

        $this->assertDatabaseHas('flutter_apps', [
            'title' => $newTitle,
            'is_approved' => true,
        ]);

        auth()->logout();

        $response = $this->get('/?legacy=true');
        $response->assertSee($newTitle);
    }
}
