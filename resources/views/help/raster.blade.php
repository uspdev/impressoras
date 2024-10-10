@extends('master')

@section('title', 'Erro ao imprimir')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger">
                    <h1>Erro ao processar o PDF</h1>
                </div>
                <div class="lead">
                    <p>Seu trabalho demorou tempo demais para ser processado.</p>
                    <p>Pode haver alguma imagem muito grande embutida no PDF.</p>
                </div>
            </div>
        </div>
        <div class="card-header">
            <h4>O que fazer?</h4>
        </div>
        <div class="card">
            <ol>
                <li>Abrir o documento no leitor de PDF (e.g. Okular);</li>
                <li>Mandar imprimir <b>para arquivo</b>;</li>
                <li>Marcar a opção <b>Forçar a rasterização</b>;</li>
                <li>Enviar o arquivo "reimpresso".</li>
            </ol>
        </div>
    </div>
@endsection
