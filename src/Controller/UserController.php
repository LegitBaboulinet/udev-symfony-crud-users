<?php

namespace App\Controller;

use App\Entity\AppUser;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {
        // Redirection vers la page de gestion des utilisateurs
        return $this->redirect("users");
    }

    /**
     * @Route("/users", name="users", methods={"GET"})
     */
    public function users(Request $request)
    {
        // Récupération des utilisateurs
        $repository = $this->getDoctrine()->getRepository(AppUser::class);

        // Récupération des paramètres GET
        $success = $request->query->get('success');
        $message = $request->query->get('message');

        // Affichage de la page de l'affichage des utilisateurs
        return $this->render('user/index.html.twig', [
            'users' => $repository->findAll(),
            'success' => $success,
            'message' => $message
        ]);
    }

    private function getForm(AppUser $user, string $action)
    {
        // Création du formulaire
        return $this->createFormBuilder($user)
            ->add('firstName', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Prénom'
            ])
            ->add('lastName', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Nom de famille'
            ])
            ->add('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType', [
                'label' => 'Adresse email'
            ])
            ->add('location', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Ville'
            ])
            ->add('age', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'label' => 'Âge'
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $action
            ])
            ->getForm();
    }

    /**
     * @Route("/user/new", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        // Création du formulaire
        $user = new AppUser();
        $form = $this->getForm($user, 'Créer l\'utilisateur');

        $form->handleRequest($request);

        // Vérification des données
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Récupération de la connexion à la base de données
                $em = $this->getDoctrine()->getManager();
                $user->setDate(new \DateTime());

                // Sauvegarde de l'utilisateur
                $em->persist($user);
                $em->flush();
                $success = 1;
                $message = 'L\'utilisateur ' . $user->getFirstName() . ' ' . $user->getLastName() . ' a bien été créé';
            } catch (Exception $e) {
                $success = 0;
                $message = 'Une erreur est survenue lors de la création de l\'utilisateur';
            }

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('users'));
        }

        // Affichage de la page de création
        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(int $id, Request $request)
    {
        try {
            // Récupération de la connexion à la base de données
            $repository = $this->getDoctrine()->getRepository(AppUser::class);

            // Récupération de l'utilisateur avec son id
            $user = $repository->find($id);
            if ($user == null) {
                throw new \Exception('L\'utilisateur n\'existe pas', 404);
            }

            // Création du formulaire
            $form = $this->getForm($user, 'Modifier l\'utilisateur');

            $form->handleRequest($request);

            // Vérification des données
            if ($form->isSubmitted() && $form->isValid()) {

                $manager = $this->getDoctrine()->getManager();

                // Sauvegarde de l'utilisateur
                $manager->persist($user);
                $manager->flush();

                // Redirection de l'utilisateur vers la page d'accueil
                return $this->redirect($this->generateUrl('users'));
            }

            // Affichage de la page de création
            return $this->render('user/create.html.twig', [
                'form' => $form->createView(),
                'user' => $user
            ]);
        } catch (\Exception $e) {
            if ($e->getCode() == 404) {
                return $this->render('404.html.twig');
            } else {
                return $this->render('500.html.twig');
            }
        }
    }

    /**
     * @Route("/user/{id}/delete", name="delete", methods={"GET"})
     */
    public function delete(int $id, Request $request)
    {
        $success = 0;
        try {
            // Récupération de la connexion à la base de données
            $manager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(AppUser::class);

            // Récupération de l'utilisateur
            $user = $repository->find($id);
            if ($user == null) {
                throw new \Exception();
            }

            // Suppression de l'utilisateur
            $manager->remove($user);
            $manager->flush();

            $success = 1;
            $message = 'L\'utilisateur a bien été supprimé';
        } catch (\Exception $e) {
            $success = 0;
            $message = 'Un erreur est survenue lors de la suppression de l\'utilisateur';
        }

        // Redirection vers la page d'accueil
        return $this->redirect('/users?success=' . $success . '&message=' . $message);
    }
}
