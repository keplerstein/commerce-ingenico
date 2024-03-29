<?php
/**
 * CompletePurchase | src/Message/Request/CompletePurchase.php.
 *
 * @author      Deniz Tezcan <howdy@deniztezcan.me>
 * @package		Omnipay-IngenicoePayments
 * @since       v0.1
 */

namespace craft\commerce\ingenico\Message\Request;

use Carbon\Carbon;
use Omnipay\Common\Exception\InvalidRequestException;
use craft\commerce\ingenico\Message\Response\CompletePurchase as CompletePurchaseResponse;
use Exception;

class CompletePurchase extends AbstractRequest
{	
	public function getData()
    {	
    	return $this->getTransaction();
    }
    
    public function createResponse($data){
    	return new CompletePurchaseResponse($this, $data);
    }
}