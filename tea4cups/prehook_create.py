#!/usr/bin/python3

import requests
import time

# Registrando printing

url = 'http://127.0.0.1:8000/api/printings/'

api_key='123'

headers = {'Authorization': api_key}

data = {'user': '5385361',
        'pages': '2',
        'copies': '3',
        'printer': 'profcs',
        'jobid': '1',
        'host': '10.89.9.5',
        'filename': 'teste.pdf',
        'filesize': '192045',
        }

response = requests.post(url, headers=headers, data=data)

# TODO: só seguir se o código for 200
if response.status_code == 200:

    # TODO: se o retorno for sent_to_printer_queue exit(0) no script
    data = response.json()
    if data["latest_status"] == "sent_to_printer_queue":
        exit(0)

    printing_id = str(data["printing_id"])

    # TODO: se o retorno for waiting_job_authorization fazer um loop com 5 tentativas com intervalos de 1 segundo
    for i in range(5):
        time.sleep(1)
        response = requests.get(url + printing_id, headers=headers)
        status = response.json()["latest_status"]
        if status == "sent_to_printer_queue":
            exit(0)
        if status == "cancelled_not_authorized":
            exit(1)

    # No final, se passou o tempo máximo e não foi autorizado, cancelamos
    # TODO: fazer a rota de cancelamento e usá-la aqui
    # Novo status: cancelled_timeout
    data = {"status": "cancelled_timeout"}
    requests.post(url + printing_id, headers=headers, data=data)
    exit(1)


