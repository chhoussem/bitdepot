<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;

use Dizda\Bundle\BlockchainBundle\Client\HttpClient;
use JMS\Serializer\SerializerInterface;
use Dizda\Bundle\BlockchainBundle\Model\AddressAbstract;


interface BlockchainWatcherInterface
{

    public function __construct(HttpClient $client, SerializerInterface $serializer);

    /**
     * @param string $address
     * @param bool   $withTransactions
     *
     * @return AddressAbstract
     */
    public function getAddress($address, $withTransactions);
    public function getAddresses(array $addresses, $withTransactions);

    public function getTransaction($txid);

    public function getAddressUnspentOutputs($address);
    public function getAddressesUnspentOutputs(array $address);

    public function getTransactionsByBlock($address);

    /**
     * @param string $address
     *
     * @return array[]
     */
    public function getTransactionsByAddress($address);

}