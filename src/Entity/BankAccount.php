<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;
use function MongoDB\Driver\Monitoring\removeSubscriber;

/**
 * @ORM\Entity(repositoryClass=BankAccountRepository::class)
 */
class BankAccount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $uniqueId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentAccount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bookletA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transfer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accountIsActive;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userBankAccount")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userBelongs;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function __toString() {

        return $this->getUniqueId();
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(string $uniqueId): self
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $uniqueId; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        $this->uniqueId = $uniqueId.''.$string;

        return $this;
    }
//retire une somme du compte courant
    public function getSubCurrentAccount()
    {
        if ($this->getTransfer() > 0){
            $result = $this->setCurrentAccount($this->getCurrentAccount() - $this->getTransfer());
        }

       return $result;
    }
//rentre la somme retiré du compte courant vers LIVRET A
    public function getTransferCurrentInBookletA()
    {
        if ($this->getTransfer() > 0){
            $result = $this->setBookletA($this->getBookletA() + $this->getTransfer());
        }

        return $result;
    }

    //retire une somme du LIVRET A
    public function getSubBookletA()
    {
        if ($this->getTransfer() > 0){
            $result = $this->setBookletA($this->getBookletA() - $this->getTransfer());
        }
        return $result;
    }

    //rentre la somme retiré du LIVRET A vers COMPTE COURANT
    public function getTransferBookletAInCurrentAccount()
    {
        if ($this->getTransfer() > 0){
            $result = $this->setCurrentAccount($this->getCurrentAccount() + $this->getTransfer());
        }
        return $result;
    }

    public function getCurrentAccount(): ?int
    {
        return $this->currentAccount;
    }

    public function setCurrentAccount(?int $currentAccount): self
    {
        $this->currentAccount = $currentAccount;

        return $this;
    }

    public function getBookletA(): ?int
    {
        return $this->bookletA;
    }

    public function setBookletA(?int $bookletA): self
    {
        $this->bookletA = $bookletA;

        return $this;
    }

    public function getTransfer(): ?int
    {
        return $this->transfer;
    }

    public function setTransfer(?int $transfer): self
    {
        $this->transfer = $transfer;

        return $this;
    }

    public function getAccountIsActive(): ?bool
    {
        return $this->accountIsActive;
    }

    public function setAccountIsActive(bool $accountIsActive): self
    {
        $this->accountIsActive = $accountIsActive;

        return $this;
    }

    public function getUserBelongs(): ?User
    {
        return $this->userBelongs;
    }

    public function setUserBelongs(?User $userBelongs): self
    {
        $this->userBelongs = $userBelongs;

        return $this;
    }
}
