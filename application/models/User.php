<?php


class User extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_data($where)
    {
        $query = $this->user->db->get_where('alumnos',$where,1);
        if( $this->db->count_all_results() )
            return $query->result();
        return FALSE;
    }
	
	public function update_user_data($where, $fields)
    {
        $this->db->update('alumnos',$fields,$where);
    }
	
	public function get_commnents_data($where)
    {
        $query = $this->user->db->get_where('notas_alumnos',$where);
        if( $this->db->count_all_results() )
            return $query->result();
        return FALSE;
    }

}