<?php

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\DeliveryOrder;
use App\Entity\Enterprise;
use App\Entity\EnterpriseDeliveryOrder;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeliveryOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryOrder[]    findAll()
 * @method DeliveryOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeliveryOrder::class);
    }

    public function createByOrderItemDetails($order_item_details) {
        $customer = null;
        if (isset($order_item_details['customer']['name']) && isset($order_item_details['customer']['address'])) {
            $person_repo = $this->getEntityManager()->getRepository(Person::class);
            $customer = $person_repo->createByPersonDetails($order_item_details['customer']);

        } else {
            throw new \Exception("Since customer details are not found delivery order can't be processed!");
        }

        $campaign = null;
        if (isset($order_item_details['campaign']['name'])
            && isset($order_item_details['campaign']['type'])
            && isset($order_item_details['campaign']['ad'])) {

            $campaign_repo = $this->getEntityManager()->getRepository(Campaign::class);
            $campaign = $campaign_repo->createByCampaignItemDetails($order_item_details['campaign']);
        }

        if ($order_item_details['deliveryType'] === 'enterpriseDelivery') {
            $deliveryOrder = new EnterpriseDeliveryOrder();
        } else {
            $deliveryOrder = new DeliveryOrder();
        }

        $deliveryOrder
            ->setCustomer($customer)
            ->setDeliveryType($order_item_details['deliveryType'])
            ->setSource(($order_item_details['source']))
            ->setWeight(($order_item_details['weight']))
            ->setRef(uniqid());

        if ($deliveryOrder->isEnterpriseDelivery()) {
            $enterprise = null;
            if (isset($order_item_details['enterprise'])) {
                $enterprise_repo = $this->getEntityManager()->getRepository(Enterprise::class);
                $enterprise = $enterprise_repo->createByEnterpriseDetails($order_item_details['enterprise']);

                $deliveryOrder->setEnterprise($enterprise);

            } else {
                throw new \Exception("Since enterprise details are not found enterprise delivery order can't be processed!");
            }

            $deliveryOrder
                ->setOnBehalf(isset($order_item_details['onBehalf']) ? $order_item_details['onBehalf'] : '');
        }

        // If campaign is linked to this delivery order item then record the link as it can have its own workflow
        if ($campaign) {
            $deliveryOrder->setCampaign($campaign);
        }

        return $deliveryOrder;
    }

    // /**
    //  * @return DeliveryOrder[] Returns an array of DeliveryOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeliveryOrder
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
