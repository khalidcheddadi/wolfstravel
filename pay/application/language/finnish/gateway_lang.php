<?php

/**
 * Contains the language translations for the payment gateways.
 */
$lang = [
    // General strings
    'online_payment'                 => 'Verkkomaksu',
    'online_payments'                => 'Verkkomaksut',
    'online_payment_for'             => 'Verkkomaksuna',
    'online_payment_for_invoice'     => 'Verkkomaksuna laskulle',
    'online_payment_method'          => 'Verkkomaksutapa',
    'online_payment_creditcard_hint' => 'Jos haluat maksaa luottokortilla. Syötä tiedot alle.<br/>Kortin tietoja ei tallenneta meidän palvelimille ja tiedot siirretään maksunvälittäjän salatun yhteyden kautta',
    'enable_online_payments'         => 'Hyväksy verkkomaksut',
    'payment_provider'               => 'Payment Provider',
    'provider_response'              => 'Provider Response',
    'add_payment_provider'           => 'Lisää Maksupalveluntarjoaja',
    'transaction_reference'          => 'Transaction Reference',
    'transaction_successful'         => 'Transaction successful',
    'payment_description'            => 'Suoritus laskulle %s',

    // Credit card strings
    'creditcard_cvv'              => 'CVV/CSC',
    'creditcard_details'          => 'Credit Card details',
    'creditcard_expiry_month'     => 'Päättymiskuukausi',
    'creditcard_expiry_year'      => 'Päättymisvuosi',
    'creditcard_number'           => 'Luottokorttinumero',
    'online_payment_card_invalid' => 'Luottokortti ei kelpaa. Tarkista annetut tiedot',

    // Payment Gateway Fields
    'online_payment_apiLoginId'          => 'API-käyttäjätunnus', // Field for AuthorizeNet_AIM
    'online_payment_transactionKey'      => 'Maksutapahtuma-avain', // Field for AuthorizeNet_AIM
    'online_payment_testMode'            => 'Testitila', // Field for AuthorizeNet_AIM
    'online_payment_developerMode'       => 'Kehittäjätila', // Field for AuthorizeNet_AIM
    'online_payment_websiteKey'          => 'Websivu avain', // Field for Buckaroo_Ideal
    'online_payment_secretKey'           => 'Salaus avain', // Field for Buckaroo_Ideal
    'online_payment_merchantId'          => 'Myyjätunnus', // Field for CardSave
    'online_payment_password'            => 'Salasana', // Field for CardSave
    'online_payment_apiKey'              => 'API-avain:', // Field for Coinbase
    'online_payment_secret'              => 'Salausavain', // Field for Coinbase
    'online_payment_accountId'           => 'Tilinumero', // Field for Coinbase
    'online_payment_storeId'             => 'Myymälän tunnus', // Field for FirstData_Connect
    'online_payment_sharedSecret'        => 'Jaettu salausavain', // Field for FirstData_Connect
    'online_payment_appId'               => 'Sovelluksen ID (App ID)', // Field for GoCardless
    'online_payment_appSecret'           => 'Sovelluksen salausavain (App secret)', // Field for GoCardless
    'online_payment_accessToken'         => 'Käyttöoikeustunnus', // Field for GoCardless
    'online_payment_merchantAccessCode'  => 'Jälleenmyyjäntunnus', // Field for Migs_ThreeParty
    'online_payment_secureHash'          => 'Salausavain (Secure hash)', // Field for Migs_ThreeParty
    'online_payment_siteId'              => 'Sivusto ID', // Field for MultiSafepay
    'online_payment_siteCode'            => 'Sivustokoodi', // Field for MultiSafepay
    'online_payment_accountNumber'       => 'Tilinumero', // Field for NetBanx
    'online_payment_storePassword'       => 'Kaupan salasana', // Field for NetBanx
    'online_payment_merchantKey'         => 'Myyjätunnus', // Field for PayFast
    'online_payment_pdtKey'              => 'Pdt avain', // Field for PayFast
    'online_payment_username'            => 'Käyttäjanimi', // Field for Payflow_Pro
    'online_payment_vendor'              => 'Toimittaja', // Field for Payflow_Pro
    'online_payment_partner'             => 'Yhteistyökumppani', // Field for Payflow_Pro
    'online_payment_pxPostUsername'      => 'Px Post käyttäjätunnus ', // Field for PaymentExpress_PxPay
    'online_payment_pxPostPassword'      => 'Px Post salasana', // Field for PaymentExpress_PxPay
    'online_payment_signature'           => 'Allekirjoitus', // Field for PayPal_Express
    'online_payment_referrerId'          => 'Viittaaja', // Field for SagePay_Direct
    'online_payment_transactionPassword' => 'Transaction Password', // Field for SecurePay_DirectPost
    'online_payment_subAccountId'        => 'Sub Account Id', // Field for TargetPay_Directebanking
    'online_payment_secretWord'          => 'Secret Word', // Field for TwoCheckout
    'online_payment_installationId'      => 'Installation Id', // Field for WorldPay
    'online_payment_callbackPassword'    => 'Callback Password', // Field for WorldPay

    // Status / Error Messages
    'online_payment_payment_cancelled'  => 'Payment cancelled.',
    'online_payment_payment_failed'     => 'Payment failed. Please try again.',
    'online_payment_payment_successful' => 'Payment for Invoice %s successful!',
    'online_payment_payment_redirect'   => 'Please wait while we redirect you to the payment page...',
    'online_payment_3dauth_redirect'    => 'Please wait while we redirect you to your card issuer for authentication...',
];
