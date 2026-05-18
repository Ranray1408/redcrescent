export function initAboutTextToggle() {
	const wrappers = document.querySelectorAll('.js-about-text-wrapper');

	if (!wrappers.length) return;

	const update = () => {
		const isMobile = window.innerWidth <= 1360;

		wrappers.forEach((wrapper) => {
			const text = wrapper.querySelector('.about-us__block-text');
			const btn = wrapper.querySelector('.js-about-text-btn');
			if (!text || !btn) return;

			if (!isMobile) {
				wrapper.classList.remove('collapsed', 'expanded');
				btn.textContent = btn.dataset.textCollapsed;
				return;
			}

			text.style.maxHeight = 'none';
			const overflows = text.scrollHeight > 84;
			text.style.maxHeight = '';

			if (!overflows) {
				wrapper.classList.remove('collapsed', 'expanded');
				return;
			}

			if (!wrapper.classList.contains('expanded')) {
				wrapper.classList.add('collapsed');
				btn.textContent = btn.dataset.textCollapsed;
			}
		});
	};

	wrappers.forEach((wrapper) => {
		const btn = wrapper.querySelector('.js-about-text-btn');
		if (!btn) return;

		btn.addEventListener('click', () => {
			const isExpanded = wrapper.classList.contains('expanded');

			if (isExpanded) {
				wrapper.classList.remove('expanded');
				wrapper.classList.add('collapsed');
				btn.textContent = btn.dataset.textCollapsed;
			} else {
				wrapper.classList.remove('collapsed');
				wrapper.classList.add('expanded');
				btn.textContent = btn.dataset.textExpanded;
			}
		});
	});

	update();
	window.addEventListener('resize', update);
}
