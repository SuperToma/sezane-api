<?php

namespace App\DataFixtures;

use App\Entity\Manager;
use App\Entity\Product;
use App\Entity\Shop;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $nbProducts = 100;
        $nbShopManagers = 15;
        $nbShops = 10;

        // products
        for ($i = 1; $i <= $nbProducts; ++$i) {
            $product = new Product();
            $product->setName('Product '.$i);
            $product->setPhoto('https://dummyimage.com/640x4:3/C51/000&text=product+'.$i);

            ${'product'.$i} = clone $product; // Products will be used for stocks creation
        }

        // shop managers
        for ($i = 1; $i <= $nbShopManagers; ++$i) {
            $shopManager = new Manager();
            $shopManager->setName('Manager '.$i);

            ${'shopManager'.$i} = clone $shopManager; // Managers will be used for shops creation
        }

        // shops
        for ($i = 1; $i <= $nbShops; ++$i) {
            $shop = new Shop();
            $shop->setName('Shop '.$i);
            $shop->setLatitude(mt_rand(-90 * 10000, 90 * 10000) / 10000);
            $shop->setLongitude(mt_rand(-180 * 10000, 180 * 10000) / 10000);
            $shop->setAddress('Shop address '.$i);
            $shop->setManager(${'shopManager'.mt_rand(1, $nbShopManagers)});

            ${'shop'.$i} = clone $shop; // Shops will be used for stocks creation
        }

        // stocks
        for ($i = 1; $i <= $nbProducts; ++$i) { // For each product
            for ($j = 1; $j <= $nbShops; ++$j) { // Set a stock for each shop
                $stock = new Stock();
                $stock->setShop(${'shop'.$j});
                $stock->setProduct(${'product'.$i});
                $stock->setQuantity(mt_rand(0, 30));
                $manager->persist($stock);
            }
        }

        $manager->flush();
    }
}
