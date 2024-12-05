import 'dart:async';
import 'package:flutter/material.dart';
import 'package:estaciona_mais/widgets/card_widget.dart';
import '../service/dynamo_service.dart';
import 'package:flutter_svg/flutter_svg.dart';

class MyHomePage extends StatefulWidget {
  MyHomePage({super.key});

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  List<Map<String, dynamic>> estacionamentos =
      []; // Lista para armazenar os estacionamentos
  Timer? _timer;

  @override
  void initState() {
    super.initState();
    consultarApi(); // Chama a API ao inicializar a página

    // Atualiza a cada 5 segundos
    _timer = Timer.periodic(Duration(seconds: 5), (Timer t) => consultarApi());
  }

  Future<void> consultarApi() async {
    try {
      // Chama o serviço da API
      final response = await DynamoService().consultarEstacionamentos();

      // Aqui vamos imprimir a resposta da API para debug
      print("Resposta da API: $response");

      // Atualiza os dados no estado
      setState(() {
        estacionamentos =
            List<Map<String, dynamic>>.from(response); // Atualiza os dados
      });
    } catch (e) {
      // Em caso de erro, exibe uma mensagem
      print("Erro ao carregar dados: $e");
    }
  }

  // Função para criar a lista de vagas com base na resposta JSON
  List<Map<String, dynamic>> _criarListaVagas(Map<String, dynamic> vagas) {
    List<Map<String, dynamic>> vagasList = [];

    // Utiliza um conjunto (Set) para garantir que as posições não sejam repetidas
    Set<String> vagasSet = Set();

    // Itera sobre as chaves de vagas (exemplo "7,5")
    vagas.forEach((key, value) {
      // Extrai as coordenadas (linha, coluna) da chave "7,5"
      List<String> parts =
          key.split(','); // Divide a chave para pegar linha e coluna
      int row = int.parse(parts[0]); // Parte antes da vírgula (linha)
      int col = int.parse(parts[1]); // Parte depois da vírgula (coluna)

      String positionKey =
          "$row,$col"; // Cria uma chave única para cada posição

      // Verificando se a vaga contém um valor válido e não vazio
      if (value != null &&
          value != "" &&
          value != 0 &&
          !(value is Map && value.isEmpty) &&
          !(value is List && value.isEmpty)) {
        if (!vagasSet.contains(positionKey)) {
          vagasSet.add(positionKey);

          // Aqui estamos verificando se o valor contém o que você pediu
          if (value is Map) {
            // Extraímos a posição e o tipo de valor, caso existam
            String tipo = value['Tipo'] ??
                ""; // Pega o tipo, ou uma string vazia se não existir

            // **Aqui está a alteração**: Verifica se o 'Tipo' não é "vazio"
            if (tipo != "vazio" && tipo.isNotEmpty) {
              print("Vaga $positionKey: Tipo = $tipo");

              vagasList.add({
                'row': row,
                'col': col,
                'status': value[
                    'Status'], // Pega o status da vaga (1 ou outro valor diferente de 0)
                'tipo': tipo, // Adiciona o tipo à vaga
              });
            }
          }
        }
      }
    });

    return vagasList;
  }

  @override
  void dispose() {
    _timer?.cancel(); // Cancela o timer quando o widget for removido
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Estaciona+',
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: Theme.of(context).colorScheme.primary,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: estacionamentos.isEmpty
              ? [
                  Center(child: CircularProgressIndicator())
                ] // Exibe um carregando enquanto não tiver dados
              : estacionamentos.map((estacionamento) {
                  // Cria a lista de vagas com base nos dados da API
                  List<Map<String, dynamic>> vagas =
                      _criarListaVagas(estacionamento['vagas']);

                  // Cria o card com o nome, total de vagas e a lista de vagas
                  return Padding(
                    padding:
                        const EdgeInsets.all(10.0), // Aumenta o padding do card
                    child: CardWidget().estacionaCard(
                      context,
                      estacionamento["nome"] ??
                          "Nome não disponível", // Nome do estacionamento
                      estacionamento["endereco"] ??
                          "Endereço não disponível", // Endereço do estacionamento
                      _parseInt(estacionamento["totalVagas"]), // Total de vagas
                      vagas, // Lista de vagas com status
                    ),
                  );
                }).toList(),
        ),
      ),
    );
  }

  // Função para converter uma string para int de maneira segura
  int _parseInt(dynamic value) {
    if (value == null) return 0; // Se for nulo, retorna 0
    if (value is int) return value; // Se já for int, retorna como está
    if (value is String) {
      return int.tryParse(value) ??
          0; // Se for string, tenta converter para int, se falhar, retorna 0
    }
    return 0; // Se for outro tipo, retorna 0
  }
}

class CardWidget {
  Widget estacionaCard(BuildContext context, String nome, String endereco,
      int totalVagas, List<Map<String, dynamic>> vagas) {
    // Obtém as linhas únicas presentes nas vagas
    List<int> linhasVisiveis =
        vagas.map((vaga) => vaga['row'] as int).toSet().toList();
    linhasVisiveis.sort(); // Ordena as linhas para exibir em ordem crescente

    // Cria uma estrutura para armazenar a contagem de itens por linha
    Map<int, List<Map<String, dynamic>>> vagasPorLinha = {};
    for (var linha in linhasVisiveis) {
      vagasPorLinha[linha] =
          vagas.where((vaga) => vaga['row'] == linha).toList();
    }

    return Card(
      elevation: 5,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: Padding(
        padding: const EdgeInsets.all(10.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(nome,
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            SizedBox(height: 10),
            Text(endereco),
            SizedBox(height: 10),
            Text('Total de Vagas: $totalVagas'),
            SizedBox(height: 10),
            // Exibe a matriz linha por linha
            ...linhasVisiveis.map((linha) {
              var vagasLinha = vagasPorLinha[linha] ?? [];
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SizedBox(height: 5),
                  GridView.builder(
                    shrinkWrap: true,
                    physics: NeverScrollableScrollPhysics(),
                    gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: vagasLinha
                          .length, // Número de itens visíveis na linha
                      crossAxisSpacing: 5,
                      mainAxisSpacing: 5,
                      childAspectRatio: 1.0,
                    ),
                    itemCount: vagasLinha.length,
                    itemBuilder: (context, index) {
                      var vaga = vagasLinha[index];
                      return Container(
                        alignment: Alignment.center,
                        decoration: BoxDecoration(
                          border: Border.all(color: Colors.blue),
                          borderRadius: BorderRadius.circular(5),
                          color:
                              vaga['status'] == 0 ? Colors.green : Colors.red,
                        ),
                        child: vaga['tipo'] == 'deficiente'
                            ? SvgPicture.asset(
                          'assets/icons/deficiente.svg',
                          color: Colors.white,// Ícone para deficiente
                          width: 30,
                          height: 30,
                        )
                            : vaga['tipo'] == 'autista'
                            ? SvgPicture.asset(
                          'assets/icons/autista.svg',
                          color: Colors.white,// Ícone para autista
                          width: 30,
                          height: 30,
                        )
                            : vaga['tipo'] == 'idoso'
                            ? SvgPicture.asset(
                          'assets/icons/idoso.svg',
                          color: Colors.white,// Ícone para idoso
                          width: 30,
                          height: 30,
                        )
                            : Text(
                          "Normal",
                          style: TextStyle(
                              color: Colors.white, fontWeight: FontWeight.bold),
                        ),
                      );
                    },
                  ),
                  SizedBox(height: 10),
                ],
              );
            }).toList(),
          ],
        ),
      ),
    );
  }
}
