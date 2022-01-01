<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('warranty_status', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('event', EntityType::class, [
                // j'utilise la propriété 'class' pour selectionner la classe de mon entity Author
                'class' => Product::class,

                // j'utilise la propriété 'choice_label' pour afficher le firstname et le lastname concaténé
                // de l'entity sous forme de string avec le getter de la classe Author
                'choice_label' => function ($event) {
                    // je retourne le firstname et le lastname
                    return $event->getEvent();

                }
            ])
            ->add('event', ChoiceType::class, [
                'choices' => [
                    'Demande validée' => 'request_check',
                    'Appareil reçu' => 'received',
                    'Remise en état' => 'repair',
                    'Appareil réparé' => 'repair_finished',
                    'Appareil envoyé' => 'send',
                    'Dossier clôturé' => 'finished',
                ],

            ])
            ->add('number')
            ->add('model')
            ->add('imei')
            ->add('purchase_date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('warranty', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('description')
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
