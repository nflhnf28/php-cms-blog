<?php ob_start(); ?>
<?php session_start() ?>
<?php include "../includes/db.php"; ?>
<?php include "functions.php"; ?>
<?php 
if(isset($_SESSION['user_role'])){
} else{
    header("Location: ../index.php");
}
// else if(isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'admin'){
    // header("Location: ../index.php");
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Google Chart JS -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- WYSIWYG Summernote -->
    <link rel="stylesheet" href="css/summernote.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>