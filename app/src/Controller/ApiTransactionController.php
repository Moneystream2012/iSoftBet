<?php

namespace App\Controller;

use App\Resource\Filtering\Transaction\TransactionFilterDefinitionFactory;
use App\Resource\Pagination\PageRequestFactory;
use App\Resource\Pagination\Transaction\TransactionPagination;
use App\Service\TransactionService;
use FOS\HttpCacheBundle\CacheManager;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\HttpCacheBundle\Configuration\Tag;
use FOS\HttpCacheBundle\Configuration\InvalidatePath;
use FOS\HttpCacheBundle\Configuration\InvalidateRoute;
use FOS\HttpCacheBundle\Http\SymfonyResponseTagger;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Class ApiTransactionController
 * @package App\Controller
 *
 * @IsGranted("ROLE_ADMIN")
 */
final class ApiTransactionController extends AbstractController
{
    const CACHE_INTERVAL = 3600;

    /** @var SerializerInterface */
    private $serializer;

    /** @var TransactionService */
    private $transactionService;

    /** @var TransactionPagination */
    private $transactionPagination;

    /** @var AdapterInterface */
    private $cache;

    /**
     * ApiTransactionController constructor.
     * @param SerializerInterface $serializer
     * @param TransactionService $transactionService
     * @param TransactionPagination $transactionPagination
     * @param AdapterInterface $cache
     */
    public function __construct(
        SerializerInterface $serializer,
        TransactionService $transactionService,
        TransactionPagination $transactionPagination,
        AdapterInterface $cache
    )
    {
        $this->serializer = $serializer;
        $this->transactionService = $transactionService;
        $this->transactionPagination = $transactionPagination;
        $this->cache = $cache;
    }

    /**
     * @Rest\Post("/api/transaction", name="createTransaction")
     *
     * @param Request $request
     * @param CacheManager $cacheManager
     * @return JsonResponse
     */
    public function createAction(Request $request, CacheManager $cacheManager): JsonResponse
    {
        $customerId = (int) $request->request->get('customerId');

        $transaction = $this->transactionService->createTransaction(
            $customerId,
            $request->request->get('amount')
        );

        $cacheManager->invalidateTags(['transactions']);

        $data = $this->serializer->serialize($transaction, 'json');
        return new JsonResponse($data, 201, [], true);
    }

    /**
     * @Rest\Put("/api/transaction/{transactionId}", name="putTransaction")
     *
     * @param int $transactionId
     * @param Request $request
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */

    public function updateAction(int $transactionId, Request $request): JsonResponse
    {
        $transaction = $this->transactionService->updateTransaction(
            $transactionId,
            $request->request->get('amount')
        );

        $this->cache->deleteItem("customer_" . $transaction->getCustomerId());

        $data = $this->serializer->serialize($transaction, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Delete("/api/transaction/{transactionId}", name="deleteTransaction")
     *
     * @param int $transactionId
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function deleteAction(int $transactionId): JsonResponse
    {
        $result = $this->transactionService->deleteTransaction($transactionId);

        $this->cache->deleteItem("customer_" . $transactionId);

        return new JsonResponse(
            $result ? "success" : "fail",
            $result ? 200 : 404, [],
            true
        );
    }

    /**
     * @Rest\Get("/api/transactions", name="getAllTransactions")
     * @return JsonResponse
     */
    public function getAllAction(): JsonResponse
    {
        $transactions = $this->transactionService->findBy([]);
        $data = $this->serializer->serialize($transactions, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\View() @Rest\Get("/api/transaction", name="getAllTransactionsFiltered")
     *
     * @param Request $request
     * @param SymfonyResponseTagger $responseTagger
     * @return JsonResponse
     */
    public function viewAction(Request $request, SymfonyResponseTagger $responseTagger): JsonResponse
    {
        $pageRequestFactory = new PageRequestFactory();
        $page = $pageRequestFactory->fromRequest($request);

        $transactionFilterDefinitionFactory = new TransactionFilterDefinitionFactory();
        $filters = $transactionFilterDefinitionFactory->factory($request);

//        $key = "view_" . $page->getHashKey(). $filters->getHashKey();

//        $data = $this->cache->get($key, function (ItemInterface $item) use ($page, $filters, $key) {
//            $item->expiresAfter(self::CACHE_INTERVAL);

            $transactions = $this->transactionPagination->paginate($page, $filters);

            $data = $this->serializer->serialize($transactions, 'json');

//            $this->cache->save($item);
//            return $data;
//        });

//        $responseTagger->addTags(['transactions']);

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Get("/api/transaction/{customerId}", name="getCustomerTransactions")
     *
     * @param int $customerId
     * @return JsonResponse
     */
    public function getCustomerTransactionsAction(int $customerId): JsonResponse
    {
        $key = "customer_" . $customerId;

        $data = $this->cache->get($key, function (ItemInterface $item) use ($customerId) {
            $item->expiresAfter(self::CACHE_INTERVAL);

            $data = $this->getFilteredList(['customerId' => $customerId]);

            $this->cache->save($item);
            return $data;
        });

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Rest\Get("/api/transaction/{customerId}/{transactionId}", name="getTransaction")
     *
     * @param int $customerId
     * @param int $transactionId
     * @return JsonResponse
     */
    public function getAction(int $customerId, int $transactionId): JsonResponse
    {
        $key = "transaction_" . $transactionId;

        $transaction = $this->cache->get($key, function (ItemInterface $item) use ($customerId, $transactionId) {
            $item->expiresAfter(self::CACHE_INTERVAL);

            $transaction = $this->transactionService->findOneBy([
                'customerId' => $customerId,
                'id' => $transactionId,
            ]);

            $this->cache->save($item);
            return $transaction;
        });

        if (!$transaction) {
            return new JsonResponse([], 404, [], true);
        }

        $data = $this->serializer->serialize($transaction, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param array $criteria
     * @param array|null $order
     * @param int $limit
     * @param int $offset
     * @return string
     */
    protected function getFilteredList(array $criteria=[], array $order=null, int $limit=null, int $offset=null): string
    {
        $transactions = $this->transactionService->findBy($criteria, $order, $limit, $offset);
        return $this->serializer->serialize($transactions, 'json');
    }
}
