<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Technology;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        $types = Type::all();
        return view('admin.projects.index', compact('projects', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.create', compact('types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $form_data = $request->validated();
        $form_data['slug'] = Project::generateSlug($form_data['title']);


        //se c'è il file nel request si creerà una cartella nella quale andrà l'immagine in request, che verrà rinominata
        if ($request->hasFile('cover_image')) {

            $path = Storage::put('project_images', $request->cover_image);
            //salviamo poi il file ottenuto in form_data
            $form_data['cover_image'] = $path;
            // dd($form_data);
        }


        // $project = new Project();
        // $project->fill($form_data);
        // $project->save();
        // Alternativa a fill() ---->
        $project = Project::create($form_data);

        // prima salviamo $project ↑ (altrimenti non avremmo cosa relazionare) e poi, se ci sono technologies, le mettiamo nella tabella ponte con attach()
        if ($request->has('technologies')) {
            $project->technologies()->attach($request->technologies);
        }

        return redirect()->route('admin.projects.index')->with('message', 'Il tuo nuovo progetto è stato creato');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.edit', compact('project', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $form_data = $request->validated();
        $form_data['slug'] = Project::generateSlug($form_data['title']);

        if ($request->hasFile('cover_image')) {
            //se esiste un file precedente eliminarlo 
            if ($project->cover_image) {
                Storage::delete($project->cover_image);
            }
            $path = Storage::put('project_images', $request->cover_image);
            $form_data['cover_image'] = $path;
        }

        $project->update($form_data);

        //se ci sono delle technologie nel progetto d modificare, 
        if ($request->has('technologies')) {
            // verranno sincronizzate a ciò che viene inviato all'update 
            $project->technologies()->sync($request->technologies);
        } else {
            //altrimenti se non c'era già nessuna tecnologia nel file da modificare, verrà mandato un array vuoto 
            $project->technologies()->sync([]);
        }

        // i doppi apici per il template literal
        return redirect()->route('admin.projects.index')->with('message', "Hai aggiornato con successo $project->title");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->technologies()->detach();

        $project->delete();
        return redirect()->route('admin.projects.index')->with('message', "$project->title è stato cancellato");
    }
}
