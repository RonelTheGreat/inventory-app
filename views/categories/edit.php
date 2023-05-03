<h1>Edit Category - <?= $category['name']; ?> </h1>

<div><a href="/index.php?p=categories&action=list">Back to list</a></div>

<form method="POST">
	<input type="text" name="name" value="<?= $category['name'] ?>">
	<button type="submit">Save</button>
</form>


