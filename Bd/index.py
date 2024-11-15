import json
import boto3
from decimal import Decimal

client = boto3.client('dynamodb')
dynamodb = boto3.resource("dynamodb")
table = dynamodb.Table('estacionamentos')
tableName = 'estacionamentos'


def lambda_handler(event, context):
    print(event)
    body = {}
    statusCode = 200
    headers = {
        "Content-Type": "application/json"
    }

    try:
        if event['routeKey'] == "DELETE /items/{id}":
            table.delete_item(
                Key={'estacionamentoId': event['pathParameters']['id']})
            body = 'Deleted item ' + event['pathParameters']['id']
        elif event['routeKey'] == "GET /items/{id}":
            body = table.get_item(
                Key={'estacionamentoId': event['pathParameters']['id']})
            body = body["Item"]
            responseBody = [
                {'estacionamentoId': body['estacionamentoId'], 'nome': body['nome'], 'vagas_totais': body['vagas_totais'], 'vagas_disponiveis': body['vagas_disponiveis'], 'vagas': body['vagas']}]
            body = responseBody
        elif event['routeKey'] == "GET /items":
            body = table.scan()
            body = body["Items"]
            responseBody = []
            for items in body:
                responseItems = [
                    {'estacionamentoId': items['estacionamentoId'], 
                    'nome': items['nome'], 
                    'vagas_totais': items['vagas_totais'], 
                    'vagas_disponiveis': items['vagas_disponiveis'], 
                    'vagas': items['vagas']}]
                responseBody.append(responseItems)
            body = responseBody
        elif event['routeKey'] == "PUT /items":
            requestJSON = json.loads(event['body'])
            table.put_item(
                Item={
                    'estacionamentoId': requestJSON['estacionamentoId'],
                    'nome': requestJSON['nome'], 
                    'vagas_totais': requestJSON['vagas_totais'], 
                    'vagas_disponiveis': requestJSON['vagas_disponiveis']
                })
            body = 'Put item ' + requestJSON['id']
    except KeyError:
        statusCode = 400
        body = 'Unsupported route: ' + event['routeKey']
    body = json.dumps(body)
    res = {
        "statusCode": statusCode,
        "headers": {
            "Content-Type": "application/json"
        },
        "body": body
    }
    return res