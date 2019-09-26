<?php

namespace craft\commerce\ingenico;

use craft\commerce\ingenico\gateways\Gateway;
use craft\commerce\services\Gateways;
use craft\events\RegisterComponentTypesEvent;
use yii\base\Event;


/**
 * Plugin represents the Ingenico integration plugin.
 *
 * @author Keplerstein <hello@keplerstein.com>
 * @since  1.0
 */
class Plugin extends \craft\base\Plugin
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(Gateways::class, Gateways::EVENT_REGISTER_GATEWAY_TYPES,  function(RegisterComponentTypesEvent $event) {
            $event->types[] = Gateway::class;
        });
    }
}
