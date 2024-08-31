<?php

if (isset($_GET['p_id'])) {
  $the_user_id = $_GET['p_id'];

  $query = "SELECT * FROM users WHERE user_id = $the_user_id";
  $select_user_by_id = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($select_user_by_id)) {
    $user_id = $row['user_id'];
    $username = $row['username'];
    $user_password = $row['user_password'];
    $user_firstname = $row['user_firstname'];
    $user_lastname = $row['user_lastname'];
    $user_email = $row['user_email'];
    // $user_image = $row['user_image'];
    $user_role = $row['user_role'];
  }


  // if (empty($post_image)) {
  //   $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
  //   $select_image = mysqli_query($connection, $query);
  //   while ($row = mysqli_fetch_array($select_image)) {
  //     $post_image = $row['post_image'];
  //   }
  // }

  if (isset($_POST['edit_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];
    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    if (!empty($user_password)) {
      $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
      $get_user_query = mysqli_query($connection, $query_password);
      confirmQuery($get_user_query);

      $row = mysqli_fetch_array($get_user_query);
      $db_user_password = $row['user_password'];

      if ($db_user_password != $user_password) {
        $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
      }

      $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password)";

      $query = "UPDATE users SET ";
      $query .= "user_firstname = '{$user_firstname}', ";
      $query .= "user_lastname = '{$user_lastname}', ";
      $query .= "user_role = '{$user_role}', ";
      $query .= "username = '{$username}', ";
      $query .= "user_email = '{$user_email}', ";
      $query .= "user_password = '{$hashed_password}' ";
      $query .= "WHERE user_id = {$the_user_id}";

      $edit_user_query = mysqli_query($connection, $query);
      confirmQuery($edit_user_query);

      echo "User updated: " . " " . "<a href='users.php'>View Users</a> ";
    }

    // $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10));
    // $salt = '$2y$10$iusesomecrazystrings22';
    // $hashed_password = crypt($user_password, $salt);
    // $post_image = $_FILES['post_image']['name'];
    // $post_image_temp = $_FILES['post_image']['tmp_name'];
    // $post_date = date('d-m-y');

    // Move uploaded image to images folder
    // move_uploaded_file($post_image_temp, "../images/$post_image");
  }
} else {
  header("Location: index.php");
}
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="user_firstname">First Name</label>
    <input type="text" class="form-control" name="user_firstname" value="<?php echo $user_firstname; ?>">
  </div>

  <div class="form-group">
    <label for="user_lastname">Last Name</label>
    <input type="text" class="form-control" name="user_lastname" value="<?php echo $user_lastname; ?>">
  </div>

  <div class="form-group">
    <label for="user_role">User Role</label>
    <select name="user_role" class="form-control">
      <option value="<?php echo $user_role; ?>"><?php echo ucfirst($user_role); ?></option>
      <?php

      if ($user_role == 'admin') {
        echo "<option value='subscriber'>Subscriber</option>";
      } else {
        echo "<option value='admin'>Admin</option>";
      }
      ?>
    </select>
  </div>

  <!-- <div class="form-group">
    <label for="post_image">Post Image</label>
    <input type="file" class="form-control" name="post_image">
  </div> -->

  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
  </div>

  <div class="form-group">
    <label for="user_email">Email</label>
    <input type="email" class="form-control" name="user_email" value="<?php echo $user_email; ?>">
  </div>

  <div class="form-group">
    <label for="user_password">Password</label>
    <input type="password" class="form-control" name="user_password" autocomplete="off">
  </div>

  <div class="form-group">
    <button class="btn btn-primary" type="submit" name="edit_user">Edit User</button>
  </div>

</form>