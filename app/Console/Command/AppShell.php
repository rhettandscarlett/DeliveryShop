<?php

/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {

  var $lockPath;
  var $lockFile;

  public function __construct($stdout = null, $stderr = null, $stdin = null) {
    parent::__construct($stdout, $stderr, $stdin);
    $this->lockPath = ROOT . '/app/Console/Lock/';
  }

  function createLock() {
    $command = is_null($this->command) ? 'main' : $this->command;
    $this->lockFile = $this->lockPath . get_class($this) . '-' . $command;
    if (is_file($this->lockFile)) {
      die("This is a running instant - " . get_class($this) . " - " . $command . "\n");
    }
    file_put_contents($this->lockFile, '');
  }

  function deleteLock() {
    if (is_file($this->lockFile)) {
      unlink($this->lockFile);
    }
  }

}
