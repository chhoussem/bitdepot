<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Application;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadApplicationData
 * @codeCoverageIgnore
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadApplicationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $app1 = (new Application())
            ->setName('Application-Fixture')
            ->setAppId('Application-Fixture')
            ->setAppSecret('Application-Fixture')
            ->setConfirmationsRequired(1)
            ->setCallbackEndpoint('http://callback-test.com')
            ->setKeychain($this->getReference('keychain-1'))
            ->setDepositsExpiresAfter('+360 days')
            ->setDepositsTopupsExpiresAfter('+360 days')
        ;

        $manager->persist($app1);
        $manager->flush();

        $this->addReference('application-1', $app1);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}