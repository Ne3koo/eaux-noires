<?php

namespace App\Form\Type;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre message :',
                'label_attr' => [
                    'style' => 'font-style : italic;'
                ],
                'attr' => [
                    'class' => 'comment_area'
                ],
            ])
            // ->add('note', ChoiceType::class, [
            //     'label' => 'Une envie de noter ?',
            //     'choices' => [
            //         '0 étoile' => 0,
            //         '1 étoile' => 1,
            //         '2 étoiles' => 2,
            //         '3 étoiles' => 3,
            //         '4 étoiles' => 4,
            //         '5 étoiles' => 5,
            //     ],
            //     'required' => false, // La note peut être nulle
            //     'placeholder' => false, // Retirer le placeholder
            //     'expanded' => true, // Affichage en radio buttons
            //     'attr' => [
            //         'class' => 'rating-stars'
            //     ],
            //     'data' => null, // Aucune sélection par défaut
            // ])
            ->add('article', HiddenType::class)
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
            ]);

        $builder->get('article')
            ->addModelTransformer(new CallbackTransformer(
                fn (Article $article) => $article->getId(),
                fn (Article $article) => $article->getTitle()
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_token_id' => 'add-comment'
        ]);
    }
}
