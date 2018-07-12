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
        $app->user_id = $user_id;
        $app->save();

        return $app;
    }

    /**
     * Update a app.
     *
     * @param FlutterApp $app
     */
    public function update($input)
    {
        $app = self::getById($input['id']);
        $app->fill($input);
        $app->save();

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
