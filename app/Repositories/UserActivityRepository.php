<?php

namespace app\Repositories;

use DB;
use App\Models\UserActivity;

class UserActivityRepository
{
    /**
     * Fetch all activities
     *
     * @return mixed
     */
    public function getAll()
    {
        return UserActivity::orderBy('id', 'desc');
    }

    /**
     * Find an activity by its primary key
     *
     * @param integer $id
     * @return mixed
     */
    public function getById($id)
    {
        return UserActivity::findOrFail($id);
    }

    /**
     * Create a new activity
     *
     * @param int $user_id
     * @param int $activity_id
     * @param string $activity_type
     *
     *
     * @return UserActivity
     */
    public function store($user_id, $activity_id, $activity_type)
    {
        $activity = new UserActivity;
        $activity->user_id = $user_id;
        $activity->activity_id = $activity_id;
        $activity->activity_type = $activity_type;
        $activity->save();

        return $activity;
    }

    /**
     * Update an activity
     *
     * @param UserActivity $activity
     * @return mixed
     */
    public function update($activity, $activity_id, $activity_type)
    {
        $activity->activity_id = $activity_id;
        $activity->activity_type = $activity_type;
        $activity->save();

        return $activity;
    }

    /**
     * Approve a specified activity
     *
     * @param integer $id
     * @param boolean $is_visible
     * @return UserActivity
     */
    public function setVisibility($id, $is_visible)
    {
        return UserActivity::where('id', $id)->update(['$is_visible' => $is_visible]);
    }

    /**
     * Delete a specified activity
     *
     * @param  UserActivity $activity
     * @return mixed
     */
    public function delete($activity)
    {
        return UserActivity::destroy($activity);
    }
}
