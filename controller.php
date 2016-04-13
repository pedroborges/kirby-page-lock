<?php

/**
 * Page Lock Field for Kirby CMS
 *
 * @version   0.1.0
 * @author    Pedro Borges <oi@pedroborg.es>
 * @copyright Pedro Borges <oi@pedroborg.es>
 * @link      https://github.com/pedroborges/kirby-pagelock
 * @license   <https://github.com/pedroborges/kirby-pagelock/blob/master/license.md>
 */

class PagelockFieldController extends Kirby\Panel\Controllers\Field {

  /**
   * Return the lock status of the page.
   *
   * @return object
   */
  public function status() {
    $pingTime = c::get('fields.pagelock.time', 8) + 2;

    if ($this->isLocked()) {
      if ($this->lastModified() > $pingTime || $this->isEditor()) {
        // Avoid locking the editor out
        $this->unlock();
        return response::json(json_encode(false));
      }

      return response::json(json_encode(true));
    }

    return response::json(json_encode(false));
  }

  /**
   * Create .lock file on the current page root.
   *
   * @return null
   */
  public function lock() {
    file_put_contents($this->filepath(), site()->user());
  }

  /**
   * Delete .lock file.
   *
   * @return null
   */
  public function unlock() {
    unlink($this->filepath());
  }

  /**
   * Verify if .lock file exists.
   *
   * @return boolean
   */
  protected function isLocked() {
    return file_exists($this->filepath());
  }

  /**
   * Verify if the user is also the
   * editor of the current locked page.
   *
   * @return boolean
   */
  protected function isEditor() {
    $editor = file_get_contents($this->filepath());

    return site()->user() == $editor;
  }

  /**
   * Get full .lock file path
   *
   * @return string
   */
  protected function filepath() {
    $filepath = $this->model()->root() . DS . '.lock';

    if (site()->multilang()) {
      $filepath .= '.' . site()->language();
    }

    return $filepath;
  }

  /**
   * How long (in seconds) since this page
   * was last pinged by the editor.
   *
   * @return int
   */
  protected function lastModified() {
    return time() - filemtime($this->filepath());
  }

}
