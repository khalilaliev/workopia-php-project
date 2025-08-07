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

    // Set user session 
    Session::set('user', [
      'id' => $user_id,
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'kanton' => $kanton
    ]);


    redirect('/');
  }

  /**
   * Logout user kill session
   * 
   * @return void
   */

  public function logout()
  {
    Session::clear_all('user');

    $params = session_get_cookie_params();

    setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

    redirect('/');
  }

  /**
   * Authenticate user with email and password
   * 
   * @return void
   */

  public function authenticate()
  {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

    if (!Validation::email($email)) {
      $errors['email'] = 'Please enter a valid email';
    }

    if (!Validation::string($password, 4, 50)) {
      $errors['password'] = 'Password must be at least 6 characters';
    }

    //Check for errors
    if (!empty($errors)) {
      load_view('users/login', [
        'errors' => $errors
      ]);
      exit;
    }

    //Check for email
    $params = [
      'email' => $email
    ];

    $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

    if (!$user) {
      $errors['email'] = 'Incorrect credentials';
      load_view('users/login', [
        'errors' => $errors
      ]);
      exit;
    }

    //Check if password is correct

    if (!password_verify($password, $user->password)) {
      $errors['email'] = 'Password incorrect';
      load_view('users/login', [
        'errors' => $errors
      ]);
      exit;
    }

    //Set user session
    Session::set('user', [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'city' => $user->city,
      'kanton' => $user->kanton
    ]);


    redirect('/');
  }
}
