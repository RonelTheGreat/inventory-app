<dialog class="min-w-min max-w-lg p-4 rounded-md" data-mark-as-sold-modal>
	<form method="dialog">
		<div class="mb-5" data-message></div>
		<div class="hidden w-full text-red-900 bg-red-100 px-4 py-2.5 rounded-md" data-error-message></div>
		<div class="my-4" data-quantity-sold>
			<label class="custom-input-label">Quantity sold: </label>
			<input type="number" class="custom-input w-full">
			<div class="text-sm text-blue-600">Current stocks: <span data-stocks></span></div>
		</div>
		<div class="flex flex-row items-center justify-end">
			<button type="button" class="custom-secondary-button" data-cancel-button>Cancel</button>
			<button type="submit" class="custom-primary-button ml-6" data-submit-button>Submit</button>
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
				const submitButton = modal.querySelector('button[data-submit-button]');
				const cancelButton = modal.querySelector('button[data-cancel-button]');
				const messageContainer = modal.querySelector('div[data-message]');
				const errorMessageContainer = modal.querySelector('div[data-error-message]');
				const currentStocksContainer = modal.querySelector('[data-stocks]');
				const quantitySoldInput = modal.querySelector('input');

				// Set message.
				messageContainer.innerHTML = `<div>Are you sure you want to mark <span class="font-bold px-1">${itemName}</span> as sold?</div>`;
				currentStocksContainer.textContent = itemStocks;

				// Set default quantity to 1.
				quantitySoldInput.value = 1;

				// Show modal.
				modal.showModal();

				const showErrorMessage = function (message) {
					errorMessageContainer.textContent = message;
					errorMessageContainer.classList.remove('hidden');
				};
				const hideErrorMessage = () => errorMessageContainer.classList.add('hidden');

				// Clear error message when user enter something on the quantity-sold input.
				quantitySoldInput.addEventListener('keyup', function (e) {
					if (!errorMessageContainer.classList.contains('hidden') && e.code !== 'Enter') {
						hideErrorMessage();
					}
				});

				submitButton.addEventListener('click', function (e) {
					e.preventDefault();

					const form = document.querySelector(`[data-mark-as-sold-form="${itemId}"]`);

					if (quantitySoldInput.value <= 0) {
						showErrorMessage('Quantity must be greater than zero.');
						return false;
					}

					if (quantitySoldInput.value > itemStocks) {
						showErrorMessage(`Quantity must be less than or equal to ${itemStocks}.`);
						return false;
					}

					form.querySelector('input[name="quantity"]').value = quantitySoldInput.value;
					form.submit();

					modal.close();
				});

				cancelButton.addEventListener('click', function () {
					modal.close();
				});

				modal.addEventListener('close', function () {
					hideErrorMessage();
				});

				return false;
			});
		}
	});
</script>