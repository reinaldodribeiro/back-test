<?php

namespace App\Http\Controllers;

use App\Services\WorkflowService;

class WorkflowController extends CrudController
{
    public function __construct(WorkflowService $service) {
        $this->service = $service;
    }

    public function consume()
    {
        return $this->service->consume();
    }
}
