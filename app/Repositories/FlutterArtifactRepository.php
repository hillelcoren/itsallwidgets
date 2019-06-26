<?php

namespace app\Repositories;

use DB;
use App\Models\FlutterArtifact;

class FlutterArtifactRepository
{
    public function getAll()
    {
        return FlutterArtifact::orderBy('id', 'desc');
    }

    /**
     * Find a app by it slug
     *
     * @param string $slug
     * @return void
     */
    public function getBySlug($slug)
    {
        return FlutterArtifact::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a app by it primary key
     *
     * @param integer $id
     * @return Collection
     */
    public function getById($id)
    {
        return FlutterArtifact::findOrFail($id);
    }

    /**
     * Create a new app.
     *
     * @param FlutterArtifact $app
     */
    public function store($input, $user_id)
    {
        $ertifact = new FlutterArtifact;
        $ertifact->fill($input);
        $ertifact->user_id = $user_id;

        $url = $ertifact->url;
        $url = explode('?', $url)[0];
        $url = rtrim($url, '/');
        $ertifact->url = $url;

        $ertifact->save();

        return $ertifact;
    }

    /**
     * Update a app.
     *
     * @param FlutterArtifact $app
     */
    public function update($ertifact, $input)
    {
        $ertifact->fill($input);

        $url = $ertifact->ertifact_url;
        $url = explode('?', $url)[0];
        $url = rtrim($url, '/');
        $ertifact->ertifact_url = $url;

        $ertifact->save();

        return $ertifact;
    }

    /**
     * Show a specified app.
     *
     * @param FlutterArtifact $slug
     */
    public function show($slug)
    {
        return FlutterArtifact::where('slug', $slug)->firstOrFail();
    }

    /**
     * Approve a specified app
     *
     * @param integer $id
     * @return Collection
     */
    public function approve($id)
    {
        return FlutterArtifact::where('id', $id)->update(['is_approved' => 1]);
    }

    /**
     * Delete a specified app
     *
     * @param  FlutterApp $app
     * @return
     */
    public function delete($app)
    {
        return FlutterArtifact::destroy($app);
    }
}
