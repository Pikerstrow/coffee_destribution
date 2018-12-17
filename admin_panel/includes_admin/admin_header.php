<?php
ob_start();

require_once '../includes/init.php';
require_once '../includes/db.php';

get_info_block();
mf_check_auth();
if (isset($_SESSION['client'])) {
   header('Location:../../login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8"/>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>KostaCoffee - кава та кавоварки в м. Львів та області!</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" type="image/png" href="../images/favicon.png" />
   <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Rancho" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet">
   <link rel="stylesheet" type="text/css" media="screen" href="bootstrap/css/bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" media="screen" href="bootstrap/css/bootstrap-theme.css"/>
   <link rel="stylesheet" href="js/quill/quill.snow.css">
   <link rel="stylesheet" type="text/css" media="screen" href="css/main.css"/>
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
         integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
   <script src="jquery/jquery-3.3.1.min.js"></script>
   <script src="js/quill/quill.js"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
           integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script src="js/main.js"></script>

</head>
<body>

