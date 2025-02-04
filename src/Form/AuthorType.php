<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('nb_books')
            // Utilisation de l'option 'submit_label' pour définir le texte du bouton
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'] ?? 'Ajouter', // Par défaut 'Ajouter' si aucune option n'est fournie
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
            'submit_label' => 'Ajouter', // Option par défaut
        ]);
    }
}
