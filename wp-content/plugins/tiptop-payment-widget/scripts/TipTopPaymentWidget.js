// Global class for theme scripts
window.TipTopPaymentWidget = class {

	constructor() {
		this.widget = new tiptop.Widget();
	}

	/**
	 * Launch payment widget
	 * @param {Object} userInfo - user info
	 * @param {Boolean} isSubscription - true if subscription
	 * @param {Object} paymentData - dynamic data from theme
	 */
	launch(paymentData = {}, isSubscription = false) {

		if (!paymentData) {
			console.error('Empty paymentData');
		}

		const today = new Date();
		const dd = String(today.getDate()).padStart(2, '0');
		const mm = String(today.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed
		const yyyy = today.getFullYear();

		const formattedDate = `${mm}${dd}${yyyy}`;

		// Base intent params from plugin settings
		const intentParams = {
			publicTerminalId: tiptopSettings.terminalId,
			description: paymentData.description || tiptopSettings.description,
			currency: tiptopSettings.currency || 'KZT',
			amount: paymentData.amount,
			externalId: paymentData.externalId || `payment_${paymentData.userInfo.email}_${formattedDate}`,
			paymentSchema: 'Single',
			userInfo: paymentData.userInfo,
			receiptEmail: paymentData.userInfo.email,
			tokenize: true,
		};

		// Add recurrent only for subscription
		if (isSubscription) {
			const startDate = new Date();
			startDate.setMonth(startDate.getMonth() + 1);

			intentParams.recurrent = {
				period: 1,
				interval: 'Month',
				maxPeriods: 12,
			};
		}

		this.widget
			.start(intentParams)
			.then(result => {
				console.log("Payment success:", result);

				const mailData = this.prepareMailData(intentParams);
				this.sendMail(mailData);
			})
			.catch(error => console.error("Payment error:", error));
	}

	/**
	 * Prepare data for email
	 */
	prepareMailData(intentParams) {
		return {
			firstName: intentParams.userInfo.firstName,
			lastName: intentParams.userInfo.lastName,
			email: intentParams.receiptEmail,
			amount: intentParams.amount,
			currency: intentParams.currency,
			description: intentParams.description,
			externalId: intentParams.externalId,
			birth: intentParams.userInfo.birth || '',
			recurrent: intentParams.recurrent || false
		};
	}

	/**
	 * Send mail via WP AJAX
	 */
	sendMail(mailData) {
		const formData = new FormData();
		formData.append('action', 'tiptop_payment_email');
		formData.append('paymentData', JSON.stringify(mailData));

		fetch(tiptopSettings.ajaxUrl, {
			method: 'POST',
			body: formData
		})
			.then(res => res.json())
			.then(data => console.log('Mail result:', data))
			.catch(err => console.error('Mail error:', err));
	}
};
