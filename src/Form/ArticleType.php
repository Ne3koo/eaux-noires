<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group']
            ])
            // ->add('slug', TextType::class, [
            //     'required' => false,
            //     'attr' => ['class' => 'form-control'],
            //     'row_attr' => ['class' => 'form-group']
            // ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('featuredText', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('link', UrlType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'placeholder' => 'Aucune catégorie',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'custom-select']
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
