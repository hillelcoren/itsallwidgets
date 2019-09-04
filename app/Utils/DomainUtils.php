<?php

function isIAW() {
    return strpos(request()->url(), 'itsallwidgets.') !== false;
}

function isFE() {
    return strpos(request()->url(), 'flutterevents.') !== false || strpos(request()->url(), iawUrl() . '/flutter-events') !== false;
}

function isFX() {
    return strpos(request()->url(), 'flutterx.') !== false;
}

function isFP() {
    return strpos(request()->url(), 'flutterpro.') !== false || strpos(request()->url(), iawUrl() . '/profile') !== false;
}

function isGL() {
    return strpos(request()->url(), 'geu.la') !== false;
}

function isTest() {
    return isValet() || isServe();
}

function isValet() {
    return strpos(request()->url(), '.test') !== false;
}

function isServe() {
    return strpos(request()->url(), '127.0.0.1:') !== false;
}

function appName() {
    if (isGL()) {
        return 'Geu.la';
    } elseif (isFE()) {
        return 'Flutter Events';
    } elseif (isFP()) {
        return 'Flutter Pro';
    } elseif (isFX()) {
        return 'FlutterX';
    } else {
        return 'It\'s All Widgets!';
    }
}

function iawUrl() {
    return isTest() ? (isValet() ? 'http://itsallwidgets.test' : 'http://127.0.0.1:8000') : 'https://itsallwidgets.com';
}

function feUrl() {
    return isTest() ? (isValet() ? 'http://flutterevents.test' : 'http://127.0.0.1:8000/flutter-events')  : 'https://flutterevents.com';
}

function fxUrl() {
    return isTest() ? (isValet() ? 'http://flutterx.test' : 'http://127.0.0.1:8000/flutterx') : 'https://flutterx.com';
}

function fpUrl() {
    return isTest() ? (isValet() ? 'http://flutterpro.test' : 'http://127.0.0.1:8000/profiles') : 'https://flutterpro.dev';
}

function glUrl() {
    return isTest() ? (isValet() ? 'http://geu.test' : 'http://127.0.0.1:8000/flutterx') : 'https://geu.la';
}
