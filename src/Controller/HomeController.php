<?php

// namespace : chemin du controller
namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Pour créer un page : 1 - une fonction publique (classe) / 2 - une route / 3 - une réponse

class HomeController extends Controller {
    
    /**
     * Création de notre 1ere route
     * @Route("/", name="homepage")
     */
    
    public function home(AdRepository $adRepo, UserRepository $userRepo){
        
        return $this->render('home.html.twig',
                            [
                                'ads'=>$adRepo->findBestAds(6),
                                'users'=>$userRepo->findBestUsers()
                            ]);
    }


    /**
     * Montre la page qui salut l'utilisateur
     * @Route("/hello/{nom}",name="hello-utlisateur")
     * @Route("/profil",name="hello-base")
     * @Route("/profil/{nom}/acces/{acces}",name="hello-profil")
     * @return void 
     */

    public function hello($nom="anonyme",$acces="visiteur"){
        return $this->render('hello.html.twig',['title'=>'Page de profil','nom'=>$nom,'acces'=>$acces]);
    }  


}