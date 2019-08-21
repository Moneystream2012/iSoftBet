<?php

namespace App\Controller;

use App\Service\CustomerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiCustomerController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
final class ApiCustomerController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var CustomerService */
    private $customerService;

    /**
     * ApiCustomerController constructor.
     * @param SerializerInterface $serializer
     * @param CustomerService $customerService
     */
    public function __construct(SerializerInterface $serializer, CustomerService $customerService)
    {
        $this->serializer = $serializer;
        $this->customerService = $customerService;
    }

    /**
     * @Rest\Post("/api/customer", name="createCustomer")
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $customer = $this->customerService->createCustomer(
            $request->request->get('name'),
            $request->request->get('cnp')
        );

        return new JsonResponse($customer->getId(), 201, [], true);
    }

    /**
     * @Rest\Get("/api/customer", name="getAllCustomers")
     * @return JsonResponse
     */
    public function getAllAction(): JsonResponse
    {
        $customers = $this->customerService->getAll();
        $data = $this->serializer->serialize($customers, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
