<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?=getBaseUrl()?>/assets/css/app.bundle.css">
    <script src="<?=getBaseUrl()?>/assets/js/app.bundle.js"></script>

    <?=\core\Base\View::getMeta()?>

</head>
<body>
<div class="container">
    <?=$content;?>
</div>
<?php
    foreach ($scripts as $script) {
        echo $script;
    }
?>
</body>
</html>