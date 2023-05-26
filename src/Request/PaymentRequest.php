<?php

/**
 * Class PaymentRequest
 */

namespace Paytrail\SDK\Request;

/**
 * Class PaymentRequest
 *
 * This class is used to create a payment request object for
 * the Paytrail\SDK\Client class.
 *
 * @see https://paytrail.github.io/api-documentation/#/?id=create-request-body
 * @package Paytrail\SDK\Request
 */
class PaymentRequest extends AbstractPaymentRequest
{
    protected ?string $orderId;

    public function setOrderId(string $orderId): PaymentRequest
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }
}
