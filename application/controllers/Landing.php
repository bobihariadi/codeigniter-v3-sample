<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{        
		$this->data['template_page'] = "blank";
		$this->data['title']         = "Dashboard";
		$this->data['subtitle']      = "Dashboard";
		$this->data['activepage']    = "dashboard";
		$this->data['activepagesub'] = "";

		$this->template->load('template', $this->data['template_page'], $this->data);
    }
    
    public function table()
	{        
		$this->data['template_page'] = "blank";
		$this->data['title']         = "Table";
		$this->data['subtitle']      = "Table";
		$this->data['activepage']    = "table";
		$this->data['activepagesub'] = "";

		$this->template->load('template', $this->data['template_page'], $this->data);
	}
    
    public function login()
	{        
		$this->data['template_page'] = "blank";
		$this->data['title']         = "Login";
		$this->data['subtitle']      = "Login";
		$this->data['activepage']    = "page";
		$this->data['activepagesub'] = "login";

		$this->template->load('template', $this->data['template_page'], $this->data);
	}
}
