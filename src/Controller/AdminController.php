<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\User;
use App\Form\BankAccountType;
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

        if ($form->isSubmitted() && $bank->getAccountIsActive() === true && $bank->getBookletA() > 0 && $bank->getCurrentAccount() > 0 && strlen($bank->getUniqueId()) >= 8 && $form->isValid() ){

            $bank = $form->getData();

            $id2 = $this->entityManager->getRepository(BankAccount::class)->findAll();

//parcour de $id2. La variable e est un tableau qui stock la function getUserBelongs().
            foreach ($id2 as $t){
                $e[] = $t->getUserBelongs();
            }
            $bool= null;
//parcour du tableau e. Si $bank->getUserBelongs() et egal a une valeur du tableau "e" alors l'id existe deja donc false.

            foreach ($e as $b){
                if ($bank->getUserBelongs() == $b){
                    $bool = false;
                    $this->addFlash('no-success', 'Déja valider !');
                }
            }

//Ont insere si different de false
            if ($bool !== false){
                $this->entityManager->persist($bank);
                $this->entityManager->flush();
                $this->addFlash('success', 'Compte bancaire valider !');
            }

        }

        return $this->render('admin/validatorBankAccount.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete_account")
     */
    public function deleteBankAccount($id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $userInformation = $this->entityManager->getRepository(User::class)->findAll();

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->render('admin/index.html.twig', [
            'userInformation' => $userInformation,
        ]);
    }
}
