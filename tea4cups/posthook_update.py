
# Chamar a rota para cancelar uma impressão (problema na impressora)
if problem:
    status = "printer_problem"
# chamar a rota de sucesso
if ok:
    status = "print_success"
    
data = {"status": status}
requests.post(url + printing_id, headers=headers, data=data)