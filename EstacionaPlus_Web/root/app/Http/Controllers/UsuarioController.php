<?php

namespace App\Http\Controllers;

use App\Models\Estacionamento;
use App\Models\Usuário;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{

    public function showLogin()
    {
        return view('login');
    }

    public function usuarios_form($_id = false)
    {
        $estacionamentos = Estacionamento::all();
        if ($_id) {
            $dados = Usuário::findOrFail($_id);
            return view('usuarios_form', compact('dados'), compact('estacionamentos'));
        } else {
            return view('usuarios_form', compact('estacionamentos'));
        }
    }

    //metodo usado pelo adminin para cadastar
    public function inserir(Request $request)
    {

        $dados = new Usuário($request->all());
        $dados->save();
        return redirect()->route('usuarios.listar');
    }

    public function listar()
    {
        $usuarios = Usuário::all();
        return view('exibir_usuarios', compact('usuarios'));
    }
    public function listar_um($id)
    {
        $dados = Usuário::findOrFail($id);
        return view('detalhes_usuario', compact('dados'));
    }
    public function alterar(Request $request, $id) //Função do painel do admin
    {
        $dados = Usuário::findOrFail($id);
        $dados->nome = $request->nome;
        $dados->email = $request->email;
        $dados->senha = $request->senha;
        $dados->favoritos = $request->favoritos;
        $dados->save();
        return redirect()->route('usuarios.listar');
    }


    public function excluir($id)
    {
        $dados = Usuário::destroy($id);
        return redirect()->route('usuarios.listar');
    }


    public function favoritar(Request $request)
    {
        $nome = $request->input('nome');

        $user_id = session('user_id');

        $usuario = Usuário::where('email', $user_id)->first();

        $favoritos = $usuario->favoritos ?? [];

        // Verifica se o nome já está presente no array de favoritos
        if (in_array($nome, $favoritos)) {
            return response()->json(['message' => 'Estacionamento já favoritado']);
        }

        $favoritos[] = $nome;

        // Atualiza o documento do usuário no MongoDB
        Usuário::where('email', $user_id)
            ->update(['favoritos' => $favoritos]);

        return response()->json(['message' => 'Estacionamento favoritado com sucesso']);
    }

    public function removerFavorito(Request $request)
    {
        $nome = $request->input('nome');
        $user_id = session('user_id');

        $usuario = Usuário::where('email', $user_id)->first();
        $favoritos = $usuario->favoritos ?? [];

        // Constrói um novo array de favoritos, excluindo o elemento desejado
        $novosFavoritos = [];
        foreach ($favoritos as $favorito) {
            if ($favorito !== $nome) {
                $novosFavoritos[] = $favorito;
            }
        }

        // Atualiza o documento do usuário no MongoDB
        Usuário::where('email', $user_id)
            ->update(['favoritos' => $novosFavoritos]);

        return response()->json(['message' => 'Estacionamento removido dos favoritos']);
    }
}