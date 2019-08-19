<?php
/*
 * Dynmic_menu.php
 */
class Dynamic_menu {
 
    private $ci;            // para CodeIgniter Super Global Referencias o variables globales
    private $id_menu        = 'id="menu"';
    private $class_menu        = 'class="sidebar-menu" data-widget="tree"';
    private $class_parent    = 'class="treeview"';
    private $class_last        = 'class="last"';
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
 
    function build_menu($type)
    {
        $menu = array();
        $role = $this->ci->get_role();
        $query = $this->ci->db->query("select * from dyn_menu where role='".$role."' order by position asc");
 
        // now we will build the dynamic menus.
        $html_out  = "\t".'<div '.$this->id_menu.'>'."\n";
 
        /**
         * check $type for the type of menu to display.
         *
         * ( 0 = top menu ) ( 1 = horizontal ) ( 2 = vertical ) ( 3 = footer menu ).
         */
        switch ($type)
        {
            case 0:            // 0 = top menu
                break;
 
            case 1:            // 1 = horizontal menu
                $html_out .= "\t\t".'<ul '.$this->class_menu.'>'."\n";
                break;
 
            case 2:            // 2 = sidebar menu
                break;
 
            case 3:            // 3 = footer menu
                break;
 
            default:        // default = horizontal menu
                $html_out .= "\t\t".'<ul '.$this->class_menu.'>'."\n";
 
                break;
        }
 
    // me despliega del query los rows de la base de datos que deseo utilizar
      foreach ($query->result() as $row)
            {
                $id = $row->id;
                $title = $row->title;
                $link_type = $row->link_type;
                $page_id = $row->page_id;
                $module_name = $row->module_name;
                $url = $row->url;
                $uri = $row->uri;
                $dyn_group_id = $row->dyn_group_id;
                $position       = $row->position;
                $target         = $row->target;
                $parent_id      = $row->parent_id;
                $is_parent      = $row->is_parent;
                $show_menu      = $row->show_menu;
                $icon           = $row->icon;
                $budge          = $row->budge;
 
              {
                if ($budge>=0){
                    if ($budge==100){
                        $color = 'success';
                    }else{
                        $color = 'danger';
                    }
                    $budge ='<span class="pull-right-container"><span class="label label-'.$color.' pull-right">'.$budge.'%</span></span>';
                }else{
                    $budge='';
                }
                if (!empty($target)){
                    $target = 'target="'.$target.'"';
                }
                if (!empty($icon)){
                    $icon = '<i class="'.$icon.'"></i>';
                }
                if ($show_menu && $parent_id == 0)   // are we allowed to see this menu?
 
                {
 
                    if ($is_parent == TRUE)
                    {
                    // CodeIgniter's anchor(uri segments, text, attributes) tag.
                    if ($this->is_active($id)){
                        $this->class_parent = 'class="treeview active"';
                    }else{
                        $this->class_parent = 'class="treeview"';
                    }
                    $html_out .= "\t\t\t\t\t\t".'<li '.$this->class_parent.'><a href="#"'.' name="'.$title.'" id="'.$id.'" '.$target.'>'.$icon.'<span>'.$title.'</span>'.$budge.'<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>';
 
                    }
                    else
                    {
                        if ($this->is_active($id)){
                            $this->class_parent = 'class="active"';
                        }else{
                            $this->class_parent = '';
                        }
                    $html_out .= "\t\t\t".'<li '.$this->class_parent.'>'.anchor($url, $icon.'<span>'.$title.'</span>'.$budge, 'name="'.$title.'" id="'.$id.'" '.$target);
                    }
 
               }
 
             }
           $html_out .= $this->get_childs($id);
          // print_r($id);
        }
         // loop through and build all the child submenus.
 
        $html_out .= '</li>'."\n";
        $html_out .= "\t\t".'</ul>' . "\n";
        $html_out .= "\t".'</div>' . "\n";
 
        return $html_out;
    }
    private function check_parent($id){
        $role = $this->ci->get_role();
        $query = $this->ci->db->where("id",$id)->where('role',$role)->get('dyn_menu')->row();

        if (count($query)>0){
            return $query->parent_id;
        }
        return FALSE;
    }
    private function is_active($id){
        $current = strtolower($this->ci->router->fetch_class());
        $return = FALSE;
        $role = $this->ci->get_role();
        $query = $this->ci->db->like("module_name",$current,'before')->where('role',$role)->order_by('position','asc')->get('dyn_menu')->row();

        if (count($query)>0){
            if ($id==$query->id){
                $return = TRUE;
            }else{
                $parent_id = $query->id;
                do{
                    $finish = FALSE;
                    $parent_id = $this->check_parent($parent_id);
                    if ($parent_id==FALSE){
                        $finish = TRUE;
                    }else if($parent_id==$id){
                        $finish = TRUE;
                        $return = TRUE;
                    }
                }while($finish == FALSE);
                
            }
        }
        return $return;
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
    function get_childs($id)
    {
        $role = $this->ci->get_role();
        $has_subcats = FALSE;
 
        $html_out  = '';
        //$html_out .= "\n\t\t\t\t".'<div>'."\n";
        $html_out .= "\t\t\t\t\t".'<ul class="treeview-menu">'."\n";
 
        // query q me ejecuta el submenu filtrando por usuario y para buscar el submenu segun el id que traigo
         $query = $this->ci->db->query("select * from dyn_menu where parent_id = $id and role='".$role."' order by position asc");
 
         foreach ($query->result() as $row)
            {
                $id = $row->id;
                $title = $row->title;
                $link_type = $row->link_type;
                $page_id = $row->page_id;
                $module_name = $row->module_name;
                $url = $row->url;
                $uri = $row->uri;
                $dyn_group_id = $row->dyn_group_id;
                $position = $row->position;
                $target = $row->target;
                $parent_id = $row->parent_id;
                $is_parent = $row->is_parent;
                $show_menu = $row->show_menu;
                $icon           = $row->icon;
 
 
                $has_subcats = TRUE;
                
                if (!empty($target)){
                    $target = 'target="'.$target.'"';
                }
                if (!empty($icon)){
                    $icon = '<i class="'.$icon.'"></i>';
                }
                if ($is_parent == TRUE)
                {
                    if ($this->is_active($id)){
                        $this->class_parent = 'class="treeview active"';
                    }else{
                        $this->class_parent = 'class="treeview"';
                    }
      $html_out .= "\t\t\t\t\t\t".'<li '.$this->class_parent.'><a href="#"'.' name="'.$title.'" id="'.$id.'" '.$target.' >'.$icon.'<span>'.$title.'</span><span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>';
 
                }
                else
                {
                    if ($this->is_active($id)){
                            $this->class_parent = 'class="active"';
                        }else{
                            $this->class_parent = '';
                        }
                   $html_out .= "\t\t\t\t\t\t".'<li '.$this->class_parent.'>'.anchor($url, $icon.'<span>'.$title.'</span>', 'name="'.$title.'" id="'.$id.'" '.$target);
                }
 
                // Recurse call to get more child submenus.
                   $html_out .= $this->get_childs($id);
        }
      $html_out .= '</li>' . "\n";
      $html_out .= "\t\t\t\t\t".'</ul>' . "\n";
      //$html_out .= "\t\t\t\t".'</div>' . "\n";
 
        return ($has_subcats) ? $html_out : FALSE;
 
    }
}
 
// ------------------------------------------------------------------------
// End of Dynamic_menu Library Class.
// ------------------------------------------------------------------------
/* End of file Dynamic_menu.php */
/* Location: ../application/libraries/Dynamic_menu.php */