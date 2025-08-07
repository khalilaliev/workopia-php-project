<?php

namespace Framework\middleware;

use Framework\Session;

class Authorize
{
  /**
   * Handle users param
   * 
   * @return bool
   */
  public function is_authenticated()
  {
    return Session::has('user');
  }


  /**
   * Handle users param
   * 
   * @param string $role
   * @return bool
   */
  public function handle($role)
  {
    if ($role === 'guest' && $this->is_authenticated()) {
      return redirect('/');
    } else if ($role === 'auth' && !$this->is_authenticated()) {
      return redirect('/auth/login');
    }
  }
}
