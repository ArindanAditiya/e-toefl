<?php

namespace App\Services\MasterData;

use App\Repositories\Contracts\MasterData\CategoryInterface as CategoryRepo;
use App\Services\Contracts\MasterData\CategoryInterface;
use App\Models\MasterData\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
use App\Traits\Uploadable;

class CategoryService implements CategoryInterface
{
    use Uploadable;

    protected $image_path = 'upload_files/categories';

    protected $certificate_path = 'upload_files/certificates/categories';

    protected $categoryRepo;

    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function all($columns = ['*'])
    {
        return $this->categoryRepo->all($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->categoryRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->categoryRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->categoryRepo->find($id);
    }

    /**
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function create($request)
    {
        $certificateTemplate = null;
        try {
            $permissions = DB::transaction(function () use ($request, &$certificateTemplate) {
                $input = $request->all();
                if($request->hasFile('thumbnail')){
                    $file = $request->file('thumbnail')->getClientOriginalName();
                    $filename = pathinfo($file, PATHINFO_FILENAME);

                    $filename = $this->uploadFile($request->file('thumbnail'), $filename, $this->image_path);
                    $input['thumbnail'] = $filename;
                }

                if ($request->hasFile('certificate_template')) {
                    $certificateTemplate = $request->file('certificate_template')->store($this->certificate_path, 'public');
                    if (!$certificateTemplate) {
                        throw new \RuntimeException('Template sertifikat gagal disimpan.');
                    }
                    $input['certificate_template'] = $certificateTemplate;
                } else {
                    unset($input['certificate_template']);
                }

                return $this->categoryRepo->create($input);
            });
        } catch (\Throwable $exception) {
            if ($certificateTemplate) {
                Storage::disk('public')->delete($certificateTemplate);
            }
            throw $exception;
        }

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
        $category = $this->find($id);
        $oldCertificateTemplate = $category->certificate_template;
        $newCertificateTemplate = null;

        try {
            $permissions = DB::transaction(function () use ($request, $id, &$newCertificateTemplate) {
                $input = $request->except('_token','_method');
                $category = $this->find($id);

                if($request->hasFile('thumbnail')){
                    $this->deleteFile($category->thumbnail, $this->image_path);
                    $file = $request->file('thumbnail')->getClientOriginalName();
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $filename = $this->uploadFile($request->file('thumbnail'), $filename, $this->image_path);
                    $input['thumbnail'] = $filename;
                } else {
                    $input['thumbnail'] = $category->thumbnail;
                }

                if ($request->hasFile('certificate_template')) {
                    $newCertificateTemplate = $request->file('certificate_template')->store($this->certificate_path, 'public');
                    if (!$newCertificateTemplate) {
                        throw new \RuntimeException('Template sertifikat gagal disimpan.');
                    }
                    $input['certificate_template'] = $newCertificateTemplate;
                } else {
                    unset($input['certificate_template']);
                }

                return $this->categoryRepo->update($input, $id);
            });
        } catch (\Throwable $exception) {
            if ($newCertificateTemplate) {
                Storage::disk('public')->delete($newCertificateTemplate);
            }
            throw $exception;
        }

        if ($newCertificateTemplate && $oldCertificateTemplate) {
            $this->deleteCertificateTemplate($oldCertificateTemplate);
        }

        return $permissions;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        $category = $this->find($id);
        if (!$category) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Kategori tidak ditemukan.');
        }

        $this->categoryRepo->delete($id);
        $this->deleteFile($category->thumbnail, $this->image_path);
        $this->deleteCertificateTemplate($category->certificate_template);

        return true;
    }

    protected function deleteCertificateTemplate($path)
    {
        $prefix = $this->certificate_path . '/';
        $filename = $path ? substr($path, strlen($prefix)) : '';
        if (
            !$path ||
            !Str::startsWith($path, $prefix) ||
            !$filename ||
            basename($filename) !== $filename
        ) {
            return;
        }

        $storage = Storage::disk('public');
        if ($storage->exists($path) && !$storage->delete($path)) {
            throw new \RuntimeException('Template sertifikat gagal dihapus.');
        }
    }
}
