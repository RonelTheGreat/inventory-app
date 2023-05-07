<div>
	<h1>Products</h1>

	<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>

	<ul>
		<?php foreach ($products as $product): ?>
			<li>
				<a href="/index.php?p=products&action=edit&id=<?= $product['id']; ?>">
					<?= $product['name']; ?>
				</a>
				<a href="/index.php?p=products&action=delete&id=<?= $product['id']; ?>">
					<span>delete</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<div>
		<a href="/index.php?p=products&action=add">Add Product</a>
	</div>
</div>
