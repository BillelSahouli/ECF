<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegisterController extends AbstractController
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
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }
        // Notification pour dire a l'utilisateur si l'inscription c'est bien déroulé
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        // Verifie si le formulaire a été soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid() && $user->getIsDelete() == null){

            $user->setIsDelete(0);
            $imgIdentity = $form->get('image')->getData();
            // Récupere les donner soumis dans le formulaire apres avoir cliquez sur submit
            $user = $form->getData();

            // Vérifie si cet email et deja pris par un utilisateur
            $search_email = $this->entityManager->getRepository(User::class)->findByEmail($user->getEmail());

            // Si l'email n'est pas pris alors il ashe le mdp et enregistre le user
            if (!$search_email && $imgIdentity){

                $originalFilename = pathinfo($imgIdentity->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgIdentity->guessExtension();

                try {
                    $imgIdentity->move(
                        $this->getParameter('img_identity'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setImage($newFilename);

                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = "Inscription Réussie";
            }else{
                $notification = "Email déja éxistant";
            }
        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
