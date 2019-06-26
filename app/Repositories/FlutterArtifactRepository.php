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
        $artifact = new FlutterArtifact;
        $artifact->fill($input);
        $artifact->user_id = $user_id;
        $artifact->url = rtrim($artifact->url , '/');
        $artifact->source_url = rtrim($artifact->source_url , '/');
        $artifact->repo_url = rtrim($artifact->repo_url , '/');
        $artifact->meta_author_url = rtrim($artifact->meta_author_url , '/');
        $artifact->save();

        return $artifact;
    }

    /**
     * Update a app.
     *
     * @param FlutterArtifact $app
     */
    public function update($artifact, $input)
    {
        $artifact->fill($input);
        $artifact->url = rtrim($artifact->url , '/');
        $artifact->source_url = rtrim($artifact->source_url , '/');
        $artifact->repo_url = rtrim($artifact->repo_url , '/');
        $artifact->meta_author_url = rtrim($artifact->meta_author_url , '/');
        $artifact->save();

        return $artifact;
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
