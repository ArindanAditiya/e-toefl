<?php

namespace App\Services\Transaction;

use App\Repositories\Contracts\Transaction\BankInterface as BankRepo;
use App\Services\Contracts\Transaction\BankInterface;
use App\Models\Transaction\Bank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use App\Traits\Uploadable;

class BankService implements BankInterface
{
    use Uploadable;

    protected $image_path = 'upload_files/banks';

    protected $bankRepo;

    public function __construct(BankRepo $bankRepo)
    {
        $this->bankRepo = $bankRepo;
    }

    public function all($columns = ['*'])
    {
        return $this->bankRepo->all($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->bankRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->bankRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->bankRepo->find($id);
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
            if($request->hasFile('image')){
                $file = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $filename = $this->uploadFile($request->file('image'), $filename, $this->image_path);
                $input['image'] = $filename;
            }

            return $this->bankRepo->create($input);
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
            $bank = $this->find($id);

            if($request->hasFile('image')){
                $this->deleteFile($bank->image, $this->image_path);
                $file = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $filename = $this->uploadFile($request->file('image'), $filename, $this->image_path);
                $input['image'] = $filename;
            } else {
                $input['image'] = $bank->image;
            }
            return $this->bankRepo->update($input, $id);
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
        $bank = $this->find($id);
        $this->deleteFile($bank->image, $this->image_path);
        return $this->bankRepo->delete($id);
    }
}
