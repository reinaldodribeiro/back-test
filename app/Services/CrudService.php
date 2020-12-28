<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class CrudService
{

    /**
     * Listagem Geral da Entidade
     *
     * @param
     * @return
     */
    public function getList($params = [])
    {
        $model = $this->prepareModel();
        return $model::all();
    }

    /**
     * @param array $data
     * @throws ValidationException
     * @return array
     */
    public function save($data) {
        $this->getValidator($data, true, null)->validate();
        return $this->performSave($data);
    }

    /**
     * @param string $id
     * @param array $data
     * @return array
     */
    public function update($id, $data) {
        $model = $this->getModel()->findOrFail($id);
        //$this->getValidator($data, false, $model)->validate();
        return $this->performUpdate($model, $data);
    }

    /**
     * @param Model $model
     * @param array $data
     * @param array $responseData
     * @return array
     */
    protected function performUpdate($model, $data) {
        return DB::transaction(function () use ($model, $data) {
            $model->update($this->prepareUpdate($model, $data));
            return $this->postUpdate($model, $data);
        });
    }

    /**
     * @param array $data
     * @param array $responseData
     * @return array
     */
    protected function performSave($data) {
        return DB::transaction(function () use ($data) {
            $model = $this->getModel($this->prepareSave($data));
            $model->save();
            return $this->postSave($model, $data);
        });
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareSave($data)
    {
        return $this->prepareFromFillable($data);
    }

    /**
     * @param Model $model
     * @param array $data
     * @return array
     */
    protected function prepareUpdate($model, $data) {
        $finalData = $this->prepareFromFillable($data);
        $guard = isset($model->guardFromUpdate) ? $model->guardFromUpdate : [];
        foreach ($guard as $key) {
            unset($finalData[$key]);
        }
        return $finalData;
    }

    /**
     * @param Model $model
     * @param array $data
     * @return array
     */
    protected function postSave($model, $data) {
        return [];
    }

    /**
     * @param Model $model
     * @param array $data
     * @return array
     */
    protected function postUpdate($model, $data) {
        return [];
    }

    /**
     * @param array $data
     * @param array $responseData
     * @return array
     */
    protected function prepareFromFillable($data) {
        $fillable = $this->getModel()->getFillable();
        $finalData = [];
        if (isset($fillable, $data)) {
            foreach ($fillable as $column) {
                if (array_key_exists($column, $data)) {
                    $finalData[$column] = $data[$column];
                }
            }
        }
        return $finalData;
    }


    /**
     * @param array $data
     * @param boolean $saving
     * @param Model $model
     * @return mixed
     */
    protected function getValidator($data, $saving, $model) {
        return Validator::make($data, $this->getRules($data, $saving, $model), []);
    }

    protected function getRules($data, $saving, $model) {
        return [];
    }


    /**
     * Retorna o Model com a instancia desejada.
     * @return Model
     */
    protected function prepareModel($data = [])
    {
        return $this->getModel();
    }


    protected abstract function getModel($data = []);
}
