<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
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
            print_r($orderContent);

            $currentCustomer = $orderContent["customer"];
            $currentCart = $orderContent["cart"];
            $customerId = $currentCustomer["@attributes"]["id"];
            $currentCustomer = [$currentCustomer["firstname"], $currentCustomer["lastname"]];

            if (!array_key_exists($customerId, $this->customerList)) {
                $this->customerList[$customerId] = $currentCustomer;
                $customer = new Customer();
                $customer->setFirstname($currentCustomer[0])->setLastname($currentCustomer[1]);

                $manager->persist($customer);
            }

            foreach ($currentCart as $cart => $products) {
                if (array_key_exists("@attributes", $products)) {
                    $this->persistProducts($manager, $products);
                } else {
                    foreach ($products as $subProducts) {
                        $this->persistProducts($manager, $subProducts);
                    }
                }
            }
        }

        $user = new User();
        $user->setUsername('admin')->setPassword($this->encoder->hashPassword($user, 'S3cr3T+'));

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrdersData()
    {
        if (file_exists('data/orders.xml')) {
            $xml = simplexml_load_file('data/orders.xml');
            $jsonFromXML = json_encode($xml);
            $ordersArray = json_decode($jsonFromXML, true);

            return $ordersArray["order"];
        }

        exit('Echec lors de l\'ouverture du fichier' . PHP_EOL);
    }

    public function persistProducts($manager, $item): void
    {
        $productId = $item["@attributes"]["sku"];
        $currentProduct = [$item["name"], $item["quantity"], $item["price"]];

        if (!array_key_exists($productId, $this->productList)) {
            $this->productList[$productId] = $currentProduct;
            $product = new Product();
            $product->setSku($productId)->setName($currentProduct[0])->setQuantity($currentProduct[1])->setPrice($currentProduct[2]);

            $manager->persist($product);
        }
    }
}
