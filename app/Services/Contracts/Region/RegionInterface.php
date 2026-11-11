<?php

namespace App\Services\Contracts\Region;

interface RegionInterface
{
    public function provincePaginatedWithParams($params, $limit);

    public function cityPaginatedWithParams($params, $limit);

    public function districtPaginatedWithParams($params, $limit);

    public function villagePaginatedWithParams($params, $limit);

}
