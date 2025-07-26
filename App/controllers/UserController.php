<?php

namespace App\controllers;

use Framework\Validation;
use Framework\Database;

class UserController
{
  protected $db;

  public function __construct()
  {
    $config = require base_path('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Show login page
   * 
   * @return void
   */

  public function login()
  {
    load_view('users/login');
  }

  /**
   * Show register page
   * 
   * @return void
   */

  public function register()
  {
    load_view('users/register');
  }
}
