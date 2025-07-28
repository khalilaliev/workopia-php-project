<?php

namespace App\controllers;

use Framework\Validation;
use Framework\Database;
use Framework\Session;

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

  /**
   * Store User to DB
   * 
   * @return void
   */

  public function store()
  {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $kanton = $_POST['kanton'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    $errors = [];

    //Validation email

    if (!Validation::email($email)) {
      $errors['email'] = 'Please enter a valid email';
    }

    if (!Validation::string($name, 2, 50)) {
      $errors['name'] = 'Name must be between 2 and 50 characters';
    }

    if (!Validation::string($name, 6, 50)) {
      $errors['password'] = 'Name must be at list 6 characters';
    }

    if (!Validation::match($password, $password_confirmation)) {
      $errors['password_confirmation'] = 'Password do not match';
    }

    if (!empty($errors)) {
      load_view('users/register', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'kanton' => $kanton,
        ]
      ]);
      exit;
    }

    // Check if email exist

    $params = [
      'email' => $email
    ];

    $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

    if ($user) {
      $errors['email'] = 'Email already exists';
      load_view('users/register', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'kanton' => $kanton,
        ]
      ]);
      exit;
    }

    // Create user acc

    $params = [
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'kanton' => $kanton,
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    $this->db->query('INSERT INTO users (name, email, city, kanton, password) VALUES (:name, :email, :city, :kanton, :password)', $params);

    // Get User ID

    $user_id = $this->db->conn->lastInsertId();

    Session::set('user', [
      'id' => $user_id,
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'kanton' => $kanton
    ]);


    redirect('/');
  }
}
