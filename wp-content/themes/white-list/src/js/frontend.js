import { CarAnimation } from "./components/CarAnimation";
import { FormValidator } from "./components/FormValidator";
import { arrowUpBtn, donationTextSlider, initPhoneMask, primaryMenu } from "./utils/helpers";
import { initFaqAccordion } from "./utils/faq-accordion";
import { initAboutTextToggle } from "./utils/about-text-toggle";
import Popup from "./utils/popup-window";
import 'swiper/css';

// Styles entry
export { }

// Minimal boilerplate JS
const onLoad = () => {
	const popup = new Popup();

	new FormValidator('.wpcf7-form');
	new FormValidator('#popup-form-shorcode .wpcf7-form');

	popup.init();

	primaryMenu();
	donationTextSlider();
	initPhoneMask();

	const paymentWidget = new TipTopPaymentWidget();

	let sfAgents = [];
	let sfVenues = [];

	fetch(php_vars.ajax_url + '?action=sf_get_active_data')
		.then(r => r.json())
		.then(data => {
			if (!data.success) return;
			sfAgents = data.agents || [];
			sfVenues = data.venues || [];

			const agentSelect = document.querySelector('.js-sf-agent-select');
			if (agentSelect && sfAgents.length) {
				agentSelect.innerHTML = '<option value="">' + (agentSelect.options[0]?.text || 'Select agent') + '</option>';
				sfAgents.forEach(a => {
					const opt = document.createElement('option');
					opt.value = a.id;
					opt.textContent = `${a.name} ID: ${a.id}`;
					agentSelect.appendChild(opt);
				});
			}

			const venueSelect = document.querySelector('.js-sf-venue-select');
			if (venueSelect && sfVenues.length) {
				venueSelect.innerHTML = '<option value="">' + (venueSelect.options[0]?.text || 'Select venue') + '</option>';
				sfVenues.forEach(v => {
					const opt = document.createElement('option');
					opt.value = v.id;
					opt.textContent = v.name;
					venueSelect.appendChild(opt);
				});
			}
		})
		.catch(e => console.error('SF fetch error:', e));

	function donationFormSubmitHandler(formSelector, options = {}) {
		const form = document.querySelector(formSelector);
		if (!form) return;

		form.addEventListener('submit', (e) => {
			e.preventDefault();

			const formData = new FormData(e.target);
			const fields = Object.fromEntries(formData.entries());

			let amount = 0;

			if (fields['custom-pay-sum']) {
				amount = parseFloat(fields['custom-pay-sum'].replace(/[^0-9]/g, ''));
			} else {
				amount = parseFloat(fields['pay-sum'].replace(/[^0-9]/g, ''));
			}

			const paymentData = {
				amount: amount,
				isSubscription: options.isSubscription !== undefined ? options.isSubscription : fields['pay-period'] === 'monthly',
				accountId: fields.email || '',
				email: fields.email || '',
				userInfo: {
					accountId: fields.email,
					firstName: fields['first-name'] || '',
					birth: fields['birth-date'] || '',
					email: fields.email || '',
					fullName: `${fields['first-name'] || ''}`,
				}
			};

			// Build metadata: base from options + dynamic fields
			paymentData.metadata = { ...(options.metadata || {}) };

			// Agent ID
			const agentSelect = form.querySelector('.js-sf-agent-select');
			if (agentSelect && agentSelect.value) {
				paymentData.metadata.agent_id = agentSelect.value;
			}

			// Venue ID
			const venueSelect = form.querySelector('.js-sf-venue-select');
			if (venueSelect && venueSelect.value) {
				paymentData.metadata.venue_id = venueSelect.value;
			}

			console.log('paymentData', paymentData);

			paymentWidget.launch(paymentData, paymentData.isSubscription, (result) => {
				if (typeof options.onSuccess === 'function') {
					options.onSuccess(result);
				}
			});
		});
	}

	const urlParams = new URLSearchParams(window.location.search);
	const metadata = {
		campaign_id: tiptopSettings.campaignId || 'donation.redcrescent.kz'
	};

	if (urlParams.get('source_code_id')) {
		metadata.source_code_id = urlParams.get('source_code_id');
	}

	donationFormSubmitHandler('.js-donation-form', {
		metadata: metadata,
		onSuccess: () => {
			popup.openOnePopup('#popup-donation-success-modal');
		},
	});

	donationFormSubmitHandler('.js-donation-subscription-form', {
		isSubscription: true,
		metadata: metadata,
		onSuccess: () => {
			popup.openOnePopup('#popup-donation-success-modal');
		},
	});

	new FormValidator('.js-donation-form');
	new FormValidator('.js-donation-subscription-form');

	// document.addEventListener('wpcf7mailsent', function (event) {
	// 	setTimeout(() => {
	// 		window.location.href = php_vars.site_url + '/thank-you';
	// 	}, 2000)
	// }, false);


	function togglePlaceholder() {
		const input = document.getElementById('id-birth-date');
		const placeholder = document.querySelector('.js-date-placeholder');


		if (!placeholder || !input) return;
		if (input.value) {
			placeholder.style.display = 'none';
		} else {
			placeholder.style.display = 'block';
		}

		input.addEventListener('input', togglePlaceholder);
		input.addEventListener('change', togglePlaceholder);
	}

	togglePlaceholder();

	arrowUpBtn();

	new CarAnimation();

	initFaqAccordion();
	initAboutTextToggle();

	const customInput = document.querySelector('input[name="custom-pay-sum"]');
	const radioInputs = document.querySelectorAll('input[name="pay-sum"]');

	if (!customInput || !radioInputs.length) return;

	customInput.addEventListener('focus', () => {
		radioInputs.forEach(radio => {
			if (radio.checked) {
				radio.checked = false;
			}
		});
	});

};

window.document.addEventListener('DOMContentLoaded', onLoad);
