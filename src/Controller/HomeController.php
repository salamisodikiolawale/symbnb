<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    /**
     * @Route("/", name="homepage")
     */
    public function home(){

        $etudiant = [
            "salami"=>23, 
            "Sodiki"=>25
        ];

        return $this->render("home.html.twig",
        ["etude"=>$etudiant]
        );
    }
}
?>