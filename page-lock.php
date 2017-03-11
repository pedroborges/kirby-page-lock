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

kirby()->set(
    'blueprint',
    'fields/lock', __DIR__ . DS . 'blueprints' . DS . 'fields' . DS . 'lock.yml'
);

kirby()->set('snippet', 'page-lock', __DIR__ . DS . 'snippets' . DS . 'page-lock.php');

kirby()->set('page::method', 'isLocked', function($page, $unique = null) {
    return pageLock($page, $unique)->isLocked();
});

kirby()->set('page::method', 'isNotLocked', function($page, $unique = null) {
    return pageLock($page, $unique)->isNotLocked();
});

kirby()->set('page::method', 'pageLock', function($page, $unique = null) {
    return pageLock($page, $unique);
});
