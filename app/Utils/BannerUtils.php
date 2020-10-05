<?php

function getBanner()
{
    return false;
    //return getEventBanner();

    /*
    $random = rand(1, 4);

    if ($random == 1) {
        return getEventBanner();
    } elseif ($random == 2) {
        return false;
        //return getBookBanner();
    } elseif ($random == 3) {
        return false;
        //return getCourseBanner();
    } elseif ($random == 4) {
        return false;
    }
    */
}

function getBookBanner()
{
    return "<b><a href='https://www.amazon.com/gp/product/B00PY771FG/ref=as_li_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=B00PY771FG&linkCode=as2&tag=itsallwidgets-20&linkId=0d56c011d8546e561744505bfa157e03' target='_blank'>Mastering Dart</a></b> by Sergey Akopkokhyants is one of the best books available for Dart and is a great way to take your Flutter programming to the next level. #Ad";
}

function getCourseBanner()
{
    return "With over 40,000 students <b><a href='https://click.linksynergy.com/deeplink?id=Ym86trDABLk&mid=39197&murl=https%3A%2F%2Fwww.udemy.com%2Fcourse%2Flearn-flutter-dart-to-build-ios-android-apps%2F' target='_blank'>Learn Flutter & Dart</a></b> by Maximilian Schwarzmüller is the most popular Flutter course available on Udemy. #Ad";
}

function getEventBanner()
{
    $eventRepo = app('App\Repositories\FlutterEventRepository');
    $event = $eventRepo->findRandomOnline();

    if ($event) {
        $event->view_count++;
        $event->save();

        return $event->getBanner();
    }
}

function getNearestEventBanner()
{
    $ip = \Request::getClientIp();
    $banner = false;

    if (cache()->has($ip . '_latitude') && ! request()->platform) {
        $latitude = cache($ip . '_latitude');
        $longitude = cache($ip . '_longitude');

        $eventRepo = app('App\Repositories\FlutterEventRepository');
        $event = $eventRepo->findByCoordinates($latitude, $longitude);

        if ($event) {
            $event->view_count++;
            $event->save();

            $banner = $event->getBanner();
        }
    }

    return $banner;
}
