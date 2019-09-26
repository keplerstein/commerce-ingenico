<?php
/**
 * Gateway | src/Gateway.php.
 *
 * @author      Deniz Tezcan <howdy@deniztezcan.me>
 * @package		Omnipay-IngenicoePayments
 * @since       v0.1
 */

namespace craft\commerce\ingenico;

use craft\commerce\ingenico\Message\Request\Purchase;
use craft\commerce\ingenico\Message\Request\CompletePurchase;

class OmniGateway extends AbstractGateway
{

	public function getName()
    {
        return 'IngenicoePayments';
    }

	public function getDefaultParameters()
    {
        return [
            "mode"					=> "",
            "PSPID"					=> "",
            "language"				=> "",
            "shaIn"					=> "",
            "shaOut"				=> "",
        ];
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(Purchase::class, $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchase::class, $parameters);
    }

}