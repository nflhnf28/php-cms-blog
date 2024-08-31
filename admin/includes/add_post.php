<?php 
if(isset($_POST['create_post'])){
  $post_title = mysqli_real_escape_string($connection, $_POST['title']);
  $post_category_id = $_POST['post_category'];
  $post_user_id = $_SESSION['user_id'];
  $post_user = $_POST['post_user'];
  $post_status = $_POST['post_status'];

  $post_image = $_FILES['post_image']['name'];
  $post_image_temp = $_FILES['post_image']['tmp_name'];

  $post_tags = mysqli_real_escape_string($connection, $_POST['post_tags']);
  $post_content = mysqli_real_escape_string($connection, $_POST['post_content']);
  $post_date = date('d-m-y');
  // $post_comment_count = 4;

  // Move uploaded image to images folder
  move_uploaded_file($post_image_temp, "../images/$post_image");

  $query = "INSERT INTO posts(post_category_id, post_title, post_user_id, post_user, post_status, post_date, post_image, post_content, post_tags)";

  $query .= "VALUES ('{$post_category_id}', '{$post_title}', '{$post_user_id}', '{$post_user}', '{$post_status}', now(), '{$post_image}', '{$post_content}', '{$post_tags}')";

  $create_post_query = mysqli_query($connection, $query);
  confirmQuery($create_post_query);

  $the_post_id = mysqli_insert_id($connection);
  echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View post</a> or <a href='posts.php'>Edit other posts</a></p>";
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="title">Post Title</label>
    <input type="text" class="form-control" name="title">
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

        echo "<option value='{$cat_id}'>{$cat_title}</option>";
      }

      ?>
    </select>
  </div>
  
  <div class="form-group">
    <label for="post_user">Post User</label>
    <select name="post_user" class="form-control">
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

  <!-- <div class="form-group">
    <label for="author">Post Author</label>
    <input type="text" class="form-control" name="author">
  </div> -->

  <div class="form-group">
    <label for="post_status">Post Status</label>
    <select name="post_status" class="form-control">
      <option value="draft">Draft</option>
      <option value="published">Published</option>
    </select>
  </div>

  <div class="form-group">
    <label for="formFile" class="form-label">Post Image</label>
    <input type="file" class="form-control" name="post_image" id="formFile">
  </div>

  <div class="form-group">
    <label for="post_tags">Post Tags</label>
    <input type="text" class="form-control" name="post_tags">
  </div>

  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea type="text" class="form-control" name="post_content" id="summernote" cols="30" rows="10">
  </textarea>
  </div>

  <div class="form-group">
    <button class="btn btn-primary" type="submit" name="create_post">Publish Post</button>
  </div>

</form>