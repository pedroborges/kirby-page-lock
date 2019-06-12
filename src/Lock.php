<?php

namespace PedroBorges\PageLock;

use C;
use F;
use Exception;
use Str;

class Lock
{
    protected static $instance = null;

    protected $data = [];
    protected $page;
    protected $pingInterval;
    protected $root;
    protected $translations;
    protected $uniqueId;

    public function __construct($page, $uniqueId = null)
    {
        if (! is_a($page, 'Page')) {
            $id = $page;

            if (! $page = page($page)) {
                throw new Exception("The page [{$id}] could not be found.");
            }
        }

        $this->page = $page;
        $this->pingInterval = c::get('page-lock.interval', 10);
        $this->translations = require __DIR__ . DS . 'translations.php';
        $this->root = kirby()->roots()->plugins() . DS . 'page-lock';
        $this->uniqueId = $uniqueId;

        static::$instance = $this;
    }

     /**
     * Create a new Lock instance.
     *
     * @param  \Page|string  $page
     * @return  Lock
     */
    public static function instance($page = null, $uniqueId = null)
    {
        return static::$instance = is_null(static::$instance)
            ? new static($page, $uniqueId)
            : static::$instance;
    }

    /**
     * Returns lock data.
     *
     * @return  array
     */
    public function data()
    {
        if (is_null($this->data)) $this->readData();

        return $this->data;
    }

    /**
     * Returns the current page.
     *
     * @return  \Page
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * Plugin's root path.
     *
     * @return  string
     */
    public function root()
    {
        return $this->root;
    }

    /**
     * Adds a lock for the current page.
     *
     * @return  boolean
     */
    public function lock()
    {
        if (site()->user()) {
            // Prevent another editor to lock the current page
            if ($this->hasLock() && ! $this->hasExpired() && ! $this->isEditor()) {
                return false;
            }

            $key = $this->key();

            $lock = [
                $key => [
                    "editor" => site()->user()->username(),
                    "locked_at" => time()
                ]
            ];

            $this->writeData($lock);

            return true;
        }

        return false;
    }

    /**
     * Returns page status.
     *
     * @return  boolean
     */
    public function isLocked() {
        if ($this->hasLock() && site()->user()) {
            // Avoid locking the editor out
            if ($this->hasExpired() || $this->isEditor()) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Returns inverted page status.
     *
     * @return  boolean
     */
    public function isNotLocked() {
        return ! $this->isLocked();
    }

    /**
     * Gets the editor User object.
     *
     * @return  \User|null
     */
    public function editor() {
        if (! $this->hasLock()) return null;

        $username = $this->data()[$this->key()]['editor'];

        return site()->user($username);
    }

    /**
     * Gets the editor full name.
     *
     * @return  string
     */
    public function editorName() {
        $editor = $this->editor();

        if (is_null($editor)) return null;

        $name = $editor->username();

        if ($editor && $editor->firstname()) {
            $name  = $editor->firstname();
            $name .= ! empty($editor->lastname())
                        ? ' ' . $editor->lastname()
                        : '';
        }

        return $name;
    }

    /**
     * Renders message string.
     *
     * @return  string
     */
    public function message($language = null) {
        $translation = $this->translation($language);
        $name = $this->editorName();

        return str::template($translation, compact('name'));
    }

    /**
     * Renders plugin script.
     *
     * @return  array
     */
    public function script($state = []) {
        $state = json_encode($this->state($state));
        $rawScript = f::read($this->root() . DS . 'assets' . DS . 'lock.js');

        $script = str_replace(
            "// lockState",
            "window.lockState = {$state}",
            $rawScript
        );

        return "<script>{$script}</script>";
    }

    /**
     * Returns lock state.
     *
     * @return  array
     */
    public function state($override = []) {
        $language = site()->multilang() ? site()->language()->code() : null;

        return array_merge([
            'isLocked' => $this->isLocked(),
            'language' => $language,
            'page' => $this->page()->id(),
            'pingInterval' => $this->pingInterval * 1000,
            'uniqueId' => null
        ], $override);
    }

    /**
     * Returns absolute data file path.
     *
     * @return  string
     */
    protected function filepath() {
        return $this->root() . DS . 'lock.json';
    }

    /**
     * Checks if the current page lock has expired.
     *
     * @return  boolean
     */
    protected function hasExpired() {
        $lockedAt = $this->data()[$this->key()]['locked_at'];
        $sinceLastPing = time() - $lockedAt;

        return $sinceLastPing > $this->pingInterval;
    }

    /**
    * Checks if the current page has been locked.
    *
    * @return  boolean
    */
    protected function hasLock() {
        return key_exists($this->key(), $this->data());
    }

    /**
    * Checks if current user is the
    * editor of the current page.
    *
    * @return  boolean
    */
    protected function isEditor() {
        return site()->user() == $this->editor();
    }

    /**
    * Generates an unique key for the lock.
    *
    * @return  string
    */
    protected function key()
    {
        $key = $this->page()->id();

        if (site()->multilang()) {
            $lang = get('language') ?: site()->language()->code();
            $key .= $lang;
        }

        if (! is_null($this->uniqueId)) {
            $key .= $this->uniqueId;
        }

        return md5($key);
    }

    /**
     * Reads JSON data from lock file.
     *
     * @return  array
     */
    protected function readData() {
        if (file_exists($this->filepath())) {
            $jsonData = file_get_contents($this->filepath());
            $this->data = json_decode($jsonData, true);
        } else {
            $this->data = [];
        }

        return $this->data;
    }

    /**
     * Persists lock data.
     *
     * @return  array
     */
    protected function writeData($data = [])
    {
        $this->removeOldData();

        $rawData = array_merge($this->data(), $data);
        $jsonData = json_encode($rawData);

        return file_put_contents(
            $this->filepath(),
            $jsonData,
            LOCK_EX
        ) ? true : false;
    }

    /**
    * Remove old data daily.
    *
    * @return  void
    */
    protected function removeOldData() {
        if (! key_exists('flushed_at', $this->data())) {
            $this->data['flushed_at'] = time();
        } elseif (time() - $this->data['flushed_at'] > 86400) {
            foreach ($this->data as $key => $value) {
                if ($key === 'flushed_at') continue;

                if (time() - $value['locked_at'] > $this->pingInterval) {
                    unset($this->data[$key]);
                }
            }

            $this->data['flushed_at'] = time();
        }
    }

    /**
    * Gets localized lock message.
    *
    * @return  string
    */
    protected function translation($language) {
        if (is_null($language) && site()->user()) {
            // User prefered language
            $language = site()->user()->language();
        } elseif (is_null($language) && site()->multilang()) {
            // Site's current language
            $language = site()->language()->code();
        }

        if (! key_exists($language, $this->translations)) {
            // Defaults to English
            $language = 'en';
        }

        return $this->translations[$language];
    }
}
