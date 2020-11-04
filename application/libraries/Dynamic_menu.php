<?php

/*
 * Dynmic_menu.php
 */
class Dynamic_menu {

    private $ci;            // para CodeIgniter Super Global Referencias o variables globales
    private $class_aktive = 'class="nav-item active"';
    private $class_menu   = 'class="menu"';
    private $class_parent = 'class="parent"';
    private $class_last   = 'class="last"';
    
    // --------------------------------------------------------------------
    /**
     * PHP5        Constructor
     *
     */
    function __construct()
    {
        $this->ci =& get_instance();    // get a reference to CodeIgniter.

        
    }
    // --------------------------------------------------------------------
     /**
     * build_menu($table, $type)
     *
     * Description:
     *
     * builds the Dynaminc dropdown menu
     * $table allows for passing in a MySQL table name for different menu tables.
     * $type is for the type of menu to display ie; topmenu, mainmenu, sidebar menu
     * or a footer menu.
     *
     * @param    string    the MySQL database table name.
     * @param    string    the type of menu to display.
     * @return    string    $html_out using CodeIgniter achor tags.
     */

    function build_menu($activepage="", $id = NULL, $activepagesub="")
    {
        $menuParent = array();
        $whereIn    = " where SHOW_MENU = 'Y'";
        $whereAnd   = "";

        // $this->ci->load->library('ion_auth');

        // if($this->ci->ion_auth->is_admin()){
        //     $id = NULL;
        // }

        // if($id != NULL){
        //     $idIN     = implode(",", $id);
        //     $where    = "WHERE ID IN(".$idIN.") AND SHOW_MENU = 1";

        //     $findMenuParent = $this->ci->db->query("SELECT PARENT_ID FROM SIMTAX_DYN_MENU ".$where." GROUP BY PARENT_ID");
        //     $menuParentRes     = $findMenuParent->result();

        //     foreach ($menuParentRes as $value) {
        //         $menuParent[] = (int)$value->PARENT_ID;
        //     }

        //     $whereIn_Id = implode(",", array_merge($id,$menuParent));
        //     $whereIn    = "WHERE ID IN(".$whereIn_Id.") AND SHOW_MENU = 1";
        //     $whereAnd   = "AND ID IN(".$whereIn_Id.") AND SHOW_MENU = 1";
        // }

        $query = $this->ci->db->query("SELECT * FROM tm_menu ".$whereIn." ORDER BY SHOWORDER");

    // now we will build the dynamic menus.
    $html_out   = "";
	  foreach ($query->result() as $row)
            {
                $ygAktif    = ' class="nav-item"';

                $id          = $row->ID;
                $title       = $row->TITLE;
                $link_type   = $row->LINK_TYPE;
                $module_name = $row->MODULE_NAME;
                $url         = $row->URL;
                $parent_id   = $row->PARENT_ID;
                $is_parent   = $row->IS_PARENT;
                $show_menu   = $row->SHOW_MENU;
                $style       = $row->STYLE;
                $class       = 'class="nav-link collapsed"';
                $colapse    = 'false';

		if($show_menu == 'Y' && $parent_id==0){
            
            if($activepage  == $module_name){
                $ygAktif = ' class="nav-item active"';
                if($activepagesub)
                    $colapse = 'true';
            }
			
				$html_out	.= "<li".$ygAktif.">";
				if ($is_parent == TRUE) {
					$html_out	.= '<a href="#"'.$class.' data-toggle="collapse" data-target="#collapsePages" aria-expanded="'.$colapse.'" aria-controls="collapsePages" >'.$style.' <span class="hide-menu">'.$title.'<span class="fa arrow"></span></span></a>';
					$html_out 	.= $this->get_childs($id, $whereAnd, $activepagesub);
				} else {
					$html_out	.= anchor($url,  $style." <span>".$title."</span>", $class);
				}
				$html_out	.="</li>";
			}


        }
        
		$query->free_result();
		return $html_out;
    }
     /**
     * get_childs($menu, $parent_id) - SEE Above Method.
     *
     * Description:
     *
     * Builds all child submenus using a recurse method call.
     *
     * @param    mixed    $id
     * @param    string    $id usuario
     * @return    mixed    $html_out if has subcats else FALSE
     */
   function get_childs($id, $whereAnd = NULL, $activepagesub="")
    {
        $show_menu   = '';
       if($activepagesub !=""){
        $show_menu   = 'show';
       }
	   $has_subcats = FALSE;
       $html_out  = '<div id="collapsePages" class="collapse '.$show_menu.'" aria-labelledby="headingPages" data-parent="#accordionSidebar"><div class="bg-white py-2 collapse-inner rounded">';

		$query = $this->ci->db->query("SELECT * FROM tm_menu WHERE PARENT_ID = ".$id." ".$whereAnd." AND SHOW_MENU = 'Y' ORDER BY SHOWORDER");

         foreach ($query->result() as $row)
            {
                $id          = $row->ID;
                $title       = $row->TITLE;
                $link_type   = $row->LINK_TYPE;
                $module_name = $row->MODULE_NAME;
                $url         = $row->URL;
                $parent_id   = $row->PARENT_ID;
                $is_parent   = $row->IS_PARENT;
                $show_menu   = $row->SHOW_MENU;
                $style       = $row->STYLE;

                $has_subcats = TRUE;
                

                if($activepagesub  ==  $module_name){
                    $show_menu   = ' active';
                }

			   if ($is_parent == TRUE)
                {
					$html_out	.= anchor($url, $style.$title);
					$html_out 	.= $this->get_childs_third($id, $whereAnd);
				}
                else
                {
                  $html_out	.= anchor($url, $style.$title, ' class="collapse-item '.$show_menu.'" ');
                }
			}
      $html_out .= "</div></div>";
      return ($has_subcats) ? $html_out : FALSE;

    }

 function get_childs_third($id, $whereAnd = NULL)
    {
	   $has_subcats = FALSE;
       $html_out  = "<ul class='nav nav-third-level'>";
	
		$query = $this->ci->db->query("SELECT * FROM SIMTAX_DYN_MENU WHERE  PARENT_ID = ".$id." ".$whereAnd." AND SHOW_MENU = 1 ORDER BY ID");

         foreach ($query->result() as $row)
            {
                $id          = $row->ID;
                $title       = $row->TITLE;
                $link_type   = $row->LINK_TYPE;
                $module_name = $row->MODULE_NAME;
                $url         = $row->URL;
                $target      = $row->TARGET;
                $parent_id   = $row->PARENT_ID;
                $is_parent   = $row->IS_PARENT;
                $show_menu   = $row->SHOW_MENU;
                $style       = $row->STYLE;

                $has_subcats = TRUE;
				$html_out	.= "<li>";
                if ($is_parent == TRUE)
                {
					$html_out	.= anchor($url, $style.$title);

                }
                else
                {
                    $html_out	.= anchor($url, $style.$title);
                }
                $html_out	.= "</li>";
        }
      $html_out .= "</ul>";
      return ($has_subcats) ? $html_out : FALSE;

    }

}

// ------------------------------------------------------------------------
// End of Dynamic_menu Library Class.
// ------------------------------------------------------------------------
/* End of file Dynamic_menu.php */
/* Location: ../application/libraries/Dynamic_menu.php */
