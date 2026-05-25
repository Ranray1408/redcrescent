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

			if (options.metadata || fields.team_member_id) {
				paymentData.metadata = { ...(options.metadata || {}) };
				if (fields.team_member_id) {
					paymentData.metadata.agent_id = fields.team_member_id;
				}
			}

			console.log('paymentData', paymentData);

			paymentWidget.launch(paymentData, paymentData.isSubscription, (result) => {
				if (typeof options.onSuccess === 'function') {
					options.onSuccess(result);
				}
			});
		});
	}

	donationFormSubmitHandler('.js-donation-form', {
		onSuccess: () => {
			popup.openOnePopup('#popup-donation-success-modal');
		},
	});

	const urlParams = new URLSearchParams(window.location.search);
	const metadata = { campaign_id: 'main' };
	if (urlParams.get('source_code_id')) {
		metadata.source_code_id = urlParams.get('source_code_id');
	}
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
