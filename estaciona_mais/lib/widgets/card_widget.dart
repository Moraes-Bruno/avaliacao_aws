import 'package:flutter/material.dart';

class CardWidget {
  Widget estacionaCard(BuildContext context, String nomeLocal, String endereco,
      int totalVagas, List<List<bool>> vagas) {
    int maxColunas =
        vagas.map((linha) => linha.length).reduce((a, b) => a > b ? a : b);

    List<bool?> vagasNormalizadas = vagas.expand((linha) {
      return linha.map((vaga) => vaga as bool?).toList() +
          List<bool?>.filled(maxColunas - linha.length, null);
    }).toList();

    return Center(
      child: Container(
        margin: const EdgeInsets.only(top: 10),
        width: MediaQuery.of(context).size.width - 10,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(5.0),
        ),
        child: Theme(
          data: Theme.of(context).copyWith(dividerColor: Colors.transparent),
          child: ExpansionTile(
            minTileHeight: 65,
            collapsedBackgroundColor: Theme.of(context).colorScheme.tertiary,
            backgroundColor: Theme.of(context).colorScheme.tertiary,
            title: Text(
              nomeLocal,
              style: const TextStyle(
                color: Colors.black,
                fontSize: 25,
                fontWeight: FontWeight.bold,
              ),
            ),
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    margin: const EdgeInsets.only(left: 15, right: 10),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Endereco: $endereco",
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 5),
                        Text(
                          "Total de Vagas: ${totalVagas.toString()}",
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 10),
                  //Logica responsavel por gerar o estacionamento
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: maxColunas,
                      mainAxisExtent: 45
                    ),
                    itemCount: vagasNormalizadas.length,
                    itemBuilder: (context, index) {
                      bool? ocupado = vagasNormalizadas[index];
      
                      //adiciona espaços em branco para preservar o layout original do estacionamento
                      if (ocupado == null) {
                        return Container(
                          margin: const EdgeInsets.all(4.0),
                          decoration: BoxDecoration(
                            color: Colors
                                .white, // Espaço vazio (branco ou cinza claro)
                            borderRadius: BorderRadius.circular(5.0),
                            border: Border.all(
                              color: Colors.white,
                              width: 2.0,
                            ),
                          ),
                        );
                      }
      
                      return Container(
                        margin: const EdgeInsets.all(4.0),
                        decoration: BoxDecoration(
                          color: ocupado ? Colors.red : Colors.green,
                          borderRadius: BorderRadius.circular(5.0),
                          border: Border.all(
                            color: Colors.white,
                            width: 3.0,
                          ),
                        ),
                        child: Center(
                          child: ocupado
                              ? const Icon(Icons.car_repair,
                                  color: Colors.white, size: 30)
                              : const Icon(Icons.local_parking,
                                  color: Colors.white, size: 30),
                        ),
                      );
                    },
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
