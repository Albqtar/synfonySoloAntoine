<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProprietaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('chaton', EntityType::class, [
                'class'=>Chaton::class, //choix de la classe liée
                'choice_label'=>"nom", //choix de ce qui sera affiché comme texte
                'multiple'=>true,
                'expanded'=>true])
            ->add('OK', SubmitType::class, ["label"=>"OK"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proprietaire::class,
        ]);
    }
}
