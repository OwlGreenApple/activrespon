<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'ckupload',
        'private-list',
        'is_pay',
        'connect-phone',
        'verify-phone',
        'entry-google-form',
        'send-simi',
        'send-message-automation',
        'send-image-url-simi',
        'send-image-url',
        'send-message-wassenger-automation',
        'send-message-queue-system',
        'send-wamate',
        'send-image-url-wamate',
        'get-webhook',
        'get_data_api',
        'validate-api-key',
        'get-msg-status-wamate',
        'gen-coupon',
        
        //wp callback
        'send-message-queue-system-wp-activtemplate',
        'send-message-queue-system-wp-celebfans',
        'send-message-queue-system-wp-activflash',
        'send-message-queue-system-wp-digimaru',
        'send-message-queue-system-wp-ms',
        'send-message-queue-system-wp-michaelsugiharto',
        'send-message-queue-system-wp-growingrich',

        //reseller
        'api/account',
        'api/device',
        'generate/qr',
        'api/status',
        'api/send-message',
        'api/resend',
        'api/delete-device',
        'api/list',
        'api/lists',
        'api/update-list',
        'api/subscriber',
        'api/subscribers',
        'api/batch_subscriber',
        'api/update-subscriber',
        'api/celebfans'
    ];
}
