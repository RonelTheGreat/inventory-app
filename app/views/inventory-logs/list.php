<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-5">
		<h1 class="text-2xl">Inventory Logs</h1>
	</div>

	<?php if (!empty($logs)): ?>
		<table class="w-full table-auto border-collapse border border-slate-500">
			<tbody>
				<?php foreach ($logs as $log): ?>
					<tr class="hover:bg-slate-100">
						<td class="p-2 border border-slate-300"><?= $log['description']; ?></td>
						<td class="text-right p-2 border border-slate-300"><?= $log['created_at']; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="flex flex-row items-center justify-center my-3">
			<div class="w-full text-sky-900 bg-sky-200 p-4 rounded-md">
				<i class="fas fa-info-circle mr-1"></i>
				<span>No inventory logs found.</span>
			</div>
		</div>
	<?php endif; ?>
</div>
