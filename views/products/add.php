<div class="w-2/5 my-4">
	<h1 class="text-2xl mb-10">Add Product</h1>

	<div class="mb-10">
		<a href="/index.php?p=products&action=list" class="custom-secondary-button">
			&larr; Back to list
		</a>
	</div>

	<form method="POST">
		<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
		<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>

		<div class="mb-4">
			<label class="custom-input-label">Name</label>
			<input type="text" name="name" value="<?= $request['old']['name'] ?? ''; ?>" class="w-full custom-input">
		</div>

		<div class="mb-3">
			<label class="custom-input-label">Category</label>
			<?php $selectedId = $request['old']['category'] ?? 0; ?>
			<select name="category" class="w-full custom-input">
				<?php foreach ($categoryOptions as $id => $name) : ?>
					<option value="<?= $id ?>" <?= $selectedId == $id ? 'selected' : ''; ?>><?= $name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-4">
			<label class="custom-input-label">Description</label>
			<textarea name="description" rows="3" class="w-full custom-input"><?= $request['old']['description'] ?? ''; ?></textarea>
		</div>

		<div class="mb-4">
			<label class="custom-input-label">Price</label>
			<input type="number" name="price" step="0.01" value="<?= $request['old']['price'] ?? ''; ?>" class="w-full custom-input">
		</div>

		<?php include_once ROOT_DIR . '/views/includes/image-url-uploader.php' ?>

		<div class="flex flex-row items-center justify-end mt-8">
			<button type="submit"class="custom-primary-button">Add product</button>
		</div>
	</form>
</div>
