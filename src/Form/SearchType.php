<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories_names = [];
        foreach ($options['data']['categories'] as $category) {
            $categories_names[] = $category->getName();
        }
        $categories_names = array_flip($categories_names);

        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Search by name'
                ),
            ])
            ->add('category', ChoiceType::class, array(
                'required' => false,
                'placeholder' => 'Choose category',
                'choices'  => $categories_names,
            ));
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Product::class,
//        ]);
//    }
}
