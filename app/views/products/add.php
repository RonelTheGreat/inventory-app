<div class="grow pt-8 pr-8">
	<h1 class="text-2xl mb-10">Add Product</h1>

	<div class="mb-10">
		<a href="/products" class="custom-secondary-button">
			&larr; Back to list
		</a>
	</div>

	<form method="POST" action="/products">
		<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>
		<?php include_once ROOT_DIR . '/app/views/includes/alert-success.php'; ?>

		<div class="w-full flex flex-row items-center space-x-4 mb-4">
			<div class="w-1/4">
				<label class="custom-input-label">Priority</label>
				<input type="number" name="priority" value="<?= $request['old']['priority'] ?? ''; ?>" class="w-full custom-input">
			</div>
			<div class="w-full">
				<label class="custom-input-label">Name</label>
				<input type="text" name="name" value="<?= $request['old']['name'] ?? ''; ?>" class="w-full custom-input">
			</div>
		</div>

		<div class="w-full flex flex-row items-center space-x-4 mb-4">
			<div class="w-1/2">
				<label class="custom-input-label">Category</label>
				<?php $selectedId = $request['old']['category'] ?? ''; ?>
				<select name="category" class="w-full custom-input">
					<?php foreach ($categoryOptions as $id => $name) : ?>
						<option value="<?= $id; ?>" <?= $selectedId == $id ? 'selected' : '' ?>>
							<?= $name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="w-1/2">
				<label class="custom-input-label">Size</label>
				<select name="size" class="w-full custom-input">
					<?php $selectedSize = $request['old']['size'] ?? $product['size']; ?>
					<?php foreach ($sizeOptions as $key => $name) : ?>
						<option value="<?= $key; ?>" <?= $selectedSize == $key ? 'selected' : '' ?>>
							<?= $name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="w-full flex flex-row items-center space-x-4 mb-4">
			<div class="w-1/2">
				<label class="custom-input-label">Price</label>
				<input type="number" name="price" step="0.01" value="<?= $request['old']['price'] ?? ''; ?>" class="w-full custom-input">
			</div>

			<div class="w-1/2">
				<label class="custom-input-label">Stocks</label>
				<input type="number" name="stocks" value="<?= $request['old']['stocks'] ?? ''; ?>" class="w-full custom-input">
			</div>
		</div>

		<div class="mb-4">
			<label class="custom-input-label">Description</label>
			<textarea name="description" rows="3" class="w-full custom-input"><?= $request['old']['description'] ?? ''; ?></textarea>
		</div>

		<?php include_once ROOT_DIR . '/app/views/includes/image-url-uploader.php' ?>

		<div class="flex flex-row items-center justify-end mt-8 mb-4">
			<button type="submit" class="custom-primary-button">Add product</button>
		</div>
	</form>
</div>
