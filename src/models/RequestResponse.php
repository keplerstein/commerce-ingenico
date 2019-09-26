<?php

namespace craft\commerce\ingenico\models;

use Craft;
use craft\commerce\omnipay\base\RequestResponse as BaseRequestResponse;

/**
 * @author    Keplerstein <hello@keplerstein.com>
 * @since     1.0
 */
class RequestResponse extends BaseRequestResponse
{
    /**
     * @inheritdoc
     */
    public function getMessage(): string
    {
        $data = $this->response->getData();

        if (is_array($data) && !empty($data['status'])) {
            switch ($data['status']) {
                case 'canceled':
                    return Craft::t('commerce-ingenico', 'The payment was canceled.');
                case 'failed':
                    return Craft::t('commerce-ingenico', 'The payment failed.');
            }
        }

        return (string)$this->response->getMessage();
    }
}
