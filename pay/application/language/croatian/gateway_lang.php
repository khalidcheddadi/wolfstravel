<?php

/**
 * Contains the language translations for the payment gateways.
 */
$lang = [
    // General strings
    'online_payment'                 => 'Online Plaćanje',
    'online_payments'                => 'Sva online plaćanja',
    'online_payment_for'             => 'Plaćanje online za',
    'online_payment_for_invoice'     => 'Plaćanje online za račun',
    'online_payment_method'          => 'Način online plaćanja',
    'online_payment_creditcard_hint' => 'Ukoliko želite platiti kreditnom karticom molimo unesite ispod slijedeće informacije.<br/>Podaci o kreditnim karticama se ne pohranjuju na našim poslužiteljima i biti će proslijeđene na online payment gateway (poslužitelj za online plaćanja) korištenjem sigurne (enkriptirane) veze.',
    'enable_online_payments'         => 'Omogućite online plaćanja',
    'payment_provider'               => 'Payment Provider',
    'provider_response'              => 'Provider Response',
    'add_payment_provider'           => 'Dodajte pružatelja usluge plaćanja',
    'transaction_reference'          => 'Transaction Reference',
    'transaction_successful'         => 'Transaction successful',
    'payment_description'            => 'Plaćanje za račun %s (Opis plaćanja)',

    // Credit card strings
    'creditcard_cvv'              => 'CVV / CSC kod (broj na pozadini kreditne kartice)',
    'creditcard_details'          => 'Credit Card details',
    'creditcard_expiry_month'     => 'Mjesec istjecanja kartice',
    'creditcard_expiry_year'      => 'Godina istjecanja kartice',
    'creditcard_number'           => 'Broj kreditne kartice',
    'online_payment_card_invalid' => 'Ova kreditna kartica je neispravna. Moimo provjerite pripadajuće informacije.',

    // Payment Gateway Fields
    'online_payment_apiLoginId'          => 'API login identifikator (field label davatelja usluge online plaćanja)', // Field for AuthorizeNet_AIM
    'online_payment_transactionKey'      => 'AuthorizeNet AIM Transakcijski ključ', // Field for AuthorizeNet_AIM
    'online_payment_testMode'            => 'AuthorizeNet AIM Probni način rada', // Field for AuthorizeNet_AIM
    'online_payment_developerMode'       => 'AuthorizeNet AIM Način rada za razvojne programere', // Field for AuthorizeNet_AIM
    'online_payment_websiteKey'          => 'Buckaroo Ideal - Ključ web stranice', // Field for Buckaroo_Ideal
    'online_payment_secretKey'           => 'Buckaroo Ideal - Tajni ključ', // Field for Buckaroo_Ideal
    'online_payment_merchantId'          => 'CardSave Identifikator trgovca', // Field for CardSave
    'online_payment_password'            => 'CardSafe Lozinka', // Field for CardSave
    'online_payment_apiKey'              => 'Coinbase API ključ', // Field for Coinbase
    'online_payment_secret'              => 'Coinbase Online payment Tajni kod', // Field for Coinbase
    'online_payment_accountId'           => 'Coinbase Online payment identifikator korisničkog računa', // Field for Coinbase
    'online_payment_storeId'             => 'FirstData Conenct Identifikator trgovine', // Field for FirstData_Connect
    'online_payment_sharedSecret'        => 'FirstData Connect Djeljeni ključ (Online payment shared secret)', // Field for FirstData_Connect
    'online_payment_appId'               => 'GoCardless identifikator aplikacije', // Field for GoCardless
    'online_payment_appSecret'           => 'GoCardless Ključ aplikacije', // Field for GoCardless
    'online_payment_accessToken'         => 'GoCardless Token pružatelja usluge online plaćanja', // Field for GoCardless
    'online_payment_merchantAccessCode'  => 'Migs 3P Pristupni kod trgovca', // Field for Migs_ThreeParty
    'online_payment_secureHash'          => 'Migs 3P Sigurnosni kod (hash)', // Field for Migs_ThreeParty
    'online_payment_siteId'              => 'MultiSafePay identifikator web stranice', // Field for MultiSafepay
    'online_payment_siteCode'            => 'MultiSafePay Kod poslužitelja autorizacije', // Field for MultiSafepay
    'online_payment_accountNumber'       => 'Netbanx Broj transakcijskog računa', // Field for NetBanx
    'online_payment_storePassword'       => 'NetBanx SMTP Lozinka', // Field for NetBanx
    'online_payment_merchantKey'         => 'PayFast Ključ trgovca', // Field for PayFast
    'online_payment_pdtKey'              => 'Payfast PDT ključ', // Field for PayFast
    'online_payment_username'            => 'Payflow Pro Korisničko ime', // Field for Payflow_Pro
    'online_payment_vendor'              => 'Payflow Pro Pružatelj usluge', // Field for Payflow_Pro
    'online_payment_partner'             => 'PayFlow Pro Partner', // Field for Payflow_Pro
    'online_payment_pxPostUsername'      => 'PaymentExpress PxPay korisničko ime', // Field for PaymentExpress_PxPay
    'online_payment_pxPostPassword'      => 'PaymentExpress PxPay Lozinka', // Field for PaymentExpress_PxPay
    'online_payment_signature'           => 'Potpis', // Field for PayPal_Express
    'online_payment_referrerId'          => 'SagePay Direct idenfikator računa trgovca', // Field for SagePay_Direct
    'online_payment_transactionPassword' => 'Lozinka za transakciju', // Field for SecurePay_DirectPost
    'online_payment_subAccountId'        => 'TargetPay Directebanking Coinbase Online payment identifikator podračuna', // Field for TargetPay_Directebanking
    'online_payment_secretWord'          => 'TwoCheckout tajna riječ', // Field for TwoCheckout
    'online_payment_installationId'      => 'WorldPay identifikator instalacije', // Field for WorldPay
    'online_payment_callbackPassword'    => 'WorldPay povratna lozinka', // Field for WorldPay

    // Status / Error Messages
    'online_payment_payment_cancelled'  => 'Plaćanje otkazano.',
    'online_payment_payment_failed'     => 'Plaćanje nije uspjelo. Molimo Vas pokušajte ponovo.',
    'online_payment_payment_successful' => 'Plaćanje za račun %s je uspješno provedeno!',
    'online_payment_payment_redirect'   => 'Molimo pričekajte dok Vas preusmjerimo na stranicu za plaćanje...',
    'online_payment_3dauth_redirect'    => 'Molimo pričekajte dok vas preusmjerimo na izdavatelja vaše kartice za autentifikaciju...',
];
