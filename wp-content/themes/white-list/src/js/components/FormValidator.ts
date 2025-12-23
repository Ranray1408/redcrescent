export interface InputField {
	name: string;
	isValid: boolean;
	required: boolean;
	element: HTMLInputElement;
}

// Main form validator class
export class FormValidator {
	private form!: HTMLFormElement;

	public inputs: Record<string, InputField> = {};
	private submitBtn: HTMLButtonElement | HTMLInputElement | null = null;

	private validateRules: Record<string, RegExp> = {
		'js-validate-phone': /^[0-9+]{6,13}$/,
		'js-validate-name': /^[a-zA-Z\u0400-\u04FF]{2,30}$/,
		'js-validate-email': /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/
	};

	constructor(formSelector: string) {
		const foundForm = document.querySelector<HTMLFormElement>(formSelector);

		if (!foundForm) {
			return;
		}

		this.form = foundForm;
		this.collectElements();
		this.attachEvents();
		this.initialValidate(); // ensure submit state is correct on load
		this.initForm();
	}

	private collectElements(): void {
		// Find all inputs and submit button
		const elements = this.form.querySelectorAll<HTMLInputElement>('input');
		const submitbutton = this.form.querySelector<HTMLButtonElement | HTMLInputElement>('[type="submit"]');

		if (submitbutton) {
			this.submitBtn = submitbutton;
		}

		elements.forEach(el => {
			if (!el.name) return;

			// Default validity: required fields -> false, optional -> true (so empty optional won't block)
			const isRequired = el.hasAttribute('required');

			this.inputs[el.name] = {
				name: el.name,
				isValid: isRequired ? false : true,
				required: isRequired,
				element: el
			};
		});
	}

	private attachEvents(): void {
		// Attach input and change listeners for each input
		Object.values(this.inputs).forEach(input => {
			// input event for live validation
			input.element.addEventListener('input', () => {
				this.validateInput(input);
				this.updateSubmitState();
			});

			// blur event to validate when user leaves field
			input.element.addEventListener('blur', () => {
				this.validateInput(input);
				this.updateSubmitState();
			});
		});
	}

	private initialValidate(): void {
		// Validate existing values (useful if form prefilled)
		Object.values(this.inputs).forEach(input => {
			this.validateInput(input, false); // don't toggle UI too aggressively on init
		});

		this.updateSubmitState();
	}

	private updateSubmitState(): void {
		if (!this.submitBtn) return;
		this.submitBtn.disabled = !this.validateAll();
	}

	public validateInput(input: InputField, markUI: boolean = true): void {
		const value = input.element.value.trim();

		// --- Required check ---
		if (input.required) {
			if (value.length === 0) {
				input.isValid = false;
				if (markUI) this.markInvalid(input);
				return;
			}
			// otherwise continue to pattern checks
		}

		// --- Pattern check: if field has a validator class, apply it ---
		let patternMatched = true;
		let hasPattern = false;

		for (const ruleClass in this.validateRules) {
			if (input.element.classList.contains(ruleClass)) {
				hasPattern = true;
				const regex = this.validateRules[ruleClass];
				patternMatched = regex.test(value);
				break; // only one validation class expected per field
			}
		}

		// If field is empty and not required and has pattern -> treat empty as valid (optional field)
		if (!input.required && value === '' && hasPattern) {
			input.isValid = true;
		} else if (hasPattern) {
			input.isValid = patternMatched;
		} else {
			// No specific pattern: if not required -> valid, else already handled required case
			input.isValid = true;
		}

		if (markUI) {
			if (!input.isValid) {
				this.markInvalid(input);
			} else {
				this.markValid(input);
			}
		}
	}

	public validateAll(): boolean {
		// Ensure we re-check every input before returning
		Object.values(this.inputs).forEach(i => this.validateInput(i, false));
		return Object.values(this.inputs).every(input => input.isValid);
	}

	private markInvalid(input: InputField): void {
		input.element.classList.add('not-valid');
	}

	private markValid(input: InputField): void {
		input.element.classList.remove('not-valid');
	}

	private initForm(): void {
		this.form.addEventListener('submit', (e: SubmitEvent) => {
			if (!this.validateAll()) {
				e.preventDefault();
				console.warn('Form validation failed');
				// visual feedback: mark all invalids
				Object.values(this.inputs).forEach(i => {
					if (!i.isValid) this.markInvalid(i);
				});
				this.updateSubmitState();
				return;
			}
			console.log('Form is valid!');
		});
	}
}
