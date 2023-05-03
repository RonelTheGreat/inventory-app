<h1>Categories</h1>

<ul>
	<?php foreach ($categories as $category): ?>
		<li>
			<a href="/index.php?p=categories&action=edit&id=<?= $category['id']; ?>">
				<?= $category['name']; ?>
			</a>
			<a href="/index.php?p=categories&action=delete&id=<?= $category['id']; ?>">
				<span>delete</span>
			</a>
		</li>
	<?php endforeach; ?>
</ul>

<div>
	<a href="/index.php?p=categories&action=add">Add Category</a>
</div>

