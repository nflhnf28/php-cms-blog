<?php
include "includes/db.php";
?>
<?php
include "includes/header.php";
?>

<body>

    <!-- Navigation -->
    <?php
    include "includes/navigation.php";
    ?>

    <?php
    if (isset($_GET['p_id'])) {
        $the_post_id = $_GET['p_id'];
        $the_post_author = $_GET['author'];
    }
    ?>


    <?php
    $query = "SELECT * FROM posts where post_user = '{$the_post_author}' order by post_id desc";
    $select_all_posts_query = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
        $post_title = $row['post_title'];
        $post_author = $row['post_user'];
        $post_date = $row['post_date'];
        $post_image = $row['post_image'];
        $post_content = $row['post_content'];
    ?>

        <!-- Page Content -->
        <div class="container">
            <div class="row">
                <!-- Blog Post Content Column -->
                <div class="col-lg-8">

                    <!-- Blog Post -->
                    <!-- Title -->
                    <h1><?php echo $post_title ?></h1>

                    <!-- Author -->
                    <p class="lead">
                        Posted by <?php echo $post_author ?>
                    </p>
                    <hr>

                    <!-- Date/Time -->
                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?></p>
                    <hr>

                    <!-- Preview Image -->
                    <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
                    <hr>

                    <!-- Post Content -->
                    <p><?php echo $post_content ?></p>
                <?php } ?>


                <hr>
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

</body>

</html>