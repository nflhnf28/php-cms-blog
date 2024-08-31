<?php
include "delete_modal.php";
?>

<?php

if (isset($_POST['checkBoxArray'])) {
  foreach ($_POST['checkBoxArray'] as $postValueId) {
    $bulk_options = $_POST['bulk_options'];

    switch ($bulk_options) {

      case 'published':
        $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}";
        $update_to_published_status = mysqli_query($connection, $query);
        confirmQuery($update_to_published_status);
        break;

      case 'draft':
        $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}";
        $update_to_draft_status = mysqli_query($connection, $query);
        confirmQuery($update_to_draft_status);
        break;

      case 'delete':
        $query = "DELETE FROM posts WHERE post_id = {$postValueId}";
        $delete_posts = mysqli_query($connection, $query);
        confirmQuery($delete_posts);
        break;

      case 'clone':
        $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}'";
        $select_posts_query = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_array($select_posts_query)) {
          // Escape each variable to protect against SQL injection
          $post_title = mysqli_real_escape_string($connection, $row['post_title']);
          $post_category_id = mysqli_real_escape_string($connection, $row['post_category_id']);
          $post_date = mysqli_real_escape_string($connection, $row['post_date']);
          $post_author = mysqli_real_escape_string($connection, $row['post_author']);
          $post_user = mysqli_real_escape_string($connection, $row['post_user']);
          $post_status = mysqli_real_escape_string($connection, $row['post_status']);
          $post_image = mysqli_real_escape_string($connection, $row['post_image']);
          $post_tags = mysqli_real_escape_string($connection, $row['post_tags']);
          $post_comment_count = mysqli_real_escape_string($connection, $row['post_comment_count']);
          $post_content = mysqli_real_escape_string($connection, $row['post_content']);

          if(empty($post_tags)){
            $post_tags = "No Tags";
          }

          if(empty($post_author)){
            $post_author = $post_user;
          }
        }

        $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_user, post_date, post_image, post_content, post_comment_count, post_tags, post_status) ";
        $query .= "VALUES($post_category_id, '{$post_title}', '{$post_author}', '{$post_user}', now(), '{$post_image}', '{$post_content}', {$post_comment_count}, '{$post_tags}', '{$post_status}')";

        $clone_query = mysqli_query($connection, $query);
        confirmQuery($clone_query);
        break;
    }
  }
}

?>

<form action="" method="post">

  <table class="table table-hover table-bordered d-grid">

    <div id="bulkOptionContainer" class="col-xs-4 mb-2">
      <select class="form-control" name="bulk_options">
        <option value="published">Publish</option>
        <option value="draft">Make it draft</option>
        <option value="delete">Delete</option>
        <option value="clone">Clone</option>
      </select>
    </div>

    <div class="col-xs-4 mb-2">
      <input type="submit" name="submit" class="btn btn-success" value="Apply">
      <a class="btn btn-primary" href="posts.php?source=add_post">Add New Post</a>
    </div>

    <thead>
      <tr>
        <th><input type="checkbox" id="selectAllBoxes"></th>
        <th>Id</th>
        <th>User</th>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Images</th>
        <th>Tags</th>
        <th>Comments</th>
        <th>Date</th>
        <th>View Post</th>
        <th>Edit</th>
        <th>Delete</th>
        <th>View Count</th>
      </tr>
    </thead>
    <im>
      <tr>
        <?php
        // $query = "SELECT * FROM posts ORDER BY post_id DESC";
        // $curr_user = currentUser();

        // query with posts join categories to display
        $query = "SELECT posts .*, categories .* ";
        $query .= "FROM posts LEFT JOIN categories ";
        $query .= "ON posts.post_category_id = categories.cat_id ";
        $query .= "ORDER BY posts.post_id DESC";
        $select_posts = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_posts)) {
          $post_id = $row['post_id'];
          $post_author = $row['post_author'];
          $post_user = $row['post_user'];
          $post_title = $row['post_title'];
          $post_category_id = $row['post_category_id'];
          $post_status = ucfirst($row['post_status']);
          $post_image = $row['post_image'];
          $post_tags = $row['post_tags'];
          $post_comment_count = $row['post_comment_count'];
          $post_date = $row['post_date'];
          $post_views_count = $row['post_views_count'];

          // This information from categories table on db
          $cat_id = $row['cat_id'];
          $cat_title = $row['cat_title'];

          echo "<tr>";
        ?>

          <td><input type='checkbox' class='checkBoxes' name="checkBoxArray[]" value="<?php echo $post_id; ?>"></td>

        <?php
          echo "<td>{$post_id}</td>";

          if(!empty($post_author)) {
            echo "<td>{$post_author}</td>";
          } elseif(!empty($post_user)) {
            echo "<td>{$post_user}</td>";
          }

          echo "<td>{$post_title}</td>";
          echo "<td>{$cat_title}</td>";
          echo "<td>{$post_status}</td>";
          echo "<td><img class='img-responsive' width='100' src='../images/{$post_image}'></td>";
          echo "<td>{$post_tags}</td>";

          // query for comments (dynamically display it)
          $query = "SELECT * FROM comments WHERE comment_post_id = {$post_id}";
          $send_comment_query = mysqli_query($connection, $query);
          $count_comments = mysqli_num_rows($send_comment_query);
          
          if ($count_comments > 0) {
            $row = mysqli_fetch_array($send_comment_query);
            $comment_id = $row['comment_id'];
          } else {
            $comment_id = null; // No comments found, set a default or handle accordingly
          }


          echo "<td><a href='./post_comments.php?pid={$post_id}'>{$count_comments}</a></td>";

          echo "<td>{$post_date}</td>";
          echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'>View Post</a></td>";
          echo "<td><a class='btn btn-info' href='./posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
          ?>

          <form action="" method="post">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <?php
            echo "<td><input class='btn btn-danger btn-s' type='submit' name='delete' value='Delete'></td>";
            ?>
          </form>

          <?php 
          // echo "<td><a rel='$post_id' href='javascript:void(0);' class='delete_link'>Delete</a></td>";


          // echo "<td><a onclick=\"javascript: return confirm('Are you sure you want to delete?');\" href='./posts.php?delete={$post_id}'>Delete</a></td>";
          echo "<td><a href='./posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
          echo "</tr>";
        }
        ?>
      </tr>
      </tbody>
  </table>
</form>

<?php
if (isset($_POST['delete'])) {
  $the_post_id = escape($_POST['post_id']);
  $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
  $delete_query = mysqli_query($connection, $query);
  header("Location: posts.php");
}

if (isset($_GET['reset'])) {
  $the_post_id = $_GET['reset'];
  $query = "UPDATE posts SET post_views_count = 0 WHERE post_id =" . mysqli_real_escape_string($connection, $_GET['reset']) . "";
  $reset_views_count = mysqli_query($connection, $query);
  header("Location: posts.php");
}

?>

<script>
  $(document).ready(function() {
    $('.delete_link').on('click', function() {
      var id = $(this).attr("rel");
      var delete_url = "posts.php?delete=" + id + "";

      $(".modal-delete-link").attr("href", delete_url);
      $("#myModal").modal('show');
    });
  });
</script>