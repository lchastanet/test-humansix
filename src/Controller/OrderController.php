<?php

namespace App\Controller;

use DateTime;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Customer;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/order')]
class OrderController extends AbstractController
{
    protected $serializer;
    protected $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/', name: 'order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([], ['orderDate' => 'DESC']);

        $responseContent = $this->serializer->serialize(
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

    #[Route('/new', name: 'order_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = $request->toArray();
        $customerId = intval($data["customer"], 10);

        $customer = $entityManager->getRepository(Customer::class)->find($customerId);

        if (!$customer) {
            return new Response(
                $this->json(["error" => "User does not exists !"]),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        $order = new Order();
        $order->setOrderDate(new DateTime())
            ->setStatus("processing")
            ->setCustomer($customer);

        $total = 0;

        foreach ($data["cart"] as $cartLine) {
            $quantity = intval($cartLine["quantity"], 10);

            $cartEntity = new Cart();

            $productRepository = $entityManager->getRepository(Product::class);

            $product = $productRepository->findOneBy(["sku" => $cartLine["sku"]]);

            $total += ($quantity * $product->getPrice());

            $cartEntity->setQuantity($quantity)
                ->setCartOrder($order)
                ->addProduct($product);

            $entityManager->persist($cartEntity);
        }

        $order->setPrice($total);

        $errors = $this->validator->validate($order);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response(
                $this->json($errorsString),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        $entityManager->persist($order);

        $entityManager->flush();

        $response = new Response();

        return $response->setStatusCode(Response::HTTP_CREATED);
    }

    #[Route('/new', name: 'order_form', methods: ['GET'])]
    public function newForm(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $productRepository = $entityManager->getRepository(Product::class);
        $customerRepository = $entityManager->getRepository(Customer::class);

        $products = $productRepository->findAll();
        $customers = $customerRepository->findAll();

        $responseContent = $this->serializer->serialize(
            ["customer" => $customers, "products" => $products],
            'json',
            ['groups' => 'form_order']
        );

        $response = new Response(
            $responseContent,
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $response;
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        $responseContent = $this->serializer->serialize(
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
}
