<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Keplerstein
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\ingenico\models\forms;

use craft\commerce\models\payments\BasePaymentForm;

class IngenicoOffsitePaymentForm extends BasePaymentForm
{
    public $paymentMethod;

    public $issuer;
}