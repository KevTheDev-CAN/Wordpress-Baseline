<?php 
  function sassquatch_check_active_menu($menu_item) {
    $actual_link = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if ( $actual_link == $menu_item->url ) {
      return 'active';
    }
    return '';
  }

  function sassquatch_custom_menu($menuSlug) {
    $menu_name = $menuSlug; // specify custom menu slug
    $menu_list ='';
    $bool = false;
    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
      $menu = wp_get_nav_menu_object($locations[$menu_name]);
      $menu_items = wp_get_nav_menu_items($menu->term_id);
      $dropdownCount = 0;
      foreach( $menu_items as $menu_item ) {
        if( $menu_item->menu_item_parent == 0 ) {
          $class = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $menu_item->classes ), $menu_item) ) );
          $activeClass = sassquatch_check_active_menu($menu_item);
          if($activeClass){
            $menu_list .= '<li class="' . $activeClass . ' ' . '" data-slug="' . sanitize_title( $menu_item->title ) . '">';
          }else{
            $menu_list .= '<li data-slug="' . sanitize_title( $menu_item->title ) . '">';
          }
          if($class){
            $menu_list .= '<a href="' . $menu_item->url . '" class="' . $class . '">' . $menu_item->title . '</a>';
          }else{
            $menu_list .= '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>';
          }
          $menu_list .= '</li>';
        }
        // end <li>
      }
    } else {
      $menu_list = '<!-- no menu defined in location -->';
    }
    echo $menu_list;
  }

  function sassquatch_custom_icon_menu($menuSlug) {
    $menu_name = $menuSlug; // specify custom menu slug
    $menu_list = '';
    $bool = false;
    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
      $menu = wp_get_nav_menu_object($locations[$menu_name]);
      $menu_items = wp_get_nav_menu_items($menu->term_id);
      foreach( $menu_items as $menu_item ) {
        if( $menu_item->menu_item_parent == 0 ) {
          $parent = $menu_item->ID;
          $menu_array = array();
          foreach( $menu_items as $submenu ) {
            $iconClass = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $submenu->classes ), $submenu) ) );
            if( $submenu->menu_item_parent == $parent ) {
              $bool = true;
              $menu_array[] = '<li><a href="' . $submenu->url . '"><i class="' . $iconClass . '" aria-hidden="true"></i><span class="webaim-hidden">' . $submenu->title . '</span></a></li> ' ."\n";
            }
          }
          $iconClass = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $menu_item->classes ), $menu_item) ) );
          if( $bool == true && count( $menu_array ) > 0) {
            $menu_list .= '<li>';
            $menu_list .= '<span class="nav__parent-item">' . $menu_item->title . '</span>';
            $menu_list .= '<ul class="nav__menu">';
            $menu_list .= implode( "\n", $menu_array );
            $menu_list .= '</ul></li>';
          } else {
            $menu_list .= '<li>';
            $menu_list .= '<a href="' . $menu_item->url . '"><i class="' . $iconClass . '" aria-hidden="true"></i><span class="webaim-hidden">' . $menu_item->title . '</span></a>';
            $menu_list .= '</li>';
          }
        }
        // end <li>
      }
    } else {
      $menu_list = '<!-- no menu defined in location -->';
    }
    echo '<ul class="nav__social-icons">' . $menu_list . '</ul>';
  }

  function get_top_level_parent(){
    global $post;
    $parent = array_reverse(get_post_ancestors($post->ID));
    if(!empty($parent)){
      $first_parent = get_page($parent[0]);
    }else{
      $first_parent = $post;
    }
    if ($first_parent->post_title != '' && $first_parent->post_title != 'News' && $first_parent->post_title != 'Rapports et Nouvelles') {
      return $first_parent;
    }
  }

  function get_second_level_parent(){
    $parent = array_reverse(get_post_ancestors($post->ID));
    $first_parent = get_page($parent[1]);
    if($parent){
      return $first_parent;
    }
  }

  function sassquatch_custom_sidebar_menu() {
    global $post;
    $theMenu = '';
    $ancestors = get_ancestors($post->ID, 'page');
    if($ancestors){
      $top_parent = end($ancestors);
    }else{
      $top_parent = $post->ID;
    }
    $children = get_pages(array('parent' => $top_parent, 'sort_column' => 'menu_order')); // append the list of children pages to the same $children variable
    if ($children && $top_parent && !is_search()):
      $childCount = 0;
      foreach($children as $child) : 
        $childCount++;
      endforeach;
      if($childCount > 0) :
        $theMenu .= "<nav class='nav nav--sidebar'>";
        $theMenu .= "<h2><a href='" . get_the_permalink(get_top_level_parent()->ID) . "'>" . get_top_level_parent()->post_title . "</a></h2>";
        $theMenu .= "<ul class='nav__menu'>";
        foreach($children as $child) : 
          if($child->ID == $post->ID) :
            $activeClass = 'active';
          elseif($child->ID == wp_get_post_parent_id($post->ID)) :
            $activeClass = 'active-trail';
          else :
            $activeClass = 'inactive';
          endif;
          if(get_pages(array('parent' => $child->ID, 'sort_column' => 'menu_order'))){
            $activeClass .= ' has-dropdown';
          }
          $theMenu .= "<li class='" . $activeClass . "'>" . sassquatch_get_nested_menu($child) . "</li>";
        endforeach;
        $theMenu .= "</ul></nav>";
      endif; 
    endif;

    return $theMenu;
  }

  function sassquatch_get_nested_menu($parent){
    global $post;
    $parentID = $parent->ID;
    $submenu = '';

    $children = get_pages(array('parent' => $parent->ID, 'sort_column' => 'menu_order'));
    if($children) :
      $submenu .= "<a id='menu-" . $parent->ID . "-control' aria-controls='menu-" . $parent->ID . "' href='" . get_permalink($parent->ID) . "'>" . $parent->post_title . "<i class='fa fa-angle-right' aria-hidden='true'></i><span class='nav__border'></span></a>";
      $submenu .= "<div id='menu-" . $parent->ID . "' aria-labelledby='menu-" . $parent->ID . "-control' class='dropdown__content'>";
      $submenu .= "<ul class='nav__menu'>";
      foreach($children as $child) : 
        if($child->ID == $post->ID) :
          $activeClass = 'active';
        else :
          $activeClass = 'inactive';
        endif;
        $submenu .= "<li class='" . $activeClass . "'>" . sassquatch_get_nested_menu($child) . "</li>";
      endforeach;
      $submenu .= "</ul>";
      $submenu .= "</div>";
    else:
      $submenu .= "<a href='" . get_permalink($parent->ID) ."'>" . $parent->post_title . "<i class='fa fa-angle-right' aria-hidden='true'></i><span class='nav__border'></span></a>";
    endif;

    return $submenu;
  }
?>
