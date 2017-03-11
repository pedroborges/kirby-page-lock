<?php

class TitleField extends TextField
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

    public function __construct()
    {
        $this->label = l::get('fields.title.label', 'Title');
        $this->icon = 'font';
        $this->lock = true;
        $this->required = true;
    }

    public function help()
    {
        if ($this->page && ! $this->page->isSite()) {
            if (! empty($this->help)) {
                $this->help  = $this->i18n($this->help);
                $this->help .= '<br />';
            }

            // build a readable version of the page slug
            $slug = ltrim($this->page->parent()->slug() . '/', '/') . $this->page->slug();

            $style = 'padding-left: .5rem; color: #777; border:none';

            if ($this->page->ui()->url() && $this->pageLock()->isNotLocked()) {
                $this->help .= '&rarr;<a style="' . $style . '" data-modal title="' . $this->page->url('preview') . '" href="' . $this->page->url('url') . '">' . $slug . '</a>';
            } else {
                $this->help .= '&rarr;<span style="' . $style . '" title="' . $this->page->url('preview') . '">' . $slug . '</span>';
            }

        }

        return parent::help();
    }

    /**
     * Build field template.
     *
     * @return string
     */
    public function template()
    {
        $element = parent::template();

        if ($this->lock()) {
            $element->append($this->message());
            $element->append($this->script());
            $element->data($this->data());
        }

        return $element;
    }

}
