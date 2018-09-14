<?php 
ob_start(); ?>
<div class="col-lg-12">
    <h1>Session expirée</h1>
    <p>Votre session à expirée suite à une inactivitée. Veuillez vous reconnecter.</p>
</div>
<?php $content = ob_get_clean(); ?>

<?php require(dirname(__DIR__).'/View/base.php'); ?>