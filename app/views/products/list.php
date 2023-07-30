<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-5">
		<h1 class="text-2xl">Products</h1>
		<div class="ml-auto">
			<a href="/products/new" class="custom-primary-button">Add Product</a>
		</div>
	</div>

	<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/app/views/includes/alert-success.php'; ?>

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
					<td class="relative group p-2 border border-slate-300">
						<a href="/products/<?= $product['id']; ?>/edit" class="block w-100 hover:underline">
							<?= $product['name']; ?>

							<?php if (!$product['imagePreview']): ?>
								<span class="text-xs text-white bg-orange-400 px-1 rounded-md">No image</span>
							<?php endif; ?>
						</a>

						<?php if ($product['imagePreview']): ?>
							<div class="hidden group-hover:block absolute right-0 z-50 w-64 h-64 bg-black bg-center bg-contain bg-no-repeat border border-slate-300 rounded-md"
								 data-product-preview="<?= $product['imagePreview']; ?>"
							>
							</div>
						<?php endif; ?>
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

					<td class="w-28 text-right p-2 border border-slate-300">
						<form action="/stocks/<?= $product['id']; ?>?mode=mark_as_sold" method="POST"
							  data-mark-as-sold-form="<?= $product['id']; ?>" class="inline mr-2"
						>
							<input type="hidden" name="method" value="PUT">
							<input type="hidden" name="quantity" value="1">
							<button type="button"
									class="<?= $product['stocks'] <= 0 ? 'cursor-not-allowed' : ''; ?>"
									data-item-name="<?= $product['name']; ?>"
									data-item-id="<?= $product['id']; ?>"
									data-item-stocks="<?= $product['stocks']; ?>"
									data-trigger-mark-as-sold-modal
									title="Mark as sold"
									<?= $product['stocks'] <= 0 ? 'disabled' : ''; ?>
							>
								<i class="fas fa-tags fa-fw <?= $product['stocks'] > 0 ? 'hover:text-blue-600' : ''; ?>"></i>
							</button>
						</form>

						<a href="/products/<?= $product['id']; ?>/edit" class="hover:text-blue-600 mr-2" title="Edit">
							<i class="far fa-edit fa-fw hover:text-"></i>
						</a>
						<form action="/products/<?= $product['id']; ?>" method="POST"
							  data-delete-product-form="<?= $product['id']; ?>" class="inline"
						>
							<input type="hidden" name="method" value="DELETE">
							<button type="button"
									data-item-name="<?= $product['name']; ?>"
									data-item-id="<?= $product['id']; ?>"
									data-trigger-delete-modal
									title="Delete"
							>
								<i class="far fa-trash-alt fa-fw hover:text-red-700"></i>
							</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php include_once ROOT_DIR . '/app/views/includes/delete-list-item-modal.php'; ?>
	<?php include_once ROOT_DIR . '/app/views/includes/mark-as-sold-modal.php'; ?>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			document.querySelectorAll('[data-product-preview]').forEach(function (item) {
				item.style.backgroundImage = `url(${item.dataset.productPreview})`;
			});
		});
	</script>
</div>
