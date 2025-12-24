import { FormValidator } from "./components/FormValidator";
import { primaryMenu } from "./utils/helpers";
import Popup from "./utils/popup-window";

// Styles entry
export { }

// Minimal boilerplate JS
const onLoad = () => {
	const popup = new Popup();

	new FormValidator('.wpcf7-form');
	new FormValidator('#popup-form-shorcode .wpcf7-form');

	popup.init();

	primaryMenu();

	const donationForm = document.querySelector('.js-donation-form');
	const paymentWidget = new TipTopPaymentWidget();

	donationForm?.addEventListener('submit', (e) => {
		e.preventDefault();

		const form = e.target;
		const formData = new FormData(form);

		const fields = Object.fromEntries(formData.entries());

		let amount = 0;


		if (fields['custom-pay-sum']) {
			amount = parseFloat(fields['custom-pay-sum'].replace(/[^0-9]/g, ''));
		} else {
			amount = parseFloat(fields['pay-sum'].replace(/[^0-9]/g, ''));
		}

		const paymentData = {
			amount: amount,
			// description: 'Donat',
			isSubscription: fields['pay-period'] === 'monthly',
			accountId: fields.email || '',
			email: fields.email || '',
			userInfo: {
				accountId: fields.email,
				firstName: fields['first-name'] || '',
				lastName: fields['last-name'] || '',
				birth: fields['birth-date'] || '',
				email: fields.email || '',
				fullName: `${fields['first-name'] || ''} ${fields['last-name'] || ''}`,
			}
		};

		console.log('paymentData', paymentData);

		paymentWidget.launch(paymentData, paymentData.isSubscription);
	});

	new FormValidator('.js-donation-form');

	document.addEventListener('wpcf7mailsent', function (event) {
		setTimeout(() => {
			window.location.href = php_vars.site_url + '/thank-you';
		}, 2000)
	}, false);

};

window.document.addEventListener('DOMContentLoaded', onLoad);
