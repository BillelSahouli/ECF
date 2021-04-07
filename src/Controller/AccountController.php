<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Exercice;
use App\Entity\User;
use App\Form\TransferMoneyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;

    /**
     * AccountController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }


    /**
     * @Route("/compte/", name="account")
     */
    public function index(): Response
    {
        $userInformation = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('account/index.html.twig',
        [
            'userInformation' => $userInformation,
        ]);
    }

    /**
     * @Route("/compte/virement-compte-courant/{id}", name="account_current")
     */
    public function transferCurrentAccount($id,Request $request): Response
    {
        $a = $this->entityManager->getRepository(BankAccount::class);
        $account = $a->findOneById($id);

        $form = $this->createForm(TransferMoneyType::class,$account)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
                $account->getSubCurrentAccount();
                $account->getTransferCurrentInBookletA();

            $this->entityManager->persist($account);

            $this->entityManager->flush();
        }

        return $this->render('account/transferCurrentAccount.html.twig',[
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/compte/virement-livret-a/{id}", name="account_bookletA")
     */
    public function transferBookletAccount($id,Request $request): Response
    {
        $a = $this->entityManager->getRepository(BankAccount::class);
        $account = $a->findOneById($id);

        $form = $this->createForm(TransferMoneyType::class,$account)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $account->getSubBookletA();
            $account->getTransferBookletAInCurrentAccount();

            $this->entityManager->persist($account);

            $this->entityManager->flush();
        }

        return $this->render('account/transferCurrentAccount.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
