<?php 
ob_start(); ?>
<div class="col-lg-12">
    <h1><?= $errorNo ?></h1>
    <p><?= $errorDesc ?></p>
</div>
<?php $content = ob_get_clean(); ?>

<?php require(dirname(__DIR__).'/View/base.php'); ?>