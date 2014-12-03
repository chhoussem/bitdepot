<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Doctrine\ORM\EntityManager;
use Nbobtc\Bitcoind\Bitcoind;
use Psr\Log\LoggerInterface;

/**
 * Class WithdrawListener
 */
class WithdrawListener
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Nbobtc\Bitcoind\Bitcoind
     */
    private $bitcoind;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param LoggerInterface     $logger
     * @param Bitcoind            $bitcoind
     * @param EntityManager       $em
     */
    public function __construct(LoggerInterface $logger, Bitcoind $bitcoind, EntityManager $em)
    {
        $this->logger     = $logger;
        $this->bitcoind   = $bitcoind;
        $this->em         = $em;
    }

    /**
     * @param WithdrawEvent $event
     */
    public function onCreate(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $withdraw->setChangeAddressAmount(bcsub($withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees(), 8));

        if (!$withdraw->isSpendable()) {
            // TODO: throw exception here
            return false;
        }

        // $withdraw->getChangeAddressAmount() > 0
        if (bccomp($withdraw->getChangeAddressAmount(), '0', 8) === 1) {
            // Get a changeaddress
            $changeAddress = $this->em->getRepository('DizdaAppBundle:Address')->getOneFreeAddress(false);

            // Sending change to a changeaddress
            $withdraw->setChangeAddress($changeAddress);
        }

        // Let bitcoind to create the rawtransaction
        $rawTransaction = $this->bitcoind->createrawtransaction(
            $withdraw->getWithdrawInputsSerializable(),
            $withdraw->getWithdrawOutputsSerializable()
        );

        $withdraw->setRawTransaction($rawTransaction);
    }

    /**
     * When the withdraw raw hex is sended to the blockchain
     *
     * @param WithdrawEvent $event
     */
    public function onSend(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $transactionId = $this->bitcoind->sendrawtransaction(
            $withdraw->getRawSignedTransaction()
        );

        $withdraw->withdrawed($transactionId);

        // Mark all inputs as spent, and reset the balance of each related addresses
        foreach ($withdraw->getWithdrawInputs() as $input) {
            $input->setIsSpent(true);
            $input->getAddress()->setBalance(0);
        }

        // Dispatch an event here, to launch a callback to all outputs
    }
}
