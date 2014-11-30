<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Veonik\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Veonik\Bundle\BlogBundle\Entity\Menu;
use Veonik\Bundle\BlogBundle\Model\MenuBuilder\MenuBuilderRegistry;

class MenuType extends AbstractType
{

    /**
     * @var MenuBuilderRegistry
     */
    private $registry;

    /**
     * Constructor
     *
     * @param MenuBuilderRegistry $registry
     */
    public function __construct(MenuBuilderRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $menuTypes = array_keys($this->registry->getBuilders());
        $names = array_map(function ($name) {
                return ucwords(str_replace(array('_', '.'), ' ', $name));
            }, $menuTypes);

        $builder
            ->add('name')
            ->add('type', 'choice', array(
                'choices' => array_combine($menuTypes, $names)
            ))
            ->add('definition', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'type' => new MenuItemType()
            ))
            ->addEventListener(FormEvents::POST_BIND, function (FormEvent $event) {
                $menu = $event->getData();

                if (! ($menu instanceof Menu)) {
                    return;
                }

                $menu->setDefinition(array_values($menu->getDefinition()));
            });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Veonik\Bundle\BlogBundle\Entity\Menu'
        ));
    }

    public function getName()
    {
        return 'menu';
    }
}
