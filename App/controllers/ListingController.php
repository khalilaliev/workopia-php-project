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

  public function store()
  {
    $allowed_fields = ['title', 'description', 'salary', 'tags', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email'];
    $new_listing_data = array_intersect_key($_POST, array_flip($allowed_fields));
    $new_listing_data['user_id'] = 1;
    $new_listing_data = array_map('sanitize', $new_listing_data);
    $required_field = ['title', 'description', 'email', 'city', 'state'];
    $errors = [];

    foreach ($required_field as $field) {
      if (empty($new_listing_data[$field]) || !Validation::string($new_listing_data[$field], 1, 100)) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (!empty($errors)) {
      load_view('listings/create', [
        'errors' => $errors,
        'listing' => $new_listing_data
      ]);
    } else {

      $fields = [];

      foreach ($new_listing_data as $field => $value) {
        $fields[] = $field;
      }

      $fields = implode(', ', $fields);

      $values = [];

      foreach ($new_listing_data as $field => $value) {
        if ($value === '') {
          $new_listing_data[$field] = null;
        }

        $values[] = ':' . $field;
      }

      $values = implode(', ', $values);

      $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

      $this->db->query($query, $new_listing_data);

      redirect('/listings');
    }
  }

  public function destroy($params): void
  {

    $id = $params['id'];

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if (!$listing) {
      ErrorController::not_found('Listing not found');
      return;
    }

    $this->db->query('DELETE FROM listings WHERE id = :id', $params);

    // Flash Message

    $_SESSION['success_message'] = 'Listing deleted successfully';

    redirect('/listings');
  }

  public function edit($params): void
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

    load_view('listings/edit', [
      'listing' => $listing,
    ]);
  }

  public function update($params)
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

    $allowed_fields = ['title', 'description', 'salary', 'tags', 'requirements', 'benefits', 'company', 'address', 'city', 'state', 'phone', 'email'];
    $update_values = [];
    $update_values = array_intersect_key($_POST, array_flip($allowed_fields));

    $update_values = array_map('sanitize', $update_values);

    $required_fields = ['title', 'description', 'city', 'state', 'salary', 'email'];

    $errors = [];

    foreach ($required_fields as $field) {
      if (empty($update_values[$field]) || !Validation::string($update_values[$field], 1, 100)) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (!empty($errors)) {
      load_view('listings/edit', [
        'listing' => $listing,
        'errors' => $errors
      ]);
      exit;
    } else {
      // Submit to DB
      $update_fields = [];

      foreach (array_keys($update_values) as $field) {
        $update_fields[] = "{$field} = :{$field}";
      }

      $update_fields = implode(', ', $update_fields);

      $update_values['id'] = $id;

      $update_query = "UPDATE listings SET $update_fields WHERE id = :id";

      $this->db->query($update_query, $update_values);

      $_SESSION['success_message'] = 'Listing updated';

      redirect('/listings/' . $id);
    }
  }
}
