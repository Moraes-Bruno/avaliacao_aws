import 'package:estaciona_mais/widgets/card_widget.dart';
import 'package:flutter/material.dart';

import '../service/dynamo_service.dart';

class MyHomePage extends StatefulWidget {
  MyHomePage({super.key});

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  //arrays apenas para testes
  //utilizados para simular como seria um estacionamento vindo do banco de dados
  //remover ao fazer o back end
  List<List<bool>> vagas = [
    [false, true, false, true],
    [true, false, true, false],
    [false, false, true, true],
  ];

  List<List<bool>> vagas2 = [
    [false, true, false],
  ];

  List<List<bool>> vagas3 = [
    [false, true, false, true, false, true],
    [true, false, true, false],
    [false, false, true, true, true, false, true, true, false],
  ];

  List<List<bool>> vagas4 = [
    [false, true, false, true],
    [true, false, true, false],
  ];

  Future<void> consultarApi() async {
    await new DynamoService().createItem("2", "Teste");
    await new DynamoService().readItems();
  }

  @override
  Widget build(BuildContext context) {
    consultarApi();
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
          children: [
            CardWidget().estacionaCard(
                context, "Umihotaru PA", "Tokyo, Japão", 12, vagas),
            CardWidget().estacionaCard(
                context, "Mercado Pag+ Lev-", "Xique-Xique, Bahia", 9, vagas2),
            CardWidget().estacionaCard(
                context, "Daikoku PA", "Tokyo Japão", 19, vagas3),
            CardWidget().estacionaCard(
                context, "Academia", "São Paulo Brasil", 8, vagas4)
          ],
        ),
      ),
    );
  }
}
