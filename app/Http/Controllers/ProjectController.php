<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormEditRequest;
use App\Http\Requests\ProjectFormRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc' )->paginate(10)->withQueryString()->onEachSide(1);

        $projects->transform(function ($project) {
            $limit = 20;
            $project->limited_description = Str::limit($project->description, $limit);
            $project->limited_observation = Str::limit($project->observation, $limit);
            $project->limited_client_name = Str::limit($project->client_name, $limit);
            $project->limited_title = Str::limit($project->title, $limit);
            return $project;
        });

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

        $updatePdf = false;
        $updatePhotos = false;

        // Verifique se um novo arquivo PDF foi enviado
        if ($request->hasFile('pdf_file')) {
            $pdf = $request->file('pdf_file');
            $pdfName = time() . '_' . $pdf->getClientOriginalName();
            $pdf->storeAs('pdfs', $pdfName);
            $data['pdf_file'] = $pdfName;

            // Exclua o arquivo PDF antigo, se existir
            if (!empty($project->pdf_file)) {
                Storage::delete('pdfs/' . $project->pdf_file);
            }

            $updatePdf = true;
        }

        // Verifique se novas imagens foram enviadas
        if ($request->hasFile('photo_file')) {
            $imageNames = [];
            foreach ($request->file('photo_file') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $imageNames[] = $imageName;
            }
            $data['photo_file'] = json_encode($imageNames);

            // Exclua as imagens antigas, se existirem
            if (!empty($project->photo_file)) {
                $oldPhotoFiles = json_decode($project->photo_file);
                foreach ($oldPhotoFiles as $oldPhotoFile) {
                    $oldPhotoPath = public_path('images/' . $oldPhotoFile);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
            }

            $updatePhotos = true;
        }

        // Atualize o projeto apenas se houver atualização nos campos correspondentes
        if ($updatePdf || $updatePhotos ) {
            $project->update($data);
        }

        $project->update([
            'client_name' => $data['client_name'],
            'title' => $data['title'],
            'description' => $data['description'],
            'observation' => $data['observation'],
            'project_link' => $data['project_link'],
        ]);

        $limit = 20;
        $project->limited_description = Str::limit($project->description, $limit);
        $project->limited_observation = Str::limit($project->observation, $limit);
        $project->limited_client_name = Str::limit($project->client_name, $limit);
        $project->limited_title = Str::limit($project->title, $limit);

        return redirect()->route('project.index')->with('success', 'Projeto atualizado com sucesso');
    }


    public function destroy($project_id)
    {
        $project = Project::findOrFail($project_id);

        if (!empty($project->pdf_file)) {
            Storage::delete('pdfs/' . $project->pdf_file);
        }

        if (!empty($project->photo_file)) {
            $photoFiles = json_decode($project->photo_file);
            foreach ($photoFiles as $photoFile) {
                $photoPath = public_path('images/' . $photoFile);
                if (file_exists($photoPath)) {
                    unlink($photoPath); // Excluir o arquivo de imagem
                } else {
                    // Adicione uma mensagem de depuração para verificar o caminho
                    dd("Arquivo não encontrado: " . $photoPath);
                }
            }
        }

        $project->delete();

        return redirect()->route('project.index')->with('delete_success', 'Projeto deletado com sucesso');
    }

    public function downloadProjectFiles($project_id)
    {
        $project = Project::findOrFail($project_id);

        $zip = new ZipArchive();
        $zipFileName = 'project_files.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Adicione o arquivo PDF ao ZIP
            if (!empty($project->pdf_file)) {
                $pdfFilePath = storage_path('app/pdfs/' . $project->pdf_file);
                if (file_exists($pdfFilePath)) {
                    $zip->addFile($pdfFilePath, 'pdf/' . $project->pdf_file);
                }
            }

            // Adicione as imagens ao ZIP
            if (!empty($project->photo_file)) {
                $photoFiles = json_decode($project->photo_file);
                foreach ($photoFiles as $photoFile) {
                    $photoFilePath = public_path('images/' . $photoFile);
                    if (file_exists($photoFilePath)) {
                        $zip->addFile($photoFilePath, 'images/' . $photoFile);
                    }
                }
            }

            $zip->close();

            // Envie o arquivo ZIP para download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return redirect()->route('project.index')->with('error', 'Erro ao criar o arquivo ZIP.');
        }
    }
}
