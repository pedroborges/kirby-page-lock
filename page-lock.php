<?php

load([
    'pedroborges\\pagelock\\lock' => __DIR__ . DS . 'src' . DS . 'Lock.php',
    'pedroborges\\pagelock\\locktrait' => __DIR__ . DS . 'src' . DS . 'LockTrait.php'
]);

function pageLock($page, $unique = null) {
    return PedroBorges\PageLock\Lock::instance($page, $unique);
}

kirby()->set('field', 'lock', __DIR__ . DS . 'fields' . DS . 'lock');
