<?php

kirby()->set('route', [
    'pattern' => 'page-lock/lock',
    'method'  => 'POST',
    'action'  => function () {
        $uniqueId = get('uniqueId') ?: null;
        $pageLock = pageLock(get('page'), $uniqueId);
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
        $uniqueId = get('uniqueId') ?: null;
        $pageLock = pageLock(get('page'), $uniqueId);

        return response::json($pageLock->state());
    }
]);
