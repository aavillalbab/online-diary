<?php

namespace App\Form\Api;

use App\Entity\Category;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('from_date', DateType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('to_date', DateType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('is_active', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'choice_value' => function ($value) {
                    if ($value === null) {
                        return '';
                    }
                    return $value ? '0' : '1';
                },
            ])
            ->add('category_id', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'property_path' => '[category]',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
