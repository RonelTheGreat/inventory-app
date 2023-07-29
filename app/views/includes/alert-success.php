<?php if (isset($successMessage) && $successMessage !== '') : ?>
	<div class="flex flex-row items-center justify-center my-3">
		<div class="w-full text-green-900 bg-green-100 px-4 py-2.5 rounded-md"><?= $successMessage; ?></div>
	</div>
<?php endif; ?>
