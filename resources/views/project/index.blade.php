<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listagem de projetos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-success-status class="mb-4 alert alert-success" :status="session('success')" />
                    <x-success-status class="mb-4 alert alert-warning" :status="session('delete_success')" />

                    <div class="pagination">
                        @if ($projects->total() > 0)
                            Mostrando {{ $projects->firstItem() }} a {{ $projects->lastItem() }} de {{ $projects->total() }} resultados
                        @else
                            Não há resultados
                        @endif
                    </div>

                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="border-b font-medium dark:border-neutral-500">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Nome do cliente</th>
                                <th scope="col" class="px-6 py-4">Titulo</th>
                                <th scope="col" class="px-6 py-4">Descrição</th>
                                <th scope="col" class="px-6 py-4">Observação</th>
                                <th scope="col" class="px-6 py-4">Link do Projeto</th>
                                <th scope="col" class="px-6 py-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $index => $project)
                                <tr class="border-b dark:border-neutral-500">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $index + 1 }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $project->client_name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $project->title }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $project->description }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $project->observation }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $project->project_link }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        <a href="{{ route('project.info', $project->id) }}" class="btn btn-info"><img src="{{ asset('img/svg-icon/eye-fill.svg') }}" alt="eye icon"></a>
                                        <a href="{{ route('project.edit', $project->id) }}" class="btn btn-primary"><img src="{{ asset('img/svg-icon/pencil-square.svg') }}" alt="pencil icon"></a>
                                        <form action="{{ route('project.delete', $project->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">
                                                <img src="{{ asset('img/svg-icon/trash-fill.svg') }}" alt="trash icon">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="1" class="mt-3">
                                        <span>Não a dados</span>
                                        <a class="btn btn-primary" href="{{ route('project.create') }}">Criar dado</a>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            @endforelse

                        </tbody>
                        <tfoot>
                            {{ $projects->links() }}
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
