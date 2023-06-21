<dialog class="min-w-min max-w-lg p-4 rounded-md" data-delete-item-modal>
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
		const deleteButtons = document.querySelectorAll('button[data-trigger-delete-modal]');

		deleteButtons.forEach((buttonElement) => deleteEventHandler(buttonElement))

		function deleteEventHandler (buttonElement) {
			buttonElement.addEventListener('click', function () {
				const itemName = this.dataset.itemName;
				const deleteUrl = this.dataset.deleteUrl;

				const deleteModal = document.querySelector('dialog[data-delete-item-modal]');
				const messageContainer = deleteModal.querySelector('div[data-message]');

				messageContainer.innerHTML = `Are you sure you want to delete <span class="font-bold px-1">${itemName}</span>?`;

				deleteModal.addEventListener('close', function () {
					if (this.returnValue === 'submit') {
						location.href = deleteUrl;
					}
				});

				deleteModal.showModal();

				return false;
			});
		}
	});
</script>