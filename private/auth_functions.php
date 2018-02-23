<?php

// Performs all actions necessary to log in an admin
function log_in_admin($admin) {
// Regenerating the ID protects the admin from session fixation.
    session_regenerate_id();
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['last_login'] = time();
    $_SESSION['username'] = $admin['username'];
    return true;
}

function log_out_admin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['last_login']);
    unset($_SESSION['username']);
    // session_destroy(); // optional: destroys the whole session
    return true;
}

function log_in_user($user) {
// Regenerating the ID protects the admin from session fixation.
    session_regenerate_id();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['last_login'] = time();
    $_SESSION['username'] = $user['username'];
    set_cookie_at_login($user['id']);
    return true;
}

function log_out_user() {
    unset($_SESSION['user_id']);
    unset($_SESSION['last_login']);
    unset($_SESSION['username']);
    // session_destroy(); // optional: destroys the whole session
    unset_cookie_at_logout();

    return true;
}

// is_logged_in() contains all the logic for determining if a
// request should be considered a "logged in" request or not.
// It is the core of require_login() but it can also be called
// on its own in other contexts (e.g. display one link if an admin
// is logged in and display another link if they are not)
function is_logged_in() {
    // Having a admin_id in the session serves a dual-purpose:
    // - Its presence indicates the admin is logged in.
    // - Its value tells which admin for looking up their record.

    // Check to see if a valid cookie exists - if yes, log the user in
    if(isset($_COOKIE['user_id'])) {
        $user = find_user_by_id($_COOKIE['user_id']);
        log_in_user($user);
    }

    return isset($_SESSION['user_id']);
}

// Call require_login() at the top of any page which needs to
// require a valid login before granting access to the page.
function require_login() {
    if(!is_logged_in()) {
        redirect_to(url_for('/login.php'));
    } else {
        // Do nothing, let the rest of the page proceed
    }
}

function set_cookie_at_login($user_id) {
    $expires = time() + 60*60*24*365; // set expiry to 1 year
    setcookie(
      'user_id',
      $user_id,
      $expires,
      '/',
      'bfn100.tinybot.ca',
      false,
      true
    );
}

function unset_cookie_at_logout() {
    $expires = time() + 60*60*24*365; // set expiry to 1 year
    setcookie(
        'user_id',
        null,
        time() - 3600,
        '/',
        'bfn100.tinybot.ca',
        false,
        true
    );
}

function is_profile_owner_or_admin($profile_id) {
  if (is_admin($_SESSION['user_id'])) {
    return true;
  } elseif($profile_id == $_SESSION['user_id']) {
    return true;
  } else {
    return false;
  }
}