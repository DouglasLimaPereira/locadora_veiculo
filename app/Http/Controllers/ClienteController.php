<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    protected $cliente;
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        // if ($request->has('atributos_modelo')) {
        //     $clienteRepository->selectAtributosRegistros('modelo:'.$request['atributos_modelo']);
        // }else{
        //     $clienteRepository->selectAtributosRegistros('modelo');
        // }

        if ($request->has('filtro')) {
            $clienteRepository->filtro($request['filtro']);
        }

        if ($request->has('atributos')) {
            $clienteRepository->selectAtributos($request['atributos']);
        }

        return response()->json($clienteRepository->getResultado());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest $request)
    {
        $cliente = $this->cliente->create($request->all());

        return response()->json($cliente);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(['error' => 'carro pesquisado não encontrada!!!'], 404);
        }
        return response()->json($cliente); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClienteRequest $request, $id)
    {
        $cliente = $this->cliente->find($id);
        
        if ($cliente === null) {
            return response()->json(['error' => 'Não foi possível realizar a atualização de carro, carro solicitada não encontrada!!!'], 404);
        }

        $cliente->update($request->all());

        return response()->json($cliente); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(['error' => 'Não foi possível realizar a exclusão de cliente, cliente pesquisado não encontrada!!!'], 404);
        }
        $cliente->delete();

        return response()->json('Cliente removido com sucesso!!!');
    }
}
