<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Keplerstein
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\ingenico\gateways;

use Craft;
use craft\commerce\base\RequestResponseInterface;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\Transaction;
use craft\commerce\ingenico\models\RequestResponse;
use craft\commerce\omnipay\base\OffsiteGateway;
use craft\helpers\UrlHelper;
use craft\web\View;
use craft\commerce\ingenico\models\forms\IngenicoOffsitePaymentForm;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use craft\commerce\ingenico\OmniGateway as OmnipayGateway;
use yii\base\NotSupportedException;

/**
 * Gateway represents Ingenico gateway
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since     1.0
 */
class Gateway extends OffsiteGateway
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $apiKey;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function populateRequest(array &$request, BasePaymentForm $paymentForm = null)
    {
        if ($paymentForm) {
            /** @var IngenicoOffsitePaymentForm $paymentForm */
            if ($paymentForm->paymentMethod) {
                $request['paymentMethod'] = $paymentForm->paymentMethod;
            }

            if ($paymentForm->issuer) {
                $request['issuer'] = $paymentForm->issuer;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function completePurchase(Transaction $transaction): RequestResponseInterface
    {
        if (!$this->supportsCompletePurchase()) {
            throw new NotSupportedException(Craft::t('commerce', 'Completing purchase is not supported by this gateway'));
        }

        $request = $this->createRequest($transaction);
        $request['transactionReference'] = $transaction->reference;
        $completeRequest = $this->prepareCompletePurchaseRequest($request);

        return $this->performRequest($completeRequest, $transaction);
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('commerce', 'Ingenico');
    }

    /**
     * @inheritdoc
     */
    public function supportsWebhooks(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getWebhookUrl(array $params = []): string
    {
        return UrlHelper::actionUrl('commerce/payments/complete-payment', $params);
    }

    /**
     * @inheritdoc
     */
    public function getPaymentTypeOptions(): array
    {
        return [
            'purchase' => Craft::t('commerce', 'Purchase (Authorize and Capture Immediately)')
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('commerce-ingenico/gatewaySettings', ['gateway' => $this]);
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormModel(): BasePaymentForm
    {
        return new IngenicoOffsitePaymentForm();
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormHtml(array $params)
    {
        try {
            $defaults = [
                'gateway' => $this,
                'paymentForm' => $this->getPaymentFormModel(),
                'paymentMethods' => $this->fetchPaymentMethods(),
                'issuers' => $this->fetchIssuers(),
            ];
        } catch (\Throwable $exception) {
            // In case this is not allowed for the account
            return parent::getPaymentFormHtml($params);
        }

        $params = array_merge($defaults, $params);

        $view = Craft::$app->getView();

        $previousMode = $view->getTemplateMode();
        $view->setTemplateMode(View::TEMPLATE_MODE_CP);

        $html = $view->renderTemplate('commerce-ingenico/paymentForm', $params);
        $view->setTemplateMode($previousMode);

        return $html;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['paymentType', 'compare', 'compareValue' => 'purchase'];

        return $rules;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function fetchPaymentMethods(array $parameters = [])
    {
        $paymentMethodsRequest = $this->createGateway()->fetchPaymentMethods($parameters);

        return $paymentMethodsRequest->sendData($paymentMethodsRequest->getData())->getPaymentMethods();
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function fetchIssuers(array $parameters = [])
    {
        $issuersRequest = $this->createGateway()->fetchIssuers($parameters);

        return $issuersRequest->sendData($issuersRequest->getData())->getIssuers();
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createGateway(): AbstractGateway
    {
        /** @var OmnipayGateway $gateway */
        $gateway = static::createOmnipayGateway($this->getGatewayClassName());

        // $gateway->setApiKey(Craft::parseEnv($this->apiKey));

        $gateway->setMode('test');
        $gateway->setPSPID('lawvechter');
        $gateway->setLanguage('nl_NL');
        $gateway->setShaIn('4Yv14789gUix1707');
        $gateway->setShaOut('Jw£Pcgj£1NuP1t>j3sq');

        return $gateway;
    }

    /**
     * @inheritdoc
     */
    protected function getGatewayClassName()
    {
        return '\\' . OmnipayGateway::class;
    }

    /**
     * @inheritdoc
     */
    protected function prepareResponse(ResponseInterface $response, Transaction $transaction): RequestResponseInterface
    {
        return new RequestResponse($response, $transaction);
    }
}
