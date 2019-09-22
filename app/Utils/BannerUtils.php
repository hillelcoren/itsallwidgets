<?php

function getBanner() {
    return getEventBanner();
}

function getEventBanner() {
    $ip = \Request::getClientIp();
    $banner = false;
    return '';
    if (cache()->has($ip . '_latitude') && ! request()->platform) {
        $latitude = cache($ip . '_latitude');
        $longitude = cache($ip . '_longitude');

        $event = $this->eventRepo->findByCoordinates($latitude, $longitude);
        if ($event) {
            $event->view_count++;
            $event->save();

            $banner = $event->getBanner();
        }
    }

    return $banner;
}
