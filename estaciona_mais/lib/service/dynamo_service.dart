import 'package:http/http.dart' as http;
import 'dart:convert';

class DynamoService {
  final apiUrl =
      'https://ys8lk9fd78.execute-api.us-east-1.amazonaws.com/Teste';

  // Criar Item
  Future<void> createItem(String id, String nome) async {
    final response = await http.put(
      Uri.parse('$apiUrl/items'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'estacionamentoId': id, 'nome': nome}),
    );
    print(response.body);
  }

  // Ler Item
  Future<void> readItem(String id) async {
    final response = await http.get(
      Uri.parse('$apiUrl/items/$id'),
    );
    print(response.body);
  }

  Future<void> readItems() async {
    final response = await http.get(
      Uri.parse('$apiUrl/items'),
    );
    print(response.body);
  }

  // Atualizar Item
  Future<void> updateItem(String id, String nome) async {
    final response = await http.put(
      Uri.parse('$apiUrl/items'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'estacionamentoId': id, 'nome': nome}),
    );
    print(response.body);
  }

  // Excluir Item
  Future<void> deleteItem(String id) async {
    final response = await http.delete(
      Uri.parse('$apiUrl/items/$id'),
    );
    print(response.body);
  }
}
