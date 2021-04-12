<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
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
        if ($this->getTransfer() <= $this->getCurrentAccount() ){
            $result = $this->setCurrentAccount($this->getCurrentAccount() - $this->getTransfer());
        }else {
           $p = $this->getCurrentAccount() - $this->getTransfer();
           $i = $p + $this->getTransfer();
           $result = $this->setCurrentAccount($this->getCurrentAccount() - $i)->setTransfer($i);
        }
        return $result;
    }
    //rentre la somme retiré du compte courant vers LIVRET A
    public function getTransferCurrentInBookletA()
    {
        return $result = $this->setBookletA($this->getBookletA() + $this->getTransfer());
    }

    //retire une somme du LIVRET A
    public function getSubBookletA()
    {
        if ($this->getTransfer() <= $this->getBookletA() ){
            $result = $this->setBookletA($this->getBookletA() - $this->getTransfer());
        }else {
            $p = $this->getBookletA() - $this->getTransfer();
            $i = $p + $this->getTransfer();
            $result = $this->setBookletA($this->getBookletA() - $i)->setTransfer($i);
        }
        return $result;
    }
    //rentre la somme retiré du LIVRET A vers COMPTE COURANT
    public function getTransferBookletAInCurrentAccount()
    {
        return $result = $this->setCurrentAccount($this->getCurrentAccount() + $this->getTransfer());
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
