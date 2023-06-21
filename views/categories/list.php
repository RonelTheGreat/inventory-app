<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-5">
		<h1 class="text-2xl">Categories</h1>
		<div class="ml-auto">
			<a href="/index.php?p=categories&action=add" class="custom-primary-button">Add Category</a>
		</div>
	</div>

	<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>

	<table class="w-full table-auto border-collapse border border-slate-500">
		<thead>
			<tr>
				<th class="text-left p-2 border border-slate-300">Name</th>
				<th class="p-2 border border-slate-300">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($categories as $category): ?>
				<tr class="hover:bg-slate-100">
					<td class="p-2 border border-slate-300">
						<a href="/index.php?p=categories&action=edit&id=<?= $category['id']; ?>" class="block w-100 hover:underline">
							<?= $category['name']; ?>
						</a>
					</td>

					<td class="w-24 text-right p-2 border border-slate-300">
						<button type="button"
								data-delete-url="/index.php?p=categories&action=delete&id=<?= $category['id']; ?>"
								data-item-name="<?= $category['name']; ?>"
								data-trigger-delete-modal
						>
							<i class="far fa-trash-alt hover:text-red-700"></i>
						</button>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php include_once ROOT_DIR . '/views/includes/delete-list-item-modal.php'; ?>
</div>

