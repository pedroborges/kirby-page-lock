<?php

load([
    'pedroborges\\pagelock\\lock' => __DIR__ . DS . 'src' . DS . 'Lock.php',
    'pedroborges\\pagelock\\locktrait' => __DIR__ . DS . 'src' . DS . 'LockTrait.php'
]);

function pageLock($page, $unique = null) {
    return PedroBorges\PageLock\Lock::instance($page, $unique);
}

if (site()->user()) {
    require __DIR__ . DS . 'src' . DS . 'routes.php';
}

kirby()->set('field', 'lock', __DIR__ . DS . 'fields' . DS . 'lock');
kirby()->set('field', 'title', __DIR__ . DS . 'fields' . DS . 'title');

