import 'dart:convert';
import 'package:http/http.dart' as http;

class DynamoService {
  final String apiUrl = "https://ekiuak4c87.execute-api.us-east-1.amazonaws.com/prod/estacionamentos";

  // Função para consultar todos os estacionamentos
  Future<List<dynamic>> consultarEstacionamentos() async {
    final response = await http.post(
      Uri.parse(apiUrl),
      headers: {"Content-Type": "application/json"},
      body: json.encode({
        "operation": "read",
        "payload": ""
      }),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data; // Retorna a lista de estacionamentos
    } else {
      throw Exception("Falha ao carregar os estacionamentos");
    }
  }
}
