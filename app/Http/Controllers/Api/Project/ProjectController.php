<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Requests\Project\Project\StoreProjectRequest;
use App\Http\Requests\Project\Project\UpdateProjectRequest;
use App\Http\Resources\Project\Project\ProjectResource;
use App\Model\Auth\Role;
use App\Model\Master\User;
use Illuminate\Http\Request;
use App\Model\Project\Project;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\Project\ProjectCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\Project\Project\ProjectCollection
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit') ?? 0;

        return new ProjectCollection(Project::where('owner_id', auth()->user()->id)->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \App\Http\Resources\Project\Project\ProjectResource
     */
    public function store(StoreProjectRequest $request)
    {
        Artisan::call('tenant:setup-database', [
            'tenant_subdomain' => 'point_' . strtolower($request->get('code'))
        ]);

        DB::connection('tenant')->beginTransaction();

        $project = new Project;
        $project->owner_id = auth()->user()->id;
        $project->code = $request->get('code');
        $project->name = $request->get('name');
        $project->address = $request->get('address');
        $project->phone = $request->get('phone');
        $project->save();

        $user = new User;
        $user->id = auth()->user()->id;
        $user->name = auth()->user()->name;
        $user->email = auth()->user()->email;
        $user->password = auth()->user()->password;
        $user->save();

        $role = Role::findByName('super admin', 'api');

        $user->assignRole($role);

        DB::connection('tenant')->commit();

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \App\Http\Resources\Project\Project\ProjectResource
     */
    public function show($id)
    {
        return new ProjectResource(Project::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Project\Project\UpdateProjectRequest $request
     * @param  int                                                    $id
     *
     * @return \App\Http\Resources\Project\Project\ProjectResource
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        DB::connection('tenant')->beginTransaction();

        $project = Project::findOrFail($id);
        $project->name = $request->get('name');
        $project->address = $request->get('address');
        $project->phone = $request->get('phone');
        $project->save();

        DB::connection('tenant')->commit();

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \App\Http\Resources\Project\Project\ProjectResource
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        $project->delete();

        return new ProjectResource($project);
    }
}