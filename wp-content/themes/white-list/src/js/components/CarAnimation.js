export class CarAnimation {
	constructor(rootElem) {
		this.rootElem = rootElem;
		this.icons = rootElem.querySelectorAll('.js-icon');
		this.car = rootElem.querySelector('.js-car');
		this.state = null;

		if (!this.icons.length || !this.car) return;

		this.startTime = 3000;   // enter
		this.dropTime = 400;    // drop-icons
		this.moveAwayTime = 2500; // move-away

		this.init();
	}

	init() {
		this.rootElem.style.setProperty('--animation-start-time', `${this.startTime}ms`);
		this.rootElem.style.setProperty('--animation-drop-time', `${this.dropTime}ms`);
		this.rootElem.style.setProperty('--animation-move-away-time', `${this.moveAwayTime}ms`);

		this.prepareIdleIcons();
		this.rootElem.offsetHeight;
		this.updateCenter();

		this.setState('enter');

		setTimeout(() => {
			this.dropIcons();

			this.resetIconAnimation();

			requestAnimationFrame(() => {
				this.icons.forEach(icon => {
					icon.style.animation = '';
				});
				this.setState('drop-icons');
			});
		}, this.startTime);

		setTimeout(() => {

			this.resetIconAnimation();

			requestAnimationFrame(() => {
				this.icons.forEach(icon => {
					icon.style.animation = '';
				});

				this.setState('move-away');
			});

		}, this.startTime + this.dropTime);
	}

	/* =========================
	   STATE CONTROL
	========================= */

	setState(state) {
		if (this.state === state) return;

		this.state = state;
		this.rootElem.dataset.animationState = state;
	}

	/* =========================
	   IDLE ICONS
	========================= */

	prepareIdleIcons() {
		this.icons.forEach(icon => {
			const x = this.random(-12, 12);
			const y = this.random(-12, 12);
			const rotate = this.random(-6, 6);
			const duration = this.random(5, 9);
			const delay = this.random(0, 2);

			icon.style.setProperty('--idle-x', `${x}px`);
			icon.style.setProperty('--idle-y', `${y}px`);
			icon.style.setProperty('--idle-rotate', `${rotate}deg`);

			icon.style.animationDuration = `${duration}s`;
			icon.style.animationDelay = `${delay}s`;
		});
	}

	/* =========================
	   UTILS
	========================= */

	random(min, max) {
		return Math.random() * (max - min) + min;
	}

	resetIconAnimation() {
		this.icons.forEach(icon => {
			icon.style.animation = 'none';
		});

		this.rootElem.offsetHeight;
	}

	updateCenter() {
		const container = this.rootElem;   // .js-car-animation
		const car = this.car;              // .js-car

		const containerRect = container.getBoundingClientRect();
		const carRect = car.getBoundingClientRect();

		const containerCenterX = containerRect.width / 2;
		const carCenterOffset = containerCenterX - carRect.width / 2;

		car.style.setProperty('--car-center-x', `${carCenterOffset}px`);
	}

	dropIcons() {
		const carBody = this.rootElem.querySelector('.js-car-body');
		if (!carBody) return;

		const bodyRect = carBody.getBoundingClientRect();
		const totalIcons = this.icons.length;

		const iconContainer = this.rootElem.querySelector('.js-icon-container');
		const containerRect = iconContainer.getBoundingClientRect();


		this.icons.forEach((icon, index) => {
			const iconRect = icon.getBoundingClientRect();

			// icon center
			const iconCenterX = iconRect.left + iconRect.width / 2;

			const iconCenterY =
				containerRect.top +
				icon.offsetTop +
				icon.offsetHeight / 2;

			// target center inside car body (even distribution by X)
			const targetCenterX =
				bodyRect.left +
				(bodyRect.width / (totalIcons + 1)) * (index + 1);

			const targetCenterY =
				bodyRect.top + bodyRect.height / 2;

			// delta for transform
			const deltaX = targetCenterX - iconCenterX;
			const deltaY = targetCenterY - iconCenterY;

			icon.style.setProperty('--drop-x', `${deltaX}px`);
			icon.style.setProperty('--drop-y', `${deltaY}px`);
		});
	}


	getAnimationTime(varName) {
		const value = getComputedStyle(this.rootElem).getPropertyValue(varName).trim();
		return parseFloat(value) * 1000;
	}
}
