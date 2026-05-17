/**
 * Initialize FAQ accordion
 */
export function initFaqAccordion() {
	const lists = document.querySelectorAll('.js-faq-list');

	if (!lists.length) return;

	lists.forEach((list) => {
		const items = list.querySelectorAll('.js-faq-item');

		items.forEach((item) => {
			const question = item.querySelector('.js-faq-question');

			if (!question) return;

			question.addEventListener('click', () => {
				const isOpen = item.classList.contains('is-open');

				// Close all items in this list
				items.forEach((otherItem) => {
					otherItem.classList.remove('is-open');
					const otherBtn = otherItem.querySelector('.js-faq-question');
					if (otherBtn) {
						otherBtn.setAttribute('aria-expanded', 'false');
					}
				});

				// Toggle clicked item
				if (!isOpen) {
					item.classList.add('is-open');
					question.setAttribute('aria-expanded', 'true');
				}
			});
		});
	});
}
