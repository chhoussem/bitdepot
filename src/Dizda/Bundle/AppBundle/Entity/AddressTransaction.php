<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * DepositTransaction
 *
 * @ORM\Table(name="address_transaction")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\AddressTransactionRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class AddressTransaction
{
    use \Dizda\Bundle\AppBundle\Traits\Timestampable;

    const TYPE_IN  = 1;
    const TYPE_OUT = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     *
     * @Serializer\Type("integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=16, scale=8, nullable=false)
     *
     * @Serializer\Type("string")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="addresses", type="simple_array", length=65535)
     *
     * @Serializer\Type("array")
     */
    private $addresses;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_spent", type="boolean")
     */
    private $isSpent = false;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Address", inversedBy="transactions")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Exclude
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="DepositTopup", mappedBy="transaction")
     *
     * @Serializer\Exclude
     **/
    private $topup;

    /**
     * WithdrawInputs
     *
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Withdraw", inversedBy="withdrawInputs")
     * @ORM\JoinColumn(name="withdraw_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Exclude
     */
    private $withdraw;

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
     * Set amount
     *
     * @param string $amount
     * @return AddressTransaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return AddressTransaction
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return AddressTransaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set addresses
     *
     * @param array $addresses
     * @return AddressTransaction
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Get addresses
     *
     * @return array 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Set address
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $address
     * @return AddressTransaction
     */
    public function setAddress(\Dizda\Bundle\AppBundle\Entity\Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Address 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set topup
     *
     * @param \Dizda\Bundle\AppBundle\Entity\DepositTopup $topup
     * @return AddressTransaction
     */
    public function setTopup(\Dizda\Bundle\AppBundle\Entity\DepositTopup $topup = null)
    {
        $this->topup = $topup;

        return $this;
    }

    /**
     * Get topup
     *
     * @return \Dizda\Bundle\AppBundle\Entity\DepositTopup 
     */
    public function getTopup()
    {
        return $this->topup;
    }

    /**
     * Set isSpent
     *
     * @param boolean $isSpent
     * @return AddressTransaction
     */
    public function setIsSpent($isSpent)
    {
        $this->isSpent = $isSpent;

        return $this;
    }

    /**
     * Get isSpent
     *
     * @return boolean 
     */
    public function getIsSpent()
    {
        return $this->isSpent;
    }

    /**
     * Set withdraw
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Withdraw $withdraw
     *
     * @return AddressTransaction
     */
    public function setWithdraw(\Dizda\Bundle\AppBundle\Entity\Withdraw $withdraw = null)
    {
        $this->withdraw = $withdraw;

        return $this;
    }

    /**
     * Get withdraw
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Withdraw 
     */
    public function getWithdraw()
    {
        return $this->withdraw;
    }
}
