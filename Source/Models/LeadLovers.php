<?php

namespace Source\Models;

class LeadLovers
{

    private $acUrl;
    private $acKey;
    private $output;
    private $callback;
    private $params;
    private $endPoint;
    private $error;

    public function __construct()
    {
        $this->acUrl = "http://llapi.leadlovers.com/webapi";
        $this->acKey = '';
        $this->output = "json";
        $this->params = [];
    }

    /**
     * getByEmail: Busca um contato pelo e-mail no AC.
     * @param string $email e-mail que deseja consultar
     */
    public function getByEmail($email)
    {
        $this->endPoint = "/lead";
        $this->params = ["email" => $email];
        $this->get();
    }

    /**
     * getMachines: Busca todas as maquinas dentro do LeadLovers.
     */
    public function getMachines()
    {
        $this->endPoint = "/machines";
        $this->get();
    }

    /**
     * getByMachine: Busca uma máquina pelo ID no LeadLovers.
     * @param int $ID da Máquina que deseja consultar
     */
    public function getByMachine($ID)
    {
        $this->endPoint = "/machines/{$ID}";
        $this->get();
    }

    
    /**
     * getTags: Busca todas as tags dentro do LeadLovers.
     */
    public function getTags()
    {
        $this->endPoint = "/Tags";
        $this->get();
    }

    /**
     * addActive: Adiciona uma tag a um lead em seu LeadLovers
     * @param string $email E-mail do Lead
     * @param int $tag ID da tag
     * @param int $score ID atrubuir pontuação ao lead (opcional)
     */
    public function addTagLead($email, $tag, $score =null)
    {
        $this->endPoint = "/tag";
        $this->params = [
            "Email" => $email,
            "Tag" => $tag,
            "Score" => $score
        ];


        $this->post();
    }
    /**
     * getEmailSequency: Busca uma sequencia de e-mail pelo ID da maquina no LeadLovers.
     * @param int $Machine ID da Máquina
     */
    public function getEmailSequency($Machine)
    {
        $this->endPoint = "/emailsequences";
        $this->params = ["machineCode" => $Machine];
        $this->get();
    }


    /**
     * getSequenceLevelCode: Busca uma sequencia de e-mail pelo ID da maquina no LeadLovers.
     * @param int $machineCode ID da Máquina
     * @param int $machineCode ID da Sequencia
     */
    public function getSequenceLevelCode($machineCode, $sequenceCode)
    {
        $this->endPoint = "/levels";
        $this->params = ["machinecode" => $machineCode, "sequencecode" => $sequenceCode];
        $this->get();
    }
    /**
     * addActive: Adiciona o lead como ativo a uma ou mais Maquinas em seu LeadLovers
     * @param string $name Nome do Lead
     * @param string $email E-mail do Lead
     * @param int $machineCode ID da maquina
     * @param int $emailSequenceCode ID do email da sequencia
     * @param int $sequenceLevelCode ID do level do email 
     * @param int $comaTags Tags para o Lead
     * @param string $source origem para indentificar
     */
    public function addLead($name, $email, $machineCode, $emailSequenceCode, $sequenceLevelCode, $comaTags = null, $source = null)
    {
   
 
        $this->endPoint = "/lead";
        $this->params = [
            "Name" => $name,
            "Email" => $email,
            "MachineCode" => $machineCode,
            "EmailSequenceCode" => $emailSequenceCode,
            "SequenceLevelCode" => $sequenceLevelCode,
            "Source" => $source,
        ];

        // foreach ($listId AS $lists) {
        //     $this->params["p[{$lists}]"] = $lists;
        //     $this->params["status[{$lists}]"] = 1;
        // }

        if (!empty($comaTags)) {
            $this->params["Tag"] = $comaTags;
        }
        $this->post();
    }
   /**
     * getError: Retorna os dados de resposta da integração!
     * @return object Objeto de retorno do Leadlovers
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * getCallback: Retorna os dados de resposta da integração!
     * @return object Objeto de retorno do ActiveCampaign
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * PRIVATE METHODS
     */

    /**
     * Efetua uma comunicação via HTTP GET
     */

    private function get()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->acUrl}{$this->endPoint}?token={$this->acKey}&" . http_build_query($this->params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['accept: application/json', 
        'authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs']);
        $this->callback = json_decode(curl_exec($ch));
        $this->error = curl_error($ch); 
        curl_close($ch);
    }

    /**
     * Efetua uma comunicação via HTTP POST
     */
    private function post()
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "{$this->acUrl}{$this->endPoint}?token={$this->acKey}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'accept: application/json', 
        'authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6IldlYkFwaSIsInN1YiI6IldlYkFwaSIsInJvbGUiOlsicmVhZCIsIndyaXRlIl0sImlzcyI6Imh0dHA6Ly93ZWJhcGlsbC5henVyZXdlYnNpdGVzLm5ldCIsImF1ZCI6IjFhOTE4YzA3NmE1YjQwN2Q5MmJkMjQ0YTUyYjZmYjc0IiwiZXhwIjoxNjA1NDQxMzM4LCJuYmYiOjE0NzU4NDEzMzh9.YIIpOycEAVr_xrJPLlEgZ4628pLt8hvWTCtjqPTaWMs' ]);
        $this->callback = json_decode(curl_exec($ch));
        $this->error = curl_error($ch); 
        curl_close($ch);

    }

}
