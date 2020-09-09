<?php

namespace App\Repositories;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * CoreRepository constructor.
     */
    public function __construct()
    {
        $this->model = app($this->getModel());
    }

    /**
     * @return mixed
     */
    abstract protected function getModel();

    /**
     * @return Application|Model|mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}
