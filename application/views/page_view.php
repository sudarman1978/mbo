<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Motomob SMT</title>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/css.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/dropdown-menu.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/menubar.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/aqtree3clickable.css" media="screen" />


  <!-- CORK CSS -->
  <link rel="icon" type="image/x-icon" href="<?=base_url()?>cork/src/assets/img/favicon.ico"/>

  <link href="<?=base_url()?>cork/layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
  <link href="<?=base_url()?>cork/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <link href="<?=base_url()?>cork/src/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url()?>cork/src/assets/css/light/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
  <link href="<?=base_url()?>cork/src/assets/css/dark/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
  <!--  -->


  <!-- import the calendar script -->
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>jscalendar/css/jscal2.css" />
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>jscalendar/css/border-radius.css" />
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>jscalendar/css/steel/steel.css" />
  <script type="text/javascript" src="<?=base_url()?>jscalendar/js/jscal2.js"></script>
  <script type="text/javascript" src="<?=base_url()?>jscalendar/js/lang/en.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <script language="JavaScript" src="<?=base_url()?>javascript/main.js"></script>
  <script language="JavaScript" src="<?=base_url()?>javascript/aqtree3clickable.js"></script>
  <script type='text/javascript' src="<?=base_url()?>javascript/jquery-1.2.3.min.js"></script>
  <script type='text/javascript' src="<?=base_url()?>javascript/menu.js"></script>
  <script type="text/JavaScript">
  <!--
  function MM_preloadImages() { //v3.0
    var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
  }
  //-->
  </script>

<!-- TinyMCE -->
<script type="text/javascript" src="<?=base_url()?>javascript/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		 mode : "textareas",
		 editor_selector : "mceEditor",
		theme : "advanced",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
    	});
</script>
<!-- /TinyMCE -->

<!-- Tabbed Menu -->
<script type="text/javascript" src="<?=base_url()?>javascript/ddtabmenu.js">

/***********************************************
* DD Tab Menu script-   Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<!-- CSS for Tab Menu #4 -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/ddcolortabs.css" />

<script type="text/javascript">

/***********************************************
* DD Tab Menu script- � Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

//Set tab to intially be selected when page loads:
//[which tab (1=first tab), ID of tab content to display (or "" if no corresponding tab content)]:
<? echo "var initialtab=[1, '$defsubform']" ?>;

//Turn menu into single level image tabs (completely hides 2nd level)?
var turntosingle=0 //0 for no (default), 1 for yes

//Disable hyperlinks in 1st level tab images?
var disabletablinks=0 //0 for no (default), 1 for yes


////////Stop editting////////////////

var previoustab=""

if (turntosingle==1)
document.write('<style type="text/css">\n#tabcontentcontainer{display: none;}\n</style>')

function expandcontent(cid, aobject){
if (disabletablinks==1)
aobject.onclick=new Function("return false")
if (document.getElementById && turntosingle==0){
highlighttab(aobject)
if (previoustab!="")
document.getElementById(previoustab).style.display="none"
if (cid!=""){
document.getElementById(cid).style.display="block"
previoustab=cid
}
}
}

function highlighttab(aobject){
if (typeof tabobjlinks=="undefined")
collectddtabs()
for (i=0; i<tabobjlinks.length; i++)
tabobjlinks[i].className=""
aobject.className="current"
}

function collectddtabs(){
var tabobj=document.getElementById("ddtabs")
tabobjlinks=tabobj.getElementsByTagName("A")
}

function do_onload(){
    var loadTime = window.performance.timing.domContentLoadedEventEnd-window.performance.timing.navigationStart;
    console.log('Page load time is '+ loadTime);
collectddtabs()
expandcontent(initialtab[1], tabobjlinks[initialtab[0]-1])
}

if (window.addEventListener)
window.addEventListener("load", do_onload, false)
else if (window.attachEvent)
window.attachEvent("onload", do_onload)
else if (document.getElementById)
window.onload=do_onload

function do_setfocus(){
document.getElementById('fld_btiid').focus() ;

if (document.getElementById('fld_btiid').value) {
alert(protect);
document.getElementById('fld_btp35').style.display = "block";
document.getElementById('fld_btp35-trigger').style.display = "block";
document.getElementById('fld_btp34').style.display = "block";
document.getElementById('fld_btp36').style.display = "block";
}
else {

document.getElementById('fld_btp35').style.display = "none";
document.getElementById('fld_btp35-trigger').style.display = "none";
document.getElementById('fld_btp34').style.display = "none";
document.getElementById('fld_btp36').style.display = "none";
}
}
<?
if ($fld_viewnm == '78000VEHICLE_TRACKING') {
echo "<body onload=\"javascript:setTimeout('location.reload(true);',5000);\">";
}
if ($fld_formnm == '78000JOB_ORDER_IMP') {
echo "window.onload=do_setfocus";
}
?>

</script>

<!--  /Tabbed Menu -->

</head>
<body class="layout-boxed">
<div id="wrapper">
<div id="headerAll">
<div id="header">
<table width=100%>
<input type='hidden' name='base_url' id='base_url' value='<?=base_url();?>'>
  <tr>
    <td>
<b><font color=white>MOTOMOB SISTEM MANAJEMEN TRANSPORTASI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
    </td>
     <td align=right>
<font color=white>Welcome <?=$ctnm ; ?>, You are login as <?=$usernm ; ?></font>
    </td>
  </tr>
</table>
</div>
<div id="navi">
<ul id="qm0" class="qmmc">
<?php
#ini_set('display_errors', '1');
#ini_set('display_startup_errors', '1');
#error_reporting(E_ALL);
include ("menubar.php");
$act = "";
?>

<li class="qmclear">&nbsp;</li></ul>
<script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</div>
<div id="apptitle">
<table width=100%>
    <tr>
	<td width=400 class="txtTitle">
	      <?
	      if ($this->uri->segment(2) == 'form') {
		    echo $fld_formlbl;
	      }
	      else if ($this->uri->segment(2) == 'view') {
		    echo $fld_viewlbl;
	      }
          else {
		    echo "Home";
	      }
	      ?>
	</td>
	<td align='center' width=180>
	      <?
	      if (isset($fld_formsearch) && $fld_formsearch != 0) {
		echo "<a href=javascript:showParBox()>Search Filter...</a>";
	      }

              if ($act == 'add') {
		echo "<b><font size='2'>1 record was added...</font></b>";
	      }

              if ($act == 'edit') {
		echo "<b><font color='green' size='2'>1 record was saved...</font></b>";
	      }

	      if ($act == 'copy') {
		echo "<b><font color='red' size='2'>1 record was copied...</font></b>";
	      }

	      ?>
	</td>

	<?
	      if ($this->uri->segment(2) == 'view')
	      {
	?>
	      <td align='right'>
	     <a href='javascript:print_action("<?echo $fld_viewnm?>","<? echo 'page' ;?>");'><img src="<?=base_url()?>images/icons/icon-export.png" border=0 width=20 height=20 title='Export this page'></a>

	     <a href='javascript:print_action("<?echo $fld_viewnm?>","<? echo 'all' ;?>");'><img src="<?=base_url()?>images/icons/icon-export-all.png" border=0 width=20 height=20 title='Export all'></a>
	</td>

	<?
	      }
	?>
	<?
	      if ($this->uri->segment(2) == 'form')
	      {
	?>
	<td  align='right' width=350>
	<? if ($fld_formcreate == 1) { ?>
	<a href="<?=base_url()?>index.php/page/form/<? echo $fld_formnm?>/add" onmouseove="document.app_add.src='<?=base_url()?>images/icons/icon-save.png'"
><img name="app_add" src="<?=base_url()?>images/icons/icon-add.png" border=0 width=22 height=22 title='Add Record'></a>
        <? } ?>
	<?
	 switch($mode)
	{
		case "add":
			?>
				<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Add This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title="Add Record"></a>
			<?
		break;

		case "edit":
			$fld_btstat=$aprvstatus->fld_btstat;
			if ($groupid!=1)
			{
				switch ($fld_btstat)
				{
					case 1:
						?>
							<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
						<?if ($fld_formdelete == 1) {?>	<a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 title='Delete Record'></a> <?}?>
						<?
					break;
					case 2:
						if  ($aprv_act['fld_aprvroleid'] = 3 && $aprv_act['status'] = 2 && ($aprv_act['status1'] != 2 || $aprv_act['status1'] = 2  ))
						{
							?>
								<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
						<?if ($fld_formdelete == 1) {?>		<a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 title='Delete Record' ></a>  <?}?>
							<?
						} else {
							echo '<img src="' . base_url() . 'images/icons/icon-save.png" border=0 width=22 height=22 >';
							echo '<img src="' . base_url() . 'images/icons/icon-delete.png" border=0 width=22 height=22 >';
						}

					break;
					case 3:
						if  ($aprv_act['fld_aprvroleid'] = 3 && $aprv_act['status'] = 2 && $aprv_act['status1'] != 2 )
						{
							?>
								<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
								<a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 title='Delete Record' ></a>
							<?
						} else {
							echo '<img src="' . base_url() . 'images/icons/icon-save.png" border=0 width=22 height=22 >';
							echo '<img src="' . base_url() . 'images/icons/icon-delete.png" border=0 width=22 height=22 >';
						}

					break;
					case 6:
						if  ($aprv_act['fld_aprvroleid'] = 3 && $aprv_act['status'] = 2 && $aprv_act['status1'] = 1 )
						{
							?>
								<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
				<?if ($fld_formdelete == 1) {?> <a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 title='Delete Record' ></a> <?}?>
							<?
						} else {
							echo '<img src="' . base_url() . 'images/icons/icon-save.png" border=0 width=22 height=22 >';
							echo '<img src="' . base_url() . 'images/icons/icon-delete.png" border=0 width=22 height=22 >';
						}
					break;
					default :
						if ($dbName=='tbl_bth'){
							echo '<img src="' . base_url() . 'images/icons/icon-save.png" border=0 width=22 height=22 >';
							echo '<img src="' . base_url() . 'images/icons/icon-delete.png" border=0 width=22 height=22 >';
						} else { ?>
							<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
                                        <a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 title='Delete Record'></a>

					<?	}
					break;
				}
			} else {
				?>
					<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
					<a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 title='Delete Record'></a>
				<?
			}
		break;
	}
	/*
	if ($mode == 'add')
	      {
	?>
	<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Add This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Add Record" onmouseover="MM_swapImage('Image2','','<?=base_url()?>images/icons/icon-add.png',1)" onmouseout="MM_swapImgRestore()"></a>
	<?
	      }
 	 //print $fld_formdelete;
	if (($mode == 'edit'  && $aprvstatus->fld_btstat != 3 && $groupid !=1) || ($groupid==1))
	{
	?>
	<a href='javascript:form_action("<?echo $fld_formnm?>","<? echo $mode ;?>");' onclick='return confirm("Are You Sure Want To Save This Record ?")'><img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 title='Save Record'></a>
	<?
	      }
	if ($mode == 'edit' && $aprvstatus->fld_btstat != 3 && $groupid != 1)
	      {
	?>
	<img src="<?=base_url()?>images/icons/icon-save.png" border=0 width=22 height=22 >
	<?
	      }

	?>
    <?

	      if ($mode == 'edit' && $aprvstatus->fld_btstat == 3 || $groupid != 1 && $fld_formdelete==1)
	      {
	?>
	<a href='<?=base_url()?>index.php/page/delete_process/<?echo "$fld_formnm/" . $this->uri->segment(5) ; ?>' onclick='return confirm("Are You Sure Want To Delete This Record ?")'><img src="<?=base_url()?>images/icons/icon-delete.png" border=0 width=22 height=22 ></a>
    <?
	     }
             if ($mode == 'edit' && $aprvstatus->fld_btstat == 3 && $groupid != 1 && $fld_formdelete == 1)
              {
              echo '<img src="' . base_url() . 'images/icons/icon-delete.png" border=0 width=22 height=22 >';
              }
	*/
	?>

	<a href="<?=base_url()?>index.php/page/view/<? echo $fld_formnm?>"><img src="<?=base_url()?>images/icons/icon-list.png" border=0 width=22 height=22 title='List Record' ></a>
	 <?
	      if (($mode == 'edit' && $fld_formcopy == 1) ||  $groupid == 1)
	      {
	?>
	<a href='javascript:form_action("<?echo $fld_formnm?>","copy");' onclick='return confirm("Are You Sure Want To Copy This Record ?")'><img src="<?=base_url()?>images/icons/icon-copy.png" border=0 width=22 height=22 title='Copy Record' ></a>
	</td>
    <?
	}
	?>
	<?
	}
	?>
	<td align='right'>
        <a href="<?=base_url()?>upload/ManualBookEXIM.pdf" target="_blank"><img src="<?=base_url()?>images/icons/icon-book.png" border=0 width=24 height=24 title="User Manual"></a>
        <a href="<?=base_url()?>index.php/page/view/78000CHANGE_PASSWORD/edit/<?=$this->session->userdata('userid') ?>"><img src="<?=base_url()?>images/icons/icon-change-password.png" border=0 width=28 height=28 title="Change Password"></a>
	<a href='javascript:logout();'><img src="<?=base_url()?>images/icons/icon-logout.png" border=0 width=28 height=28 title='Logout'></a>
	<a href="<?=base_url()?>index.php"><img src="<?=base_url()?>images/icons/icon-home.png" border=0 width=28 height=28 title='Home Page'></a>
	</td>
    </tr>
</table>
</div>
</div>



<div id="main" align='center'>

 <?
              if ($this->uri->segment(2) == 'form')
              {
 ?>
<br>
<div class="hfeed">
<article class="entry post">
<div class="entry-content">
<?
if(isset($content))
{
    $this->load->view($content);
}
else
{
?>
<br>
<br>
<br>
<br>
<br>
<?
}
?>
</div>
</article>
</div>

<?
}
else {
?>

<?
if(isset($content))
{
    $this->load->view($content);
}
else
{
?>
<br>
<br>
<br>
<br>
<br>
<?
}
?>
<?
}
?>
</div>
<div id="footerbar" align='center'>Copyright 2014, Dunia Express Transindo</div>
</div>
</body>  
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?=base_url()?>cork/src/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>cork/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?=base_url()?>cork/src/plugins/src/mousetrap/mousetrap.min.js"></script>
<script src="<?=base_url()?>cork/layouts/vertical-light-menu/app.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="<?=base_url()?>cork/src/plugins/src/apex/apexcharts.min.js"></script>
<script src="<?=base_url()?>cork/src/assets/js/dashboard/dash_1.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
</html>
