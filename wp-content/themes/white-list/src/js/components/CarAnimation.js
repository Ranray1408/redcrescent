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

		this.prepareIdleIcons();

		// Початкова позиція машини за екраном (ліворуч)
		this.rootElem.style.setProperty('--car-move-x', '-100vw');

		// Встановлюємо початкові позиції іконок (вони не рухаються з машиною до моменту падіння)
		this.icons.forEach(icon => {
			icon.style.setProperty('--icon-offset-x', '0px');
			icon.style.setProperty('--icon-offset-y', '0px');
		});

		// Машина рухається в центр
		requestAnimationFrame(() => {
			const container = this.rootElem.getBoundingClientRect();
			const carWidth = this.car.getBoundingClientRect().width;
			const centerX = container.width / 2 - carWidth / 2;
			this.rootElem.style.setProperty('--car-move-x', `${centerX}px`);
		});

		// Після того як машина доїхала до центру - іконки падають
		setTimeout(() => {
			this.dropIcons();
		}, this.startTime);

		// Після падіння іконок - машина з іконками їде вправо
		setTimeout(() => {
			// Оновлюємо transition для машини перед рухом вправо
			this.car.style.transition = `transform ${this.moveAwayTime}ms ease-in-out`;

			// Оновлюємо transition для іконок перед рухом вправо
			this.icons.forEach(icon => {
				icon.style.transition = `transform ${this.moveAwayTime}ms ease-in-out`;
			});

			// Примусовий рефлоу для застосування transitions
			this.car.offsetHeight;
			this.icons.forEach(icon => icon.offsetHeight);

			// Машина і іконки разом рухаються вправо через --car-move-x
			// offset-x/y залишаються незмінними, щоб іконки залишилися в кузові
			requestAnimationFrame(() => {
				this.rootElem.style.setProperty('--car-move-x', '120vw');
			});
		}, this.startTime + this.dropTime);
	}

	prepareIdleIcons() {
		this.icons.forEach(icon => {
			const x = this.random(-12, 12);
			const y = this.random(-12, 12);
			const rotate = this.random(-6, 6);
			icon.style.setProperty('--idle-x', `${x}px`);
			icon.style.setProperty('--idle-y', `${y}px`);
			icon.style.setProperty('--idle-rotate', `${rotate}deg`);
		});
	}

	dropIcons() {
		const carBody = this.rootElem.querySelector('.js-car-body');
		if (!carBody) return;

		const bodyRect = carBody.getBoundingClientRect();
		const totalIcons = this.icons.length;

		this.icons.forEach((icon, index) => {
			const iconRect = icon.getBoundingClientRect();
			const iconCenterX = iconRect.left + iconRect.width / 2;
			const iconCenterY = iconRect.top + iconRect.height / 2;

			const targetCenterX = bodyRect.left + (bodyRect.width / (totalIcons + 1)) * (index + 1);
			const targetCenterY = bodyRect.top + bodyRect.height / 2;

			const deltaX = targetCenterX - iconCenterX;
			const deltaY = targetCenterY - iconCenterY;

			// Встановлюємо початкові позиції (іконки ще не рухаються)
			icon.style.setProperty('--icon-offset-x', '0px');
			icon.style.setProperty('--icon-offset-y', '0px');
			icon.style.transition = `transform ${this.dropTime}ms ease-out`;

			// Примусовий рефлоу для застосування початкових значень
			icon.offsetHeight;

			// Встановлюємо фінальні координати для падіння
			requestAnimationFrame(() => {
				icon.style.setProperty('--icon-offset-x', `${deltaX}px`);
				icon.style.setProperty('--icon-offset-y', `${deltaY}px`);
			});
		});
	}

	random(min, max) {
		return Math.random() * (max - min) + min;
	}
}

