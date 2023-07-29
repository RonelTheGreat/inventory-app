<div class="w-80 mt-16">
	<h1 class="text-2xl mb-8">
		<i class="fas fa-user-shield"></i>
		<span class="ml-1">Sign in</span>
	</h1>

	<form method="POST">
		<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>

		<div class="mb-4">
			<label class="custom-input-label">Username</label>
			<input type="text" name="username" value="<?= $request['old']['username'] ?? ''; ?>" class="w-full custom-input">
		</div>

		<div class="mb-4">
			<label class="custom-input-label">Password</label>
			<input type="password" name="password" class="w-full custom-input">
		</div>

		<div class="flex flex-row items-center justify-end mt-8">
			<button type="submit" class="custom-primary-button">Sign in</button>
		</div>
	</form>
</div>
