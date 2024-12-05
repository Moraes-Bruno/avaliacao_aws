import https from 'https';

const API_URL = "API_ESTACIONAMENTO";

export const handler = async (event) => {
    try {
        console.log("Mensagem recebida do IoT Core:", JSON.stringify(event, null, 2));

        const novoStatus = event.novoStatus;

        if (novoStatus === undefined) {
            throw new Error("Campo 'novoStatus' ausente na mensagem");
        }

        const payload = {
            operation: "update",
            payload: {
                Key: {
                    _id: "3444080917323399906"
                },
                UpdateExpression: "SET #campoVagas.#vagaStatus.#campoStatus = :novoStatus",
                ExpressionAttributeNames: {
                    "#campoVagas": "vagas",
                    "#vagaStatus": "2,9", 
                    "#campoStatus": "Status"
                },
                ExpressionAttributeValues: {
                    ":novoStatus": novoStatus
                }
            }
        };

        const options = {
            hostname: 'API_ESTACIONAMENTO',
            port: 443,
            path: '/prod/estacionamentos',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Content-Length': Buffer.byteLength(JSON.stringify(payload))
            }
        };

        return new Promise((resolve, reject) => {
            const req = https.request(options, (res) => {
                let data = '';
                res.on('data', (chunk) => {
                    data += chunk;
                });
                res.on('end', () => {
                    if (res.statusCode === 200) {
                        console.log("API atualizada com sucesso:", data);
                        resolve({
                            statusCode: 200,
                            body: 'Update successful'
                        });
                    } else {
                        console.error("Erro ao atualizar a API:", data);
                        reject({
                            statusCode: res.statusCode,
                            body: data
                        });
                    }
                });
            });

            req.on('error', (e) => {
                console.error("Erro na requisição HTTPS:", e.message);
                reject({
                    statusCode: 500,
                    body: e.message
                });
            });

            req.write(JSON.stringify(payload));
            req.end();
        });
    } catch (error) {
        console.error("Erro geral:", error.message);
        return {
            statusCode: 500,
            body: JSON.stringify(error.message)
        };
    }
};
