<?php

namespace App\Repositories\Filters;


/**
 * Class FilterBasicsRepository
 * @package App\Repositories\Filters
 */
class FilterBasicsRepository {

    /**
     *
     * @param $query
     * @param $accounts
     * @return mixed
     */
    public function filterAccounts($query, $accounts)
    {
        if ($accounts['in']) {
            $query = $query->whereIn('account_id', $accounts['in']);
        }
        if ($accounts['out']) {
            $query = $query->whereNotIn('account_id', $accounts['out']);
        }
        return $query;
    }

    /**
     * Filter by type (credit, debit, or transfer)
     * @param $query
     * @param $types
     * @return mixed
     */
    public function filterTypes($query, $types)
    {
        if ($types['in']) {
            $query = $query->whereIn('type', $types['in']);
        }
        if ($types['out']) {
            $query = $query->whereNotIn('type', $types['out']);
        }
        return $query;
    }

    /**
     * Filter by total
     * @param $query
     * @param $total
     * @return mixed
     */
    public function filterTotal($query, $total)
    {
        if ($total['in']) {
            $query = $query->where('total', $total['in']);
        }
        if ($total['out']) {
            $query = $query->where('total', '!=', $total['out']);
        }
        return $query;
    }

    /**
     * Filter by reconciliation
     * @param $query
     * @param $value
     * @return mixed
     */
    public function filterReconciled($query, $value)
    {
        if ($value !== "any") {
            return $query->where('reconciled', convertFromBoolean($value));
        }

        return $query;
    }

    /**
     *
     * @param $query
     * @param $type
     * @param $value
     * @return
     */
    public function filterDescriptionOrMerchant($query, $type, $value)
    {
        if ($value['in']) {
            $query = $query->where($type, 'LIKE', '%' . $value['in'] . '%');
        }
        if ($value['out']) {
            $query = $query->where($type, 'NOT LIKE', '%' . $value['out'] . '%');
        }
        return $query;
    }

    /**
     * Filter by date
     * @param $query
     * @param $type
     * @param $value
     */
    public function filterDates($query, $type, $value)
    {
        if ($type === "single_date") {
            if ($value['in']['sql']) {
                $query = $query->where('date', $value['in']['sql']);
            }
            if ($value['out']['sql']) {
                $query = $query->where('date', '!=', $value['out']['sql']);
            }
        }

        elseif ($type === "from_date") {
            if ($value['in']['sql']) {
                $query = $query->where('date', '>=', $value['in']['sql']);
            }
        }

        elseif ($type === "to_date") {
            if ($value['in']['sql']) {
                $query = $query->where('date', '<=', $value['in']['sql']);
            }
        }

        return $query;
    }

}