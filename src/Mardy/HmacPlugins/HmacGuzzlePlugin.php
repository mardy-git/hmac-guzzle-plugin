<?php

namespace Mardy\HmacPlugins;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Guzzle\Common\Event;
use Mardy\Hmac\Manager;

class HmacGuzzlePlugin implements EventSubscriberInterface
{
    /**
     * @var \Mardy\Hmac\Manager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param \Mardy\Hmac\Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => array('onRequestBeforeSend', -1000),
        );
    }

    /**
     * Event triggered right before sending a request
     *
     * @param \Guzzle\Common\Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        $hmac = $this->manager->encode()->toArray();

        $request = $event['request'];

        $request->setHeader('Mardy-Hmac-Hash', $hmac['hmac']);
        $request->setHeader('Mardy-Hmac-Time', $hmac['time']);
        $request->setHeader('Mardy-Hmac-Data', $hmac['data']);

        return $this;
    }
}
