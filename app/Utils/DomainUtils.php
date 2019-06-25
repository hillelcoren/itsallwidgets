<?php

function isIAW() {
    return strpos(request()->url(), 'itsallwidgets.') !== false;
}

function isFE() {
    return strpos(request()->url(), 'flutterevents.') !== false;
}

function isFX() {
    return strpos(request()->url(), 'flutterx.') !== false;
}

function isFC() {
    return strpos(request()->url(), 'fluttercollective.') !== false;
}

function isTest() {
    return strpos(request()->url(), '.test') !== false;
}


function iawUrl() {
    return isTest() ? 'http://itsallwidgets.test' : 'https://itsallwidgets.com';
}

function feUrl() {
    return isTest() ? 'http://flutterevents.test' : 'https://flutterevents.com';
}

function fxUrl() {
    return isTest() ? 'http://flutterx.test' : 'https://flutterx.com';
}

function fcUrl() {
    return isTest() ? 'http://fluttercollective.test' : 'https://fluttercollective.com';
}
