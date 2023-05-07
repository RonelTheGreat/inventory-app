<div class="w-2/5 my-4">
	<h1 class="text-2xl mb-10">Edit Product - <?= $product['name']; ?> </h1>

	<div class="mb-10">
		<a href="/index.php?p=products&action=list" class="custom-secondary-button">
			&larr; Back to list
		</a>
	</div>

	<form method="POST">
		<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
		<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>

		<div class="mb-3">
			<input type="text" name="name" value="<?= $request['old']['name'] ?? $product['name']; ?>" class="w-full custom-input">
		</div>

		<div class="mb-3">
			<?php $selectedId = $request['old']['category'] ?? $product['category_id']; ?>
			<select name="category" class="w-full custom-input">
				<?php foreach ($categoryOptions as $id => $name) : ?>
					<option value="<?= $id; ?>" <?= $selectedId == $id ? 'selected' : '' ?>>
						<?= $name; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-3">
			<input type="text" name="description" placeholder="Description" value="<?= $product['description']; ?>" class="w-full custom-input">
		</div>

		<div class="flex flex-row items-center justify-end">
			<button type="submit" class="custom-primary-button">Save</button>
		</div>
	</form>
</div>

