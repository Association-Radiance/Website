<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Ce champ est requis'])
            ])
            ->add('email', EmailType::class, [
                'constraints' => new NotBlank(['message' => 'Ce champ est requis'])
            ])
            ->add('telephone', TelType::class, ['required' => false])
            ->add('company', TextType::class, ['required' => false])
            ->add('subject', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Ce champ est requis'])
            ])
            ->add('message', TextareaType::class, [
                'constraints' => new NotBlank(['message' => 'Ce champ est requis'])
            ])
            ->add('terms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => new isTrue(['message' => 'Vous devez accepter les conditions si vous voulez nous contactez'])
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
