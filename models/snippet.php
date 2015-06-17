<?php

/* Not sure I want to store these as flat files for forever
 * but for now ... it should be ok to refactor for later
 * -kc
*/

class snippet {
    var $filesystem;

    function __construct($filesystem) {
        $this->filesystem = $filesystem;
    }

    function save($snippet, $string = true) {
        if($snippet = $this->valid($snippet)) {
            if(file_put_contents($this->file($snippet['id']), json_encode($snippet, JSON_PRETTY_PRINT))) {
                return $this->fetch($snippet['id'], $string);
            } else {
                return false;
            }
        }
        return false;
    }

    function fetch($id, $string = false) {
        if(is_file($this->file($id))) {
            return !$string ? json_decode(file_get_contents($this->file($id)), TRUE) : file_get_contents($this->file($id));
        } else {
            return false;
        }
    }

    function file($id) {
        return $this->filesystem . DIRECTORY_SEPARATOR . $id;
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
        if(!isset($snippet['id']) || !$snippet['id']) {
            $snippet['id'] = uniqid();
        }
        return $snippet;
    }
}
