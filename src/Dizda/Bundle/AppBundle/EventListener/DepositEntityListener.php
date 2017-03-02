<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class DepositEntityListener
 *
 * Watch Expected Deposit only
 */
class DepositEntityListener
{
    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $depositProducer;

    /**
     * @var Deposit
     */
    private $deposit;

    /**
     * @param Producer $depositProducer
     */
    public function __construct(Producer $depositProducer)
    {
        $this->depositProducer = $depositProducer;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $deposit = $event->getEntity();

        // When a deposit is occurred, we verify that is not a Topup reload
        if (!$deposit instanceof Deposit) {
            return;
        }

        // If RabbitMQ is not available, an Exception will be thrown, and a rollback of transactions will be made
        // so we will not loose any topup transactions if an error occur.
        if ($deposit->getType() !== Deposit::TYPE_AMOUNT_EXPECTED) {
            return;
        }

        // If the deposit is already processed, we don't push it a second time
        // TODO: What if the Deposit is not fulfilled?
        if ($deposit->getQueueStatus() !== Deposit::QUEUE_STATUS_QUEUED) {
            return;
        }

        $this->deposit = $deposit;

        $event->getEntityManager()->getEventManager()->addEventListener(array(Events::postFlush), $this);
    }

    /**
     * @param PostFlushEventArgs $event
    */
    public function postFlush(PostFlushEventArgs $event)
    {
        $this->depositProducer->publish(serialize($this->deposit->getId()));
    }
}
