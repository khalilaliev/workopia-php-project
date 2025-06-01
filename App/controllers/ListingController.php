<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
  protected $db;

  public function __construct()
  {
    $config = require base_path('config/db.php');
    $this->db = new Database($config);
  }


  public function index(): void
  {

    $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
    load_view('/listings/index', [
      'listings' => $listings
    ]);
  }

  public function create(): void
  {
    load_view('/listings/create');
  }

  public function show($params): void
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if (!$listing) {
      ErrorController::not_found('Listing not found');
      return;
    }


    load_view('listings/show', [
      'listing' => $listing,
    ]);
  }
}
