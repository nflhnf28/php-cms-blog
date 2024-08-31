<?php

if (isset($_GET['p_id'])) {
  $the_post_id = $_GET['p_id'];
}
$query = "SELECT * FROM posts WHERE post_id = $the_post_id";
$select_posts_by_id = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($select_posts_by_id)) {
  $post_id = $row['post_id'];
  $post_user = $row['post_user'];
  $post_title = $row['post_title'];
  $post_category_id = $row['post_category_id'];
  $post_status = $row['post_status'];
  $post_image = $row['post_image'];
  $post_content = $row['post_content'];
  $post_tags = $row['post_tags'];
  $post_comment_count = $row['post_comment_count'];
  $post_date = $row['post_date'];
}

if (isset($_POST['update_post'])) {
  $post_user = $_POST['post_user'];
  $post_title = $_POST['post_title'];
  $post_category_id = $_POST['post_category'];
  $post_status = $_POST['post_status'];
  $post_image = $_FILES['image']['name'];
  $post_image_temp = $_FILES['image']['tmp_name'];
  $post_content = $_POST['post_content'];
  $post_tags = $_POST['post_tags'];
  
move_uploaded_file($post_image_temp, "../images/$post_image");

  if (empty($post_image)) {
    $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
    $select_image = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_array($select_image)) {
      $post_image = $row['post_image'];
    }
  }
  
  $query = "UPDATE posts SET ";
  $query .= "post_title = '" . addslashes($post_title) . "', ";
  $query .= "post_category_id = '" . addslashes($post_category_id) . "', ";
  $query .= "post_date = now(), ";
  $query .= "post_user = '" . addslashes($post_user) . "', ";
  $query .= "post_status = '" . addslashes($post_status) . "', ";
  $query .= "post_tags = '" . addslashes($post_tags) . "', ";
  $query .= "post_content = '" . addslashes($post_content) . "', ";
  $query .= "post_image = '" . addslashes($post_image) . "' ";
  $query .= "WHERE post_id = {$the_post_id}";

  $update_post = mysqli_query($connection,$query);
  confirmQuery($update_post);
  echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$the_post_id}'>View post</a> or <a href='posts.php'>Edit other posts</a></p>";
}
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="title">Post Title</label>
    <input type="text" class="form-control" name="post_title" value="<?php echo $post_title; ?>">
  </div>

  <div class="form-group">
    <label for="post_category">Post Category</label>
    <select name="post_category" class="form-control">
      <?php

      $query = "SELECT * FROM categories";
      $select_categories = mysqli_query($connection, $query);
      confirmQuery($select_categories);

      while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        if($cat_id == $post_category_id){
          echo "<option value='{$cat_id}' selected>{$cat_title}</option>";
        } else {
          echo "<option value='{$cat_id}'>{$cat_title}</option>";
        }
      }

      ?>
    </select>
  </div>


  <div class="form-group">
    <label for="post_user">Post User</label>
    <select name="post_user" class="form-control">
      <option value="<?php echo $post_user; ?>"><?php echo $post_user; ?></option>
      <?php

      $query = "SELECT * FROM users";
      $select_users = mysqli_query($connection, $query);
      confirmQuery($select_users);

      while ($row = mysqli_fetch_assoc($select_users)) {
        $user_id = $row['user_id'];
        $username = $row['username'];

        echo "<option value='{$username}'>{$username}</option>";
      }

      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="post_status">Post Status</label>
    <select name="post_status" value>
      <?php
        if ($post_status == 'published') {
          echo "<option value='published' selected='selected'>Published</option>";
          echo "<option value='draft'>Draft</option>";
        } else {
          echo "<option value='published'>Published</option>";
          echo "<option value='draft' selected='selected'>Draft</option>";
        }

      ?>
    </select>
  </div>

  <!-- <div class="form-group">
    <label for="post_status">Post Status</label>
    <input type="text" class="form-control" name="post_status" value="<?php echo $post_status; ?>">
  </div> -->

  <div class="form-group">
    <label for="post_image">Post Image</label><br>
    <img src="../images/<?php echo $post_image; ?>" alt="" width="100" name="image">
    <input type="file" class="form-control" name="image">
  </div>

  <div class="form-group">
    <label for="post_tags">Post Tags</label>
    <input type="text" class="form-control" name="post_tags" value="<?php echo $post_tags; ?>">
  </div>

  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea type="text" class="form-control" name="post_content" id="summernote" cols="30" rows="10"><?php echo str_replace('\r\n', '</br>', $post_content); ?>
    </textarea>
  </div>

  <div class="form-group">
    <button class="btn btn-primary" type="submit" name="update_post">Update Post</button>
  </div>

</form>