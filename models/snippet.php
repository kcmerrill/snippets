<?php

class snippet {
    var $db;
    var $log;

    function __construct($db, $log) {
        $this->db = $db;
        $this->log = $log;
    }

    function save($snippet, $string = true) {
        if($snippet = $this->valid($snippet)) {
            $id = $this->db->update(array('_id'=>$snippet['_id']), $snippet, array('upsert'=>true));
            return $this->fetch($snippet['_id'], $string);
        }
        return false;
    }

    function fetch($id, $string = false) {
        $snippet = $this->db->findOne(array('_id'=>$id));
        return $string ? json_encode($snippet, TRUE) : (array) $snippet;
    }

    function valid($snippet) {
        if(!is_array($snippet)) {
            return false;
        }
        if(!isset($snippet['snippet'])) {
            return false;
        }
        if(!isset($snippet['type'])) {
            return false;
        }
        if(!isset($snippet['tags'])) {
            $snippet['tags'] = array();
        }
        if(!isset($snippet['_id']) || !$snippet['_id']) {
            $snippet['_id'] = uniqid();
        }
        return $snippet;
    }
}
