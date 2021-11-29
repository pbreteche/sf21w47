<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'Pas plus long que 60 caractères',
            ])
            ->add('body', TextareaType::class, [
                'attr' => [ 'cols' => 60, 'rows' => 10 ],
                'help' => 'Écrivez un contenu suffisamment long (10).',
            ])
            ->add('writtenBy', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un auteur.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
