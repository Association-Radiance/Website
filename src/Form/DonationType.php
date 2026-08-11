<?php

namespace App\Form;

use App\Entity\Donation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("helloAssoId", IntegerType::class, ["required" => false, "label" => "HelloAsso ID"])

            ->add("amount", IntegerType::class, [
                "label" => "Montant",
                "constraints" => [new NotBlank(["message" => "Le montant est requis."]), new Positive(["message" => "Le montant doit être supérieur à 0."])],
            ])

            ->add("date", DateTimeType::class, [
                "label" => "Date",
                "constraints" => [new NotBlank(["message" => "Ce champ est requis."])],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Donation::class,
        ]);
    }
}
