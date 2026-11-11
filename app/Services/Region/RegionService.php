<?php

namespace App\Services\Region;

use App\Repositories\Contracts\Region\ProvinceInterface as ProvinceRepository;
use App\Repositories\Contracts\Region\CityInterface as CityRepository;
use App\Repositories\Contracts\Region\DistrictInterface as DistrictRepository;
use App\Repositories\Contracts\Region\VillageInterface as VillageRepository;
use App\Services\Contracts\Region\RegionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Ramsey\Uuid\Uuid;

class RegionService implements RegionInterface
{    
    protected $provinceRepository, $cityRepository, $districtRepository, $villageRepository;

    public function __construct(ProvinceRepository $provinceRepository, CityRepository $cityRepository, DistrictRepository $districtRepository, VillageRepository $villageRepository)
    {
        $this->provinceRepository = $provinceRepository;
        $this->cityRepository = $cityRepository;
        $this->districtRepository = $districtRepository;
        $this->villageRepository = $villageRepository;
    }

    /**
     * get data province with paginate and paramss
     *
     * @param $params
     * @param integer $limit
     * @return mixed
     */
    public function provincePaginatedWithParams($params, $limit = 10)
    {
        $provinces = $this->provinceRepository;
        if(isset($params->search) && !empty($params->search)) $provinces = $provinces->whereLike(['name'], $params->search);
        $provinces = $provinces->simplePaginate($limit);

        return $provinces;
    }

    /**
     * get city with paginate and params
     *
     * @param $params
     * @param integer $limit
     * @return mixed
     */
    public function cityPaginatedWithParams($params, $limit = 10)
    {
        $cities = $this->cityRepository;
        if(isset($params->search) && !empty($params->search)) $cities = $cities->whereLike(['name'], $params->search);
        if(isset($params->province_id) && !empty($params->province_id)) $cities = $cities->where('province_id', $params->province_id);
        $cities = $cities->simplePaginate($limit);

        return $cities;
    }

    /**
     * get district with paginate and params
     *
     * @param $params
     * @param integer $limit
     * @return mixed
     */
    public function districtPaginatedWithParams($params, $limit = 10)
    {
        $districts = $this->districtRepository;
        if(isset($params->search) && !empty($params->search)) $districts = $districts->whereLike(['name'], $params->search);
        if(isset($params->city_id) && !empty($params->city_id)) $districts = $districts->where('city_id', $params->city_id);
        $districts = $districts->simplePaginate($limit);

        return $districts;
    }

    /**
     * get village with paginate and params
     *
     * @param $params
     * @param integer $limit
     * @return mixed
     */
    public function villagePaginatedWithParams($params, $limit = 10)
    {
        $villages = $this->villageRepository;
        if(isset($params->search) && !empty($params->search)) $villages = $villages->whereLike(['name'], $params->search);
        if(isset($params->district_id) && !empty($params->district_id)) $villages = $villages->where('district_id', $params->district_id);
        $villages = $villages->simplePaginate($limit);

        return $villages;
    }

}
