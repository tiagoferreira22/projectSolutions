<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do projeto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h1 class="card-title">{{ $project->title }}</h1>
                                    <h6 class="card-subtitle mb-2">{{ $project->created_at->format('d/m/Y H:i:s') }}</h6>
                                </div>
                                @if ($project->pdf_file)
                                    <div>
                                        <a href="{{ route('download.pdf', ['filename' => $project->pdf_file]) }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-down-fill" viewBox="0 0 16 16">
                                                <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 6.854-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><strong>Nome do Cliente:</strong>{{ $project->client_name }}</h5>
                                @if ($project->observation)
                                    <p class="card-text"><strong>Observação:</strong> {{ $project->observation }}</p>
                                @endif

                                <p class="card-text"><strong>Descrição:</strong> {{ $project->description }}</p>

                                @if ($project->project_link)
                                    <p class="card-text"><strong>Link do Projeto:</strong> <a href="{{ $project->project_link }}" target="_blank">{{ $project->project_link }}</a></p>
                                @endif

                                <div class="photo_project">
                                    <p class="photo_project_title">Fotos do projeto:</p>
                                    @if (!empty($project->photo_file))
                                        @foreach (json_decode($project->photo_file) as $photoName)
                                        <span class="photo_project_item_info">
                                            <img src="{{ asset('images/' . $photoName) }}" alt="Foto do Projeto">
                                        </span>
                                        @endforeach
                                    @else
                                        <span class="photo_project_item">Nenhuma foto disponível para este projeto.</span>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
