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
            ->add("name", TextType::class, [
                "label" => "Nom",
                "constraints" => new NotBlank(["message" => "Ce champ est requis"])
            ])
            ->add("email", EmailType::class, [
                "label" => "Email",
                "constraints" => new NotBlank(["message" => "Ce champ est requis"])
            ])
            ->add("telephone", TelType::class, ["label" => "Téléphone", "required" => false])
            ->add("company", TextType::class, ["label" => "Société", "required" => false])
            ->add("subject", TextType::class, [
                "label" => "Sujet",
                "constraints" => new NotBlank(["message" => "Ce champ est requis"])
            ])
            ->add("message", TextareaType::class, [
                "label" => "Message",
                "constraints" => new NotBlank(["message" => "Ce champ est requis"])
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Contact::class
        ]);
    }
}
