<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    // ! 1_ Action
    // ! quand on crée une fonction dans un controller, cela devient une "action". une "action" commence toujours par un verbe. la convention de nommage est le camelCase

    // ! 2_ Injections de dépendances
    // ! dans les parenthèses d'une fonction (action), vous allez peut etre avoir besoin d'outils (objets). pour vous en servir dans Symfony, on injectera des dépendances. cela revient a les definir comme paramètres.

    // ! 3_ Route
    // ! La route depuis PHP8, peut s'ecrire sous forme d'Attribut, cela permet de dissocier des Annotations. cela se traduit par une syntaxe differente. une Route prendra TOUJOURS 3 arguments :
    // * a_ une URI, qui est un bout d'URL, 
    // * b_ une name, qui permet de nommer la routepour s'en servir plus tard,
    // * c_ une methode HTTP qui autorise telle ou telle requete HTTP (question de securité)
    // ! TOUTES VOS ROUTES DOIVENT ETRE COLLEES A VOTRE FONCTION !!

    #[Route('/ajouter-un-employe', name: 'create_employe', methods: ['GET', 'POST'])]
    public function createEmploye(Request $request, EntityManagerInterface $entityManager): Response
    {
        // ? ------------------------------------1ère Méthode : GET ------------------------------------//
        // instanciation d'un objet Employe
        $employe = new Employe();

        // création d'une variable $form qui contiendra le formulaire créé par la méthode createForm()
        // l emécanisme d'auto-hydratation se fait par l'ajout d'un 2ème argument dans la methode form(). on met $employe comme argument.
        $form = $this->createForm(EmployeFormType::class, $employe);

        // pour que le mécanisme de symfony soit respecté on devra manipuler la requête avec la methode handleRequest() et l'objet $request
        $form->handleRequest($request);

        // ? ------------------------------------2ème Méthode : POST ------------------------------------//
        if ($form->isSubmitted() && $form->isValid()) {
            // il faut renseigner manuellement une valeur pour la propriété createdAt car cette valeur ne peut être null et n'est pas settled par le form
            $employe->setCreatedAt(new DateTime());

            // ? insertion en BDD grace a $entityManager et la methode persist()
            $entityManager->persist($employe);
            // ? obligation de vider l'entityManager avec flush() pour réellmeent ajouter une ligne de BDD
            $entityManager->flush();

            // ? pour terminer, nous devons rediriger l'utilisateur sur une page html. on utilise la methode redirectToRoute() pour faire la redirection.
            return $this->redirectToRoute("default_home");
        }

        // ? ------------------------------------1ère Méthode : GET ------------------------------------//
        // return pour rendre la vue (page html) du formulaire
        return $this->render('form/employe.html.twig', [
            'form_employe' => $form->createView()
        ]);
    } // end function create

} // en class
