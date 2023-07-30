<dialog class="min-w-min max-w-lg p-4 rounded-md" data-mark-as-sold-modal>
	<form method="dialog">
		<div class="mb-5" data-message></div>
		<div class="flex flex-row items-center justify-end">
			<button formmethod="dialog" type="submit" class="custom-secondary-button">Cancel</button>
			<button type="submit" class="custom-primary-button ml-6" value="submit">Submit</button>
		</div>
	</form>
</dialog>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		const markAsSoldButtons = document.querySelectorAll('button[data-trigger-mark-as-sold-modal]');

		markAsSoldButtons.forEach((buttonElement) => deleteEventHandler(buttonElement))

		function deleteEventHandler (buttonElement) {
			buttonElement.addEventListener('click', function (e) {
				e.preventDefault();

				const itemName = this.dataset.itemName;
				const itemId = this.dataset.itemId;
				const itemStocks = this.dataset.itemStocks;

				const modal = document.querySelector('dialog[data-mark-as-sold-modal]');
				const messageContainer = modal.querySelector('div[data-message]');

				messageContainer.innerHTML = `
					<div>Are you sure you want to mark <span class="font-bold px-1">${itemName}</span> as sold?</div>
					<div class="my-4">
						<label class="custom-input-label">Quantity sold: </label>
						<input type="number" value="1" min="1" max="${itemStocks}" class="custom-input w-full" data-quantity-sold>
						<span class="inline-block text-sm text-blue-600">Current stocks: ${itemStocks}</span>
					</div>
				`;

				modal.addEventListener('close', function () {
					if (this.returnValue === 'submit') {
						const form = document.querySelector(`[data-mark-as-sold-form="${itemId}"]`);
						const quantitySold = document.querySelector('input[data-quantity-sold]').value;

						form.querySelector('input[name="quantity"]').value = quantitySold;

						form.submit();
					}
				});

				modal.showModal();

				return false;
			});
		}
	});
</script>