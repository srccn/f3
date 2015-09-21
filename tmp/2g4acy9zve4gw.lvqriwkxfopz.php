<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $BASE.'/'.$UI; ?>" />
        <title><?php echo $site; ?></title>
        <!-- Bootstrap -->
        <link href="../../lib/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
 
    <body>
 
        <div class="container">
            <div class="jumbotron">
                <h1><?php echo $page_head; ?></h1>
            </div>
 
            <?php echo $this->render('customer/nav.htm',$this->mime,get_defined_vars()); ?>
