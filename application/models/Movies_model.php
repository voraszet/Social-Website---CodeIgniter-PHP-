<?php
  class Movies_model extends CI_Model{


    function __construct(){
      parent::__construct();

      //$this->load->database();
    }

    /*
    function loadMovies(){
      //$this->db->select('title');
      //$this->db->get('Movies');
    }*/


    $name = "John";

    function loadJohn(){
      return $name;

    }

  }

?>
