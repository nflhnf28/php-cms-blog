<table class="table table-hover table-bordered">
  <thead>
    <tr>
      <th>Id</th>
      <th>Username</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Email</th>
      <th>Role</th>
    </tr>
  </thead>
  <im>
    <tr>
      <?php
      $query = "SELECT * FROM users";
      $select_users = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_users)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = ucfirst($row['user_role']);
        // $comment_date = $row['user_date'];

        echo "<tr>";
        echo "<td>{$user_id}</td>";
        echo "<td>{$username}</td>";
        echo "<td>{$user_firstname}</td>";

        echo "<td>{$user_lastname}</td>";
        echo "<td>{$user_email}</td>";
        echo "<td>{$user_role}</td>";


        // $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
        // $select_post_id_query = mysqli_query($connection, $query);
        // while($row = mysqli_fetch_assoc($select_post_id_query)){
        //   $post_id = $row['post_id'];
        //   $post_title = $row['post_title'];
        //   echo "<td><a href='../post.php?p_id={$post_id}'>{$post_title}</a></td>";
        // }

        echo "<td><a href='./users.php?change_to_admin={$user_id}'>Make as Admin</a></td>";
        echo "<td><a href='./users.php?change_to_sub={$user_id}'>Make as Subscriber</a></td>";
        echo "<td><a href='./users.php?source=edit_user&p_id={$user_id}'>Edit</a></td>";
        echo "<td><a href='./users.php?delete={$user_id}'>Delete</a></td>";
        echo "</tr>";
      }
      ?>
    </tr>
    </tbody>

</table>

<?php
if (isset($_GET['change_to_admin'])) {
  $the_user_id = $_GET['change_to_admin'];
  $query = "UPDATE users SET user_role = 'admin' WHERE user_id = {$the_user_id}";
  $change_to_admin_query = mysqli_query($connection, $query);
  header("Location: users.php");
}

if (isset($_GET['change_to_sub'])) {
  $the_user_id = $_GET['change_to_sub'];
  $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = {$the_user_id}";
  $change_to_sub_query = mysqli_query($connection, $query);
  header("Location: users.php");
}

if (isset($_SESSION['user_role'])) {
  if ($_SESSION['user_role'] == 'admin') {
    if (isset($_GET['delete'])) {
      $the_user_id = mysqli_real_escape_string($connection, $_GET['delete']);
      $query = "DELETE FROM users WHERE user_id = {$the_user_id}";
      $delete_user_query = mysqli_query($connection, $query);
      header("Location: users.php");
    }
  }
}

?>