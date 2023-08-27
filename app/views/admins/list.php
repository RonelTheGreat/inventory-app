<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-5">
		<h1 class="text-2xl">Admins</h1>
		<div class="ml-auto">
			<a href="/admins/new" class="custom-primary-button">Add Admin</a>
		</div>
	</div>

	<?php include_once ROOT_DIR . '/app/views/includes/alert-error.php'; ?>
	<?php include_once ROOT_DIR . '/app/views/includes/alert-success.php'; ?>

	<?php if (!empty($admins)): ?>
		<table class="w-full table-auto border-collapse border border-slate-500">
			<thead>
			<tr>
				<th class="text-left p-2 border border-slate-300">Full Name</th>
				<th class="text-left p-2 border border-slate-300">Username</th>
				<th class="p-2 border border-slate-300">Actions</th>
			</tr>
			</thead>
			<tbody>
				<?php foreach ($admins as $admin): ?>
					<tr class="hover:bg-slate-100">
						<td class="p-2 border border-slate-300">
							<a href="/admins/<?= $admin['id']; ?>/edit" class="block w-100 hover:underline">
								<?= $admin['first_name'] . ' ' . $admin['last_name']; ?>
							</a>
						</td>
						<td class="p-2 border border-slate-300"><?= $admin['username']; ?></td>
						<td class="w-28 text-right border border-slate-300 p-2">
							<a href="/admins/<?= $admin['id']; ?>/edit" class="hover:text-blue-600 mr-2" title="Edit">
								<i class="far fa-edit fa-fw hover:text-"></i>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="flex flex-row items-center justify-center my-3">
			<div class="w-full text-sky-900 bg-sky-200 p-4 rounded-md">
				<i class="fas fa-info-circle mr-1"></i>
				<span>No admins found.</span>
			</div>
		</div>
	<?php endif; ?>
</div>


