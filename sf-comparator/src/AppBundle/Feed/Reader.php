<?php

namespace AppBundle\Feed;

use AppBundle\Entity\Merchant;
use AppBundle\Entity\Offer;
use AppBundle\Repository\OfferRepository;
use AppBundle\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ValidatorInterface;


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

    private $validator;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->manager = $entityManager;
        $this->validator = $validator;
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
        $url = $merchant->getFeedUrl();
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $productRepository = $this->manager->getRepository("AppBundle:Product");
        $offerRepository = $this->manager->getRepository("AppBundle:Offer");

        $tabCount = [
            "new" => "Error",
            "updated" => "Error",
            "errors" => "Error",
        ];

        switch ($extension)
        {
            case "json":
                $tabCount = $this->forJson($merchant, $url, $productRepository, $offerRepository);
                break;
            case "csv":
                $tabCount = $this->forCsv($merchant, $url, $productRepository, $offerRepository);
                break;
            case "xml":
                $tabCount = $this->forXml($merchant, $url, $productRepository, $offerRepository);
        }

        return $tabCount;
    }

    public function forJson(Merchant $merchant, $url, ProductRepository $productRepository, OfferRepository $offerRepository)
    {
        $countError = 0;
        $countNew = 0;
        $countUpdated = 0;

        $array = json_decode(file_get_contents($url), true);

        foreach ($array as $key => $value)
        {
            $product = $productRepository->findOneBy(["eanCode" => $value["ean_code"]]);
            if ($product === null)
            {
                break;
            }

            $offer = $offerRepository->findOneBy(["product" => $product]);

            if ($offer)
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

            $errors = $this->validate($offer);

            if($errors->count() > 0)
            {
                $countError++;
            }
            else
            {
                $this->manager->persist($offer);
                $this->manager->flush();
            }
        }
        $tabCount = [
            "new" => $countNew,
            "updated" => $countUpdated,
            "errors" => $countError,
        ];

        return $tabCount;

    }

    public function forCsv(Merchant $merchant, $url, ProductRepository $productRepository, OfferRepository $offerRepository)
    {

        $countError = 0;
        $countNew = 0;
        $countUpdated = 0;

        $handle = fopen($url, 'r');

        while (false !== $data = fgetcsv($handle, null, ';'))
        {
            $product = $productRepository->findOneBy(["eanCode" => $data[0]]);
            if ($product === null)
            {
                break;
            }

            $offer = $offerRepository->findOneBy(["product" => $product]);

            if ($offer)
            {
                $offer->setPrice($data[1]);
                $offer->setUpdatedAt(new \DateTime());
                $countUpdated++;
            }
            else
            {
                $offer = new Offer();
                $offer->setProduct($product)
                    ->setMerchant($merchant)
                    ->setPrice($data[1])
                    ->setUpdatedAt(new \DateTime());
                $countNew++;
            }

            $errors = $this->validate($offer);

            if($errors->count() > 0)
            {
                $countError++;
            }
            else
            {
                $this->manager->persist($offer);
                $this->manager->flush();
            }
        }
        fclose($handle);

        $tabCount = [
            "new" => $countNew,
            "updated" => $countUpdated,
            "errors" => $countError,
        ];

        return $tabCount;
    }

    public function forXml(Merchant $merchant, $url, ProductRepository $productRepository, OfferRepository $offerRepository)
    {
        $countError = 0;
        $countNew = 0;
        $countUpdated = 0;

        $dom = new \DOMDocument();
        $dom->load($url);

        $offers = $dom->getElementsByTagName('offer');

        foreach ($offers as $offer)
        {
            $eanCode = $offer->getAttribute("ean_code");
            $price = $offer->getAttribute("price");

            $product = $productRepository->findOneBy(["eanCode" => $eanCode]);

            if ($product === null)
            {
                break;
            }

            $offer = $offerRepository->findOneBy(["product" => $product]);

            if ($offer)
            {
                $offer->setPrice($price);
                $offer->setUpdatedAt(new \DateTime());
                $countUpdated++;
            }
            else
            {
                $offer = new Offer();
                $offer->setProduct($product)
                    ->setMerchant($merchant)
                    ->setPrice($price)
                    ->setUpdatedAt(new \DateTime());
                $countNew++;
            }

            $errors = $this->validate($offer);
            if($errors->count() > 0)
            {
                $countError++;
            }
            else
            {
                $this->manager->persist($offer);
                $this->manager->flush();
            }

        }
        $tabCount = [
            "new" => $countNew,
            "updated" => $countUpdated,
            "errors" => $countError,
        ];

        return $tabCount;
    }

    public function validate($entity)
    {
        $validator = $this->validator;

        $errors = new ArrayCollection();
        $errors->add($validator->validate($entity));

        return $errors;
    }
}
