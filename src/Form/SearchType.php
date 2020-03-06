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
        $categorys_names = [];
        foreach ($options['data']['categorys'] as $category) {
            $categorys_names[] = $category->getName();
        }
        $categorys_names = array_flip($categorys_names);

        $builder
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('category', ChoiceType::class, array(
                'required' => false,
                'placeholder' => 'Choose category',
                'choices'  => $categorys_names,
            ));
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Product::class,
//        ]);
//    }
}
