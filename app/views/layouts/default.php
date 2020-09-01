<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> <?php echo $this->getSiteTitle() ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo PROOT?>/css/bootstrap.min.css" >
    <?php echo $this->content("head") ;  ?>
</head>
<body>
    <?php  echo $this->content("body") ;?>
<script src="<?php echo PROOT?>/mvc/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo PROOT?>/mvc/js/bootstrap.min.js"></script>
</body>
</html>