<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\User;
use App\Form\BankAccountType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $userInformation = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'userInformation' => $userInformation,
        ]);
    }

    /**
     * @Route("/admin/validator", name="admin_validator")
     */
    public function validatorBankAccount(Request $request): Response
    {
        $notification = null;
        $bank = new BankAccount();

        $form = $this->createForm( BankAccountType::class, $bank);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if ($bank->getAccountIsActive() == 0)
            {
                $notification = "Le compte doit etre activer";
            }else{
                $bank = $form->getData();
                $this->entityManager->persist($bank);
                $this->entityManager->flush();
            }
        }

        return $this->render('admin/validatorBankAccount.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
