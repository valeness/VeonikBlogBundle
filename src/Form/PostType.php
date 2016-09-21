<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Veonik\Bundle\BlogBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('body', 'ckeditor')
            ->add('author', null, array(
                    'query_builder' => function (EntityRepository $entityRepository) {
                        return $entityRepository->createQueryBuilder('a')
                            ->where('a.active = true');
                    }
                ))
            ->add('author', null, array(
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('a')
                        ->where('a.active = true');
                }
            ))
            ->add('enableComments', 'checkbox', array('required' => false))
            ->add('active', null, array('required' => false, 'label' => 'Publish'))
            ->add('datePublished', 'date', array('required' => false, 'format' => 'MM/dd/yyyy', 'widget' => 'single_text'))
            ->add('tags', 'select2_tag', array(
                'class' => 'Veonik\Bundle\BlogBundle\Entity\Tag',
                'required' => false
            ))
            ->add('categories', 'select2_tag', array(
                'class' => 'Veonik\Bundle\BlogBundle\Entity\Category',
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Veonik\Bundle\BlogBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'post';
    }
}
