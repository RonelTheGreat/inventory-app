<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-5">
		<h1 class="text-2xl">Products</h1>
		<div class="ml-auto">
			<a href="/index.php?p=products&action=add" class="custom-primary-button">Add Product</a>
		</div>
	</div>

	<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>

	<table class="w-full table-auto border-collapse border border-slate-500">
		<thead>
			<tr>
				<th class="text-left p-2 border border-slate-300">Name</th>
				<th class="text-left p-2 border border-slate-300">Description</th>
				<th class="p-2 border border-slate-300">Price</th>
				<th class="p-2 border border-slate-300">Stocks</th>
				<th class="p-2 border border-slate-300">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($products as $product): ?>
				<tr class="hover:bg-slate-100">
					<td class="p-2 border border-slate-300">
						<a href="/index.php?p=products&action=edit&id=<?= $product['id']; ?>" class="block w-100 hover:underline">
							<?= $product['name']; ?>
						</a>
					</td>

					<td class="p-2 border border-slate-300">
						<?= $product['description']; ?>
					</td>

					<td class="w-24 text-right p-2 border border-slate-300">
						<?= $product['price']; ?>
					</td>

					<td class="w-16 text-right p-2 border border-slate-300">
						<?= $product['stocks']; ?>
					</td>

					<td class="w-24 text-right p-2 border border-slate-300">
						<a href="/index.php?p=products&action=edit&id=<?= $product['id']; ?>"
						   class="hover:text-blue-600 mr-2" title="Edit"
						>
							<i class="far fa-edit hover:text-"></i>
						</a>
						<button type="button"
								data-delete-url="/index.php?p=products&action=delete&id=<?= $product['id']; ?>"
								data-item-name="<?= $product['name']; ?>"
								data-trigger-delete-modal
								title="Delete"
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
