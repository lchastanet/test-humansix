<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository, SerializerInterface $serializer): Response
    {
        $orders = $orderRepository->findAll();

        $responseContent = $serializer->serialize(
            $orders,
            'json',
            ['groups' => 'list_order']
        );

        $response = new Response(
            $responseContent,
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(Order $order, SerializerInterface $serializer): Response
    {
        $responseContent = $serializer->serialize(
            $order,
            'json',
            ['groups' => 'show_order']
        );

        $response = new Response(
            $responseContent,
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }

    #[Route('/new', name: 'order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }
}
