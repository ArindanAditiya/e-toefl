<?php

namespace App\Services\MasterData;

use App\Repositories\Contracts\MasterData\AnnouncementInterface as AnnouncementRepo;
use App\Services\Contracts\MasterData\AnnouncementInterface;
use App\Models\MasterData\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

class AnnouncementService implements AnnouncementInterface
{
    protected $announcementRepo;

    public function __construct(AnnouncementRepo $announcementRepo)
    {
        $this->announcementRepo = $announcementRepo;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->announcementRepo->getAllPaginatedWithParams($params, $limit);
    }

     /**
     * Find data by id
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->announcementRepo->find($id);
    }

    /**
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function create($request)
    {
        $permissions = DB::transaction(function () use ($request) {
            $input = $request->all();
            return $this->announcementRepo->create($input);
        });

        return $permissions;
    }

     /**
     * @param $id
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
    */
    public function update($request, $id)
    {
        $permissions = DB::transaction(function () use ($request, $id) {
            $input = $request->except('_token','_method');
            return $this->announcementRepo->update($input, $id);
        });

        return $permissions;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->announcementRepo->delete($id);
    }

    public function getAnnouncementSummaries()
    {
        return $this->announcementRepo->getAnnouncementSummaries();
    }
}
