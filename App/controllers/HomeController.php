<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
  protected $db;

  public function __construct()
  {
    $config = require base_path('config/db.php');
    $this->db = new Database($config);
  }


  public function index(): void
  {
    $listings = $this->db->query('SELECT * FROM listings ORDER by created_at DESC LIMIT 6')->fetchAll();
    load_view('home', [
      'listings' => $listings
    ]);
  }
}
