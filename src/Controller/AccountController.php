<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\User;
use App\Form\IsDeleteType;
use App\Form\TransferMoneyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        $userInformation = $this->entityManager->getRepository(User::class)->findAll();
        $user = $this->getUser();
        $form = $this->createForm(IsDeleteType::class, $this->getUser())->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $imgSignature = $form->get('signatureDeleteAccount')->getData();
            $originalFilename = pathinfo($imgSignature->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imgSignature->guessExtension();

            try {
                $imgSignature->move(
                    $this->getParameter('img_signature'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $this->entityManager->persist($user->setSignatureDeleteAccount($newFilename));
            $this->entityManager->flush();
        }

        return $this->render('account/index.html.twig',
        [
            'userInformation' => $userInformation,
            'form' => $form->createView(),
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
