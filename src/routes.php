<?php

kirby()->set('route', [
    'pattern' => 'page-lock/lock',
    'method'  => 'POST',
    'action'  => function () {
        $unique = get('unique') ?: null;
        $pageLock = pageLock(get('page'), $unique);
        $isLocked = $pageLock->lock();

        return response::json($pageLock->state([
            'isLocked' => $isLocked
        ]));
    }
]);

kirby()->set('route', [
    'pattern' => 'page-lock/status',
    'method'  => 'GET',
    'action'  => function () {
        $unique = get('unique') ?: null;
        $pageLock = pageLock(get('page'), $unique);

        return response::json($pageLock->state());
    }
]);
