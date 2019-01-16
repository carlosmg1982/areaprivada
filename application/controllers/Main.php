<?php

class Main  extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->logged_in = $this->session->logged_in ? TRUE : FALSE ;
        $this->load->model('user');
        $this->load->model('configuration');
    }
	
	public function logout()
    {
        $this->session->logged_in = FALSE;
        redirect('main/login');
    }

    public function login()
    {
		if( $this->logged_in )
        {
            redirect('main/misdatos');
        } 
		else 
		{
			$data['login_failed'] = FALSE;
			$this->_load_layout('login', $data);
		}
    }

    public function login_action()
    {
		$where = array();
		$where['dni'] = $this->input->post('username');
        $user_data = $this->user->get_user_data($where);

        setlocale(LC_ALL, 'es_ES');

        if( $user_data[0]->dni )
        {
            $configuration_data = $this->configuration->get_configuration_data();

            $configuration_session = array();
            foreach($configuration_data as $item) {
                $configuration_session[$item->clave]=$item->valor;
            }

            $this->session->logged_in = TRUE;
			$user_session = array();
			foreach($user_data[0] as $property=>$value) {
				$user_session[$property]=$value;
			}
			
			$user_comments_query = $this->db->query("SELECT ca.bloque,ca.descripcion,na.original,na.alumno,na.comentarios FROM notas_alumnos na INNER JOIN correcciones_auto ca ON (ca.id=na.correcciones_auto_id) WHERE na.alumnos_id=".intval($user_data[0]->id));
			$user_comments = $user_comments_query->result_array();
			if( count($user_comments) ) {
				$user_session['notas_alumnos'] = $user_comments;
			}
			
			$this->session->set_userdata("usuario",$user_session);
            $this->session->set_userdata("configuracion",$configuration_session);

            redirect('main/misdatos');
        }else
        {
            $data['login_failed'] = TRUE;
            $this->_load_layout('login', $data);
        }
    }
	
	public function entrega()
    {
		if( $this->logged_in )
        {
            redirect('main/misdatos');
        } 
		else 
		{
			$data['login_failed'] = FALSE;
			$this->_load_layout('login', $data);
		}
    }

    public function entrega_action()
    {
		$user_session = $this->session->get_userdata();
		$where = $fields = array();
		$where['id'] = $user_session['usuario']['id'];
		$fields['finalizado'] = 1;
		$fields['fechaNotificacion'] = date('Y-m-d H:i:s');
        
		rename($_SERVER['DOCUMENT_ROOT'].'/'.strtoupper($user_session['usuario']['grupo'].$user_session['usuario']['dni']).'/',$_SERVER['DOCUMENT_ROOT'].'/FINALIZADO'.strtoupper($user_session['usuario']['dni']).'/');
		
		$this->user->update_user_data($where,$fields);
		$user_data = $this->user->get_user_data($where);
		
        if( $user_data[0]->dni )
        {
			$user_session = array();
			foreach($user_data[0] as $property=>$value) {
				$user_session[$property]=$value;
			} 
			$this->session->set_userdata("usuario",$user_session);
            redirect('main/misdatos');
        }
		else
        {
            $data['login_failed'] = TRUE;
            $this->_load_layout('login', $data);
        }
    }

    public function misdatos()
    {
        if( !$this->logged_in )
        {
            redirect('main/login');
        } 
		else 
		{
			$this->_load_layout('misdatos',$this->session->get_userdata());
		}
    }

    function _load_layout($template, $data = '')
    {

        $this->load->view('layout/header');
        $this->load->view($template,$data);
        $this->load->view('layout/footer');

    }

}