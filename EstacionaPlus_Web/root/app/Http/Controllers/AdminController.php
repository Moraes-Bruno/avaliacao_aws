<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function show()
    {
        if (session('admin_authenticated')) {
            return view('estacionamentos.listar');
        } else {
            return redirect()->route('adminLogin');
        }
    }

    public function adminLogin(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'senha' => 'required'
    ], [
        'email.required' => 'O campo de email é obrigatório',
        'email.email' => 'Este campo deve possuir um email válido',
        'senha.required' => 'O campo senha é obrigatório'
    ]);

    $email = $request->input('email');
    $senha = $request->input('senha');

    $apiClient = new ApiClient();
    
    $loginValido = $apiClient->validLogin($email, $senha);

    if ($loginValido == true) {

        $request->session()->put('admin_authenticated', true);
        
        return redirect()->route('estacionamentos.listar');
    } else {
        return redirect()->back()->with('login_failed', true);
    }
 }

 public function logout(){
    Auth::logout();
    return redirect()->route('adminLogin');
}

}
