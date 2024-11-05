import 'package:aws_dynamodb_api/dynamodb-2011-12-05.dart';

class DynamoService {
  final service = DynamoDB(
      region: 'us-east-1',
      credentials: AwsClientCredentials(
          accessKey: "someAccessKey", secretKey: "someSecretKey"));

  Future<List<Map<String, AttributeValue>>?> getAll(
      {required String tableName}) async {
    var result = await service.scan(tableName: tableName);
    return result.items;
  }

  Future<Map<String, AttributeValue>?> getItemById(
      {required String tableName, required String id}) async {
    final response = await service.getItem(
      tableName: tableName,
      key: {"id": AttributeValue(s: id)} as Key,
    );
    return response.item;
  }

  Future<void> updateItem(
      {required Map<String, AttributeValue> dbData,
      required String tableName,
      required String id}) async {
    await service.updateItem(
      tableName: tableName,
      key: {"id": AttributeValue(s: id)} as Key,
      attributeUpdates: dbData.map((key, value) => MapEntry(
            key,
            AttributeValueUpdate(
              value: value,
              action: AttributeAction.put,
            ),
          )),
    );
  }

  Future<void> deleteItem(
      {required String tableName, required String id}) async {
    await service.deleteItem(
      tableName: tableName,
      key: {"id": AttributeValue(s: id)} as Key,
    );
  }

  Future insertNewItem(
      Map<String, AttributeValue> dbData, String tableName) async {
    service.putItem(item: dbData, tableName: tableName);
  }
}
