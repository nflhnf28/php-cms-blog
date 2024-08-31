<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<body>
    <!-- Navigation -->
    <?php
    include "includes/navigation.php";
    ?>

    <?php
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['liked'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // 1 = FETCH POST
        $query = "SELECT * FROM posts WHERE post_id = $post_id";
        $postResult = mysqli_query($connection, $query);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];

        // 2 = UPDATE POST WITH LIKES (INCREMENT)
        mysqli_query($connection, "UPDATE posts SET likes = $likes+1 WHERE post_id = $post_id");

        // 3 = INSERT DATA
        mysqli_query($connection, "INSERT INTO likes (user_id, post_id) VALUES ($user_id, $post_id)");
        exit();
    }

    if (isset($_POST['unliked'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // 1 = FETCH POST
        $query = "SELECT * FROM posts WHERE post_id = $post_id";
        $postResult = mysqli_query($connection, $query);
        $post = mysqli_fetch_array($postResult);
        $unlikes = $post['unlikes'];

        // 2 = DELETE LIKES
        mysqli_query($connection, "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id");


        // 3 = UPDATE POST WITH UNLIKES (DECREMENT)
        mysqli_query($connection, "UPDATE posts SET likes = $likes-1 WHERE post_id = $post_id");
        exit();
    }

    ?>
    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <!-- Blog Post Content Column -->
            <div class="col-md-8">

                <?php
                if (isset($_GET['p_id'])) {
                    $the_post_id = $_GET['p_id'];

                    $query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id";
                    $update_view_count = mysqli_query($connection, $query);
                    if (!$update_view_count) {
                        die("QUERY FAILED" . mysqli_error($connection));
                    }

                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                        $query = "SELECT * FROM posts where post_id = $the_post_id";
                    } else {
                        $query = "SELECT * FROM posts where post_id = $the_post_id AND post_status = 'published' ";
                    }

                    $select_all_posts_query = mysqli_query($connection, $query);

                    if (mysqli_num_rows($select_all_posts_query) < 1) {
                        echo "<h1 class='text-center'>No post available</h1>";
                    } else {

                        while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                            $post_title = $row['post_title'];
                            $post_user = $row['post_user'];
                            $post_date = $row['post_date'];
                            $post_image = $row['post_image'];
                            $post_content = $row['post_content'];
                ?>


                            <!-- Blog Post -->
                            <h1><?php echo $post_title ?></h1>

                            <!-- User -->
                            <p class="lead">
                                by <a href="#"><?php echo $post_user ?></a>
                            </p>

                            <hr>

                            <!-- Date/Time -->
                            <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?></p>

                            <hr>

                            <!-- Preview Image -->
                            <img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="">



                            <!-- Post Content -->
                            <p><?php echo $post_content ?></p>

                            <hr>
                            <?php if (isLoggedIn()) { ?>
                                <div class="row">
                                    <p
                                        class="pull-right h4"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="<?php echo userLikedThisPost($the_post_id) ? 'I liked this post already!' : 'Want to like this post?'; ?>">
                                        <a class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like'; ?>" href="">
                                            <span class="glyphicon <?php echo userLikedThisPost($the_post_id) ? 'glyphicon-thumbs-down' : 'glyphicon-thumbs-up'; ?>"></span> <?php echo userLikedThisPost($the_post_id) ? 'Unlike' : 'Like'; ?>
                                        </a>
                                    </p>
                                </div>

                            <?php } else { ?>

                                <div class="row">
                                    <p class="pull-right h4">
                                        You need to <a href="/cms/login.php">Login</a> to like
                                    </p>
                                </div>
                            <?php } ?>


                            <!-- <div class="row">
                                <p class="pull-right"><a class="unlike" href="#"><span class="glyphicon glyphicon-thumbs-down"></span> Unlike</a></p>
                            </div> -->

                            <div class="row">
                                <p class="pull-right h4">Like: <?php echo getPostLikes($the_post_id); ?></p>
                            </div>

                        <?php } ?>
                        <hr>

                        <!-- Blog Comments -->
                        <?php
                        if (isset($_POST['create_comment'])) {
                            $the_post_id = $_GET['p_id'];

                            $comment_author = $_POST['comment_author'];
                            $comment_email = $_POST['comment_email'];
                            $comment_content = $_POST['comment_content'];

                            if (!empty($_POST['comment_author']) && !empty($_POST['comment_email']) && !empty($_POST['comment_content'])) {
                                $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                                $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";

                                $create_comment_query = mysqli_query($connection, $query);

                                if (!$create_comment_query) {
                                    die("QUERY FAILED" . mysqli_error($connection));
                                }

                                $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                                $query .= "WHERE post_id = $the_post_id ";
                                $update_comment_count = mysqli_query($connection, $query);
                            } else {
                                echo "<script>alert('Fields cannot be empty')</script>";
                            }
                        }
                        // include "admin/functions.php";
                        // redirect("/cms/post.php?p_id=$the_post_id");
                        ?>

                        <!-- Comments Form -->
                        <div class="well">
                            <h4>Leave a Comment:</h4>
                            <form role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="author">Author</label>
                                    <input type="text" class="form-control" name="comment_author"></input>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="comment_email"></input>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control" name="comment_content" rows="3"></textarea>
                                </div>
                                <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                            </form>
                        </div>

                        <hr>

                        <!-- Posted Comments -->

                        <?php
                        $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                        $query .= "AND comment_status = 'approved' ";
                        $query .= "ORDER BY comment_id DESC";
                        $select_comment_query = mysqli_query($connection, $query);

                        if (!$select_comment_query) {
                            die("QUERY FAILED" . mysqli_error($connection));
                        }

                        while ($row = mysqli_fetch_array($select_comment_query)) {
                            $comment_date = $row['comment_date'];
                            $comment_content = $row['comment_content'];
                            $comment_author = $row['comment_author'];
                        ?>

                            <!-- Comment -->
                            <div class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><?php echo $comment_author ?>
                                        <small><?php echo $comment_date ?></small>
                                    </h4>
                                    <?php echo $comment_content ?>
                                </div>
                            </div>

                <?php }
                    }
                } else {
                    header("Location: index.php");
                }
                ?>
                <!-- End of comment -->
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php
        include "includes/footer.php";
        ?>

        <script>
            $(document).ready(function() {
                // Tooltip
                $("[data-toggle='tooltip']").tooltip();

                // define 
                var post_id = <?php echo $the_post_id; ?>;
                var user_id = <?php echo loggedInUserId(); ?>;

                // Like jQuery AJAX
                $('.like').click(function(event) {
                    // event.preventDefault();
                    console.log("Like button clicked");

                    $.post("/cms/post.php?p_id=<?php echo $the_post_id; ?>", {
                            'liked': 1,
                            'post_id': post_id,
                            'user_id': user_id
                        })
                        .done(function(response) {
                            console.log("Ajax request was successful");
                            console.log(response);
                        })
                        .fail(function(xhr, status, error) {
                            console.error("Ajax request failed: " + status + ", " + error);
                        });
                });

                // Unlike jQuery AJAX
                $('.unlike').click(function(event) {
                    // event.preventDefault();
                    console.log("Like button clicked");

                    $.post("/cms/post.php?p_id=<?php echo $the_post_id; ?>", {
                            'unliked': 1,
                            'post_id': post_id,
                            'user_id': user_id
                        })
                        .done(function(response) {
                            console.log("Ajax request was successful");
                            console.log(response);
                        })
                        .fail(function(xhr, status, error) {
                            console.error("Ajax request failed: " + status + ", " + error);
                        });
                });
            });
        </script>


</body>

</html>