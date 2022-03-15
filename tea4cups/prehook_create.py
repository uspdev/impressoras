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
        'printer': 'impressora_proaluno_letras',
        'jobid': '1',
        'host': '10.89.9.5',
        'filename': 'mamute.pdf',
        'filesize': '192045',
        }

response = requests.post(url, headers=headers, data=data)

# TODO: só seguir se o código for 200
print(response.status_code)

# TODO: se o retorno for sent_to_printer_queue exit(0) no script
data = response.json()
printing_id = data[1]

# TODO: se o retorno for waiting_job_authorization fazer um loop com 5 tentativas com intervalos de 1 segundo
for i in range(50):
    time.sleep(1)
    response = requests.get(url + str(printing_id), headers=headers)
    status = response.json()[0]
    if status == 'sent_to_printer_queue':
        exit(0)
    if response[0] == 'cancelled_not_authorized':
        exit(1)

# No final, se passou o tempo máximo e não foi autorizado, cancelamos
# TODO: fazer a rota de cancelamento e usá-la aqui
# Novo status: cancelled_timeout
exit(1)


