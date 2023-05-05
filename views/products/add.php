<div class="w-2/5 my-4">
	<h1 class="text-2xl mb-10">Add Product</h1>
	
	<form method="POST">
		<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
		<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>

		<div>
			<div class="mb-3">
				<input type="text" name="name" placeholder="Product Name" class="w-full custom-input">
			</div>
			
			<div class="mb-3">
				<select name="category" class="w-full custom-input">
					<?php foreach ($categories as $category) : ?>
						<option value="<?= $category['id'] ?>"><?= $category['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="mb-3">
				<input type="text" name="description" placeholder="Description" class="w-full custom-input">
			</div>
			
			<div class="flex flex-row items-center justify-end mt-4">
				<button type="submit"class="custom-primary-button">Add product</button>
			</div>
		</div>
	</form>
</div>
