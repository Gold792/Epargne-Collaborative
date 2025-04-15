<?php

namespace App\Form;

use App\Entity\Cotisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la cotisation',
                'attr' => [
                    'placeholder' => 'Entrez le titre de la cotisation',
                    'class' => 'form-control'
                ]
            ])
            ->add('typeCotisation', ChoiceType::class, [
                'label' => 'Type de cotisation',
                'choices' => Cotisation::getTypesCotisation(),    
                'attr' => ['class' => 'form-select']
            ])


            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Description détaillée de la cotisation',
                    'rows' => 5,
                    'class' => 'form-control'
                ]
            ])
            ->add('montantObjectif', MoneyType::class, [
                'label' => 'Montant objectif (FCFA)',
                'currency' => '',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('montantParEcheance', MoneyType::class, [
                'label' => 'Montant par échéance (FCFA)',
                'currency' => '',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('montantMinimum', MoneyType::class, [
                'label' => 'Montant minimum (FCFA)',
                'currency' => '',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date de fin (optionnelle)',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('periodicite', ChoiceType::class, [
                'label' => 'Périodicité',
                'choices' => Cotisation::getPeriodicites(),
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('frequencePeriode', ChoiceType::class, [
                'label' => 'Fréquence de période',
                'choices' => Cotisation::getFrequencesPeriode(),
                'attr' => [
                    'class' => 'form-select'
                ]
            ]);

        // Ajout des champs conditionnels en fonction du type de cotisation
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $cotisation = $event->getData();
            $form = $event->getForm();

            // Champs spécifiques pour le type périodique
            if ($cotisation && $cotisation->getTypeCotisation() === Cotisation::TYPE_PERIODIQUE) {
                $this->addPeriodicFields($form);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            // Champs spécifiques pour le type périodique
            if (isset($data['typeCotisation']) && $data['typeCotisation'] === Cotisation::TYPE_PERIODIQUE) {
                $this->addPeriodicFields($form);
            }
        });
    }

    private function addPeriodicFields($form): void
    {
        $form->add('montantParEcheance', MoneyType::class, [
            'label' => 'Montant par échéance (FCFA)',
            'currency' => '',
            'attr' => [
                'class' => 'form-control'
            ]
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cotisation::class,
        ]);
    }
}