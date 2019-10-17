<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination{
    
    private $entityClass;
    private $limit=10;
    private $currentPage=1;

    private $manager;

    private $twig;
    private $route;

    private $templatePath;

    public function __construct(ObjectManager $manager,Environment $twig,RequestStack $request,$templatePath){
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this ->twig = $twig;

        $this->templatePath = $templatePath;
    }

    public function display(){
        // Appel le moteur twig et on précise quel template on veut utiliser

        $this->twig->display($this->templatePath,[
            // Option necessaire à l'affichage des données
            // Variables : route / page /pages
            'page'=>$this->currentPage,
            'pages'=>$this->getPages(),
            'route'=>$this->route
        ]);
    }

    // 1- utiliser la pagination à partir de n'imoprte quelle entité / on devra préciser l'entité concerné
    
    public function setEntityClass($entityClass){
        // Ma donnée entityClass = donnée qui va m'etre envoyé
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityclass(){
        return $this->entityClass;
    }

    // 2- Quelle est la limite ?

    public function getLimit(){
        return $this->limit;
    }

    public function setLimit($limit){
        $this->limit=$limit;
        return $this;
    }

    // 3- Sur quelle page je me trouve actuellement ?

    public function getPage(){
        return $this->currentPage;
    }

    public function setPage($page){
        $this->currentPage=$page;
        return $this;
    }

    // 4- On va chercher le nomùbre de pages au total

    public function getData(){

        if(empty($this->entityClass)){
            throw new \Exception("setEntityClass n'a pas été renseigné dasn le controller correspondant");
        }
        
        // Calculer l'offset

            $offset = $this->currentPage * $this->limit - $this->limit;

        // demande au repository de trouver les éléments
        // On va chercher le bon repository

            $repo = $this->manager->getRepository($this->entityClass);

        // On construit notre requete

            $data = $repo->findBy([],[],$this->limit,$offset);
            return $data;

    }

    public function getPages(){
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getRoute(){
        return $this->route;
    }

    public function setRoute($route){
        $this->route = $route;
        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath){
        $this->setTemplatePath = $templatePath;
        return $this;
    }

}