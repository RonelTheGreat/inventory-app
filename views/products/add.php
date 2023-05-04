<div class="w-2/5 my-4">
	<h1 class="text-2xl mb-10">Add Product</h1>
	
	<?php include_once ROOT_DIR . '/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/views/includes/alert-success.php'; ?>
	
	<form method="POST">
		<div>
			<div class="mb-3">
				<input type="text" name="name" placeholder="Product Name"
					   class="w-full px-2 py-1 border-solid border-2 border-slate-400 rounded-md hover:border-slate-900
					   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300"
				>
			</div>
			
			<div class="mb-3">
				<select name="category"
						class="w-full px-2 py-1 border-solid border-2 border-slate-400 rounded-md hover:border-slate-900
						focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300"
				>
					<?php foreach ($categories as $category) : ?>
						<option><?= $category['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="mb-3">
				<input type="text" name="name" placeholder="Description"
					   class="w-full px-2 py-1 border-solid border-2 border-slate-400 rounded-md hover:border-slate-900
					   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300"
				>
			</div>
			
			<div class="flex flex-row items-center justify-end mt-4">
				<button type="submit"
						class="text-slate-200 bg-slate-800 px-4 py-2 rounded-md shadow-md hover:text-slate-100
						hover:bg-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-slate-300"
				>
					Add product
				</button>
			</div>
		</div>
	</form>
</div>
