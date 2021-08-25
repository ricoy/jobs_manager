<?php

namespace JobsManager;

class Job {

    private $id;
    private $descricao;
    private $dataMaximaConclusao;
    private $tempoEstimado;

    public function __construct($id, $descricao, $dataMaximaConclusao, $tempoEstimado) {
        
        if (!is_int($id)) {
            throw new \InvalidArgumentException('O id deve ser inteiro');
        }
        
        if (empty($descricao)) {
            throw new \InvalidArgumentException('A descricao nao pode ser vazia');
        }

        if (!strtotime($dataMaximaConclusao)) {
            throw new \InvalidArgumentException('A dataMaximaConclusao deve obedecer o formato yyyy-mm-dd hh:mm:ss');
        }

        if (!is_int($tempoEstimado)) {
            throw new \InvalidArgumentException('O tempoEstimado deve ser inteiro');
        }

        $this->id = $id;
        $this->descricao = $descricao;
        $this->dataMaximaConclusao = $dataMaximaConclusao;
        $this->tempoEstimado = $tempoEstimado;
    }

    public function toArray()
    {
        return [
            'id' => $this->id, 
            'descricao' => $this->descricao,
            'dataMaximaConclusao' => $this->dataMaximaConclusao,
            'tempoEstimado' => $this->tempoEstimado
        ];
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