<h1>Edit Product - <?= $product['name']; ?> </h1>

<div><a href="/index.php?p=products&action=list">Back to list</a></div>

<form method="POST">
	<div><?= $error ?? ''; ?></div>

	<input type="text" name="name" value="<?= $product['name'] ?>">
	<button type="submit">Save</button>
</form>


