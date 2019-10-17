<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Service\Pagination;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_list")
     */
    public function index(AdRepository $repo,$page,Pagination $paginationService)
    {

        // find() => trouve un objet par rapport à son Id
        // findOnBy() => trouve une donnée via des critères de recherche
        // findBy() => trouve plusieurs données grace à des critères

        $paginationService->setEntityClass(Ad::class)
                          ->setPage($page)
                          //->setRoute('admin_ads_list')
                          ;

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $paginationService
        ]);
    }

    /**
     * Permet de modifier une annonce dans la partie admin
     * @Route("admin/ads/{id}/edit",name="admin_ads_edit")
     *
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Ad $ad,Request $request,ObjectManager $manager){
        $form = $this->createForm(AnnonceType::class,$ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addflash('success','L\'annonce a bien été modifié');
        }
        
        return $this->render('admin/ad/edit.html.twig',[
            'ad'=>$ad,
            'form'=>$form->createView()
        ]);

    }

    /**
     * Suppression d'un annonce
     * @Route("/admin/ads/{id}/delete",name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad,ObjectManager $manager){
        if(count($ad->getBookings()) > 0){
            $this->addFlash("warning","Vous ne pouvez pas supprimez une annonce qui possède des réservations.");
        }else{
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");
        }
        return $this->redirectToRoute('admin_ads_list');
    }
}
