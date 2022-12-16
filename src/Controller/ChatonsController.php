<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaire;
use App\Form\CategorieType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    /**
     * @Route("/chatons/{idCategorie}", name="chaton_voir")
     */
    public function index($idCategorie, ManagerRegistry $doctrine): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($idCategorie);
        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $idCategorie");
        }

        return $this->render('chatons/index.html.twig', [
            'categorie' => $categorie,
            "chatons" => $categorie->getChatons()
        ]);
    }
    /**
     * @Route("/voir_chaton/{idproprietaire}", name="chatonproprietaire_voir")
     */
    public function voirchatonproprio($idproprietaire, ManagerRegistry $doctrine): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($idproprietaire);
        //si on n'a rien trouvé -> 404
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucune propriétaire avec l'id $idproprietaire");
        }

        return $this->render('proprietaire/voirChatons.html.twig', [
            'proprietaire' => $proprietaire,
            "chatons" => $proprietaire->getChaton()
        ]);
    }

    /**
     * @Route("/chaton/ajouter/", name="chaton_ajouter")
     */
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();

        $form = $this->createForm(ChatonType::class, $chaton);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("chaton_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/ajouter.html.twig", [
            'formulaire' => $form->createView()
        ]);
    }
}
