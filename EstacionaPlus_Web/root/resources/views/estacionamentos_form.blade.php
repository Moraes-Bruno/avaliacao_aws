@extends('admin')

@section('js')
<script>
    let tipoSelecionado = 'normal';
    
    let vagasSelecionadas = 0;

    $(document).ready(function() {
    
        let totalVagasInput = $('#totalVagas');
        if (totalVagasInput.val() === "") {
            totalVagasInput.val(vagasSelecionadas);  // Inicializa com 0 ou com um valor específico caso necessário
        } else {
            vagasSelecionadas = parseInt(totalVagasInput.val(), 10);  // Preenche com o valor inicial se já houver dados
        }
    
        $('.form-check-input').click(function () {
            tipoSelecionado = this.value;
        });

        $('input[type="checkbox"]').click(function() {
            var checkbox = $(this);
            var isChecked = checkbox.is(":checked");
            var td = checkbox.closest('td');

            if (isChecked) {
                changeVagaType(checkbox, td);
                if (tipoSelecionado != "objeto") vagasSelecionadas++;
            } else {
                clearVagaType(checkbox, td);
                 if (tipoSelecionado != "objeto") vagasSelecionadas--;
            }
            
            totalVagasInput.val(vagasSelecionadas);
        });
        
    });
    
    function changeVagaType(checkbox, td) {
        var vagaTypes = ["normal", "deficiente", "idoso", "autista", "objeto", "vazio"];

        if (tipoSelecionado === "vazio") {
            clearVagaType(checkbox, td);
        } else {
            td.removeClass(vagaTypes.join(' ')).addClass(tipoSelecionado);

            checkbox.closest('td').find('input[type="hidden"]').val(tipoSelecionado);
        }
    }

    function clearVagaType(checkbox, td) {
        var vagaTypes = ["normal", "deficiente", "idoso", "autista", "objeto", "vazio"];

        checkbox.val("");
        td.removeClass(vagaTypes.join(' '));
        td.find('input[type="hidden"]').val("vazio");
    }
</script>
@endsection
@section('css')
<style>
    .normal {
        background-color: #388659;
        color: white;
    }

    .deficiente {
        background-color: #FDE74C;
        color: white;
    }

    .idoso {
        background-color: #81ADC8;
        color: white;
    }

    .autista {
        background-color: #BA3B46;
        color: white;
    }

    .objeto {
        background-color: #252422;
        color: white;
    }

    .vazio {
        background-color: white;
    }
    
    .box {
        width: 30px;
        height: 30px;
        display: inline-block; 
        margin-right: 10px; 
        border-radius: 4px; 
    }
</style>
@endsection
@section('content-header')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid mb-2">
        <div class="float-right">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/admin/estacionamentos">Estacionamentos</a></li>
                <li class="breadcrumb-item active">Formulário</li>
            </ol>
        </div>
    </div><!-- /.container-fluid -->
</section>
@endsection
@section('content')
<section class="content m-1">
    @if(isset($dados))
    <form action="{{ route('estacionamento.alterar', ['id' => $dados['_id']]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Editar Estacionamento</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="form-group col-5">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" class="form-control" value="{{ $dados['nome'] }}">
                    </div>
                    <div class="form-group col-5">
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" class="form-control" value="{{ $dados['endereco'] }}">
                    </div>
                    <div class="form-group col-2">
                        <p>Total de Vagas:</label>
                        <input type="number" id="totalVagas" name="totalVagas" class="form-control" value="{{ $dados['totalVagas'] }}" readonly>
                    </div>
                </div>
                <b>
                    <p>Modifique abaixo o layout do seu estacionamento:</p>
                </b>
                <div class="card" style="margin: 1% 30%; padding:2%;">
                    <b><p class="card-title">Selecione o tipo de vaga que deseja adicionar no layout:</p></b>
                    <div class="form-check d-flex align-items-center">
                        <div class='box normal'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="normal" value="normal" checked>
                        <label class="form-check-label ms-2" for="normal">Vaga Normal</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box deficiente'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="deficiente" value="deficiente">
                        <label class="form-check-label ms-2" for="deficiente">Vaga para Deficientes</label>
                    </div>
                
                    <!-- Repita o mesmo para as outras opções -->
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box idoso'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="idoso" value="idoso">
                        <label class="form-check-label ms-2" for="idoso">Vaga para Idosos</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box autista'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="autista" value="autista">
                        <label class="form-check-label ms-2" for="autista">Vaga para Autistas</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box objeto'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="objeto" value="objeto">
                        <label class="form-check-label ms-2" for="objeto">Objeto Qualquer</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box vazio'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="vazio" value="vazio">
                        <label class="form-check-label ms-2" for="vazio">Espaço Vazio</label>
                    </div>
                </div>
                <?php
                // Inicializa a matriz vazia
                $matrix = array_fill(0, 12, array_fill(0, 24, null));

                // Preenche a matriz com os elementos da array de vagas
                foreach ($dados['vagas'] as $key => $vaga) {
                    $posicao = explode(",", $key);
                    $i = $posicao[0];
                    $j = $posicao[1];
                    $matrix[$i][$j] = $vaga;
                }
                ?>
                <table class="table table-bordered">
                    @for ($i = 0; $i < 12; $i++) 
                        <tr>
                            @for ($j = 0; $j < 24; $j++) 
                                <?php $index = "$i,$j"; ?>
                                <td class="{{ isset($matrix[$i][$j]) && is_array($matrix[$i][$j]) && $matrix[$i][$j]['Tipo'] !== null ? strtolower($matrix[$i][$j]['Tipo']) : 'vazio' }}">
                                    <div style="display: flex; justify-content: center;">
                                        <label for="vagas[]"></label>
                                        <input style="height:20px; width:20px;margin:25%" type="checkbox" id="vaga{{ $index }}" name="vagas[]" value="{{ $index }}" 
                                            @if(isset($matrix[$i][$j]) && is_array($matrix[$i][$j]) && $matrix[$i][$j]['Tipo'] != "vazio" && $matrix[$i][$j]['Tipo'] != null) checked @endif>
                                    </div>
                                    @if(isset($matrix[$i][$j]) && is_array($matrix[$i][$j]))
                                        <input type="hidden" name="vagas[{{ $index }}][Posição]" id="posicao" value="{{ $index }}">
                                        <input type="hidden" name="vagas[{{ $index }}][Tipo]" id="tipo" value="{{ $matrix[$i][$j]['Tipo'] }}">
                                        <input type="hidden" name="vagas[{{ $index }}][Status]" id="status" value="{{ $matrix[$i][$j]['Status'] }}">
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <!-- /.card -->
        </div>
        <div class="row">
            <div class="col-12">
                <a href="../estacionamentos" class="btn btn-secondary">Cancelar</a>
                <input type="submit" value="Salvar" class="btn btn-warning float-right">
            </div>
        </div>
    </form>
    @else
    <form action="{{ route('estacionamento.inserir') }}" method="post">
        @csrf
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Cadastrar Estacionamento</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="form-group col-5">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" class="form-control">
                    </div>
                    <div class="form-group col-5">
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" class="form-control">
                    </div>
                    <div class="form-group col-2">
                        <label for="totalVagas">Total de Vagas:</label>
                        <input type="number" id="totalVagas" name="totalVagas" class="form-control" readonly>
                    </div>
                </div>
                <b>
                    <p>Desenhe o layout do seu estacionamento:</p>
                </b>
                <div class="card" style="margin: 1% 30%; padding:2%;">
                    <b><p class="card-title">Selecione o tipo de vaga que deseja adicionar no layout:</p></b>
                    <div class="form-check d-flex align-items-center">
                        <div class='box normal'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="normal" value="normal" checked>
                        <label class="form-check-label ms-2" for="normal">Vaga Normal</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box deficiente'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="deficiente" value="deficiente">
                        <label class="form-check-label ms-2" for="deficiente">Vaga para Deficientes</label>
                    </div>
                
                    <!-- Repita o mesmo para as outras opções -->
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box idoso'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="idoso" value="idoso">
                        <label class="form-check-label ms-2" for="idoso">Vaga para Idosos</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box autista'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="autista" value="autista">
                        <label class="form-check-label ms-2" for="autista">Vaga para Autistas</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box objeto'></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="objeto" value="objeto">
                        <label class="form-check-label ms-2" for="objeto">Objeto Qualquer</label>
                    </div>
                    
                    <div class="form-check d-flex align-items-center mt-2">
                        <div class='box vazio' style="border: 1px solid black;"></div>
                        <input class="form-check-input" type="radio" name="tipoVaga" id="vazio" value="vazio">
                        <label class="form-check-label ms-2" for="vazio">Espaço Vazio</label>
                    </div>
                </div>
                <table class="table table-bordered">
                    @for ($i = 0; $i < 12; $i++) <tr>
                        @for ($j = 0; $j < 24; $j++) <?php $index = "$i,$j"; ?> <td>
                            <div>
                                <input style="height:20px; width:20px;margin:25%" type="checkbox" id="vaga{{ $index }}" name="vagas[]" value="{{ $index }}">
                            </div>
                            <input type="hidden" name="tipoVaga{{ $index }}" id="tipoVaga{{ $index }}" value="vazio">
                            </td>
                            @endfor
                            </tr>
                            @endfor
                </table>
            </div>
            <!-- /.card-body -->
            <!-- /.card -->
        </div>
        <div class="row">
            <div class="col-12">
                <a href="../estacionamentos" class="btn btn-secondary">Cancelar</a>
                <input type="submit" value="Salvar" class="btn btn-success float-right">
            </div>
        </div>
    </form>
    @endif
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtI-_4umSKFC-kkL4yNoUTRfBI-Qo0NDM&callback=initMap&v=weekly" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</section>
@endsection