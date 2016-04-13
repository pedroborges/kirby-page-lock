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

class PagelockField extends BaseField {

  /**
   * Interval to ping server for locking
   * and checking status (in seconds)
   *
   * @var int
   */
  protected $pingTime;

  /**
   * Default message displayed to end user.
   *
   * @var string
   */
  protected $message = "<strong>%s</strong> is editing this page.
    <br><br>Stay here to be notified when it's your turn.";

  /**
   * Panel assets.
   *
   * @var array
   */
  static public $assets = [
    'js' => [
      'pagelock.js'
    ]
  ];

  /**
   * Set initial configuration.
   *
   * @return null
   */
  public function __construct() {
    $this->pingTime = c::get('fields.pagelock.time', 8); // seconds
  }

  /**
  * Define routes.
  *
  * @return array
  */
  public function routes() {
    return [
      [
        'pattern' => 'status',
        'method'  => 'GET',
        'action'  => 'status',
      ],
      [
        'pattern' => 'lock',
        'method'  => 'POST',
        'action'  => 'lock',
      ],
      [
        'pattern' => 'unlock',
        'method'  => 'POST',
        'action'  => 'unlock',
      ]
    ];
  }


  /**
   * Get editor's username from lock file.
   *
   * @return string
   */
  protected function editor() {
    if (file_exists($this->filepath())) {
      return file_get_contents($this->filepath());
    }

    return 'unknown';
  }

  /**
   * Get full .lock file path
   *
   * @return string
   */
  protected function filepath() {
    $filepath = $this->page()->root() . DS . '.lock';

    if (site()->multilang()) {
      $filepath .= '.' . site()->language();
    }

    return $filepath;
  }


  /**
   * Get localized message.
   *
   * @return string
   */
  protected function message() {
    $editor  = $this->editor();
    $message = l::get('fields.pagelock.message', $this->message);

    return sprintf($message, $editor);
  }

  /**
   * Build field template.
   *
   * @return string
   */
  public function template() {
    return $this->element()
                ->data([
                  'lock-time' => $this->pingTime - 2,
                  'ping-time' => $this->pingTime,
                  'field'     => 'pagelock',
                  'message'   => $this->message(),
                  'name'      => $this->name(),
                ]);
  }

}
