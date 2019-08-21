<?php

namespace App\Resource\Filtering\Transaction;

use App\Resource\Filtering\AbstractFilterDefinitionFactory;
use App\Resource\Filtering\FilterDefinitionFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class TransactionFilterDefinitionFactory
    extends AbstractFilterDefinitionFactory
    implements FilterDefinitionFactoryInterface
{
    private const KEY_CUSTOMER_ID = 'customerId';
    private const KEY_AMOUNT_FROM = 'amountFrom';
    private const KEY_AMOUNT_TO = 'amountTo';
    private const KEY_DATE_FROM = 'dateFrom';
    private const KEY_DATE_TO = 'dateTo';
    private const KEY_SORT_BY_QUERY = 'sortBy';
    private const KEY_SORT_BY_ARRAY = 'sortBy';
    private CONST ACCEPTED_SORT_FIELDS = ['id', 'customerId', 'amount', 'date'];

    /**
     * @param Request $request
     * @return TransactionFilterDefinition
     */
    public function factory(Request $request): TransactionFilterDefinition
    {
        return new TransactionFilterDefinition(
            $request->get(self::KEY_CUSTOMER_ID),
            $request->get(self::KEY_AMOUNT_FROM),
            $request->get(self::KEY_AMOUNT_TO),
            $request->get(self::KEY_DATE_FROM),
            $request->get(self::KEY_DATE_TO),
            $request->get(self::KEY_SORT_BY_QUERY),
            $this->sortQueryToArray($request->get(self::KEY_SORT_BY_ARRAY))
        );
    }

    /**
     * @return array
     */
    public function getAcceptedSortField(): array
    {
        return self::ACCEPTED_SORT_FIELDS;
    }
}
