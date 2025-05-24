<?php

namespace app\controllers;

class MessagesController
{
    private $msg;
    private $statuscode;
    private array $configStatusCode;

    public function setMensagesController(String $msg, int $statuscode){
        $this->msg = $msg;
        $this->statuscode = $statuscode;
        $this->configStatusCode = $this->configStatusCode($statuscode);
    }

    public function configStatusCode(int $code){
        switch ($code) {
            case 404:
                return [
                    'title' => "Erro no sistema",
                    'type' => "error",
                    'modal' => 'modalErro',
                    'bg' => 'bg-danger'
                ];
            case 500:
                return [
                    'title' => "Erro interno",
                    'type' => "error",
                    'modal' => 'modalErro',
                    'bg' => 'bg-danger'
                ];
            case 401:
                return [
                    'title' => "Acesso negado",
                    'type' => "warning",
                    'modal' => 'modalErro',
                    'bg' => 'bg-danger'
                ];
            case 200:
                return [
                    'title' => "Sucesso",
                    'type' => "success",
                    'modal' => 'modalError',
                    'bg' => 'bg-success'
                ];
            case 302:
                return [
                    'title' => "Redirecionamento",
                    'type' => "info",
                    'modal' => 'modalErro',
                    'bg' => 'bg-warning'
                ];
            default:
                return [
                    'title' => "Erro desconhecido",
                    'type' => "error",
                    'modal' => 'modalErro',
                    'bg' => 'bg-dark'
                ];
        }
    }

    public  function mensagemCadastroProduto(String $msg, int $statusCode)
    {
        $this->setMensagesController($msg,$statusCode);
        echo "
        <div class=\"modal fade modal-msg\" id=\"{$this->configStatusCode['modal']}\" tabindex=\"-1\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-dialog-centered\">
                <div class=\"modal-content {$this->configStatusCode['bg']} text-white\">
                    <div class=\"modal-header\">
                        <h5 class=\"modal-title\">{$this->configStatusCode['title']}</h5>
                        <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\"></button>
                    </div>
                    <div class=\"modal-body\">
                        {$msg}
                    </div>
                </div>
            </div>
        </div>
        ";
    }

    //entrar o modal de alterar produto, cadastrar produto, excluir produto.

}