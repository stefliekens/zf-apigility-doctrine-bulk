<?php

namespace Phpro\Apigility\Doctrine\Bulk\Listener;
use Phpro\Apigility\Doctrine\Bulk\Event\BulkEvent;
use Phpro\Apigility\Doctrine\Bulk\Model\Result;
use Zend\EventManager\EventManagerInterface;

/**
 * Class UpdateListener
 *
 * @package Phpro\Apigility\Doctrine\Bulk\Listener
 */
class UpdateListener extends AbstractListener
{

    const EVENT_NAME = 'update';
    const EVENT_PRIORITY = 1000;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(self::EVENT_NAME, [$this, 'update'], self::EVENT_PRIORITY);
    }

    /**
     * @param BulkEvent $event
     *
     * @return Result
     */
    public function update(BulkEvent $event)
    {
        $data = $event->getParams();
        $entity = $this->loadEntity($event);

        // Patch entity:
        $this->hydrator->hydrate((array) $data, $entity);
        $this->saveEntity($entity);;

        $event->stopPropagation(true);
        return $this->createResult(self::EVENT_NAME, $entity);
    }
}
