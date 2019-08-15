<?php

namespace App\Controller;

use App\Service\TransactionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiTransactionController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class ApiTransactionController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var TransactionService */
    private $transactionService;

    /**
     * ApiTransactionController constructor.
     * @param SerializerInterface $serializer
     * @param TransactionService $transactionService
     */
    public function __construct(SerializerInterface $serializer, TransactionService $transactionService)
    {
        $this->serializer = $serializer;
        $this->transactionService = $transactionService;
    }

    /**
     * @Rest\Post("/api/transaction/create", name="createTransaction")
     * @param Request $request
     * @return JsonResponse
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAction(Request $request): JsonResponse
    {
        $transaction = $this->transactionService->createTransaction(
            $request->request->get('customerId'),
            $request->request->get('amount')
        );

        return new JsonResponse($transaction->getId(), 200, [], true);
    }

    /**
     * @Rest\Get("/api/transactions", name="getAllTransactions")
     * @return JsonResponse
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAllActions(): JsonResponse
    {
        $transactions = $this->transactionService->getAll();
        $data = $this->serializer->serialize($transactions, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
