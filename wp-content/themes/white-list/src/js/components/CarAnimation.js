import { isInViewport } from '../utils/helpers';

export class CarAnimation {
	constructor() {
		this.rootElem = document.querySelector('.js-car-animation');
		if (!this.rootElem) return;

		this.icons = this.rootElem.querySelectorAll('.js-icon');
		this.car = this.rootElem.querySelector('.js-car');
		this.carBody = this.rootElem.querySelector('.js-car-body');

		if (!this.icons.length || !this.car || !this.carBody) return;

		// Timings
		this.startTime = 3000;
		this.dropTime = 2000;
		this.moveAwayTime = 2500;
		this.totalTime = this.startTime + this.dropTime + this.moveAwayTime;

		// Runtime
		this.state = null;
		this.startTimestamp = null;
		this.rafId = null;

		this.initialIconPositions = [];

		this.init();
	}

	init() {
		this.rootElem.style.setProperty('--animation-start-time', `${this.startTime}ms`);
		this.rootElem.style.setProperty('--animation-drop-time', `${this.dropTime}ms`);
		this.rootElem.style.setProperty('--animation-move-away-time', `${this.moveAwayTime}ms`);

		// Initial car position
		this.rootElem.style.setProperty('--car-move-x', '-100vw');

		this.calcIconsDefaultPosition();
		this.setupIconDelays();

		this.start();
	}

	start() {
		if (this.rafId) return;

		this.startTimestamp = performance.now();
		this.rafId = requestAnimationFrame(this.loop);
	}

	loop = (now) => {
		const elapsed = now - this.startTimestamp;
		const nextState = this.getStateByTime(elapsed);

		if (nextState !== this.state) {
			this.state = nextState;
			this.applyState(nextState);
		}

		// IMPORTANT: icons must follow car every frame
		if (this.state === 'move-away') {
			this.setIconsPositionInContainer();
		}

		if (elapsed >= this.totalTime) {
			this.reset();
			this.start();
			return;
		}

		this.rafId = requestAnimationFrame(this.loop);
	};

	reset() {
		if (this.rafId) {
			cancelAnimationFrame(this.rafId);
			this.rafId = null;
		}

		this.state = null;
		this.startTimestamp = null;

		this.setIconsPositionDefault();
	}

	getStateByTime(elapsed) {
		if (elapsed < this.startTime) return 'enter';
		if (elapsed < this.startTime + this.dropTime) return 'drop-icons';
		if (elapsed < this.totalTime) return 'move-away';
		return 'idle';
	}

	applyState(state) {
		this.rootElem.dataset.animationState = state;

		switch (state) {
			case 'enter':
				this.enterPhase();
				break;

			case 'drop-icons':
				this.setIconsPositionInContainer();
				break;

			case 'idle':
				this.setIconsPositionDefault();
				break;

			default:
				break;
		}
	}

	enterPhase() {
		const container = this.rootElem.getBoundingClientRect();
		const carWidth = this.car.getBoundingClientRect().width;
		const centerX = container.width / 2 - carWidth / 2;
		this.rootElem.style.setProperty('--car-move-x', `${centerX}px`);
	}

	/* ---------------- ICON FOLLOW LOGIC ---------------- */

	setIconsPositionInContainer() {
		const points = this.generateIconPoints();
		if (!points.length || !this.initialIconPositions.length) return;

		this.icons.forEach((icon, index) => {
			const start = this.initialIconPositions[index];
			const target = points[index];

			const dx = target.x - start.x;
			const dy = target.y - start.y;

			icon.style.setProperty('--icon-in-container-x', `${dx}px`);
			icon.style.setProperty('--icon-in-container-y', `${dy}px`);
		});
	}

	setIconsPositionDefault() {
		this.icons.forEach(icon => {
			icon.style.setProperty('--icon-in-container-x', `0px`);
			icon.style.setProperty('--icon-in-container-y', `0px`);
		});
	}

	calcIconsDefaultPosition() {
		const rootRect = this.rootElem.getBoundingClientRect();

		this.initialIconPositions = Array.from(this.icons).map(icon => {
			const rect = icon.getBoundingClientRect();
			return {
				x: rect.left - rootRect.left + rect.width / 2,
				y: rect.top - rootRect.top + rect.height / 2
			};
		});
	}

	generateIconPoints() {
		const bodyRect = this.carBody.getBoundingClientRect();
		const rootRect = this.rootElem.getBoundingClientRect();
		const iconRect = this.icons[0].getBoundingClientRect();

		const radiusX = iconRect.width / 2;
		const safePaddingX = radiusX * 2;

		const minX = bodyRect.left - rootRect.left + safePaddingX;
		const maxX = bodyRect.right - rootRect.left - safePaddingX;
		const y = bodyRect.top - rootRect.top + bodyRect.height / 2;

		const count = this.icons.length;
		if (count === 1) {
			return [{ x: (minX + maxX) / 2, y }];
		}

		const step = (maxX - minX) / (count - 1);
		return Array.from({ length: count }, (_, i) => ({
			x: minX + step * i,
			y
		}));
	}

	setupIconDelays() {
		this.icons.forEach(icon => {
			const delay = Math.random() * 500;
			icon.style.setProperty('--icon-delay', `${delay}ms`);
		});
	}
}
