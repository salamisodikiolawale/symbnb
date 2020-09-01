<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType{

     /**
     * Permet de voir la configuration de base des champs
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */

    protected function getConfiguration($label, $placeholder, $option = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $option);
    }
    
}