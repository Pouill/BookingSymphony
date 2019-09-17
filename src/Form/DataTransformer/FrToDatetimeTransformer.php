<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrToDatetimeTransformer implements DataTransformerInterface{

    // Transforme les données originelles pour qu'elles puissent s'afficher dasn un formulaire
    public function transform($date){
        if($date === null){
        return '';
        }
        // Retourne une date en Fr
        return $date->format('d/m/Y');
    }

    // C'est l'inverse, elle prend la donnée qui arrive du formulaire et la remet dans le format que l'on attend
    public function reverseTransform($dateFr){
        // date en fr
        if($dateFr === null){
            // Exception
            throw new TransformationFailedException("fournir une date");
        }
        $date = \DateTime::createFromFormat('d/m/Y',$dateFr);

        if($date === false){
            // Exception
            throw new TransformationFailedException("le format de la date n'est pas correct");
        }
        return $date;
    }

}