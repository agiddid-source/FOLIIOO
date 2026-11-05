/**
 * Creates temporary, local-only previews for selected image files.
 */

const galleryInput = document.querySelector('[data-local-gallery-input]');
const galleryPreviewList = document.querySelector('[data-local-gallery-previews]');
const galleryMessage = document.querySelector('[data-local-gallery-message]');
const localImageUrls = [];

if (galleryInput instanceof HTMLInputElement && galleryPreviewList instanceof HTMLElement) {
    galleryInput.addEventListener('change', () => {
        galleryPreviewList.replaceChildren();
        localImageUrls.forEach((url) => URL.revokeObjectURL(url));
        localImageUrls.length = 0;

        const selectedImages = Array.from(galleryInput.files || [])
            .filter((file) => file.type.startsWith('image/'));
        const allowedCount = Number.parseInt(galleryInput.dataset.limit || '0', 10);
        const permittedImages = selectedImages.slice(0, Math.max(0, allowedCount));

        if (galleryMessage instanceof HTMLElement) {
            galleryMessage.classList.toggle('hidden', selectedImages.length <= permittedImages.length);
            galleryMessage.textContent = permittedImages.length > 0
                ? `Only ${permittedImages.length} selected image${permittedImages.length === 1 ? '' : 's'} fit in the remaining plan allowance. These previews are temporary and are not saved to the server.`
                : 'No additional saved-photo slots are available on this plan.';
        }

        permittedImages.forEach((file) => {
            const imageUrl = URL.createObjectURL(file);
            localImageUrls.push(imageUrl);

            const item = document.createElement('li');
            item.className = 'aspect-square overflow-hidden rounded-lg bg-[#F3EDE5]';

            const image = document.createElement('img');
            image.className = 'h-full w-full object-cover';
            image.src = imageUrl;
            image.alt = '';

            item.append(image);
            galleryPreviewList.append(item);
        });
    });

    window.addEventListener('pagehide', () => {
        localImageUrls.forEach((url) => URL.revokeObjectURL(url));
    });
}
