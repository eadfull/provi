<?php

namespace Source\Models;

/**
 * Provi: Classe de integração com o Provi

 
 * @author Marcos Andrade <marcossbgbg@gmail.com>
 * @link https://www.movatee.com/ Saiba mais
 * @copyright (c) 2021,Marcos A Andrade **/
class Provi
{

    private $acUrl;
    private $acToken;
    private $output;
    private $callback;
    private $params;
    private $endPoint;
    private $error;

    public function __construct()
    {
        $this->acUrl = "https://ms-checkout.provi.com.br";
        $this->acToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MjI0NzcsImVtYWlsIjoic3lzdGVtQG1vdmF0ZWUuY29tLmJyIiwicGFydG5lciI6eyJpZCI6NTE2LCJuYW1lIjoiTW92YVRlZSIsInNsdWciOiJtb3ZhdGVlIn0sImV4cCI6IjM2aCJ9.FcMdF55-XtnhUYeCEA9I5XYT0qdZZO3NbwZ30efyxyA";
        $this->output = "json";
        $this->params = [];
    }

    /**
     * getByCourse: Busca um curso pelo ID na Provi.
     * @param string $ID que deseja consultar
     */
    public function getByCourse($ID)
    {
        $this->endPoint = "/courses/{$ID}";
        $this->get();
    }

    /**
     * getCourses: Busca todas os dentro da Provi.
     */
    public function getCourses()
    {
        $this->endPoint = "/courses";
        $this->get();
    }

 /**
     * updateVisibleCourse: Altera a visibilidade de um curso na Provi
     * @param int $tag ID do checkout
     * @param string $Bool dado boleano.
     */
    public function updateVisibleCourse($ID, $Bool)
    {
        $this->endPoint = "/courses/{$ID}";
        $this->params = [
            "visible" => $Bool
        ];

        $this->put();
    }

    /**
     * getByCheckout: Busca um checkout pelo ID na Provi.
     * @param int $ID do Checkout que deseja consultar
     */
    public function getByCheckout($ID)
    {
        $this->endPoint = "/checkouts/{$ID}";
        $this->get();
    }
    /**
     * getCheckouts: Busca um checkout pelo ID na Provi.
     * @param int $page do Checkout e qtd que deseja consultar
     */
    public function getCheckouts(int $page = 1, int $qtd = 10)
    {
        if($qtd && $qtd > 10 ){
            $this->endPoint = "/checkouts&page={$page}&quantity={$qtd}"; 
        }else{
            $this->endPoint = "/checkouts&page={$page}&quantity=10";
        }
        
        $this->get();
    }
    /**
     * updateCheckoutURL: Atualiza a URL de Notificação do Checkout na Provi
     * @param int $tag ID do checkout
     * @param string $UrlNotify nova URL de notificação.
     */
    public function updateCheckoutURL($ID, $UrlNotify)
    {
        $this->endPoint = "/checkouts/{$ID}";
        $this->params = [
            "notification_url" => $UrlNotify
        ];

        $this->put();
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

        curl_setopt($ch, CURLOPT_URL, "{$this->acUrl}{$this->endPoint}?" . http_build_query($this->params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["accept: application/json","Authorization: {$this->acToken}"]);
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
        
        curl_setopt($ch, CURLOPT_URL, "{$this->acUrl}{$this->endPoint}?");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["accept: application/json","Authorization: {$this->acToken}"]);
        $this->callback = json_decode(curl_exec($ch));
        $this->error = curl_error($ch); 
        curl_close($ch);

    }

    /**
     * Efetua uma comunicação via HTTP POST
     */
    private function put()
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "{$this->acUrl}{$this->endPoint}?");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["accept: application/json","Authorization: {$this->acToken}"]);
        $this->callback = json_decode(curl_exec($ch));
        $this->error = curl_error($ch); 
        curl_close($ch);

    }


}
