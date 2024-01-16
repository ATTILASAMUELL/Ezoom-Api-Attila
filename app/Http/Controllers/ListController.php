<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ListStoreRequest;
use App\Http\Requests\ListUpdateRequest;
use App\Http\Resources\ListCollection;
use App\Http\Resources\ListResource;
use App\Service\ListService;
use App\Traits\ReturnDefault;

class ListController extends Controller
{
    use ReturnDefault;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user(); // Obter o usuário autenticado

            $listService = $this->listService();

            $id = null;

            // Verificar se o query parameter 'id' está presente na solicitação
            if ($request->has('id')) {
                $id = $request->input('id');
                // Faça algo com o valor de 'id'
            }
            
            // Recuperar todas as listas associadas ao usuário autenticado
            $lists = $listService->findAllDefaultOrOrder(
                $user->id,
                $request->has("orderBy"),
                $request->input("orderBy", "created_at"),
                $request->input("orderDir", "asc"),
                $id
            );

            return new ListCollection($lists);
        } catch (\Exception $e) {
            return response()->json($this->error("Try again later!!!"), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ListStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

	        $user = auth()->user(); // Obter o usuário autenticado

    
            // Se o campo 'check' for nulo ou não foi fornecido, definir como 'pendente'
            if (!isset($validatedData['check'])) {
                $validatedData['check'] = 'pendente';
            }

	        $validatedData['user_id'] = $user->id;
    
            // Continuar com a lógica de armazenamento
            $listService = $this->listService();
            $store = $listService->store($validatedData);
    
            if ($store->id) {
                return new ListResource($store);
            }
    
            return response()->json($this->error("Try again later!!!"), 404);
        } catch (\Exception $e) {
            return response()->json($this->error("Try again later!!!"), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = auth()->user(); // Obter o usuário autenticado

            $listService = $this->listService();
            $store = $listService->show($id);

            // Verificar se a lista pertence ao usuário autenticado
            if ($store->user_id === $user->id) {
                return new ListResource($store);
            }

            return response()->json($this->error("List not found or unauthorized"), 404);
        } catch (\Exception $e) {
            return response()->json($this->error("Try again later!!!"), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ListUpdateRequest $request, string $id)
    {
        try {
            $user = auth()->user(); // Obter o usuário autenticado

            $listService = $this->listService();
            
            // Verificar se a lista existe antes de tentar atualizar
            if (!$listService->exists($id)) {
                return response()->json($this->error("List with ID $id does not exist!!!"), 404);
            }

            $list = $listService->show($id);

            // Verificar se a lista pertence ao usuário autenticado
            if ($list->user_id !== $user->id) {
                return response()->json($this->error("Unauthorized to update this list"), 403);
            }

            // Atualizar a lista
            $update = $listService->update($request->all(), $id);

            if ($update->id) {
                return new ListResource($update);
            }

            return response()->json($this->error("Try again later!!!"), 404);
        } catch (\Exception $e) {
            return response()->json($this->error("Try again later!!!"), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = auth()->user(); // Obter o usuário autenticado

            $listService = $this->listService();

            // Verificar se a lista existe antes de tentar excluir
            if (!$listService->exists($id)) {
                return response()->json($this->error("List with ID $id does not exist!!!"), 404);
            }

            $list = $listService->show($id);

            // Verificar se a lista pertence ao usuário autenticado
            if ($list->user_id !== $user->id) {
                return response()->json($this->error("Unauthorized to delete this list"), 403);
            }

            // Excluir a lista
            $destroy = $listService->destroy($id);

            if ($destroy) {
                return response()->json($this->success("Successfully deleted!!!"), 200);
            }

            return response()->json($this->error("Try again later!!!"), 404);
        } catch (\Exception $e) {
            return response()->json($this->error("Try again later!!!"), 500);
        }
    }

    private function listService()
    {
        return new ListService;
    }
}
