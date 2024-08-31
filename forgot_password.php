<!-- Load Composer's autoloader -->
<?php require './vendor/autoload.php' ?>
<?php require './classes/Config.php' ?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
?>

<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "admin/functions.php"; ?>

<?php
if (!isset($_GET['forgot'])) {
    redirect('index.php');
}

if (ifItIsMethod('post')) {
    if (isset($_POST['email'])) {
        $email = escape($_POST['email']);
        $length = 50;
        $token = bin2hex(openssl_random_pseudo_bytes($length));

        if (email_exists($email)) {
            if ($stmt = mysqli_prepare($connection, "UPDATE users SET token = '{$token}' WHERE user_email = ?")) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                /*
                * 
                *   SEND EMAIL  
                *   Configuration PHPMailer
                */
                $mail = new PHPMailer();
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = Config::SMTP_HOST;                     //Set the SMTP server to send through
                $mail->Port       = Config::SMTP_PORT;                      //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                $mail->Username   = Config::SMTP_USER;                       //SMTP username
                $mail->Password   = Config::SMTP_PASSWORD;                  //SMTP password
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->SMTPSecure = "tls";            //Enable implicit TLS encryption

                // Recipients
                $mail->setFrom('fairy.dragon2809@gmail.com', 'Naufal');
                $mail->addAddress($email);     //Add a recipient, Name is optional (2nd parameter)
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');

                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                // Content
                $mail->isHTML(true);                                        // Set email format to HTML
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Reset your password';
                $mail->Body = '<p>Please click on the link below to reset your password.
                <a href="http://localhost/cms/reset_password.php?email=' . $email . '&token=' . $token . '">Reset Password</a></p>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                if ($mail->send()) {
                    $emailSent = true;
                } else {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo "Error: " . mysqli_error($connection) . "Please contact admin";
            }
        }
    }
}


?>

<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                            <?php if (!isset($emailSent)): ?>

                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Forgot Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">

                                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                <input id="email" name="email" placeholder="email address" class="form-control" type="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                        </div>

                                        <input type="hidden" class="hide" name="token" id="token" value="">
                                    </form>
                                </div><!-- Body-->

                            <?php else: ?>
                                <h2>Please check your email</h2>
                                <p>We sent a link to your email. Please check your email and click on the link to reset your password.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php"; ?>

</div> <!-- /.container -->