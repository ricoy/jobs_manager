<?php

namespace JobScheduler;

class Job {

    private $id;
    private $descricao;
    private $dataMaximaConclusao;
    private $tempoEstimado;

    public function __construct($id, $descricao, $dataMaximaConclusao, $tempoEstimado) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->dataMaximaConclusao = $dataMaximaConclusao;
        $this->tempoEstimado = $tempoEstimado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getDataMaximaConclusao()
    {
        return $this->dataMaximaConclusao;
    }

    public function getTempoEstimado()
    {
        return $this->tempoEstimado;
    }
    
}