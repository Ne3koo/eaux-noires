<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'class' => 'form-control' // Ajoutez la classe form-control
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "Accepter les conditions d'utilisations",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez accepter les conditions d'utilisations",
                    ]),
                ],
                'attr' => [
                    'class' => 'form-check-input' // Ajoutez la classe form-check-input
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre mot de passe ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Le mot de passe ne peut contenir < {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control' // Ajoutez la classe form-control
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
