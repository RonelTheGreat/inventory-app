<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-5">
		<h1 class="text-2xl">Products</h1>
		<div class="ml-auto">
			<a href="/products/new" class="custom-primary-button">Add Product</a>
		</div>
	</div>

	<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/app/views/includes/alert-success.php'; ?>

	<div class="bg-slate-50 p-4 mb-4 rounded-md">
		<form action="/products" method="GET">
			<div class="text-lg mb-4">
				<i class="fas fa-filter mr-1"></i>
				<span>Filters</span>
			</div>
			<div class="flex flex-row items-end">
				<div class="grow">
					<label class="custom-input-label">Search name/description</label>
					<input type="search" value="<?= $searchParams['search_str']; ?>" name="searchParams[search_str]" class="custom-input w-full">
				</div>
				<div class="w-1/5 ml-4">
					<label class="custom-input-label">Category</label>
					<?php $selectedId = 0; ?>
					<select name="searchParams[category]" class="w-full custom-input">
						<?php foreach ($categoryOptions as $id => $name) : ?>
							<option value="<?= $id; ?>" <?= $searchParams['category'] == $id ? 'selected' : '' ?>>
								<?= $name; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="ml-4">
					<label id="stocks-label" class="custom-input-label">Stocks</label>
					<div class="relative flex flex-row items-center">
						<input type="number" value="<?= $searchParams['stocks']; ?>" name="searchParams[stocks]" class="custom-input w-52 pl-14">
						<select value="<?= $searchParams['stocks_comparison_operator']; ?>" name="searchParams[stocks_comparison_operator]"
								class="absolute left-px w-12 text-lg bg-slate-800 text-slate-200 px-2 py-1 rounded-l-md"
								data-label-id="stocks-label"
								data-name="stocks"
						>
							<?php foreach ($comparisonOperatorOptions as $key => $value): ?>
								<option
									value="<?= $key; ?>"
									<?= $key === $searchParams['stocks_comparison_operator'] ? 'selected': ''; ?>>
									<?= $value ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="ml-4">
					<label id="price-label" class="custom-input-label">Price</label>
					<div class="relative flex flex-row items-center">
						<input type="number" step="0.01" value="<?= $searchParams['price'] ?? ''; ?>" name="searchParams[price]" class="custom-input w-52 pl-14">
						<select value="<?= $searchParams['price_comparison_operator']; ?>" name="searchParams[price_comparison_operator]"
								class="absolute left-px w-12 text-lg bg-slate-800 text-slate-200 px-2 py-1 rounded-l-md"
								data-label-id="price-label"
								data-name="price"
						>
							<?php foreach ($comparisonOperatorOptions as $key => $value): ?>
								<option
									value="<?= $key; ?>"
									<?= $key === $searchParams['price_comparison_operator'] ? 'selected': ''; ?>>
									<?= $value ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="flex flex-row items-center justify-end mt-4">
				<a href="/products?reset=1" class="text-red-500 hover:text-slate-50 border-2 border-red-500 hover:bg-red-500 py-2 px-4 rounded-md mr-2">
					<i class="fas fa-redo mr-1"></i>
					<span>Reset</span>
				</a>
				<button type="submit" class="custom-primary-button">
					<i class="fas fa-search mr-1"></i>
					<span>Search</span>
				</button>
			</div>
		</form>
	</div>

	<?php if (!empty($products)): ?>
		<table class="w-full table-auto border-collapse border border-slate-500">
			<thead>
				<tr>
					<th class="text-left p-2 border border-slate-300">Name</th>
					<th class="text-left p-2 border border-slate-300">Category</th>
					<th class="text-left p-2 border border-slate-300">Description</th>
					<th class="text-right p-2 border border-slate-300">Price</th>
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
									<span class="text-xs text-white bg-orange-400 px-1 rounded-lg">No image</span>
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
							<?php if ($product['categoryName']!== ''): ?>
								<?= $product['categoryName']; ?>
							<?php else: ?>
								<span class="text-xs text-white bg-orange-400 px-1 rounded-lg">No category</span>
							<?php endif; ?>
						</td>

						<td class="p-2 border border-slate-300">
							<?= $product['description']; ?>
						</td>

						<td class="w-24 text-right p-2 border border-slate-300">
							<?= number_format($product['price'], 2); ?>
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
	<?php else: ?>
		<div class="flex flex-row items-center justify-center my-3">
			<div class="w-full text-sky-900 bg-sky-200 p-4 rounded-md">
				<i class="fas fa-info-circle mr-1"></i>
				<span>No products found.</span>
			</div>
		</div>
	<?php endif; ?>

	<?php include_once ROOT_DIR . '/app/views/includes/delete-list-item-modal.php'; ?>
	<?php include_once ROOT_DIR . '/app/views/includes/mark-as-sold-modal.php'; ?>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			document.querySelectorAll('[data-product-preview]').forEach(function (item) {
				item.style.backgroundImage = `url(${item.dataset.productPreview})`;
			});

			// Dynamic label (comparison operator).
			document
				.querySelectorAll('select[name*="comparison_operator"]')
				.forEach(function (item) {
					item.addEventListener('change', function () {
						const label = document.querySelector(`#${this.dataset.labelId}`);

						let comparisonOperatorText = '';
						switch (this.value) {
							case '>': comparisonOperatorText = 'greater than'; break;
							case '>=': comparisonOperatorText = 'greater than or equal to'; break;
							case '<': comparisonOperatorText = 'less than'; break;
							case '<=': comparisonOperatorText = 'less than or equal to'; break;
							case '=': comparisonOperatorText = 'equal to'; break;
						}

						label.innerHTML = `${this.dataset.name} <span class="text-blue-600 font-bold ml-1">${comparisonOperatorText}</span>`;
					});

					item.dispatchEvent(new Event('change'));
				});
		});
	</script>
</div>
