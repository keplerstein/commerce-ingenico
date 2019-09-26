<?php
/**
 * Purchase | src/Message/Response/Purchase.php.
 *
 * @author      Deniz Tezcan <howdy@deniztezcan.me>
 * @package		Omnipay-iDeal
 * @since       v0.1
 */

namespace craft\commerce\ingenico\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;

abstract class AbstractResponse extends CommonAbstractResponse
{
    public function isSuccessful()
    {
        return ($this->data['NCERROR'] === "0" ? true : false) && $this->rootElementExists();
    }
    
    public abstract function rootElementExists();
    
    public function getError() {
        return $this->data['NCERROR'];
    }

    public function getErrorMessage()
    {
        return $this->data['NCERRORPLUS'];
    }
    
}