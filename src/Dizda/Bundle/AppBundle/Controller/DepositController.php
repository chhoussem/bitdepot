<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\Request\GetDepositsRequest;
use Dizda\Bundle\AppBundle\Request\PostDepositsRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class DepositController
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class DepositController extends Controller
{
    /**
     * Get list of deposits
     *
     * @REST\View(serializerGroups={"Deposits"})
     * @Security("has_role('DEPOSIT_LIST')")
     *
     * @param Request $request
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDepositsAction(Request $request)
    {
        $filters = (new GetDepositsRequest($request->query->all()))->options;

        $this->denyAccessUnlessGranted('access', $filters['application_id']);

        $deposits = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:Deposit')
            ->getDeposits($filters)
        ;

        return $deposits;
    }

    /**
     * @REST\View(serializerGroups={"Deposits"})
     * @Security("has_role('DEPOSIT_CREATE')")
     *
     * @param Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Deposit
     * @throws \Exception
     */
    public function postDepositsAction(Request $request)
    {
        $depositSubmitted = (new PostDepositsRequest($request->request->all()))->options;

        $this->denyAccessUnlessGranted('access', $depositSubmitted['application_id']);

        $deposit = $this->get('dizda_app.manager.deposit')->create($depositSubmitted);

        $this->get('doctrine.orm.default_entity_manager')->flush();

        return $deposit;
    }
}
