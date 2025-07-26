<?php

/**
 * get the base path 
 * 
 * @param string $path
 * @return string
 */

function base_path($path = ''): string
{
  return __DIR__ . '/' . $path;
}

/**
 * load a view
 * 
 * @param string $name
 * @return void
 */

function load_view($name, $data = [])
{
  $view_path = base_path("App/views/{$name}.view.php");

  // inspect($view_path);

  if (file_exists($view_path)) {
    extract($data);

    require $view_path;
  } else {
    echo "Path '{$name} not found!'";
  }
}

/**
 * load a partial
 * 
 * @param string $name
 * @return void
 */

function load_partial($name, $data = [])
{
  $view_partial = base_path("App/views/partials/{$name}.php");
  if (file_exists($view_partial)) {
    extract($data);
    require $view_partial;
  } else {
    echo " Path '{$name} not found!'";
  }
}

/**
 * Inspect a value
 * 
 * @param mixed $value
 * @return void
 */

function inspect($value)
{
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
}

/**
 * Inspect a value and die
 * 
 * @param mixed $value
 * @return void
 */

function inspect_and_die($value)
{
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
  die();
}

/**
 * Format Salary
 * 
 * @param string $salary
 * @return string
 */

function format_salary($salary)
{
  return '$' . number_format(floatval($salary));
}

/**
 * Sanitize Data
 * 
 * @param string $dirty
 * @return string
 */

function sanitize($dirty)
{
  return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}


/**
 * Redirect to url
 * 
 * @param string $url
 * @return void
 */

function redirect($url)
{
  header("Location: {$url}");
  exit;
}
