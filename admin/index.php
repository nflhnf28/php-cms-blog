<?php include "includes/admin_header.php" ?>

<body>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php" ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to Admin, 

                            <small>
                                <?php echo ucfirst(getUsername()); ?>
                            </small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->

                <!-- /.row -->
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-file-text fa-5x"></i>
                                    </div>

                                    <div class="col-xs-9 text-right">
                                        <!-- Using recordCount function (refactoring) from functions.php to make it more readable -->
                                        <div class='huge'><?php echo $post_count = countRecords(getAllUserPost()); ?></div>
                                        <div>Posts</div>
                                    </div>

                                </div>
                            </div>
                            <a href="posts.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'><?php echo $comment_count = countRecords(getAllPostsUsersComments()); ?></div>
                                        <div>Comments</div>
                                    </div>
                                </div>
                            </div>
                            <a href="comments.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-list fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'><?php echo $category_count = countRecords(getAllUsersCategories()); ?></div>
                                        <div>Categories</div>
                                    </div>
                                </div>
                            </div>
                            <a href="categories.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
                <!-- /.row -->

                <?php
                // Published Posts Query
                $post_published_count = countRecords(getAllUserPublishedPost());

                // Draft Posts Query
                $post_draft_count = countRecords(getAllUserDraftPost());
                
                // Approved Comment query
                $approved_comment_count = countRecords(getAllUserApprovedComments());
                
                // Unapproved Comment query
                $unapproved_comment_count = countRecords(getAllUserUnapprovedComments());
                ?>

                <!-- Google Chart -->
                <div class="row">
                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Data', 'Count'],

                                <?php
                                $element_text = ['Total Posts', 'Published Posts', 'Draft Posts', 'Comments', 'Approved Comments', 'Unapproved Comments', 'Categories'];
                                $element_count = [$post_count, $post_published_count, $post_draft_count, $comment_count, $approved_comment_count, $unapproved_comment_count, $category_count];

                                for ($i = 0; $i < 7; $i++) {
                                    echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                                }
                                ?>
                                // ['Posts', 1000]
                            ]);

                            var options = {
                                chart: {
                                    title: '',
                                    subtitle: '',
                                }
                            };

                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>

                    <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php include "includes/admin_footer.php" ?>