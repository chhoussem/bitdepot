<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\BlockchainBundle\Model\Insight\Transaction;
use Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionInput;
use Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionOutput;
use Doctrine\Common\Collections\ArrayCollection;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Dizda\Bundle\AppBundle\Manager\AddressManager;

/**
 * Class AddressManagerTest
 */
class AddressManagerTest extends ProphecyTestCase
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
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $manager;

    /**
     * @var \Dizda\Bundle\AppBundle\Service\AddressService
     */
    private $addressService;

    /**
     * AddressManager::saveTransactions()
     */
    public function testSaveTransactionsInputs()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $address->getValue()->shouldBeCalled()->willReturn('addressExpected');
        $address->hasTransaction('transactionId', AddressTransaction::TYPE_OUT, 5)
            ->shouldBeCalled()
            ->willReturn(false)
        ; // check that doesn't match the input

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\AddressTransaction'))->shouldBeCalledTimes(1);

        $return = $this->manager->saveTransactions($address->reveal(), new ArrayCollection($this->getDummyTransactionsInput()));
        $this->assertCount(0, $return);
    }

    /**
     * AddressManager::saveTransactions()
     */
    public function testSaveTransactionsOutputs()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $address->getValue()->shouldBeCalled()->willReturn('addressExpectedIN');
        $address->hasTransaction('transactionId', AddressTransaction::TYPE_IN, 6)
            ->shouldBeCalled()
            ->willReturn(false)
        ; // check that doesn't match the output

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\AddressTransaction'))->shouldBeCalledTimes(1);

        $return = $this->manager->saveTransactions($address->reveal(), new ArrayCollection($this->getDummyTransactionsOutput()));
        $this->assertCount(1, $return);
    }

    /**
     * AddressManager::saveTransactions()
     */
    public function testSaveTransactionsHasTransactionsContinueInput()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $address->getValue()->shouldBeCalled()->willReturn('addressExpected');
        $address->hasTransaction('transactionId', AddressTransaction::TYPE_OUT, 5)
            ->shouldBeCalled()
            ->willReturn(true)
        ; // check that match the input

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\AddressTransaction'))->shouldNotBeCalled();

        $return = $this->manager->saveTransactions($address->reveal(), new ArrayCollection($this->getDummyTransactionsNone()));
        $this->assertCount(0, $return);
    }

    /**
     * AddressManager::saveTransactions()
     */
    public function testSaveTransactionsHasTransactionsContinueOutput()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $address->getValue()->shouldBeCalled()->willReturn('addressExpectedIN');
        $address->hasTransaction('transactionId', AddressTransaction::TYPE_IN, 6)
            ->shouldBeCalled()
            ->willReturn(true)
        ; // check that match the output

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\AddressTransaction'))->shouldNotBeCalled();

        $return = $this->manager->saveTransactions($address->reveal(), new ArrayCollection($this->getDummyTransactionsNone()));
        $this->assertCount(0, $return);
    }

    /**
     * @return array
     */
    public function getDummyTransactionsInput()
    {
        return [
            (new Transaction())
                ->setTxid('transactionId')
                ->setInputs([
                    (new TransactionInput())
                        ->setAddress('addressExpected')
                        ->setIndex(5)
                ])
                ->setOutputs(
                    (new TransactionOutput())
                )
        ];
    }

    /**
     * @return array
     */
    public function getDummyTransactionsOutput()
    {
        return [
            (new Transaction())
                ->setTxid('transactionId')
                ->setInputs([
                    (new TransactionInput())
                ])
                ->setOutputs([
                    (new TransactionOutput())
                        ->setAddresses(['addresses' => ['addressExpectedIN']])
                        ->setIndex(6)
                ])
        ];
    }

    /**
     * @return array
     */
    public function getDummyTransactionsNone()
    {
        return [
            (new Transaction())
                ->setTxid('transactionId')
                ->setInputs([
                    (new TransactionInput())
                        ->setAddress('addressExpected')
                        ->setIndex(5)
                ])
                ->setOutputs([
                    (new TransactionOutput())
                        ->setAddresses(['addresses' => ['addressExpectedIN']])
                        ->setIndex(6)
                ])
        ];
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->em           = $this->prophesize('Doctrine\ORM\EntityManager');
        $this->logger       = $this->prophesize('Psr\Log\LoggerInterface');
        $this->dispatcher   = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->addressService = $this->prophesize('Dizda\Bundle\AppBundle\Service\AddressService');
        $this->manager      = new AddressManager(
            $this->em->reveal(),
            $this->logger->reveal(),
            $this->dispatcher->reveal(),
            $this->addressService->reveal()
        );
    }
}
