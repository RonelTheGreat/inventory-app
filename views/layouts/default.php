<?php include_once ROOT_DIR . '/views/includes/top.php' ?>

<?php if (isset($errorMessage) && $errorMessage !== '') : ?>
	<div><?= $errorMessage; ?></div>
<?php endif; ?>

<?php if (isset($successMessage) && $successMessage !== '') : ?>
	<div><?= $successMessage; ?></div>
<?php endif; ?>

<?php include_once $view; ?>

<?php include_once ROOT_DIR . '/views/includes/bottom.php' ?>

