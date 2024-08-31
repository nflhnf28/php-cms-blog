<?php

function queryFunc($query)
{
  global $connection;
  return mysqli_query($connection, $query);
}

function imagePlaceholder($image = '')
{
  if (!$image) {
    return '../images/image_1.jpg';
  } else {
    return $image;
  }
}

function redirect($location)
{
  header('Location: ' . $location);
  exit;
}

// function to check if method is POST/GET
function ifItIsMethod($method = null)
{
  if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
    return true;
  }
  return false;
}

// function to check if user is logged in
function isLoggedIn()
{
  if (isset($_SESSION['user_role'])) {
    return true;
  }
  return false;
}

// function to check if user liked this post
function userLikedThisPost($post_id)
{
  $result = queryFunc("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = " . loggedInUserId());
  return mysqli_num_rows($result) >= 1 ? true : false;
}

// function to check if user logged in and return user id
function loggedInUserId()
{
  if (isLoggedIn()) {
    $result = queryFunc("SELECT * FROM users WHERE username = '{$_SESSION['username']}'");
    confirmQuery($result);
    $user = mysqli_fetch_array($result);
    if (mysqli_num_rows($result) >= 1) {
      return $user['user_id'];
    }
  } else {
    return false;
  }
}

function getPostLikes($post_id)
{
  $result = queryFunc("SELECT * FROM likes WHERE post_id = '$post_id'");
  confirmQuery($result);
  return mysqli_num_rows($result);
}

// function to check if user logged in and redirect
function checkIfUserIsLoggedInAndRedirect($redirectLocation = null)
{
  if (isLoggedIn()) {
    redirect($redirectLocation);
  }
}

function currentUser()
{
  if (isset($_SESSION['username'])) {
    return $_SESSION['username'];
  }
  return false;
}

// function to escape for injection (security purposes)
function escape($string)
{
  global $connection;
  return mysqli_real_escape_string($connection, trim($string));
}

function register_user($username, $email, $password)
{
  global $connection;

  $username = mysqli_real_escape_string($connection, $username);
  $email = mysqli_real_escape_string($connection, $email);
  $password = mysqli_real_escape_string($connection, $password);
  // new system for hashing password
  $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

  // query to insert user to database table
  $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
  $query .= "VALUES ('{$username}', '{$email}', '{$password}', 'subscriber') ";

  $register_user_query = mysqli_query($connection, $query);
  confirmQuery($register_user_query);
}

function login_user($username, $password)
{
  global $connection;

  // trim() function to remove whitespace
  $username = trim($username);
  $password = trim($password);

  // real escape string to prevent sql injection
  $username = mysqli_real_escape_string($connection, $username);
  $password = mysqli_real_escape_string($connection, $password);

  $query = "SELECT * FROM users WHERE username = '{$username}' ";
  $select_user_query = mysqli_query($connection, $query);

  if (!$select_user_query) {
    die("QUERY FAILED" . mysqli_error($connection));
  }

  // while loop to check if user exists 
  while ($row = mysqli_fetch_array($select_user_query)) {
    $db_user_id = $row['user_id'];
    $db_username = $row['username'];
    $db_user_password = $row['user_password'];
    $db_user_firstname = $row['user_firstname'];
    $db_user_lastname = $row['user_lastname'];
    $db_user_role = $row['user_role'];

    // validation id & pass check
    // $username === $db_username && $password === $db_user_password
    if (password_verify($password, $db_user_password)) {
      $_SESSION['user_id'] = $db_user_id;
      $_SESSION['username'] = $db_username;
      $_SESSION['firstname'] = $db_user_firstname;
      $_SESSION['lastname'] = $db_user_lastname;
      $_SESSION['user_role'] = $db_user_role;

      redirect("/cms/admin");
    } else {
      return false;
    }
  } // end of while

}

// function to count rows in (table on database) parameter
function recordCount($table)
{
  global $connection;
  $query = "SELECT * FROM " . $table;
  $select_all_func_query = mysqli_query($connection, $query);

  $result = mysqli_num_rows($select_all_func_query);
  confirmQuery($result);
  return $result;
}

function getAllPostsUsersComments()
{
  return queryFunc("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=" . loggedInUserId() . "");
  // confirmQuery($result);
}

function getAllUsersCategories()
{
  return queryFunc("SELECT * FROM categories WHERE user_id=" . loggedInUserId() . "");
  // confirmQuery($result);
}

// refactoring function to check status (table, column, and the status on database) parameter
function checkStatus($table, $column, $status)
{
  global $connection;
  $query = "SELECT * FROM $table WHERE $column = '$status'";
  $check_status_query = mysqli_query($connection, $query);

  $result = mysqli_num_rows($check_status_query);
  return $result;
}

function getAllUserPublishedPost()
{
  $result = queryFunc("SELECT * FROM posts WHERE user_id=" . loggedInUserId() . " AND post_status = 'published'");
  confirmQuery($result);
  return $result;
}

function getAllUserDraftPost()
{
  $result = queryFunc("SELECT * FROM posts WHERE user_id=" . loggedInUserId() . " AND post_status = 'draft'");
  confirmQuery($result);
  return $result;
}

function getAllUserApprovedComments()
{
  $result = queryFunc("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=" . loggedInUserId() . " AND comment_status = 'approved'");
  confirmQuery($result);
  return $result;
}

function getAllUserUnapprovedComments()
{
  $result = queryFunc("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=" . loggedInUserId() . " AND comment_status = 'unapproved'");
  confirmQuery($result);
  return $result;
}

function checkUserRole($table, $column, $role)
{
  global $connection;
  $query = "SELECT * FROM $table WHERE $column = '$role'";
  $select_all_query = mysqli_query($connection, $query);
  confirmQuery($select_all_query);

  $result = mysqli_num_rows($select_all_query);
  return $result;
}

// function isAdmin to check if the user role is admin or not. If not, they can't access certain pages
function isAdmin()
{
  if (isLoggedIn()) {
    $result = queryFunc("SELECT user_role FROM users WHERE user_id=" . $_SESSION['user_id'] . "");
    confirmQuery($result);
    $row = fetchRecords($result);
    if ($row['user_role'] == 'admin') {
      return true;
    } else {
      return false;
    }
  } // end of isLoggedIn
  return false;
}

function fetchRecords($result)
{
  return mysqli_fetch_array($result);
}

function getUsername()
{
  return $_SESSION['username'] ? $_SESSION['username'] : "Subscriber";
}

function getAllUserPost()
{
  return queryFunc("SELECT * FROM posts WHERE user_id=" . loggedInUserId() . "");
}

function countRecords($result)
{
  return mysqli_num_rows($result);
}

// function to check if username exists (duplicate username)
function username_exists($username)
{
  global $connection;

  $query = "SELECT username FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $query);
  confirmQuery($result);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

// function to check if email exists (duplicate email)
function email_exists($email)
{
  global $connection;

  $query = "SELECT user_email FROM users WHERE user_email = '$email'";
  $result = mysqli_query($connection, $query);
  confirmQuery($result);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

function users_online()
{
  if (isset($_GET['onlineusers'])) {
    global $connection;
    if (!$connection) {
      session_start();
      include "../includes/db.php";

      $session = session_id();
      $time = time();
      $timeout_in_seconds = 30;
      $timeout = $time - $timeout_in_seconds;

      $query = "SELECT * FROM users_online WHERE session = '$session'";
      $send_online_query = mysqli_query($connection, $query);
      $count = mysqli_num_rows($send_online_query);

      if ($count == NULL) {
        mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
      } else {
        mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
      }
      $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$timeout'");
      echo $count_user = mysqli_num_rows($users_online_query);
    }
  } // get request isset
}

users_online();

function confirmQuery($result)
{
  global $connection;
  if (!$result) {
    die('Query Failed' . mysqli_error($connection));
  }
}

function insert_categories()
{
  global $connection;

  if (isset($_POST['submit'])) {
    $cat_title = $_POST['cat_title'];

    if ($cat_title == "" || empty($cat_title)) {
      echo "This field should not be empty";
    } else {
      $query = "INSERT INTO categories(cat_title) ";
      $query .= "VALUE ('{$cat_title}')";
      $create_category_query = mysqli_query($connection, $query);

      if (!$create_category_query) {
        die('Query Failed' . mysqli_error($connection));
      }
    }
  }
}

function find_all_categories()
{
  global $connection;
  $query = "SELECT * FROM categories";
  $select_categories = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($select_categories)) {
    $cat_id = $row['cat_id'];
    $cat_title = $row['cat_title'];

    echo "<tr>";
    echo "<td>{$cat_id}</td>";
    echo "<td>{$cat_title}</td>";
    echo "<td><a href='categories.php?delete={$cat_id}'>Delete</td>";
    echo "<td><a href='categories.php?edit={$cat_id}'>Edit</td>";
    echo "</tr>";
  }
}

function deleteCategories()
{
  global $connection;
  if (isset($_GET['delete'])) {
    $the_cat_id = $_GET['delete'];

    $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
    $delete_query = mysqli_query($connection, $query);
    header("Location: categories.php");
  }
}
