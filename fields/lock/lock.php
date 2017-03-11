<?php

class LockField extends BaseField
{
    use PedroBorges\PageLock\LockTrait;

    /**
     * Panel assets.
     *
     * @var array
     */
    static public $assets = [
        'js' => [
            'lock.js'
        ]
    ];

    /**
     * Build field template.
     *
     * @return string
     */
    public function template()
    {
        return $this->element()
                    ->append($this->message())
                    ->append($this->script())
                    ->data($this->data());
    }

}
