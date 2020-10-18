<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Problem\ProblemChangeDescriptionRequest;
use App\Http\Requests\Problem\ProblemChangeExperienceRequest;
use App\Http\Requests\Problem\ProblemChangeImportanceRequest;
use App\Http\Requests\Problem\ProblemChangePossibleSolutionRequest;
use App\Http\Requests\Problem\ProblemChangeProgressRequest;
use App\Http\Requests\Problem\ProblemChangeResultRequest;
use App\Http\Requests\Problem\ProblemChangeUrgencyRequest;
use App\Http\Requests\Problem\ProblemCreateRequest;
use App\Http\Requests\Problem\ProblemFiltrationForConfirmationRequest;
use App\Http\Requests\Problem\ProblemFiltrationForExecutionRequest;
use App\Http\Requests\Problem\ProblemFiltrationRequest;
use App\Http\Requests\Problem\ProblemsArchiveFiltrationRequest;
use App\Http\Requests\Problem\UserProblemsFiltrationRequest;
use App\Models\Group;
use App\Models\Problem;
use App\Repositories\ProblemRepository;
use App\Services\ProblemService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProblemController extends Controller
{
    private $problemService;
    private $problemRepository;

    public function __construct()
    {
        $this->problemRepository = app(ProblemRepository::class);
        $this->problemService = app(ProblemService::class);
    }

    /**
     * Получение списка всех проблем
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json($this->problemRepository->getProblems(), 200);
    }

    /**
     * Создание новой проблемы
     *
     * @param ProblemCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProblemCreateRequest $request)
    {
        return $this->problemService->store($request->validated());
    }

    /**
     * Получение проблемы
     *
     * @param Problem $problem
     * @return JsonResponse
     */
    public function show(Problem $problem)
    {
        return response()->json($this->problemRepository->showProblem($problem), 200);
    }

    /**
     * Изменение названия проблемы
     *
     * @param ProblemCreateRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function update(ProblemCreateRequest $request, Problem $problem)
    {
        $correctStatuses = ['На рассмотрении'];
        $error = 'Действие возможно только при статусе проблемы “на рассмотрении”';

        return $this->problemService->updateWithStatusCheck($problem, $request->validated(), $correctStatuses, $error);
    }

    /**
     * Удаление проблемы
     *
     * @param Problem $problem
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Problem $problem)
    {
        return $this->problemService->delete($problem);
    }

    /**
     * Поставить лайк проблеме
     *
     * @param Problem $problem
     * @return JsonResponse
     */
    public function likeProblem(Problem $problem)
    {
        return $this->problemService->likeProblem($problem);
    }

    /**
     * Направить проблему в подразделение
     *
     * @param Request $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function sendToGroup(Request $request, Problem $problem)
    {
        return $this->problemService->sendToGroup($problem, $request['group_ids']);
    }

    /**
     * Задать/изменить для проблемы полученный в процессе решения опыт
     *
     * @param ProblemChangeExperienceRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function setExperience(ProblemChangeExperienceRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    /**
     * Задать/изменить для проблемы полученный результат
     *
     * @param ProblemChangeResultRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function setResult(ProblemChangeResultRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    /**
     * Задать/изменить возможное решение проблемы
     *
     * @param ProblemChangePossibleSolutionRequest $request
     * @param Problem $problem
     * @return Problem|JsonResponse
     */
    public function setPossibleSolution(ProblemChangePossibleSolutionRequest $request, Problem $problem)
    {
        $correctStatuses = ['На рассмотрении'];
        $error = 'Действие возможно только при статусе проблемы “на рассмотрении”';

        return $this->problemService->updateWithStatusCheck($problem, $request->validated(), $correctStatuses, $error);
    }

    /**
     * Задать/изменить описание проблемы
     *
     * @param ProblemChangeDescriptionRequest $request
     * @param Problem $problem
     * @return Problem|JsonResponse
     */
    public function setDescription(ProblemChangeDescriptionRequest $request, Problem $problem)
    {
        $correctStatuses = ['На рассмотрении'];
        $error = 'Действие возможно только при статусе проблемы “на рассмотрении”';

        return $this->problemService->updateWithStatusCheck($problem, $request->validated(), $correctStatuses, $error);
    }

    /**
     * Изменить важность проблемы
     *
     * @param ProblemChangeImportanceRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function setImportance(ProblemChangeImportanceRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    /**
     * Изменить прогресс решения проблемы
     *
     * @param ProblemChangeProgressRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function setProgress(ProblemChangeProgressRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    /**
     * Изменить срочность проблемы
     *
     * @param ProblemChangeUrgencyRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function setUrgency(ProblemChangeUrgencyRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    /**
     * Отправить проблему для подтверждения заказчику решения
     *
     * @param Problem $problem
     * @return Problem|JsonResponse
     */
    public function sendForConfirmation(Problem $problem)
    {
        return $this->problemService->sendForConfirmation($problem, $problem->creator_id, $problem->solution);
    }

    /**
     * Отклонение подтверждения проблемы заказчиком решения
     *
     * @param Problem $problem
     * @return Problem|JsonResponse
     */
    public function rejectSolution(Problem $problem)
    {
        $data = ['status' => 'На рассмотрении'];
        $correctStatuses = ['На проверке заказчика'];
        $error = 'Действие возможно только при статусе проблемы “На проверке заказчика”';

        return $this->problemService->updateWithStatusCheck($problem, $data, $correctStatuses, $error);
    }

    /**
     * Подтверждение решения проблемы заказчиком решения
     *
     * @param Problem $problem
     * @return Problem|JsonResponse
     */
    public function confirmSolution(Problem $problem)
    {
        $data = ['status' => 'Решена'];
        $correctStatuses = ['На проверке заказчика'];
        $error = 'Действие возможно только при статусе проблемы “На проверке заказчика”';

        return $this->problemService->updateWithStatusCheck($problem, $data, $correctStatuses, $error);
    }

    /**
     * Список проблем текущего пользователя, который является их создателем со всеми статусами,
     * кроме “решена” и “удалена”
     *
     * @param UserProblemsFiltrationRequest $request
     * @return Collection
     */
    public function userProblems(UserProblemsFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->userProblems($filters);
    }

    /**
     * Список проблем со статусом “на рассмотрении” сотрудников подразделения
     *
     * @param ProblemFiltrationForConfirmationRequest $request
     * @return JsonResponse
     */
    public function problemsForConfirmation(ProblemFiltrationForConfirmationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsForConfirmation($filters);
    }

    /**
     * Список проблем со статусом “в работе”, для которых пользователь является ответственным за решение и/или
     * ответственным для задачи + проблемы со статусом “на проверке заказчика”, для которых пользователь
     * является ответственным за решение
     *
     * @param ProblemFiltrationForExecutionRequest $request
     * @return JsonResponse
     */
    public function problemsForExecution(ProblemFiltrationForExecutionRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsForExecution($filters);
    }

    /**
     * Списки проблем, которые были направлены в подразделения со статусом “в работе”, “на проверке заказчика”
     *
     * @param ProblemFiltrationRequest $request
     * @param Group $group
     * @return JsonResponse
     */
    public function problemsByGroups(ProblemFiltrationRequest $request, Group $group)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsByGroups($filters, $group);
    }

    /**
     * Проблемы всех подразделений со статусом “в работе”, “на проверке заказчика”
     *
     * @param ProblemFiltrationRequest $request
     * @return JsonResponse
     */
    public function problemsOfAllGroups(ProblemFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsOfAllGroups($filters);
    }

    /**
     * Архив проблем, т.е. проблемы со статусом “решена”, “удалена”
     *
     * @param ProblemsArchiveFiltrationRequest $request
     * @return array
     */
    public function problemsArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsArchive($filters);
    }

    /**
     * Количественные показатели статистики
     *
     * @return JsonResponse
     */
    public function statisticQuantitativeIndicators()
    {
        return response()->json($this->problemRepository->statisticQuantitativeIndicators(), 200);
    }

    /**
     * Статистика: Отдельные категории проблем
     *
     * @return JsonResponse
     */
    public function statisticCategories()
    {
        return response()->json($this->problemRepository->statisticCategories(), 200);
    }

    /**
     * Статистика проблем по кварталам
     *
     * @return JsonResponse
     */
    public function statisticQuarterly()
    {
        return response()->json($this->problemRepository->statisticQuarterly(), 200);
    }

    /**
     * Подсчет количества проблем во вкладках "Предложенные мной", "На рассмотрении",
     * "Для исполнения" для текущего пользователя
     *
     * @return JsonResponse
     */
    public function countProblems()
    {
        return response()->json($this->problemRepository->countProblems(), 200);
    }

}
