<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Withdraw
 *
 * @ORM\Table(name="withdraw")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Withdraw
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Total took from inputs, and the difference with $totalOutputs
     * need to be sent to $amountTransferredToChange (change address)
     *
     * @var string
     *
     * @ORM\Column(name="total_inputs", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     */
    private $totalInputs;

    /**
     * Total to withdraw
     *
     * @var string
     *
     * @ORM\Column(name="total_outputs", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     */
    private $totalOutputs;

    /**
     * This field is NULL until a withdraw is processed.
     * After that, if we don't need to seed any amount to a change address, this field will be set to 0.
     *
     * @var string
     *
     * @ORM\Column(name="amount_transferred_to_change", type="decimal", precision=16, scale=8, nullable=true)
     */
    private $amountTransferredToChange;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_signed", type="boolean")
     */
    private $isSigned = false;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_transaction", type="text", length=65535, nullable=true)
     */
    private $rawTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_signed_transaction", type="text", length=65535, nullable=true)
     */
    private $rawSignedTransaction;

    /**
     * Same than above.
     *
     * @var string
     *
     * @ORM\Column(name="fees", type="decimal", precision=16, scale=8, nullable=true)
     */
    private $fees;

    /**
     * @var string
     *
     * @ORM\Column(name="txid", type="string", length=255, nullable=true)
     */
    private $txid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="withdrawed_at", type="datetime", nullable=true)
     */
    private $withdrawedAt;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "AddressTransaction",
     *      mappedBy        = "withdraw"
     * )
     *
     * @Serializer\Exclude
     */
    private $withdrawInputs;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "WithdrawOutput",
     *      mappedBy        = "withdraw"
     * )
     *
     * @Serializer\Exclude
     */
    private $withdrawOutputs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->withdrawInputs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->withdrawOutputs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isSigned
     *
     * @param boolean $isSigned
     * @return Withdraw
     */
    public function setIsSigned($isSigned)
    {
        $this->isSigned = $isSigned;

        return $this;
    }

    /**
     * Get isSigned
     *
     * @return boolean
     */
    public function getIsSigned()
    {
        return $this->isSigned;
    }

    /**
     * Set rawTransaction
     *
     * @param string $rawTransaction
     * @return Withdraw
     */
    public function setRawTransaction($rawTransaction)
    {
        $this->rawTransaction = $rawTransaction;

        return $this;
    }

    /**
     * Get rawTransaction
     *
     * @return string
     */
    public function getRawTransaction()
    {
        return $this->rawTransaction;
    }

    /**
     * Set rawSignedTransaction
     *
     * @param string $rawSignedTransaction
     * @return Withdraw
     */
    public function setRawSignedTransaction($rawSignedTransaction)
    {
        $this->rawSignedTransaction = $rawSignedTransaction;

        return $this;
    }

    /**
     * Get rawSignedTransaction
     *
     * @return string
     */
    public function getRawSignedTransaction()
    {
        return $this->rawSignedTransaction;
    }

    /**
     * Set amountTransferredToChange
     *
     * @param string $amountTransferredToChange
     * @return Withdraw
     */
    public function setAmountTransferredToChange($amountTransferredToChange)
    {
        $this->amountTransferredToChange = $amountTransferredToChange;

        return $this;
    }

    /**
     * Get amountTransferredToChange
     *
     * @return string
     */
    public function getAmountTransferredToChange()
    {
        return $this->amountTransferredToChange;
    }

    /**
     * Set fees
     *
     * @param string $fees
     * @return Withdraw
     */
    public function setFees($fees)
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * Get fees
     *
     * @return string
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Set withdrawedAt
     *
     * @param \DateTime $withdrawedAt
     * @return Withdraw
     */
    public function setWithdrawedAt($withdrawedAt)
    {
        $this->withdrawedAt = $withdrawedAt;

        return $this;
    }

    /**
     * Get withdrawedAt
     *
     * @return \DateTime 
     */
    public function getWithdrawedAt()
    {
        return $this->withdrawedAt;
    }

    /**
     * Add withdrawInputs
     *
     * @param \Dizda\Bundle\AppBundle\Entity\AddressTransaction $withdrawInputs
     * @return Withdraw
     */
    public function addWithdrawInput(\Dizda\Bundle\AppBundle\Entity\AddressTransaction $withdrawInputs)
    {
        $this->withdrawInputs[] = $withdrawInputs;

        $withdrawInputs->setWithdraw($this);

        return $this;
    }

    /**
     * Remove withdrawInputs
     *
     * @param \Dizda\Bundle\AppBundle\Entity\AddressTransaction $withdrawInputs
     */
    public function removeWithdrawInput(\Dizda\Bundle\AppBundle\Entity\AddressTransaction $withdrawInputs)
    {
        $this->withdrawInputs->removeElement($withdrawInputs);
    }

    /**
     * Get withdrawInputs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWithdrawInputs()
    {
        return $this->withdrawInputs;
    }

    /**
     * @param array $withdrawInputs
     *
     * @return $this
     */
    public function setWithdrawInputs(array $withdrawInputs)
    {
        $this->withdrawInputs = $withdrawInputs;

        return $this;
    }

    /**
     * Add withdrawOutputs
     *
     * @param \Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs
     * @return Withdraw
     */
    public function addWithdrawOutput(\Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs)
    {
        $this->withdrawOutputs[] = $withdrawOutputs;

        $withdrawOutputs->setWithdraw($this);

        return $this;
    }

    /**
     * Remove withdrawOutputs
     *
     * @param \Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs
     */
    public function removeWithdrawOutput(\Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs)
    {
        $this->withdrawOutputs->removeElement($withdrawOutputs);
    }

    /**
     * @param array $withdrawOutputs
     *
     * @return $this
     */
    public function setWithdrawOutputs(array $withdrawOutputs)
    {
        $this->withdrawOutputs = $withdrawOutputs;

        return $this;
    }

    /**
     * Get withdrawOutputs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWithdrawOutputs()
    {
        return $this->withdrawOutputs;
    }

    /**
     * Set txid
     *
     * @param string $txid
     * @return Withdraw
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;

        return $this;
    }

    /**
     * Get txid
     *
     * @return string 
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * Set totalInputs
     *
     * @param string $totalInputs
     * @return Withdraw
     */
    public function setTotalInputs($totalInputs)
    {
        $this->totalInputs = $totalInputs;

        return $this;
    }

    /**
     * Get totalInputs
     *
     * @return string 
     */
    public function getTotalInputs()
    {
        return $this->totalInputs;
    }

    /**
     * Set totalOutputs
     *
     * @param string $totalOutputs
     * @return Withdraw
     */
    public function setTotalOutputs($totalOutputs)
    {
        $this->totalOutputs = $totalOutputs;

        return $this;
    }

    /**
     * Get totalOutputs
     *
     * @return string 
     */
    public function getTotalOutputs()
    {
        return $this->totalOutputs;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Withdraw
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
