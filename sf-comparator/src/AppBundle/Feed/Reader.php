<?php

namespace AppBundle\Feed;

use AppBundle\Entity\Merchant;
use AppBundle\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Class Reader
 * @package AppBundle\Feed
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Reader
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    /**
     * Reads the merchant's feed and creates or update the resulting offers.
     *
     * @param Merchant $merchant
     *
     * @return array The number of created or updated offers in associative array.
     */
    public function read(Merchant $merchant)
    {
        $countNew = 0;
        $countUpdated = 0;

        $productRepository = $this->manager->getRepository("AppBundle:Product");
        $offerRepository = $this->manager->getRepository("AppBundle:Offer");

        $url = $merchant->getFeedUrl();

        $array = json_decode(file_get_contents($url), true);

        foreach($array as $key => $value)
        {
            $product = $productRepository->findOneBy(["eanCode" => $value["ean_code"]]);
            if($product)
            {
                $offer = $offerRepository->findOneBy(["product" => $product]);
                if($offer)
                {
                    $offer->setPrice($value["price"]);
                    $offer->setUpdatedAt(new \DateTime());
                    $countUpdated++;
                }
                else
                {
                    $offer = new Offer();
                    $offer->setProduct($product)
                          ->setMerchant($merchant)
                          ->setPrice($value["price"])
                          ->setUpdatedAt(new \DateTime());
                    $countNew++;
                }
                $this->manager->persist($offer);
                $this->manager->flush();
            }
        }
        $tabCount = [
            "new" => $countNew,
            "updated" => $countUpdated
        ];

        return $tabCount;
    }
}
