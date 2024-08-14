<?php
$menuData = array(
  'items' => array(),
  'parents' => array()
);
$isgroupid    = $this->session->userdata('group');
$isgroup_add  = ($this->session->userdata('group_add') == "") ? 99999 : $this->session->userdata('group_add');
// $result = mysql_query("SELECT t0.fld_menuid id, t0.fld_menuidp parentId, t0.fld_menunm name, t0.fld_menuurl url FROM tbl_menu t0
// left join tbl_acl t1 on t1.fld_aclval=t0.fld_menuid
// ORDER BY fld_menuorder");
$result = $this->db->query("
                      SELECT DISTINCT t0.fld_menuid id, t0.fld_menuidp parentId, t0.fld_menunm 'name', t0.fld_menuurl 'url',
                            IF(
                              ISNULL(ms4.fld_menunm),
                              if(
                              ISNULL(ms3.fld_menunm),
                              if(
                              ISNULL(ms2.fld_menunm),
                              if(
                              ISNULL(ms.fld_menunm),t0.fld_menunm,
                              CONCAT(ms.fld_menunm,' > ',t0.fld_menunm)
                              )
                              ,
                              CONCAT(ms2.fld_menunm,' > ',ms.fld_menunm,' > ',t0.fld_menunm)
                              )
                              ,
                              CONCAT(ms3.fld_menunm,' > ',ms2.fld_menunm,' > ',ms.fld_menunm,' > ',t0.fld_menunm)
                              )
                              ,CONCAT(ms4.fld_menunm,' > ',ms3.fld_menunm,' > ',ms2.fld_menunm,' > ',ms.fld_menunm,' > ',t0.fld_menunm)
                              ) 'root'
                      FROM tbl_menu t0
                      left join tbl_acl t1 on t1.fld_aclval=t0.fld_menuid
                      left join tbl_usergrp t2 on t2.fld_usergrpid=t1.fld_usergrpid
                      LEFT JOIN tbl_menu ms ON ms.fld_menuid = t0.fld_menuidp
                      LEFT JOIN tbl_menu ms2 ON ms2.fld_menuid = ms.fld_menuidp
                      LEFT JOIN tbl_menu ms3 ON ms3.fld_menuid = ms2.fld_menuidp
                      LEFT JOIN tbl_menu ms4 ON ms4.fld_menuid = ms3.fld_menuidp
                      where t1.fld_usergrpid in ($isgroupid,$isgroup_add)
                      ORDER BY t0.fld_menuorder")->result('array');

                      // log_message("my_error",$this->db->last_query());
foreach ($result as $menuItem) {
  $menuData['items'][$menuItem['id']] = $menuItem;
  $menuData['parents'][$menuItem['parentId']][] = $menuItem['id'];
}
// log_message("my_error",print_r($menuData, true));
$menu_root = array();
# <strong class="highlight">menu</strong> builder function, parentId 0 is the root
function buildMenu($parentId, $menuData, $expPass, $userIdm, $lastLogin)
{
  $CI =& get_instance();

  $html   = '';

  $parent = '';

  if (isset($menuData['parents'][$parentId]))
  {
    $menuClass  = ($parentId==0) ? ' class="<strong class="highlight">nav22</strong>" id="<strong class="highlight">nav11</strong>"' : '';
    $parent     = ($parentId==0) ? 0 : 1;
    # $html = "<ul id=''>\n";
    $result2 = $CI->db->query("SELECT *,
    (
    select tx.fld_menuidp FROM tbl_menu tx where tx.fld_menuid = t0.fld_menuidp order by fld_menuorder
    ) 'ppp'
    FROM tbl_menu t0 where t0.fld_menuidp = $parentId order by fld_menuorder")->result('array');

    foreach ($result2 as $row) {
      $idp = $row['fld_menuidp'];
      $ppp = $row['ppp'];
      # echo $row['fld_menuid']. "#" . $row['fld_menuidp'] . "#" .$row['fld_menunm']. "#" .$row['ppp'];
      # echo "<br>";
    }

    if ($idp== 0) {
      $html = "";
    } else {
      $html = "<ul>\n";
    }
      // $html_root   = '';
      $nos   = 0;
    foreach ($menuData['parents'][$parentId] as $itemId) {
      # submenu
      $result = $CI->db->query("select * from tbl_menu where fld_menuidp='$itemId'");
      if ($result->num_rows() > (int) 0 && $parentId != 0 ) {
        $subm  =' class=""';

      } else {
        $subm  ='';
      }
      # submenu end

     
        $url = $menuData['items'][$itemId]['url'];
      

      $menu = $parentId == 0 ? ' class="qmparent"' : ''; # class of main <strong class="highlight">menu</strong>
      $separator_menu = $parentId == 0 ? '' : ' > ';
      // array_push($menu_root, (object)[
      //   'url' => $url,
      //   'root' => $separator_menu.$menuData['items'][$itemId]['name'],
      // ]);
      // $menu_root['root'][] = $separator_menu.$menuData['items'][$itemId]['name'];
      $html_root = $menuData['items'][$itemId]['root'];

      $html .= '<li>' . "<a{$subm}{$menu} href=\"" .base_url()."{$url}\" title=\"{$menuData['items'][$itemId]['name']}\" dt-root=\"{$html_root}\" >{$menuData['items'][$itemId]['name']}</a>";
      # find child items recursively
      $html .= buildMenu($itemId, $menuData, $expPass, $userId, $lastLogin);
      $html .= '</li>';
      $nos++;
    }
      $html .= '</ul>';
  }
  return $html;
}

# output the <strong class="highlight">menu</strong>
echo buildMenu(0, $menuData, $this->session->userdata('exp_pass'), $this->session->userdata('userid'), $this->session->userdata('last_login'));

?>
