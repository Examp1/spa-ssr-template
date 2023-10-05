<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Daaner\NovaPoshta\Models\Address;

class NovaPoshtaController extends Controller
{
    /**
     * Search city by name
     *
     * @param string|null $city
     * @return JsonResponse
     */
    public function searchCity(?string $city = null, int $limit = 50, int $page = 1)
    {
        $adr = new Address;
        $adr->setLimit($limit);
        $adr->setPage($page);

        $result = $adr->getCities($city);

        if (
            isset($result['success'])
            && true === $result['success']
            && isset($result['info']['info']['totalCount'])
            && $result['info']['info']['totalCount'] > 0
        ) {
            return response()->json([
                'success' => 1,
                'data' => $result['result']
            ]);
        }

        return  response()->json([
            'success' => 0,
            'data' => null
        ]);
    }

    public function searchSettlements(?string $city = null, int $limit = 50, int $page = 1)
    {
        $adr = new Address;
        $adr->setLimit($limit);
        $adr->setPage($page);

        $result = $adr->searchSettlements($city);

        if (
            isset($result['success'])
            && true === $result['success']
            && isset($result['result'][0]['TotalCount'])
            && $result['result'][0]['TotalCount'] > 0
        ) {
            return response()->json([
                'success' => 1,
                'data' => $result['result'][0]['Addresses']
            ]);
        }

        return  response()->json([
            'success' => 0,
            'data' => null
        ]);
    }

    /**
     * Return all branches by cityRef
     *
     * @param string $cityRef
     * @return JsonResponse
     */
    public function getBranchesByCity(string $cityRef, int $limit = 50, int $page = 1)
    {
        $adr = new Address;
        $adr->setLimit($limit);
        $adr->setPage($page);

        $result = $adr->getWarehouses($cityRef, false);

        if (
            isset($result['success'])
            && true === $result['success']
            && isset($result['info']['info']['totalCount'])
            && $result['info']['info']['totalCount'] > 0
        ) {
            return response()->json([
                'success' => 1,
                'data' => $result['result']
            ]);
        }

        return  response()->json([
            'success' => 0,
            'data' => null
        ]);
    }
}
