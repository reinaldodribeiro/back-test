<?php

namespace App\Services;

use App\Constants\EStatusWorkflow;
use App\Models\Workflow;
use App\Traits\RabbitMQ;

class WorkflowService extends CrudService
{
    use RabbitMQ;

    protected function prepareUpdate($model, $data)
    {
        $finalData = [];
        $finalData['status'] = EStatusWorkflow::CONSUMED;
        return $finalData;
    }

    protected function postUpdate($model, $data)
    {
        return $model;
    }

    protected function prepareSave($data)
    {
        $finalData = parent::prepareSave($data);
        $finalData['status'] = EStatusWorkflow::INSERTED;
        return $finalData;
    }

    protected function postSave($model, $data)
    {
        $this->sendMessageQueue($model);
        return $model;
    }

    protected function getRules($data, $saving, $model)
    {
       $rules = [
           "data" => "string|required",
           "steps" => "array|required"
       ];

       return $rules;
    }

    protected function getModel($data = [])
    {
        return new Workflow($data);
    }
}
