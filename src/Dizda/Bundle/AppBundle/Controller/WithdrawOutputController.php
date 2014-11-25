<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;

/**
 * Class WithdrawOutputController
 */
class WithdrawOutputController extends Controller
{
    /**
     * Get list of withdraws
     *
     * @param Application $application
     *
     * @REST\View(serializerGroups={"WithdrawOutputs"})
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWithdrawOutputsAction(Application $application)
    {
        $withdrawOutputs = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:WithdrawOutput')
            ->getWithdrawOutputs($application)
        ;

        return $withdrawOutputs;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawDetail"})
     *
     * @param Application $application
     * @param Withdraw    $withdrawOutput
     *
     * @return Withdraw
     */
    public function getWithdrawOutputAction(Application $application, Withdraw $withdrawOutput)
    {

        return $withdrawOutput;
    }
}
