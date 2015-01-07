<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WithdrawManager
 */
class WithdrawManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EntityManager            $em
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->em         = $em;
        $this->logger     = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Search if there are outputs available to group them into a withdraw.
     *
     * @param Application $application
     *
     * @return bool|ArrayCollection
     */
    public function search(Application $application)
    {
        $outputs = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->getWhereWithdrawIsNull($application);

        if ($application->getGroupWithdrawsByQuantity() === null
            || count($outputs) < $application->getGroupWithdrawsByQuantity()) {

            return false;
        }

        return $outputs;
    }

    /**
     * Create a withdraw according to outputs
     * If sufficient money available, we can proceed to the creation of the withdraw.
     * Otherwise, the function will return null.
     *
     * @param Application $application
     * @param array       $outputs
     *
     * @return null|Withdraw
     */
    public function create(Application $application, array $outputs)
    {
        $withdraw = new Withdraw();
        $withdraw->setKeychain($application->getKeychain());
        $withdraw->setFees('0.0001');

        // Setting outputs
        $withdraw->setOutputs($outputs);

        $transactions = $this->em->getRepository('DizdaAppBundle:AddressTransaction')
            ->getSpendableTransactions()
//            ->getSpendableTransactions($application, $withdraw->getTotalOutputs())
        ;

        // Setting inputs
        $withdraw->setInputs($transactions);

        // $withdraw->getTotalInputs() < $withdraw->getTotalOutputsWithFees()
        if (bccomp($withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees(), 8) === -1) {
            // if the amount of inputs is insufficient, we give up the creation of the withdraw
            $this->logger->warning(
                'WithdrawManager: Insufficient amount available to create a new withdraw as requested. Available/Requested',
                [ $withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees() ]
            );

            return null;
        }

        $this->em->persist($withdraw);

        // Create the rawtransaction
        $this->dispatcher->dispatch(AppEvents::WITHDRAW_CREATE, new WithdrawEvent($withdraw));

        $this->em->flush();

        return $withdraw;
    }

    /**
     * Saving data received from Angular
     *
     * @param Withdraw $withdraw          The original $withdraw fetched from DB
     * @param array    $withdrawSubmitted The json Withdraw data submitted by angular
     */
    public function save(Withdraw $withdraw, $withdrawSubmitted)
    {
        if ($withdrawSubmitted['raw_signed_transaction']) {
            $withdraw->setRawSignedTransaction($withdrawSubmitted['raw_signed_transaction']);

            // dispatch event here
        }

        // Add signature if submitted
        if ($withdrawSubmitted['signed_by']) {
            $pubkey = $this->em->getRepository('DizdaAppBundle:PubKey')->findOneBy([
                'keychain' => $withdraw->getKeychain(),
                'value'    => $withdrawSubmitted['signed_by']
            ]);

            $withdraw->addSignature($pubkey);

            // dispatch event there, like PushOver through Rabbit ?
        }

        if ($withdrawSubmitted['is_signed'] === true) {
            $withdraw->setIsSigned(true);

            // dispatch event to sendrawtransaction !
            $this->dispatcher->dispatch(AppEvents::WITHDRAW_SEND, new WithdrawEvent($withdraw));
        }
    }
}
