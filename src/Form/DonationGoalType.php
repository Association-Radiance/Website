<?php

namespace App\Form;

use App\Entity\DonationGoal;
use App\Form\DataTransformer\EurosToCentsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class DonationGoalType extends AbstractType
{
    public function __construct(private readonly EurosToCentsTransformer $eurosToCentsTransformer) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("amount", NumberType::class, [
                "label" => "Montant (€)",
                "html5" => true,
                "scale" => 2,
                "attr" => ["min" => 0.01, "step" => "0.01", "inputmode" => "decimal"],
                "constraints" => [
                    new NotBlank(["message" => "Le montant est requis."]),
                    new Positive(["message" => "Le montant doit être supérieur à 0."])
                ]
            ])

            ->add("title", TextType::class, [
                "label" => "Titre",
                "constraints" => [new NotBlank(["message" => "Ce champ est requis."])]
            ])

            ->add("description", TextareaType::class, [
                "required" => false,
                "label" => "Description"
            ]);

        $builder->get("amount")->addModelTransformer($this->eurosToCentsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => DonationGoal::class
        ]);
    }
}
