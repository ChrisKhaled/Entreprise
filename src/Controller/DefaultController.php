<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {
        // ? instruction PHP pour recuperer toutes le slignes de la table "employe". c'est possible grace au Repository accessible pas $entityMAnager
        $employes = $entityManager->getRepository(Employe::class)->findAll();

        // ? il faut ensuite passer la variable $employes (qui contient tous les employes de la BDD) a notre vue twig pour pouvoir afficher les différentes donnees.
        return $this->render('default/home.html.twig', [
            'employes' => $employes
        ]);
    }
}
