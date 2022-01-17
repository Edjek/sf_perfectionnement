<?php

namespace App\Form;

use App\Entity\Writer;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('published')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'data' => new \DateTime()
            ])
            ->add('writer', EntityType::class,[
                'class' => Writer::class,
                'choice_label' => 'name'
            ])
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
