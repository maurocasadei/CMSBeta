<?php

namespace AppBundle\Controller\Admin;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use AppBundle\Entity\MacrocategorieMag;
use AppBundle\Form\MacrocategorieMagForm;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/macrocategorieMag")
 */
class MacrocategorieMagController extends Controller 
{

    /**
     * @Route("/", name="admin_macrocategorieMag_home")
     * @Method("GET")
     */
    public function indexAction()
    {
		
		$request = Request::createFromGlobals();
		$categories = $this->getDoctrine()->getRepository('AppBundle:MacrocategorieMag')->findBy(
            ['locale' => $this->get('locales')->getLocaleActive()]
        );
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(	$categories,$request->query->get('page','1'),10);
        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_index.html.twig',
            [
                 'categories' => $pagination,
            		'translations' => $this->get('locales')->getLocales(),
            		'active' => $this->get('locales')->getLocaleActive(),
            ]
        );		
	/*	
        $categories = $this->getDoctrine()->getRepository('AppBundle:MacrocategorieMag')->findBy(
            ['locale' => $this->get('locales')->getLocaleActive()]
        );

        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_index.html.twig',
            [
                'categories' => $categories,
            ]
        );
		*/
    }

    /**
     * @Route("y/new/", name="admin_macrocategorieMag_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $macrocategorieMag = new MacrocategorieMag($this->get('locales')->getLocaleActive());

        $form = $this->createForm(MacrocategorieMagForm::class, $macrocategorieMag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($macrocategorieMag);
            $em->flush();

            $this->addFlash('success', 'macrocategorieMag.flash.created');

            return $this->redirectToRoute('admin_macrocategorieMag_home');
        }

        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("y/{id}/edit/", name="admin_macrocategorieMag_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(MacrocategorieMag $macrocategorieMag, Request $request)
    {
        $form = $this->createForm(MacrocategorieMagForm::class, $macrocategorieMag);
        $form->handleRequest($request);

        $form_delete = $this->formDelete($macrocategorieMag);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($macrocategorieMag);
            $em->flush();

            $this->addFlash('success', 'macrocategorieMag.flash.edited');

            return $this->redirectToRoute('admin_macrocategorieMag_home');
        }

        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_edit.html.twig',
            [
                'form' => $form->createView(),
                'form_delete' => $form_delete->createView(),
            ]
        );
    }

    /**
     * @Route("y/{id}/translations/", name="admin_macrocategorieMag_translations")
     * @Method("GET")
     */
    public function translationsAction(MacrocategorieMag $macrocategorieMag)
    {
        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_translations.html.twig',
            [
                'macrocategorieMag' => $macrocategorieMag,
                'translations' => $this->get('locales')->getLocales(),
                'active' => $this->get('locales')->getLocaleActive(),
            ]
        );
    }

    /**
     * @Route("y/{id}/translations/add/{localeMacrocategorieMag}/{localeTranslation}", name="admin_macrocategorieMag_translation_add")
     * @Method({"GET", "POST"})
     */
    public function addTranslationAction(Request $request, MacrocategorieMag $macrocategorieMag, $localeTranslation)
    {
        $newMacrocategorieMag = new MacrocategorieMag($localeTranslation);

        $form = $this->createForm(MacrocategorieMagForm::class, $newMacrocategorieMag, ['parent' => $macrocategorieMag]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newMacrocategorieMag);
            $em->flush();

            $this->addFlash('success', 'macrocategorieMag.flash.translation.created');
            return $this->redirectToRoute('admin_macrocategorieMag_home', ['id' => $macrocategorieMag->getId()]);
            //return $this->redirectToRoute('admin_macrocategorieMag_translations', ['id' => $macrocategorieMag->getId()]);
        }

        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_new.html.twig',
            [
                'form' => $form->createView(),
                'macrocategorieMag' => $macrocategorieMag,
            ]
        );
    }

    /**
     * @Route("y/{idParent}/translations/{id}/edit/{localeMacrocategorieMag}/{localeTranslation}", name="admin_macrocategorieMag_translation_edit")
     * @Method({"GET", "POST"})
     */
    public function editTranslationAction(Request $request, $idParent, MacrocategorieMag $macrocategorieMag, $localeTranslation)
    {
        $form = $this->createForm(MacrocategorieMagForm::class, $macrocategorieMag);
        $form->handleRequest($request);

        $form_delete = $this->formDelete($macrocategorieMag);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($macrocategorieMag);
            $em->flush();

            $this->addFlash('success', 'macrocategorieMag.flash.translation.edited');

            //return $this->redirectToRoute('admin_macrocategorieMag_translations', ['id' => $idParent]);
            return $this->redirectToRoute('admin_macrocategorieMag_home', ['id' => $macrocategorieMag->getId()]);
            
        }

        return $this->render(
            'admin/macrocategorieMag/admin_macrocategorieMag_edit.html.twig',
            [
                'form' => $form->createView(),
                'form_delete' => $form_delete->createView(),
            ]
        );
    }

    /**
     * @Route("y/{id}/delete/", name="admin_macrocategorieMag_delete")
     * @Method("DELETE")
     */
    public function deleteAction(MacrocategorieMag $macrocategorieMag, Request $request)
    {
        $form = $this->formDelete($macrocategorieMag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($macrocategorieMag);
            try {
                $em->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('success', 'macrocategorieMag.flash.deleted.children');

                return $this->redirectToRoute('admin_macrocategorieMag_home');
            }

            $this->addFlash('success', 'macrocategorieMag.flash.deleted');
        }

        return $this->redirectToRoute('admin_macrocategorieMag_home');
    }

    /**
     * @param MacrocategorieMag $macrocategorieMag
     *
     * @return \Symfony\Component\Form\Form
     */
    private function formDelete(MacrocategorieMag $macrocategorieMag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_macrocategorieMag_delete', ['id' => $macrocategorieMag->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
