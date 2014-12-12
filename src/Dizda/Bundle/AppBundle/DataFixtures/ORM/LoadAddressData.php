<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Address;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadAddressData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadAddressData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Default address
        $address1 = (new Address())
            ->setValue('3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU')
            ->setIsExternal(true)
            ->setDerivation(1)
            ->setBalance('0.00020000') // Be careful, SQLite doesn't shows 0's positive digit. (eg. if we set 0, SQLite returns "0" instead of "0.00000000")
            ->setRedeemScript('52210244d61e612701fbefe46feb51ced989473a1c83233aaaca16c27a4fa8511df459210364759d648cbf406eed9b68d01c7cc7ebbdadb966472b042c8fcf538505ba954221021bb3d58673ba887980a91d4a56fdeaaf263f500e3db581bec3140b684895a9df53ae')
            ->setScriptPubKey('a914d40ac36da2473ef892ebe5223ab928824acf735487')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        // Will be used for the first deposit as expected amount
        $address2 = (new Address())
            ->setValue('3QYr3UHFsTbEKVheCRx5CMJSiEECS4ZWX4')
            ->setIsExternal(true)
            ->setDerivation(2)
            ->setBalance('0')
            ->setRedeemScript('52210290b020f94903144c87810bbd95bf023bb923154923a34c90ea47343744192c0821025fa6de2cebb0d6a33f3d0d6f02639ae9679b7db977a0b3bc00541830a79440242103eb3ff8af9eb5a406665d8a33fe86e4d7b7a4648c614dc3275e09729c4ab24f4e53ae')
            ->setScriptPubKey('a914fabf5144b9604647eeee589eb94454eb69c5ef7b87')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        // Will be used for the second deposit as Topup address
        $address3 = (new Address())
            ->setValue('3MxR1yHVpfB7cXULzpetoyNVvUeqhoaJhE')
            ->setIsExternal(true)
            ->setDerivation(3)
            ->setBalance('0')
            ->setRedeemScript('522102087a30059abeb82ceb8b0a0413c16307a0d29cec97073bbc8d4a584e60f19f232102a3ce2f9b90ac59d6cd5a2a01b3c1d5e9e379627ae9c9e1b2a3542f8cf80f7ae721029bfbac8f2bfca762df3ecd1500bdf291a9dad7c7491533a8b6d8925c9039432f53ae')
            ->setScriptPubKey('a914de4c085bbec53538636e7d9533922eaccf4f535d87')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $manager->persist($address1);
        $manager->persist($address2);
        $manager->persist($address3);
        $manager->flush();

        $this->addReference('address-1', $address1);
        $this->addReference('address-2', $address2);
        $this->addReference('address-3', $address3);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}