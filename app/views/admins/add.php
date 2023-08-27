<div class="grow pt-8 pr-8">
	<h1 class="text-2xl mb-10">Add Admin</h1>

	<div class="mb-10">
		<a href="/admins" class="custom-secondary-button">
			&larr; Back to list
		</a>
	</div>

	<form method="POST" action="/admins">
		<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>

		<div class="w-full flex flex-row items-center space-x-4 mb-4">
			<div class="w-1/3 mb-4">
				<label class="custom-input-label">First Name</label>
				<input type="text" name="first_name" class="w-full custom-input">
			</div>

			<div class="w-1/3 mb-4">
				<label class="custom-input-label">Last Name</label>
				<input type="text" name="last_name" class="w-full custom-input">
			</div>

			<div class="w-1/3 mb-4">
				<label class="custom-input-label">Username</label>
				<input type="text" name="username" class="w-full custom-input">
			</div>
		</div>

		<div class="w-full flex flex-row items-center space-x-4 mb-4">
			<div class="w-1/3 mb-4">
				<label class="custom-input-label">Password</label>
				<input type="password" name="password" class="w-full custom-input">
			</div>

			<div class="w-1/3 mb-4">
				<label class="custom-input-label">Confirm Password</label>
				<input type="password" name="confirm_password" class="w-full custom-input">
			</div>

			<div class="w-1/3">
				<label class="mt-3">
					<input type="checkbox" name="force_password_change" checked>
					<span>Force password change on login</span>
				</label>
			</div>
		</div>



		<div class="flex flex-row items-center justify-end mt-8">
			<button type="submit" class="custom-primary-button">Add Admin</button>
		</div>
	</form>
</div>
