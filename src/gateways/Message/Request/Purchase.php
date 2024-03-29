<?php
/**
 * Purchase | src/Message/Request/Purchase.php.
 *
 * @author      Deniz Tezcan <howdy@deniztezcan.me>
 * @package		Omnipay-IngenicoePayments
 * @since       v0.1
 */

namespace craft\commerce\ingenico\Message\Request;

use Carbon\Carbon;
use Omnipay\Common\Exception\InvalidRequestException;
use craft\commerce\ingenico\Message\Response\Purchase as PurchaseResponse;
use Exception;

class Purchase extends AbstractRequest
{	
	public function getData()
    {
        $data = $this->getBaseData();
        return $data;
    }
    
    public function createResponse($data){
        return new PurchaseResponse($this, $data);
    }
}