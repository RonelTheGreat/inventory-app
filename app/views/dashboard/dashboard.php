<div class="grow pt-8 pr-8">
	<div class="flex flex-row items-center mb-12">
		<h1 class="text-2xl">Welcome back <?= $admin; ?>!</h1>
	</div>

	<div class="flex flex-row items-center">
		<div class="w-80 min-w-max h-40 flex flex-col rounded-md mr-6 shadow-lg">
			<div class="text-lg text-center text-slate-50 bg-slate-800 px-5 py-4 rounded-t-md">
				<i class="fas fa-tags"></i>
				<span class="ml-2">ITEMS SOLD</span>
			</div>
			<div class="grow flex flex-row items-center justify-center text-5xl px-5">
				<div class=""><?= $itemsSold; ?></div>
			</div>
		</div>

		<div class="w-80 min-w-max h-40 flex flex-col rounded-md mr-6 shadow-lg">
			<div class="text-lg text-center text-slate-50 bg-slate-800 px-5 py-4 rounded-t-md">
				<i class="fas fa-hand-holding-usd"></i>
				<span class="ml-2">TOTAL EARNINGS</span>
			</div>
			<div class="grow flex flex-row items-center justify-center text-5xl px-5">
				<div class="">&#8369;<?= $totalEarnings; ?></div>
			</div>
		</div>

		<div class="w-80 min-w-max h-40 flex flex-col rounded-md mr-6 shadow-lg">
			<div class="text-lg text-center text-slate-50 bg-slate-800 px-5 py-4 rounded-t-md">
				<i class="fas fa-box-open"></i>
				<span class="ml-2">PRODUCTS</span>
			</div>
			<div class="grow flex flex-row items-center justify-center text-5xl px-5">
				<div class=""><?= $productsCount; ?></div>
			</div>
		</div>
	</div>
</div>