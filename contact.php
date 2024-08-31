<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php

if (isset($_POST['submit'])) {
    $to = "fairy.dragon2809@gmail.com";
    // use wordwrap to standardize line breaks if lines are longer than 50 characters
    $subject = wordwrap($_POST['subject'], 70);
    $body = $_POST['body'];
    $header = "From: " . $_POST['email'];
    
    // send email with mail()function
    mail($to, $subject, $body, $header);

} // end of isset


?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>


<!-- Page Content -->
<div class="container">

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-wrap">
                        <h1>Contact us</h1>
                        <form role="form" action="contact.php" method="post" id="contact-form" autocomplete="off">
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                            </div>
                            <div class="form-group">
                                <label for="subject" class="sr-only">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter a subject">
                            </div>
                            <div class="form-group">
                                <textarea name="body" id="body" placeholder="Enter your message" class="form-control" cols="30" rows="10"></textarea>
                            </div>

                            <input type="submit" name="submit" id="btn-submit" class="btn btn-custom btn-lg btn-block" value="Submit">
                        </form>

                    </div>
                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>


    <hr>



    <?php include "includes/footer.php"; ?>