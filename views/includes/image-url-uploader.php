<div class="mb-4">
	<label class="custom-input-label">Image URL</label>
	<div class="flex flex-row items-center">
		<input type="text" class="w-full px-2 py-1 border-solid border-2 border-slate-400
				rounded-l-md hover:border-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300"
			   data-image-url-input
		>
		<button type="button" class="text-sm text-slate-200 bg-slate-800 px-4 py-2 rounded-r-md hover:text-slate-100 hover:bg-slate-900
				focus:ring-2 focus:ring-offset-2 focus:ring-slate-300" data-add-image-button
		>
			ADD
		</button>
	</div>
	<div class="text-xs text-red-700 hidden" data-error-message>Image cannot be loaded.</div>

	<div class="flex flex-row flex-wrap items-center" data-images-container></div>
</div>

<script>
	<?php if (isset($product['images'])): ?>
		const existingImages = <?= json_encode($product['images']) ?>;
	<?php endif; ?>

	const addImageButton = document.querySelector('button[data-add-image-button]');
	const imageUrlInput = document.querySelector('input[data-image-url-input]');
	const imagesContainer = document.querySelector('div[data-images-container]');

	addImageButton.addEventListener('click', async () => {
		const imageUrl = imageUrlInput.value.trim();
		if (imageUrl === '') return;

		let hasErrors = false;
		try {
			await fetch(imageUrl);
		} catch (e) {
			hasErrors = true;
		}
		if (hasErrors) {
			document.querySelector('div[data-error-message]').classList.remove('hidden');
			return;
		}

		document.querySelector('div[data-error-message]').classList.add('hidden');

		createImagePreview([{id: 0, url: imageUrl}]);

		// Clear image-url input value.
		imageUrlInput.value = '';
	});

	// Pre-populate images.
	createImagePreview(existingImages);

	function createImagePreview (images) {
		images.forEach((image) => {
			const imageContainer = document.createElement('div');
			const deleteButton = document.createElement('button');
			const hiddenInput = document.createElement('input');

			// Add styles to image container.
			imageContainer.style.width = '100px';
			imageContainer.style.height = '100px';
			imageContainer.classList.add('relative', 'border-solid', 'border-2', 'border-slate-400', 'rounded-md', 'mr-2', 'my-2');
			// Add image as a background.
			imageContainer.style.backgroundImage = `url(${image.url})`;
			imageContainer.style.backgroundPosition = 'center';
			imageContainer.style.backgroundRepeat = 'no-repeat';
			imageContainer.style.backgroundSize = 'cover';

			// Add styles and text to delete button.
			deleteButton.classList.add('absolute', 'top-0.5', 'right-0.5', 'bg-red-600', 'font-medium', 'text-xs', 'text-slate-50', 'px-2', 'py-1', 'rounded', 'hover:bg-red-700');
			deleteButton.innerHTML = '&times;';
			// Add event listener.
			deleteButton.addEventListener('click', deleteImage);

			// Set hidden input's attributes and value.
			hiddenInput.setAttribute('type', 'hidden');
			hiddenInput.setAttribute('name', image.id === 0 ? 'new_images[]' : `existing_images[${image.id}]`);
			hiddenInput.value = image.url;

			// Append delete button to image container
			imageContainer.appendChild(deleteButton);

			// Append hidden input to image container.
			imageContainer.appendChild(hiddenInput);

			// Append individual image container to images container.
			imagesContainer.appendChild(imageContainer);
		});
	}

	function prePopulateImages(imageUrls) {
		if (typeof imageUrls !== 'object') return;

		const images = [];
		imageUrls.forEach((image) => {
			images.push(image.url);
		});

		createImagePreview(images);
	}

	function deleteImage (e) {
		e.preventDefault();

		e.target.parentElement.remove();
	}
</script>