<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\OptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    public function __construct(
        private OptionService $optionService
    )
    {
        
    }

#[Route('/user/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        $userCanRegister = $this->getParameter('users_can_register'); 

        if (!$userCanRegister) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Compte créé avec succès.');
                    return $this->redirectToRoute('app_home');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite lors de la création du compte.');
                }
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/user/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_home');
        }


        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }

        return $this->render('user/login.html.twig', [
            'username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/user/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/user/{username}', name: 'user')]
    public function index(User $user): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}