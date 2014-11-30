<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Veonik\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A tag
 *
 * @ORM\Entity
 * @ORM\Table(name="tags")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "Tag"      = "Veonik\Bundle\BlogBundle\Entity\Tag",
 *   "Category" = "Veonik\Bundle\BlogBundle\Entity\Category"
 * })
 */
abstract class AbstractTag extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    protected $name;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
