<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Cart;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $customerList;
    private $productList;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->customerList = [];
        $this->productList = [];
    }

    public function load(ObjectManager $manager): void
    {
        $orders = $this->getOrdersData();

        foreach ($orders as $order => $orderContent) {
            $currentCustomer = $orderContent["customer"];
            $currentCart = array_values($orderContent["cart"]);

            $cutomer = $this->persistCustomer($manager, $currentCustomer);

            $order = new Order();
            $orderDate = new DateTime($orderContent["orderDate"]);

            $order->setCustomer($cutomer)
                ->setOrderDate($orderDate)
                ->setPrice($orderContent["price"])
                ->setStatus($orderContent["status"]);

            $manager->persist($order);

            foreach ($currentCart as $products) {
                if (array_key_exists("@attributes", $products)) {
                    $this->persistProduct($manager, $products, $order);
                } else {
                    foreach ($products as $subProducts) {
                        $this->persistProduct($manager, $subProducts, $order);
                    }
                }
            }
        }

        $user = new User();
        $user->setUsername('admin')
            ->setPassword($this->encoder->hashPassword($user, 'S3cr3T+'));

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrdersData(): array
    {
        if (file_exists('data/orders.xml')) {
            $xml = simplexml_load_file('data/orders.xml');
            $jsonFromXML = json_encode($xml);
            $ordersArray = json_decode($jsonFromXML, true);

            return $ordersArray["order"];
        }

        exit('Echec lors de l\'ouverture du fichier' . PHP_EOL);
    }

    public function persistCustomer($manager, $currentCustomer): Customer
    {
        $customerId = $currentCustomer["@attributes"]["id"];

        $customer = new Customer();
        $customer->setFirstname($currentCustomer["firstname"])
            ->setLastname($currentCustomer["lastname"]);

        if (!array_key_exists($customerId, $this->customerList)) {
            $this->customerList[$customerId] = $customer;
            $manager->persist($customer);
        } else {
            $customer = $this->customerList[$customerId];
        }

        return $customer;
    }

    public function persistProduct($manager, $item, $order): void
    {
        $productSku = $item["@attributes"]["sku"];

        $product = new Product();
        $product->setSku($productSku)
            ->setName($item["name"])
            ->setPrice($item["price"]);

        if (!array_key_exists($productSku, $this->productList)) {
            $this->productList[$productSku] = $product;
            $manager->persist($product);
        } else {
            $product = $this->productList[$productSku];
        }

        $cart = new Cart();
        $cart->setCartOrder($order)
            ->setQuantity($item["quantity"])
            ->addProduct($product);

        $manager->persist($cart);
    }
}
