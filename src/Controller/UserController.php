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
     * @Route("/users", name="users", methods={"GET"})
     */
    public function index(Request $request)
    {
        // Récupération des utilisateurs
        $repository = $this->getDoctrine()->getRepository(AppUser::class);

        $success = $request->query->get('success');
        $message = $request->query->get('message');

        // Affichage de la page de l'affichage des utilisateurs
        return $this->render('user/index.html.twig', [
            'users' => $repository->findAll(),
            'success' => $success,
            'message' => $message
        ]);
    }

    /**
     * @Route("/user/new", name="displayCreate", methods={"GET"})
     */
    public function displayCreate()
    {
        // Affichage de la page de création
        return $this->render('user/create.html.twig');
    }

    /**
     * @Route("/user/new", name="create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $success = false;
        try {
            $manager = $this->getDoctrine()->getManager();

            // Création de l'utilisateur
            $user = new AppUser();
            $user->setFirstName($request->get('firstName'));
            $user->setLastName($request->get('lastName'));

            // Sauvegarde de l'utilisateur
            $manager->persist($user);
            $manager->flush();

            $success = true;
        } catch (Exception $e) {
            $success = 1;
        }

        // Redirection vers la page d'accueil
        return $this->redirect('/users?success=' . $success . '&message=L\'utilisateur a bien été créé');
    }

    /**
     * @Route("/user/{id}", name="displayUpdate", methods={"GET"})
     */
    public function displayUpdate(int $id)
    {
        // Récupération de l'utilisateur
        $repository = $this->getDoctrine()->getRepository(AppUser::class);
        $user = $repository->find($id);

        if ($user != null) {
            // Affichage de la page de modification
            return $this->render('user/create.html.twig', [
                'user' => $user
            ]);
        } else {
            return $this->render('404.html.twig');
        }
    }

    /**
     * @Route("/user/{id}", name="update", methods={"POST"}, requirements={"page"="\d+"})
     */
    public function update(int $id, Request $request)
    {
        $success = false;
        try {
            $manager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(AppUser::class);

            // Création de l'utilisateur
            $user = $repository->find($id);
            if ($user == null) {
                return $this->render('404.html.twig');
            }
            $user->setFirstName($request->get('firstName'));
            $user->setLastName($request->get('lastName'));

            // Sauvegarde de l'utilisateur
            $manager->persist($user);
            $manager->flush();

            $success = true;
        } catch (Exception $e) {
            $success = 1;
        }

        // Redirection vers la page d'accueil
        return $this->redirect('/users?success=' . $success . '&message=L\'utilisateur a bien été modifié');
    }

    /**
     * @Route("/user/{id}/delete", name="delete", methods={"GET"})
     */
    public function delete(int $id, Request $request)
    {
        try {
            $manager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(AppUser::class);

            // Récupération de l'utilisateur
            $user = $repository->find($id);
            if ($user == null) {
                throw new Exception();
            }

            // Suppression de l'utilisateur
            $manager->remove($user);
            $manager->flush();

            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        // Redirection vers la page d'accueil
        return $this->redirect('/users?success=' . $success . '&message=L\'utilisateur a bien été supprimé');
    }
}
