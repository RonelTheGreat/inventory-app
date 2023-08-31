<div class="w-64 bg-slate-800 text-slate-200 hover:text-slate-100 pt-8">
	<?php
		$menuItems = [
			[
				'name' => 'dashboard',
				'url' => '/dashboard',
				'icon' => 'far fa-chart-bar',
				'label' => 'Dashboard',
			],
			[
				'name' => 'products',
				'url' => '/products',
				'icon' => 'fas fa-box-open',
				'label' => 'Products',
			],
			[
				'name' => 'categories',
				'url' => '/categories',
				'icon' => 'fas fa-boxes',
				'label' => 'Categories',
			],
			[
				'name' => 'admins',
				'url' => '/admins',
				'icon' => 'fas fa-users-cog',
				'label' => 'Admins',
			],
			[
				'name' => 'inventoryLogs',
				'url' => '/inventory-logs',
				'icon' => 'fas fa-file-alt',
				'label' => 'Inventory Logs',
			],
			[
				'name' => 'logout',
				'url' => '/logout',
				'icon' => 'fas fa-sign-out-alt',
				'label' => 'Logout',
			],
		];
	?>
	<?php foreach ($menuItems as $item): ?>
		<div class="px-4 py-3 hover:bg-slate-900/10
			<?= $item['url'] === '/logout' ? 'mt-10' : ''; ?>
			<?= $item['name'] === $activeSidebarMenu ? 'bg-slate-900 border-r-4 border-orange-400' : ''; ?>"
		>
			<a href="<?= $item['url']; ?>" class="w-100 block">
				<i class="<?= $item['icon'] ?>"></i>
				<span class="ml-1"><?= $item['label']; ?></span>
			</a>
		</div>
	<?php endforeach; ?>
</div>