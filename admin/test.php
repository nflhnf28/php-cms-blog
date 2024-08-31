<?php include '../includes/db.php';
include 'includes/admin_header.php'; ?>

<?php 

echo loggedInUserId();

if(userLikedThisPost(1)){
  echo 'User liked this post';
} else{
  echo 'User did not like this post';
}