<?php

namespace App\Controller;

use App\Entity\Draw;
use App\Form\Draw\DrawAddForm;
use App\Form\Draw\DrawEditForm;
use App\Repository\DrawsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DrawsController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/draws/list", name="draws_list")
     * @return Response
     */
    public function list(): Response
    {
        $draws = $this->getDoctrine()
            ->getRepository(Draw::class)
            ->findAll();

        return $this->render('draws/list.html.twig', [
            'drawsList'=> $draws
        ]);
    }

    /**
     * @Route("/draws/add", name="draws_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $draws = $this->getDoctrine()
            ->getRepository(Draw::class)
            ->findAll();

        if (count($draws) >=  DrawsRepository::DRAWS_LIMIT) {
            $this->addFlash('error', $this->translator->trans('errors.drawsLimit'));
            return $this->redirectToRoute('draws_list');
        }

        $form = $this->createForm(DrawAddForm::class, new Draw());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $draw = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($draw);
                $entityManager->flush();

                $this->addFlash('success', $this->translator->trans('draw.add.success'));

                return $this->redirectToRoute('draws_list');

            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('draw.add.failed'));
            }
        }

        return $this->render('draws/add.html.twig', [
            'form' => $form->createView(),
            'action' => 'add'
        ]);
    }

    /**
     * @Route("/draws/edit/{name}", name="draws_edit")
     * @param string $name
     * @param Request $request
     * @return Response
     */
    public function edit(string $name, Request $request): Response
    {
        $draw = $this->getDoctrine()
            ->getRepository(Draw::class)
            ->findOneBy(['name' => $name]);

        if (!$draw) {
            throw $this->createNotFoundException('404 Not Found');
        }

        $form = $this->createForm(DrawEditForm::class, $draw);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $draw = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($draw);
                $entityManager->flush();

                $this->addFlash('success', $this->translator->trans('draw.edit.success'));

                return $this->redirectToRoute('draws_list');

            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('draw.edit.failed'));
            }
        }


        return $this->render('draws/edit.html.twig', [
            'form' => $form->createView(),
            'action' => 'edit'
        ]);
    }

    /**
     * @Route("/draw/{name}", name="draw")
     * @param string $name
     * @return Response
     */
    public function draw(string $name): Response
    {
        $draw = $this->getDoctrine()
            ->getRepository(Draw::class)
            ->findOneBy(['name' => $name]);

        if (!$draw) {
            throw new \Error('404 Not Found', 404);
        }

        return $this->render('draws/draw.html.twig', [
            'draw' => $draw
        ]);
    }
}