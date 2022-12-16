<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Proprietaire;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{
    /**
     * @Route("/proprietaire/modifier/{id}", name="proprietaire_modifier")
     */
    public function modifierProprietaire($id, ManagerRegistry $doctrine, Request $request){
        //récupérer la catégorie dans la BDD
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si on n'a rien trouvé -> 404
        if(!$proprietaire){
            throw $this->createNotFoundException("Aucune proprietaire avec l'id $id");
        }

        //si on arrive là, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //Gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //le handleRequest a rempli notre objet $categorie
            //qui n'est plus vide
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gère les entités
            $em=$doctrine->getManager();
            //on lui dit de la ranger dans la BDD
            $em->persist($proprietaire);

            //générer l'insert
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_proprietaire");
        }

        return $this->render("proprietaire/modifier.html.twig",[
            'proprietaire'=>$proprietaire,
            'formulaire'=>$form->createView()
        ]);
    }
    /**
     * @Route("/proprietaire", name="app_proprietaire")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaire=$repo->findAll();

        $newProprio = new Proprietaire();

        $form=$this->createForm(ProprietaireType::class, $newProprio);

        //Gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();
            $em->persist($newProprio);

            //générer l'insert
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_proprietaire");
        }

        return $this->render('proprietaire/index.html.twig', [
            'proprietaire'=>$proprietaire,
            'formulaire'=>$form->createView()
        ]);
    }
    /**
     * @Route("/proprietaire/supprimer/{id}", name="proprietaire_supprimer")
     */
    public function supprimerProprietaire($id, ManagerRegistry $doctrine, Request $request){
        //récupérer la catégorie dans la BDD
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si on n'a rien trouvé -> 404
        if(!$proprietaire){
            throw $this->createNotFoundException("Aucune proprietaire avec l'id $id");
        }

        //si on arrive là, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //Gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //le handleRequest a rempli notre objet $categorie
            //qui n'est plus vide
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gère les entités
            $em=$doctrine->getManager();
            //on lui dit de la supprimer de la BDD
            $em->remove($proprietaire);

            //générer l'insert
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_proprietaire");
        }

        return $this->render("proprietaire/supprimer.html.twig",[
            'proprietaire'=>$proprietaire,
            'formulaire'=>$form->createView()
        ]);
    }
}
