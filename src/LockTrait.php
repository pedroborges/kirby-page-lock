<?php

namespace PedroBorges\PageLock;

use Tpl;

trait LockTrait
{
    /**
     * Sets Lock instance.
     *
     * @return  Lock
     */
    protected function pageLock()
    {
        return pageLock($this->page());
    }

    /**
     * Gets localized lock message.
     *
     * @return  string
     */
    protected function data()
    {
        return [
            'field' => 'lock',
            'state' => json_encode($this->pageLock()->scriptState())
        ];
    }

    /**
     * Gets localized lock message.
     *
     * @return  string
     */
    protected function message()
    {
        $message = $this->pageLock()->message(panel()->translation()->code());

        return tpl::load(__DIR__ . DS . 'template.php', compact('message'));
    }

    /**
     * Gets Lock script.
     *
     * @return  string
     */
    protected function script()
    {
        return $this->pageLock()->script();
    }

}
