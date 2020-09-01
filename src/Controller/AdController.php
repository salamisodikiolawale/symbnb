<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();
        
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Permet de créer une annonce
     * 
     *@Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();

        /*$image1 = new Image();
        $image1->setUrl('http://placeholder.it/400*200')
              ->setCaption('Titre 1');
        
        $image2 = new Image();
        $image2->setUrl('http://placeholder.it/400*200')
              ->setCaption('Titre 2');
        
        $ad->addImage($image1);
        $ad->addImage($image2);*/

        $form = $this->createForm(AdType::class, $ad);//$form = $this->createFormBuilder($ad);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());//Lier l'auteur a son article qu'il a creer
            
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "l'annonce <strong>{$ad->getTitle()}</strong>  à bien été enregistrée"
            );
            
            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render("ad/new.html.twig", [
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     * @return Response
     */

     public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(AdType::class, $ad);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "Les mmodifications de l'annonce <strong>{$ad->getTitle()}</strong>  
                ont bien été enregistrées"
            );
            
            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render("ad/edit.html.twig", [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
     }

    /**
     * Permet d'afficher une seule annonce.
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad) //public function show($slug, AdRepository $repo) (ParamConverter)
    {
        //$ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig',[
            'ad' => $ad
        ]);
    }


    
    
}
