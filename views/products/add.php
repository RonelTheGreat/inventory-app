<form method="POST">
	<div><?= $errorMessage ?? ''; ?></div>

	<input type="text" name="name" placeholder="Product Name">
	<button type="submit">Add product</button>
</form>
