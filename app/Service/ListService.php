<?php

namespace App\Service;

use App\Models\ListModel;
use Illuminate\Support\Facades\DB;

class ListService
{
    public function find(string $id)
    {
        return $this->listModel()->find($id);
    }

    public function findAllDefaultOrOrder(int $userId, bool $order = false, string $orderBy = "created_at", string $orderDir = "desc", $id = null)
    {
        try {
            $listModel = $this->listModel();
            
            $query = $listModel->where('user_id', $userId);

            if($id != null) {
                $query->where(["id" => $id]);
            }

            if ($order) {
                $query->orderBy($orderBy, $orderDir);
            }

            return $query->get();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $listModel = $this->listModel();
            $storeService = $listModel->updateOrCreate($data);
            
            return $storeService;
            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function show(string $id)
    {
        try {
            $listModel = $this->listModel();
            $showService = $listModel->find($id);
            return $showService;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $listModel = $this->listModel();
            $findListModel = $listModel->find($id);

            if ($findListModel) {
                $findListModel->update($data);
                DB::commit();
                return $findListModel;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $listModel = $this->listModel();
            $findListModel = $listModel->find($id);
            
            if ($findListModel) {
                $deleteListModel = $findListModel->delete();
                DB::commit();
                return $deleteListModel;
            }
            
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function exists(string $id)
    {
        return $this->listModel()->find($id) !== null;
    }

    private function listModel()
    {
        return new ListModel;
    }
}
