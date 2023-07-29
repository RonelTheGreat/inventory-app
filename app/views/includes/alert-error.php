<?php if (isset($errorMessage) && $errorMessage !== '') : ?>
	<div class="flex flex-row items-center justify-center my-3">
		<div class="w-full text-red-900 bg-red-100 px-4 py-2.5 rounded-md"><?= $errorMessage; ?></div>
	</div>
<?php endif; ?>
