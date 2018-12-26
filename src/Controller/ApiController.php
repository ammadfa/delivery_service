<?php

namespace App\Controller;

use App\Entity\DeliveryOrder;
use App\Entity\EnterpriseDeliveryOrder;
use App\Entity\Person;
use App\Entity\Provider\Delivery;
use App\Provider\EnterpriseDelivery;
use App\Provider\PersonalDelivery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/test", name="apiTest")
     */
    public function testAction()
    {
        $testPayloadFilePath = $this->getParameter('kernel.project_dir') . '/var/data/test_payload.json';
        $jsonStringContent = file_get_contents($testPayloadFilePath);
        $jsonArrayContent = json_decode($jsonStringContent, true);

        $responseData = array();
        $deliveryOrders = array();
        foreach ($jsonArrayContent as $item) {
            $deliveryOrdersRepo = $this->getDoctrine()->getRepository(DeliveryOrder::class);
            $deliveryOrder = $deliveryOrdersRepo->createByOrderItemDetails($item);

            if ($deliveryOrder->isEnterpriseDelivery()) {
                $deliveryProvider = new EnterpriseDelivery();
            } else {
                $deliveryProvider = new PersonalDelivery();
            }

            $deliveryProvider->setDeliveryOrder($deliveryOrder);
            $deliveryOrder = $deliveryProvider->process();

            $deliveryOrders[] = $deliveryOrder;

            $responseData[] = array(
                'ref' => $deliveryOrder->getRef(),
                'fulfillment_status' => $deliveryOrder->getFulfillmentStatus(),
            );
        }

        return $this->returnJsonResponse($responseData);
    }

    public function returnJsonResponse($data) {
        $datetime = new \DateTime('2010-12-30 23:21:46');
        $datetime_iso = $datetime->format(\DateTime::ATOM);

        return new JsonResponse(array(
            'program' => 'delivery_service',
            'version' => '1.0.0',
            'release' => '1',
            'datetime' => $datetime_iso,
            'timestamp' => $datetime->getTimestamp(),
            'status' => 'success',
            'code' => 200,
            'message' => 'OK',
            'data' => $data
        ));
    }
}
