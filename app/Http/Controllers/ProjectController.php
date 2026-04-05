<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Customer;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

/**
 * Class ProjectController
 * 
 * Handles CRUD operations for Projects.
 */

class ProjectController extends Controller
{
    /**
     * Display the projects index page.
     *
     * @return View
     */

    public function index(): View
    {
        $customers = Customer::active()->get();

        return view('administration.projects.index', compact('customers'));
    }

    /**
     * Store a newly created project in the database.
     *
     * @param  StoreProjectRequest $request
     * @return JsonResponse
     */

    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $project = Project::create($data);

            return $this->sendResponse($project, 'Project successfully created!');
        } catch (Exception $e) {
            Log::error('Project store failed', [
                'user_id' => Auth::id(),
                'payload' => $request->safe()->all(),
                'error' => $e->getMessage(),
            ]);

            return $this->sendError('Error saving project', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified project.
     *
     * @param Project $project
     * @return JsonResponse
     */

    public function show(Project $project): JsonResponse
    {

        return $this->sendResponse($project->load('customer'), 'Project data retrieved');
    }

    /**
     * Update the specified project in the database.
     *
     * @param ProjectRequest $request
     * @param Project $project
     * @return JsonResponse
     */

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $data = $request->validated();

            $project->update($data);

            return $this->sendResponse($project, 'Project updated successfully');
        } catch (Exception $e) {
            Log::error('Project update failed', [
                'user_id' => Auth::id(),
                'id' => $project->id,
                'payload' => $request->safe()->all(),
                'error' => $e->getMessage(),
            ]);

            return $this->sendError('Update failed', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified project from the database.
     *
     * @param Project $project
     * @return JsonResponse
     */

    public function destroy(Project $project): JsonResponse
    {
        try {
            $project->delete();

            return $this->sendResponse(null, 'Project deleted successfully');
        } catch (Exception $e) {
            return $this->sendError('Delete failed', [$e->getMessage()]);
        }
    }

    /**
     * Handle DataTables server-side processing for projects.
     *
     * @param Request $request
     * @param ProjectTableService $service
     * @return JsonResponse
     */

    public function tableData(Request $request, ProjectTableService $service): JsonResponse
    {
        $data = $service->getTableData($request->all());

        return response()->json($data);
    }
}