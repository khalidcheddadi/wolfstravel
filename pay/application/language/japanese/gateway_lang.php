<?php

/**
 * Contains the language translations for the payment gateways.
 */
$lang = [
    // General strings
    'online_payment'                 => 'オンライン決済',
    'online_payments'                => 'オンライン決済',
    'online_payment_for'             => '〜のオンライン決済',
    'online_payment_for_invoice'     => '請求書のオンライン決済',
    'online_payment_method'          => 'オンライン決済方法',
    'online_payment_creditcard_hint' => 'クレジットカードでお支払いの場合は、下記の情報を入力してください。<br/>クレジットカード情報は当社のサーバーに保存されず、セキュアな接続を通じてオンライン決済ゲートウェイに送信されます。',
    'enable_online_payments'         => 'オンライン決済を有効にする',
    'payment_provider'               => '決済プロバイダー',
    'provider_response'              => 'プロバイダー応答',
    'add_payment_provider'           => '支払い方法の追加',
    'transaction_reference'          => '取引参照番号',
    'transaction_successful'         => '取引成功',
    'payment_description'            => '請求書 %s の支払い',

    // Credit card strings
    'creditcard_cvv'              => 'CVV/CSC',
    'creditcard_details'          => 'クレジットカード情報',
    'creditcard_expiry_month'     => '有効期限-月',
    'creditcard_expiry_year'      => '有効期限-年',
    'creditcard_number'           => 'クレジットカード番号',
    'online_payment_card_invalid' => 'このクレジット カードは無効です。提供された情報を確認してください。',

    // Payment Gateway Fields
    'online_payment_apiLoginId'          => 'API ログイン ID', // Field for AuthorizeNet_AIM
    'online_payment_transactionKey'      => '取引用キー', // Field for AuthorizeNet_AIM
    'online_payment_testMode'            => 'テストモード', // Field for AuthorizeNet_AIM
    'online_payment_developerMode'       => '開発者モード', // Field for AuthorizeNet_AIM
    'online_payment_websiteKey'          => 'ウェブサイトキー', // Field for Buckaroo_Ideal
    'online_payment_secretKey'           => '秘密鍵', // Field for Buckaroo_Ideal
    'online_payment_merchantId'          => 'マーチャント Id', // Field for CardSave
    'online_payment_password'            => 'パスワード', // Field for CardSave
    'online_payment_apiKey'              => 'Api キー', // Field for Coinbase
    'online_payment_secret'              => '秘密', // Field for Coinbase
    'online_payment_accountId'           => 'アカウント Id', // Field for Coinbase
    'online_payment_storeId'             => 'ストア Id', // Field for FirstData_Connect
    'online_payment_sharedSecret'        => '共有秘密鍵', // Field for FirstData_Connect
    'online_payment_appId'               => 'アプリID', // Field for GoCardless
    'online_payment_appSecret'           => 'アプリシークレット', // Field for GoCardless
    'online_payment_accessToken'         => 'アクセストークン', // Field for GoCardless
    'online_payment_merchantAccessCode'  => 'マーチャントアクセスコード', // Field for Migs_ThreeParty
    'online_payment_secureHash'          => 'セキュアハッシュ', // Field for Migs_ThreeParty
    'online_payment_siteId'              => 'サイト ID', // Field for MultiSafepay
    'online_payment_siteCode'            => 'サイト コード', // Field for MultiSafepay
    'online_payment_accountNumber'       => '口座番号', // Field for NetBanx
    'online_payment_storePassword'       => 'ストアパスワード', // Field for NetBanx
    'online_payment_merchantKey'         => 'マーチャント キー', // Field for PayFast
    'online_payment_pdtKey'              => 'PDTキー', // Field for PayFast
    'online_payment_username'            => 'ユーザー名', // Field for Payflow_Pro
    'online_payment_vendor'              => 'ベンダー', // Field for Payflow_Pro
    'online_payment_partner'             => 'パートナー', // Field for Payflow_Pro
    'online_payment_pxPostUsername'      => 'Px Post ユーザー名', // Field for PaymentExpress_PxPay
    'online_payment_pxPostPassword'      => 'Px Post パスワード', // Field for PaymentExpress_PxPay
    'online_payment_signature'           => '署名', // Field for PayPal_Express
    'online_payment_referrerId'          => 'リファラーID', // Field for SagePay_Direct
    'online_payment_transactionPassword' => '取引パスワード', // Field for SecurePay_DirectPost
    'online_payment_subAccountId'        => 'サブアカウントID', // Field for TargetPay_Directebanking
    'online_payment_secretWord'          => '秘密の単語', // Field for TwoCheckout
    'online_payment_installationId'      => 'インストール Id', // Field for WorldPay
    'online_payment_callbackPassword'    => 'コールバックのパスワード', // Field for WorldPay

    // Status / Error Messages
    'online_payment_payment_cancelled'  => '支払いがキャンセルされました。',
    'online_payment_payment_failed'     => '支払いが失敗しました。もう一度お試しください。',
    'online_payment_payment_successful' => '請求書 %s の支払いが成功しました！',
    'online_payment_payment_redirect'   => '支払いページにリダイレクトしています。少々お待ちください...',
    'online_payment_3dauth_redirect'    => '認証のため、カード発行会社のページにリダイレクトしています。少々お待ちください...',
];
