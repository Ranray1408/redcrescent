export class CarAnimation {
	constructor(rootElem) {
		this.rootElem = rootElem;
		this.icons = rootElem.querySelectorAll('.js-icon');
		this.car = rootElem.querySelector('.js-car');

		if (!this.icons.length || !this.car) return;

		this.startTime = 3000;
		this.dropTime = 2000;
		this.moveAwayTime = 2500;

		this.init();
	}

	init() {
		this.rootElem.style.setProperty('--animation-start-time', `${this.startTime}ms`);
		this.rootElem.style.setProperty('--animation-drop-time', `${this.dropTime}ms`);
		this.rootElem.style.setProperty('--animation-move-away-time', `${this.moveAwayTime}ms`);

		// Початкова позиція машини за екраном (ліворуч)
		this.rootElem.style.setProperty('--car-move-x', '-100vw');

		// // Встановлюємо початкові позиції іконок (вони не рухаються з машиною до моменту падіння)
		// this.icons.forEach(icon => {
		// 	icon.style.setProperty('--icon-offset-x', '0px');
		// 	icon.style.setProperty('--icon-offset-y', '0px');
		// });


		this.setState('enter');

		setTimeout(() => {
			this.setState('drop-icons');

			this.dropIcons();
		}, this.startTime);

		setTimeout(() => {
			this.setState('move-away');

			this.dropIcons();
		}, this.startTime + this.dropTime);

		// // Машина рухається в центр
		// requestAnimationFrame(() => {
		// 	const container = this.rootElem.getBoundingClientRect();
		// 	const carWidth = this.car.getBoundingClientRect().width;
		// 	const centerX = container.width / 2 - carWidth / 2;
		// 	this.rootElem.style.setProperty('--car-move-x', `${centerX}px`);
		// });

		// // Після того як машина доїхала до центру - іконки падають
		// setTimeout(() => {
		// 	this.dropIcons();
		// }, this.startTime);

		// // Після падіння іконок - машина з іконками їде вправо
		// setTimeout(() => {
		// 	// Оновлюємо transition для машини перед рухом вправо
		// 	this.car.style.transition = `transform ${this.moveAwayTime}ms ease-in-out`;

		// 	// Оновлюємо transition для іконок перед рухом вправо
		// 	this.icons.forEach(icon => {
		// 		icon.style.transition = `transform ${this.moveAwayTime}ms ease-in-out`;
		// 	});

		// 	// Примусовий рефлоу для застосування transitions
		// 	this.car.offsetHeight;
		// 	this.icons.forEach(icon => icon.offsetHeight);

		// 	// Машина і іконки разом рухаються вправо через --car-move-x
		// 	// offset-x/y залишаються незмінними, щоб іконки залишилися в кузові
		// 	requestAnimationFrame(() => {
		// 		this.rootElem.style.setProperty('--car-move-x', '120vw');
		// 	});
		// }, this.startTime + this.dropTime);
	}

	enterPhase() {
		const container = this.rootElem.getBoundingClientRect();
		const carWidth = this.car.getBoundingClientRect().width;
		const centerX = container.width / 2 - carWidth / 2;
		this.rootElem.style.setProperty('--car-move-x', `${centerX}px`);
	}

	dropIcons() {
		const points = this.generateIconPoints();
		if (!points.length) return;

		const rootRect = this.rootElem.getBoundingClientRect();

		this.icons.forEach((icon, index) => {
			const rect = icon.getBoundingClientRect();

			const centerX = rect.left - rootRect.left + rect.width / 2;
			const centerY = rect.top - rootRect.top + rect.height / 2;

			const target = points[index];

			const deltaX = target.x - centerX;
			const deltaY = target.y - centerY;

			icon.style.setProperty('--icon-in-container-x', `${deltaX}px`);
			icon.style.setProperty('--icon-in-container-y', `${deltaY}px`);
		});
	}

	random(min, max) {
		return Math.random() * (max - min) + min;
	}

	setState(state) {
		if (this.state === state) return;

		this.state = state;
		this.rootElem.dataset.animationState = state;

		this.checkState();
	}

	checkState() {
		switch (this.state) {
			case 'enter':
				this.enterPhase();
				break;
			case 'drop-icons':
				this.dropIcons();
				break;
			case 'move-away':
				this.iconsFollow();
				break;
			default:
				'idle'

		}
	}

	iconsFollow() {
		if (this.followRaf) return;

		const follow = () => {
			if (this.state !== 'move-away') {
				this.followRaf = null;
				return;
			}

			const points = this.generateIconPoints();

			this.icons.forEach((icon, index) => {
				const rect = icon.getBoundingClientRect();

				const centerX = rect.left + rect.width / 2;
				const centerY = rect.top + rect.height / 2;

				const target = points[index];

				const deltaX = target.x - centerX;
				const deltaY = target.y - centerY;

				icon.style.setProperty('--icon-in-container-x', `${deltaX}px`);
				icon.style.setProperty('--icon-in-container-y', `${deltaY}px`);
			});

			this.followRaf = requestAnimationFrame(follow);
		};

		this.followRaf = requestAnimationFrame(follow);
	}

	generateIconPoints() {
		const carBody = this.rootElem.querySelector('.js-car-body');
		if (!carBody || !this.icons.length) return [];

		const bodyRect = carBody.getBoundingClientRect();
		const rootRect = this.rootElem.getBoundingClientRect();
		const iconRect = this.icons[0].getBoundingClientRect();

		const radiusX = iconRect.width / 2;
		const count = this.icons.length;
		const points = [];

		const safePaddingX = radiusX * 2;

		const minX = bodyRect.left - rootRect.left + safePaddingX;
		const maxX = bodyRect.right - rootRect.left - safePaddingX;

		const y = bodyRect.top - rootRect.top + bodyRect.height / 2;

		if (count === 1) {
			points.push({ x: (minX + maxX) / 2, y });
			return points;
		}

		const step = (maxX - minX) / (count - 1);

		for (let i = 0; i < count; i++) {
			points.push({
				x: minX + step * i,
				y
			});
		}

		return points;
	}


	renderDebugPoint(point) {
		const el = document.createElement('div');
		el.className = 'debug-point';
		el.style.left = `${point.x}px`;
		el.style.top = `${point.y}px`;

		document.body.appendChild(el);
	}
}

