<?php

namespace app\Repositories;

use App\Models\FlutterApp;

class FlutterAppRepository
{
    public function getAll()
    {
        return FlutterApp::orderBy('id', 'desc');
    }

    /**
     * Find a app by it slug
     *
     * @param string $slug
     * @return void
     */
    public function getBySlug($slug)
    {
        return FlutterApp::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a app by it primary key
     *
     * @param integer $id
     * @return Collection
     */
    public function getById($id)
    {
        return FlutterApp::findOrFail($id);
    }

    /**
     * Create a new app.
     *
     * @param FlutterApp $app
     */
    public function store($input, $user_id)
    {
        $app = new FlutterApp;
        $app->fill($input);
        $app->screenshot1_url = ''; // TODO remove once field is removed
        $app->user_id = $user_id;
        $app = $this->cleanAppUrls($app);
        $app->save();

        return $app;
    }

    /**
     * Update a app.
     *
     * @param FlutterApp $app
     */
    public function update($app, $input)
    {
        $app->fill($input);
        $app = $this->cleanAppUrls($app);
        $app->save();

        return $app;
    }

    private function cleanAppUrls($app) 
    {
        $app->google_url = explode('?', $app->google_url)[0];
        $app->apple_url = explode('?', $app->apple_url)[0];
        $app->microsoft_url = explode('?', $app->microsoft_url)[0];
        $app->snapcraft_url = explode('?', $app->snapcraft_url)[0];
        $app->twitter_url = explode('?', $app->twitter_url)[0];

        $app->google_url = explode('#', $app->google_url)[0];
        $app->apple_url = explode('#', $app->apple_url)[0];
        $app->microsoft_url = explode('#', $app->microsoft_url)[0];
        $app->snapcraft_url = explode('#', $app->snapcraft_url)[0];
        $app->twitter_url = explode('#', $app->twitter_url)[0];

        return $app;
    }

    /**
     * Show a specified app.
     *
     * @param FlutterApp $slug
     */
    public function show($slug)
    {
        return FlutterApp::where('slug', $slug)->firstOrFail();
    }

    /**
     * Approve a specified app
     *
     * @param integer $id
     * @return Collection
     */
    public function approve($id)
    {
        return FlutterApp::where('id', $id)
                        ->update(['status' => 1]);
    }

    /**
     * Delete a specified app
     *
     * @param  FlutterApp $app
     * @return
     */
    public function delete($app)
    {
        return FlutterApp::destroy($app);
    }
}
