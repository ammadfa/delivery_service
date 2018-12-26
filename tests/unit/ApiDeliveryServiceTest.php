<?php 
class ApiDeliveryServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {

    }

    protected function _after()
    {
    }

    private function getFileContents($filePath) {
        return file_get_contents($filePath);
    }

    private function processOrdersPayload($jsonArrayContent) {
        $processedOrdersCount = 0;
        $responseData = array();
        $deliveryOrders = array();
        $errors = array();
        foreach ($jsonArrayContent as $item) {
            try {
                $deliveryOrdersRepo = $this->getModule('Symfony')->_getEntityManager()->getRepository(\App\Entity\DeliveryOrder::class);
                $deliveryOrder = $deliveryOrdersRepo->createByOrderItemDetails($item);

                if ($deliveryOrder->isEnterpriseDelivery()) {
                    $deliveryProvider = new \App\Provider\EnterpriseDelivery();
                } else {
                    $deliveryProvider = new \App\Provider\PersonalDelivery();
                }

                $deliveryOrder = $deliveryProvider->process($deliveryOrder);

                $deliveryOrders[] = $deliveryOrder;

                $responseData[] = array(
                    'ref' => $deliveryOrder->getRef(),
                    'fulfillment_status' => $deliveryOrder->getFulfillmentStatus(),
                );

                if ($deliveryOrder->getFulfillmentStatus() === 'COMPLETED') {
                    $processedOrdersCount++;
                }

            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        \Codeception\Util\Debug::debug($responseData);
        \Codeception\Util\Debug::debug($errors);

        return array(
            'responseData' => $responseData,
            'deliveryOrders' => $deliveryOrders,
            'processedOrdersCount' => $processedOrdersCount,
            'errors' => $errors
        );
    }

    // tests
    public function testFetchDummyOrdersPayload()
    {
        $this->assertNotNull($this->getFileContents(__DIR__ . '/../../var/data/test_payload.json'));
    }

    public function testProcessDummyOrdersPayloadWithNoCustomerInfo()
    {
        $jsonStringContent = $this->getFileContents(__DIR__ . '/../../var/data/test_payload_no_customer_info.json');
        $jsonArrayContent = json_decode($jsonStringContent, true);

        $results = $this->processOrdersPayload($jsonArrayContent);

        // 0 = expected completed orders since all three dummy orders have no customer data
        $this->assertEquals($results['processedOrdersCount'], 0);
    }

    public function testProcessDummyOrdersPayloadWithNoEnterpriseInfo()
    {
        $jsonStringContent = $this->getFileContents(__DIR__ . '/../../var/data/test_payload_no_enterprise_info.json');
        $jsonArrayContent = json_decode($jsonStringContent, true);

        $results = $this->processOrdersPayload($jsonArrayContent);

        // 2 = expected completed orders since one of the orders is an enterprise delivery and it has no enterprise info
        $this->assertEquals($results['processedOrdersCount'], 2);
    }

    public function testProcessDummyOrdersPayload()
    {
        $jsonStringContent = $this->getFileContents(__DIR__ . '/../../var/data/test_payload.json');
        $jsonArrayContent = json_decode($jsonStringContent, true);

        $results = $this->processOrdersPayload($jsonArrayContent);

        // 3 = expected completed orders (based on the test_payload.json data)
        $this->assertEquals($results['processedOrdersCount'], 3);
    }
}