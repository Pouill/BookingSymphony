<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Affiche la liste des réservations
     * @Route("/admin/bookings", name="admin_booking_list")
     * 
     * @return Response
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Edition d'une réservation
     * @Route("/admin/booking/{id}/edit",name="admin_booking_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManger $manager
     * @return Response
     */
    public function edit(Booking $booking,Request $request,ObjectManager $manager){
        $form = $this->createForm(AdminBookingType::class,$booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$booking->setAmount($booking->getad()->gerPrice() * $booking->getduration());
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash("success","La réservation a bien été modifiée ");
        }

        return $this->render('admin/booking/edit.html.twig',[
            'booking'=>$booking,
            'form'=>$form->createView()]);
    }

    /**
     * Suppression d'un réservation
     * @Route("/admin/booking/{id}/delete",name="admin_booking_delete")
     *
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Booking $booking,ObjectManager $manager){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash("success","réservation n° {$booking->getId()} supprimée avec succès");

        return $this->redirectToRoute('admin_booking_list');
    }
}
