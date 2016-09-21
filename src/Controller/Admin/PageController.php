<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Veonik\Bundle\BlogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Veonik\Bundle\BlogBundle\Entity\Page;
use Veonik\Bundle\BlogBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/manage/page")
 */
class PageController extends Controller
{
    /**
     * Lists all Page entities.
     *
     * @Route("s/", name="manage_pages")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VeonikBlogBundle:Page')
            ->createQueryBuilder('p')
            ->orderBy('p.active', 'ASC')
            ->addOrderBy('p.datePublished', 'DESC')
            ->getQuery()
            ->getResult();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}/show", name="manage_page_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VeonikBlogBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}/preview", name="manage_page_preview")
     * @Template("VeonikBlogBundle:Home:index.html.twig")
     */
    public function previewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VeonikBlogBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $request->attributes->set('slug', $entity->getSlug());

        return array(
            'entities'      => array($entity),
        );
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="manage_page_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createForm(new PageType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/create", name="manage_page_create")
     * @Method("POST")
     * @Template("VeonikBlogBundle:Admin/Page:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Page();
        $form = $this->createForm(new PageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The page has been created successfully.');

            return $this->redirect($this->generateUrl('manage_page_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="manage_page_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VeonikBlogBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $form = $this->createForm(new PageType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}/update", name="manage_page_update")
     * @Method("POST")
     * @Template("VeonikBlogBundle:Admin/Page:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VeonikBlogBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $form = $this->createForm(new PageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The page has been updated successfully.');

            return $this->redirect($this->generateUrl('manage_page_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }
}
