<?php

function isIAW() {
    return strpos(request()->url(), 'itsallwidgets.') !== false;
}

function isFE() {
    return strpos(request()->url(), 'flutterevents.') !== false || strpos(request()->url(), iawUrl() . '/flutter-events') !== false;
}

function isFS() {
    return strpos(request()->url(), 'flutterstreams.') !== false || strpos(request()->url(), iawUrl() . '/flutter-streams') !== false;
}

function isFX() {
    return strpos(request()->url(), 'flutterx.') !== false || strpos(request()->url(), iawUrl() . '/flutterx') !== false;
}

function isFP() {
    return strpos(request()->url(), 'flutterpro.') !== false || strpos(request()->url(), iawUrl() . '/profile') !== false;
}

function isGL() {
    return strpos(request()->url(), 'geu.la') !== false;
}

function isTest() {
    return isValet() || isServe() || isHomestead();
}

function isValet() {
    return strpos(request()->url(), '.test') !== false;
}

function isHomestead() {
    return strpos(request()->url(), 'dev.') !== false;
}

function isServe() {
    return strpos(request()->url(), '127.0.0.1:') !== false;
}

function appName() {
    if (isGL()) {
        return 'Geu.la';
    } elseif (isFE()) {
        return 'Flutter Events';
    } elseif (isFS()) {
        return 'Flutter Streams';
    } elseif (isFP()) {
        return 'Flutter Pro';
    } elseif (isFX()) {
        return 'FlutterX';
    } else {
        return 'It\'s All Widgets!';
    }
}

function iawUrl() {
    if (isValet()) {
        return 'http://itsallwidgets.test';
    } else if (isHomestead()) {
        return 'http://dev.itsallwidgets.com';
    } else if (isServe()) {
        return 'http://127.0.0.1:8000';
    }

    return 'https://itsallwidgets.com';
}

function fsUrl() {
    if (isValet()) {
        return 'http://flutterstreams.test';
    } else if (isHomestead()) {
        return 'http://dev.flutterstreams.com';
    } else if (isServe()) {
        return 'http://127.0.0.1:8000/flutter-streams';
    }

    return 'https://flutterstreams.com';
}

function fxUrl() {
    if (isValet()) {
        return 'http://flutterx.test';
    } else if (isHomestead()) {
        return 'http://dev.flutterx.com';        
    } else if (isServe()) {
        return 'http://127.0.0.1:8000/flutterx';
    }

    return 'https://flutterx.com';
}

function fpUrl() {
    if (isValet()) {
        return 'http://flutterpro.test';
    } else if (isHomestead()) {
        return 'http://dev.flutterpro.com';
    } else if (isServe()) {
        return 'http://127.0.0.1:8000/flutterpro';
    }

    return 'https://flutterpro.dev';
}

function glUrl() {
    return isTest() ? (isValet() ? 'http://geu.test' : 'http://127.0.0.1:8000/flutterx') : 'https://geu.la';
}

function feUrl() {
    return isTest() ? (isValet() ? 'http://flutterevents.test' : 'http://127.0.0.1:8000/flutter-events')  : 'https://flutterevents.com';
}
