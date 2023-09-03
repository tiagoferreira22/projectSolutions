<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormEditRequest;
use App\Http\Requests\ProjectFormRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc')->paginate(10)->withQueryString()->onEachSide(1);

        return view('project.index', compact('projects'));
    }

    public function info($project_id)
    {
        $project = Project::find($project_id);

        if (!$project) {
            return abort(404);
        }

        return view('project.info', compact('project'));
    }

    public function create()
    {
        return view('project.create');
    }

    public function store(ProjectFormRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('pdf_file')) {
            $pdf = $request->file('pdf_file');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->storeAs('pdfs', $pdfName);
            $data['pdf_file'] = $pdfName;
        }

        if ($request->hasFile('photo_file')) {
            $imageNames = [];
            foreach ($request->file('photo_file') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $imageNames[] = $imageName;
            }
            $data['photo_file'] = json_encode($imageNames);
        }

        Project::create($data);

        return redirect()->route('project.index')->with('success', 'Projeto criado com sucesso');
    }

    public function downloadPDF($filename)
    {
        $filePath = storage_path('app/pdfs/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath, $filename);
        } else {
            return "O arquivo não foi encontrado em: " . $filePath;
        }
    }

    public function edit($project_id)
    {
        $project = Project::findOrFail($project_id);
        return view('project.edit', compact('project'));
    }

    public function update(ProjectFormEditRequest $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $data = $request->validated();

        if ($request->hasFile('pdf_file')) {
            $pdf = $request->file('pdf_file');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->storeAs('pdfs', $pdfName);
            $data['pdf_file'] = $pdfName;
        }

        if ($request->hasFile('photo_file')) {
            $imageNames = [];
            foreach ($request->file('photo_file') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $imageNames[] = $imageName;
            }
            $data['photo_file'] = json_encode($imageNames);
        }

        $project->update($data);

        return redirect()->route('project.index')->with('success', 'Projeto atualizado com sucesso');
    }

    public function destroy($project_id)
    {
        $project = Project::findOrFail($project_id);

        // Exclua o arquivo PDF associado, se existir
        if (!empty($project->pdf_file)) {
            Storage::delete('pdfs/' . $project->pdf_file);
        }

        // Exclua os arquivos de fotos associados, se existirem
        if (!empty($project->photo_file)) {
            $photoFiles = json_decode($project->photo_file);
            foreach ($photoFiles as $photoFile) {
                Storage::delete('images/' . $photoFile);
            }
        }


        $project->delete();

        return redirect()->route('project.index')->with('delete_success', 'Projeto deletado com sucesso');
    }
}
