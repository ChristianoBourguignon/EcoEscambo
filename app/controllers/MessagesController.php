<?php

namespace app\controllers;

class MessagesController
{
    private String $msg;
    private int $statuscode;
    /** @var array<string, string> */
    private array $configStatusCode;

    public function setMensagesController(String $msg, int $statuscode): void
    {
        $this->msg = $msg;
        $this->statuscode = $statuscode;
        $this->configStatusCode = $this->configStatusCode($statuscode);
    }

    /**
     * @return array<string, string>
     */
    public function configStatusCode(int $code): array{
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

    public function mensagemCadastroProduto(String $msg, int $statusCode): void
    {
        $this->setMensagesController($msg,$statusCode);
        echo "
        <div class=\"modal fade modal-msg\" id=\"{$this->configStatusCode['modal']}\" tabindex=\"-1\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-dialog-centered\">
                <div class=\"modal-content {$this->configStatusCode['bg']} text-white\">
                    <div class=\"modal-header\">
                        <h5 class=\"modal-title\" id='{$this->statuscode}'>{$this->configStatusCode['title']}</h5>
                        <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\"></button>
                    </div>
                    <div class=\"modal-body\">
                        {$this->msg}
                    </div>
                </div>
            </div>
        </div>
        ";
    }

    //entrar o modal de alterar produto, cadastrar produto, excluir produto.

}