<?php

namespace App\Repositories;

use App\Models\Solution as Model;
use Illuminate\Database\Eloquent\Collection;

class SolutionRepository extends CoreRepository
{
    /**
     * @return mixed|string
     */
    protected function getModel()
    {
        return Model::class;
    }

    public function  getIndex($id)
    {
        $solutions1 = $this
            ->startConditions()
            ->where('problem_id', $id)
            ->where('in_work', true)
            ->orderBy('name');
        $solutions2 = $this
            ->startConditions()
            ->where('problem_id', $id)
            ->where('in_work', false)
            ->latest();
        $solutions = $solutions1->unionAll($solutions2);

        return $solutions;
    }

    public function getShowInWork($id)
    {
        $solutions = $this
            ->startConditions()
            ->where('problem_id', $id)
            ->where('in_work', true)
            ->orderBy('name')
            ->get();

        return $solutions;
    }

    public function getCountSolution($id)
    {
        $countSolution = $this
            ->startConditions()
            ->where('problem_id', $id)
            ->where('in_work', '=', true)
            ->count();

        return $countSolution;
    }
}
