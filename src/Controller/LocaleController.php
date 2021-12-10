<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleController extends AbstractController
{
    public function choiceList(Request $request): Response
    {
        $form = $this->createLocaleForm($request);

        return $this->renderForm('locale/select.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/locale", methods="POST")
     */
    public function select(
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $form = $this->createLocaleForm($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newLocale = $form->getData();
            $request->getSession()->set('_locale', $newLocale);
            $this->addFlash('notice', $translator->trans('site.locale.flash.change', [], null, $newLocale));
        }

        return $this->redirectToRoute('app_post_homepage');
    }

    private function createLocaleForm(Request $request): FormInterface
    {
        return $this->createForm(LocaleType::class, $request->getLocale(), [
            'choices' => [
                'English' => 'en',
                'French' => 'fr',
            ],
            'choice_loader' => null,
            'choice_translation_domain' => true,
        ]);
    }
}