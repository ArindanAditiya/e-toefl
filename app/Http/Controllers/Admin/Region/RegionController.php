<?php

namespace App\Http\Controllers\Admin\Region;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\Region\{ProvinceInterface, CityInterface, DistrictInterface, VillageInterface};

class RegionController extends Controller
{
    protected $provinceRepository, $cityRepository, $districtRepository, $villageRepository;

    public function __construct(ProvinceInterface $provinceRepository, CityInterface $cityRepository, DistrictInterface $districtRepository, VillageInterface $villageRepository)
    {
        $this->provinceRepository = $provinceRepository;
        $this->cityRepository = $cityRepository;
        $this->districtRepository = $districtRepository;
        $this->villageRepository = $villageRepository;
        $this->villageRepository = $villageRepository;
    }

    public function province()
    {
        return $this->provinceRepository->all();
    }

    /**
     * get city order by id provinsi
     *
     * @param $id
     * @return void
     */
    public function city($id)
    {
        return $this->cityRepository->findWhere(['province_id' => $id])->get();
    }

    /**
     * get district order by id city
     *
     * @param $id
     * @return void
     */
    public function district($id)
    {
        return $this->districtRepository->findWhere(['city_id' => $id])->get();
    }

    /**
     * get village order by id district
     *
     * @param $id
     * @return void
     */
    public function village($id)
    {
        return $this->villageRepository->findWhere(['district_id' => $id])->get();
    }
}
