<?php

namespace app\Repositories;

use App\Models\FlutterEvent;

class FlutterEventRepository
{
    public function getAll()
    {
        return FlutterEvent::orderBy('id', 'desc');
    }

    /**
     * Find a app by it slug
     *
     * @param string $slug
     * @return void
     */
    public function getBySlug($slug)
    {
        return FlutterEvent::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a app by it primary key
     *
     * @param integer $id
     * @return Collection
     */
    public function getById($id)
    {
        return FlutterEvent::findOrFail($id);
    }

    /**
     * Create a new app.
     *
     * @param FlutterEvent $app
     */
    public function store($input, $user_id)
    {
        $app = new FlutterEvent;
        $app->fill($input);
        $app->user_id = $user_id;
        $app->save();

        return $app;
    }

    /**
     * Update a app.
     *
     * @param FlutterEvent $app
     */
    public function update($event, $input)
    {
        $event->fill($input);
        $event->save();

        return $event;
    }

    /**
     * Show a specified app.
     *
     * @param FlutterEvent $slug
     */
    public function show($slug)
    {
        return FlutterEvent::where('slug', $slug)->firstOrFail();
    }

    /**
     * Approve a specified app
     *
     * @param integer $id
     * @return Collection
     */
    public function approve($id)
    {
        return FlutterEvent::where('id', $id)->update(['is_approved' => 1]);
    }

    /**
     * Delete a specified app
     *
     * @param  FlutterApp $app
     * @return
     */
    public function delete($app)
    {
        return FlutterEvent::destroy($app);
    }
}
