<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Projeto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-success-status class="mb-4" :status="session('success')" />

                    <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-3">
                            <x-input-label for="client_name" :value="__('Nome do cliente')" />
                            <x-text-input id="client_name" class="block mt-1 w-full" type="text" name="client_name" :value="old('client_name')" autofocus/>
                            <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-input-label for="title" :value="__('Titulo do Projeto')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-input-label for="description" :value="__('Descrição')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-input-label for="observation" :value="__('Observação')" />
                            <x-text-input id="observation" class="block mt-1 w-full" type="text" name="observation" :value="old('observation')"/>
                            <x-input-error :messages="$errors->get('observation')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-input-label for="project_link" :value="__('Link do projeto')" />
                            <x-text-input id="project_link" class="block mt-1 w-full" type="text" name="project_link" :value="old('project_link')"/>
                            <x-input-error :messages="$errors->get('project_link')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="pdf_file" :value="__('Arquivo em PDF')" />
                            <input class="form-control" id="pdf_file" type="file" name="pdf_file" accept=".pdf" />
                            <x-input-error :messages="$errors->get('pdf_file')" class="mt-2" />
                        </div>

                        <!-- Galeria de fotos do projeto -->
                        <div class="mt-4">
                            <x-input-label for="photos" :value="__('Galeria de fotos do projeto')" />
                            <input class="form-control" id="photos" type="file" name="photo_file[]" accept="image/*" multiple />
                            <x-input-error :messages="$errors->get('photo_file')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-primary-button class="mt-3">{{ __('Cadastrar projeto') }}</x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
