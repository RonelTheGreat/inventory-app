<div class="grow pt-8 pr-8">
	<h1 class="text-2xl mb-10">Edit Category - <?= $category['name']; ?> </h1>

	<div class="mb-10">
		<a href="/categories" class="custom-secondary-button">
			&larr; Back to list
		</a>
	</div>

	<form method="POST" action="/categories/<?= $category['id']; ?>">
		<!-- Method override -->
		<input type="hidden" name="method" value="PUT">

		<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>
		<?php include_once ROOT_DIR . '/app/views/includes/alert-success.php'; ?>

		<div class="mb-4">
			<label class="custom-input-label">Name</label>
			<input type="text" name="name" value="<?= $request['old']['name'] ?? $category['name']; ?>" class="w-full custom-input">
		</div>

		<div class="flex flex-row items-center justify-end mt-8">
			<button type="submit" class="custom-primary-button">Save</button>
		</div>
	</form>
</div>


