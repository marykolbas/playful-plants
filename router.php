<?php
require_once('includes/db.php');
//open database, taken and deleted from other pages
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

include_once('includes/sessions.php');
$session_messages=array();
process_session_params($db, $session_messages);

//my own function, based on is_user_member_of function from sessions.php
// is the user an admin?
function is_user_admin($db)
{
  global $current_user;
  if ($current_user === NULL) {
    return False;
  }

  $records = exec_sql_query(
    $db,
    "SELECT id FROM users WHERE (isadmin = 1) AND (id = :user_id);",
    array(
      ':user_id' => $current_user['id']
    )
  )->fetchAll();
  if ($records) {
    return True;
  } else {
    return False;
  }
}

$is_admin = is_user_admin($db);

////////////////////////////////////////////////////

function match_routes($uri, $routes)
{
  if (is_array($routes)) {
    foreach ($routes as $route) {
      if (($uri == $route) || ($uri == $route . '/')) {
        return True;
      }
    }
    return False;
  } else {
    return ($uri == $routes) || ($uri == $routes . '/');
  }
}

// Grabs the URI and separates it from query string parameters
error_log('');
error_log('HTTP Request: ' . $_SERVER['REQUEST_URI']);
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

if (preg_match('/^\/public\//', $request_uri) || $request_uri == '/favicon.ico') {
  // let the web server respond for static resources
  return False;
} else if (match_routes($request_uri, '/')) {
  require 'pages/home.php';
} else if (match_routes($request_uri, '/admin')) {
  require 'pages/admin_home.php';
} else if(match_routes($request_uri, '/admin_plant')){
  require 'pages/admin_plant.php';
} else if(match_routes($request_uri, '/plant')){
  require 'pages/plant.php';
}else if(match_routes($request_uri, '/addplant')){
  require 'pages/addplant.php';
}else if(match_routes($request_uri, '/login')){
  require 'pages/login.php';
}else {
  error_log("  404 Not Found: " . $request_uri);
  http_response_code(404);
  require 'pages/404.php';
}
