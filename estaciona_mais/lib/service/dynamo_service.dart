import 'package:http/http.dart' as http;
import 'dart:convert';

class DynamoService {
  final apiUrl =
      'https://your-api-id.execute-api.your-region.amazonaws.com/your-stage';

  // Criar Item
  Future<void> createItem(String id, String value) async {
    final response = await http.post(
      Uri.parse('$apiUrl/items'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'id': id, 'value': value}),
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

  // Atualizar Item
  Future<void> updateItem(String id, String newValue) async {
    final response = await http.put(
      Uri.parse('$apiUrl/items'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'id': id, 'newValue': newValue}),
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
