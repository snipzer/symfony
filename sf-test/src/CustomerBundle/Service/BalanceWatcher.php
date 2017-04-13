<?php
namespace CustomerBundle\Service;


use Doctrine\ORM\EntityManager;

class BalanceWatcher
{
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function getCustomerWithBalanceLowerThan($balance)
    {
        $customerRepository = $this->manager->getRepository("CustomerBundle:Customer");

        $query = $customerRepository->createQueryBuilder('customer')
                                        ->where("customer.balance <= :balance")
                                        ->setParameter("balance", $balance)
                                        ->getQuery();

        return $query->getResult();
    }
}

?>