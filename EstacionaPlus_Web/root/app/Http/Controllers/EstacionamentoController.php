<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiClient;

class EstacionamentoController extends Controller
{
    public function show()
    {
        return view('adminLogin');
    }
    
    public function estacionamentos_form($id = false)
    {
        $apiClient = new ApiClient();
        
        if ($id) {
            $response = $apiClient->readOne($id);
            $dados = $response['Item'];
            return view('estacionamentos_form', compact('dados'));
        } else {
            return view('estacionamentos_form');
        }
    }
    
    public function inserir(Request $request)
    {
        $apiClient = new ApiClient();
        
        // Obtenha os dados do formulário
        $dados = $request->all();

        // Crie uma matriz para armazenar as informações das vagas
        $vagas = [];

        // Percorra os dados do formulário para extrair as informações das vagas
        for ($i = 0; $i < 12; $i++) {
            for ($j = 0; $j < 24; $j++) {
                $index = "$i,$j";
                $tipoVaga = $dados["tipoVaga$index"];
                $status = $this->gerarStatusVaga();
                $vaga = [
                    'Posição' => $index,
                    'Tipo' => $tipoVaga,
                    'Status' => $status
                ];
                $vagas[$index] = $vaga;
            }
        }
        
        $newItem = [
            'nome' => $dados['nome'],
            'endereco' => $dados['endereco'],
            'totalVagas' => $dados['totalVagas'],
            'vagas' => $vagas
            ];
        
        $apiClient->create($newItem);

        return redirect()->route('estacionamentos.listar');
    }
    
    private function gerarStatusVaga()
    {
        return rand(0, 1);
    }
    
    public function listar()
    {
        $apiClient = new ApiClient();
        
        $estacionamentos = $apiClient->readAll();
        
        return view('exibir_estacionamentos', compact('estacionamentos'));
    }
    
    public function listar_um($id)
    {
        $apiClient = new ApiClient();
        
        $response = $apiClient->readOne($id);
        
        $dados = $response['Item'];
        
        return view('detalhes_estacionamentos', compact('dados'));
    }
    
    public function alterar(Request $request, $id)
    {
        $apiClient = new ApiClient();
        
        $response = $apiClient->readOne($id);
        
        $dados = $response['Item'];
        
        $dados['nome'] = $request['nome'];
        $dados['totalVagas'] = $request['totalVagas'];
        $dados['endereco'] = $request['endereco'];

        $vagas = [];

        // Percorra os dados do formulário para extrair as informações das vagas
        for ($i = 0; $i < 12; $i++) {
            for ($j = 0; $j < 24; $j++) {
                $index = "$i,$j";
                $tipoVaga = $request->input("vagas.$index.Tipo");
                $vaga = [
                    'Posição' => $index,
                    'Tipo' => $tipoVaga,
                    'Status' => $this->gerarStatusVaga()
                ];
                $vagas[$index] = $vaga;
            }
        }

        // Adicione as informações das vagas aos dados do estacionamento
        $dados["vagas"] = $vagas;
        
        $apiClient->update($id, $dados);
    
        return redirect()->route('estacionamentos.listar');
    }

    public function excluir($id)
    {
        $apiClient = new ApiClient();
        
        $apiClient->delete($id);
        
        return redirect()->route('estacionamentos.listar');
    }
}
