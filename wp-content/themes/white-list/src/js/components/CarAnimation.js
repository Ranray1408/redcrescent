import { debounce, isInViewport } from '../utils/helpers';

export class CarAnimation {
	constructor() {
		this.rootElem = document.querySelector('.js-car-animation');
		this.icons = this.rootElem.querySelectorAll('.js-icon');
		this.car = this.rootElem.querySelector('.js-car');
		this.carBody = this.rootElem.querySelector('.js-car-body');

		if (!this.icons.length || !this.car) return;

		this.startTime = 3000;
		this.dropTime = 2000;
		this.moveAwayTime = 2500;

		this.initialIconPositions = [];

		this.animationStarted = false;

		this.init();
	}

	init() {
		this.rootElem.style.setProperty('--animation-start-time', `${this.startTime}ms`);
		this.rootElem.style.setProperty('--animation-drop-time', `${this.dropTime}ms`);
		this.rootElem.style.setProperty('--animation-move-away-time', `${this.moveAwayTime}ms`);

		// Start car postion
		this.rootElem.style.setProperty('--car-move-x', '-100vw');


		this.calcIconsDefaultPosition();

		window.addEventListener('resize', debounce(() => { this.calcIconsDefaultPosition() }, 200));

		// Add random dealys for icons
		this.setupIconDelays();

		this.checkVisibilityAndStart();

		this.setupScrollListener();

	}

	calcIconsDefaultPosition() {
		if (this.followRaf) {
			cancelAnimationFrame(this.followRaf);
			this.followRaf = null;
		}

		// Save start position of icons
		const rootRect = this.rootElem.getBoundingClientRect();
		this.initialIconPositions = Array.from(this.icons).map(icon => {
			const rect = icon.getBoundingClientRect();
			return {
				x: rect.left - rootRect.left + rect.width / 2,
				y: rect.top - rootRect.top + rect.height / 2
			};
		});

		if (this.state === 'drop-icons' || this.state === 'move-away') {
			this.setIconsPositionInContainer();
		}
	}

	setupIconDelays() {
		this.icons.forEach((icon, index) => {
			const delay = this.random(0, 500);
			icon.style.setProperty('--icon-delay', `${delay}ms`);
		});
	}

	checkVisibilityAndStart() {
		if (this.animationStarted) return;

		if (this.rootElem && isInViewport(this.rootElem, 200)) {
			this.startAnimation();
		}
	}

	setupScrollListener() {
		this.checkVisibilityHandler = () => {
			this.checkVisibilityAndStart();
		};

		window.addEventListener('scroll', this.checkVisibilityHandler, { passive: true });
		window.addEventListener('resize', this.checkVisibilityHandler, { passive: true });
	}

	startAnimation() {
		if (this.animationStarted) return;

		this.animationStarted = true;

		this.setState('enter');

		setTimeout(() => {
			this.setState('drop-icons');
		}, this.startTime);

		setTimeout(() => {
			this.setState('move-away');
		}, this.startTime + this.dropTime);

		setTimeout(() => {
			this.setState('idle');
		}, this.startTime + this.dropTime + this.moveAwayTime);
	}

	enterPhase() {
		const container = this.rootElem.getBoundingClientRect();
		const carWidth = this.car.getBoundingClientRect().width;
		const centerX = container.width / 2 - carWidth / 2;
		this.rootElem.style.setProperty('--car-move-x', `${centerX}px`);
	}

	setIconsPositionInContainer() {
		const points = this.generateIconPoints();
		if (!points.length || !this.initialIconPositions.length) return;

		// Get default positions
		this.icons.forEach((icon, index) => {
			const initialPos = this.initialIconPositions[index];
			const target = points[index];

			// Delta depend on start position
			const deltaX = target.x - initialPos.x;
			const deltaY = target.y - initialPos.y;

			icon.style.setProperty('--icon-in-container-x', `${deltaX}px`);
			icon.style.setProperty('--icon-in-container-y', `${deltaY}px`);
		});
	}

	setIconsPositionDefault() {
		if (!this.initialIconPositions.length) return;

		// Get default positions
		this.icons.forEach((icon, index) => {
			icon.style.setProperty('--icon-in-container-x', `0px`);
			icon.style.setProperty('--icon-in-container-y', `0px`);
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
				this.setIconsPositionInContainer();
				break;
			case 'move-away':
				this.iconsFollow();
				break;
			case 'idle':
				this.stopFollowing();
				break;
			default:
				break;
		}
	}

	stopFollowing() {
		if (this.followRaf) {
			cancelAnimationFrame(this.followRaf);
			this.followRaf = null;
			this.animationStarted = false;
			this.setIconsPositionDefault();
		}
	}

	iconsFollow() {
		if (this.followRaf) return;

		const follow = () => {

			if (this.state !== 'move-away') {
				this.followRaf = null;
				return;
			}

			this.setIconsPositionInContainer();

			if (this.state === 'move-away') {
				this.followRaf = requestAnimationFrame(follow);
			} else {
				this.followRaf = null;
			}
		};

		this.followRaf = requestAnimationFrame(follow);
	}

	generateIconPoints() {
		if (!this.carBody || !this.icons.length) return [];

		const bodyRect = this.carBody.getBoundingClientRect();
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
		const rootRect = this.rootElem.getBoundingClientRect();
		const el = document.createElement('div');
		el.className = 'debug-point';
		el.style.left = `${rootRect.left + point.x}px`;
		el.style.top = `${rootRect.top + point.y}px`;

		document.body.appendChild(el);
	}
}

