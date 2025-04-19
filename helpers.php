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

function load_view($name)
{
  $view_path = base_path("views/{$name}.view.php");

  // inspect($view_path);

  if (file_exists($view_path)) {
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

function load_partial($name)
{
  $view_partial = base_path("views/partials/{$name}.php");
  if (file_exists($view_partial)) {
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
