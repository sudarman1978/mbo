<div id='message' style="padding-top:5px;padding-bottom:5px;background-color: red; height='300px'; border:1px solid #000; visibility: hidden;" ></div>
<?
if ($mode == 'edit') {
  foreach($formfieldval as $rffval):
  endforeach;
  if (isset($rffval->fld_btstat) && ($rffval->fld_btstat == 3 || $rffval->fld_btstat == 6) ) {
    $ro = 1;
  }
}
?>
<html>
<form name="<? echo $fld_formnm; ?>" method='post' id="<? echo $fld_formnm;?>" action="<?=base_url();?>index.php/page/form_process">
<input type="hidden" name="fid" value="<? echo $fld_formid; ?>" />
<input type="hidden" name="fnm" value="<? echo $fld_formnm; ?>" />
<input type="hidden" name="act" value="<? echo $mode; ?>" id="act"/>
<input type="hidden" name="sf" value="<? echo $sf; ?>" id="act"/>
<?
if ($mode == 'edit' && $fld_tblid == 16 && $aprvdata->fld_bttyrule == 1){
?>
<div align='center'>
<?
 //print $rffval->fld_btstat;

if (($ro !=1 && ($aprv_act['fld_aprvroleid']==3 && ($rffval->fld_btstat == 2 || $rffval->fld_btstat == 6 )) || ($aprv_act['fld_aprvroleid'] == 3 && $aprv_act['status1']==1 && $aprv_act['status']==2  ))) {
?>
<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/aprv"  onclick= "cekMandatory () ; return confirm('Are you sure want to set Approve this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Approve">Approve</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/aprv"  onclick= "return confirm('Are you sure want to Reject this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Reject">Reject</a>
<?
}
?>

<?
//print $rffval->fld_btstat;
if (($ro !=1 && $aprv_act['fld_aprvroleid']  == 2 && $aprv_act['status']==2 && $aprv_act['status1']==1) || ($aprv_act['fld_aprvroleid']  == 2 && $aprv_act['status']==2 && $aprv_act['status1']==1 )) {
?>
<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/very"  onclick= "return confirm('Are you sure want to set Verified this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Verify">Verify</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?
}
?>


<?
if ($ro !=1 && $rffval->fld_btstat == 1 && $aprv_req == 1)
{
?>
<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/req"  onclick= "cekMandatory() ; return confirm('Are you sure want to Request Approval this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Request Approval"> Request Approval</a>
<?
}
?>
<?
//print "($rffval->fld_btstat == 3 && $aprv_act == 3 )";
if ($rffval->fld_btstat == 3 && $aprv_act['fld_aprvroleid']  == 3 )
{
?>
<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/rev"  onclick= "cekMandatory() ; return confirm('Are you sure want to Revise this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Revise"> Revise</a>
<?
}
?>

<?
###approval user finance bosb
#echo ":$userid<br>";
#echo ": $rffval->fld_btstat<br>";
if ($fld_formid ==307 && ($userid ==83 ||$userid == 337) && $rffval->fld_btstat !=3){
?>
 <div align='center'>
 <a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/aprv"  onclick= "cekMandatory () ; return confirm('Are you sure want to set Approve this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Approve">Approve</a>
 <?
}
?>
<?
if($fld_formid ==462 && $userid ==83 && $rffval->fld_btstat ==3){

?>
<div align='center'>
<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/rev"  onclick= "cekMandatory() ; return confirm('Are you sure want to Revise this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Revise"> Revise</a>
<?
}
?>
<?
if( $mode == 'edit' && ((($userid ==253 ||$userid == 1 || $userid == 234 || $userid == 212 || $userid ==232 ||$userid == 249)  && ($rffval->fld_bttyid == 41 || $rffval->fld_bttyid == 82 || $rffval->fld_bttyid == 46 ||$rffval->fld_bttyid == 51 || $rffval->fld_bttyid == 8 ||  $rffval->fld_bttyid == 9 || $rffval->fld_bttyid == 95 || $rffval->fld_bttyid == 73)) ||(($userid == 253 || $userid == 315) && $rffval->fld_bttyid == 42)) && $rffval->fld_btstat != 3 ){
?>
		<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/canc"  onclick= "return confirm('Are you sure want to Cancel this Transaction ?')"><img src="<?=base_url()?>images/action_stop.gif" width="14" height="14" border=0 title="Cancel">Cancel</a>
<?
}
if( $mode == 'edit' && (($groupid==1 || $groupid==5 || $groupid==6 || $groupid==7 || $groupid==36 || $groupid==38 || $groupid==63 || $groupid==75) && ($rffval->fld_bttyid == 2))){
?>
		<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/cancJOCJST"  onclick= "return confirm('Are you sure want to Cancel this Transaction ?')"><img src="<?=base_url()?>images/action_stop.gif" width="14" height="14" border=0 title="Cancel">Cancel</a>
<?
}
if( $mode == 'edit' && ($rffval->fld_bttyid == 114 && $rffval->fld_btstat != 3)){
?>
		<a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/deleteASM"  onclick= "return confirm('Are you sure want to Delete this Transaction ?')"><img src="<?=base_url()?>images/action_stop.gif" width="14" height="14" border=0 title="Delete">Delete</a>
<?
}
if( $mode == 'edit' && ($userid ==219 ||$userid == 1 || $userid == 249 || $userid == 284) && $rffval->fld_btstat != 3 && ($rffval->fld_bttyid == 41 && ($rffval->fld_btp38 == 2 || $rffval->fld_btp38 == 3))){
?>

                <a href="<?=base_url()?>index.php/page/setApproval/<?=$rffval->fld_btid?>/aprv"  onclick= "cekMandatory () ; return confirm('Are you sure want to set Approve this record ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="Approve">Approve</a>
                <a href="<?=base_url()?>index.php/page/view/78000INVOICE_AGING3?fld_baidc=<?=$rffval->fld_baidc?>&fgid=<?=$rffval->fld_btp38?>&id=<?=$rffval->fld_btid?>"  onclick= "return confirm('Are you sure want to Update this Transaction ?')"><img src="<?=base_url()?>images/application_approval.gif" width="14" height="14" border=0 title="revise1">Update Detail</a>
<?
 }


?>
</div>
<?
}
?>
<br>
<table border="0" cellspacing="0" cellpadding="0" width=100%>
    <tr>
    <td style="border-right: solid 1px black" width='85%'>
<table border="0" cellspacing="0" cellpadding="0" align='center'>
<?
foreach($formfield as $rff):
if ($rff->fld_formfieldroauth != ""){
  $grp = "$groupid,$group_add";
  $arr_group = explode(',', $grp);
  $x =  count($arr_group);
  $ro = 0;
  foreach ($arr_group as $value) {
    $ro_Auth = preg_replace("/groupid/is",$value,$rff->fld_formfieldroauth);
    $ro_Auth = $this->db->query("select if($ro_Auth,1,0) 'readonly'");
    $ro_Auth = $ro_Auth->row();
    if($ro_Auth->readonly == 1) {
      $ro = $ro + 1;
    }


  }
  // if($ro > 0) {
   if($ro == $x) {
    $rff->fld_formfieldronly = 1;
  }
}

if ($rff->fld_formfieldhideauth != ""){
  $grp = "$groupid,$group_add";
  $arr_group = explode(',', $grp);
  $x =  count($arr_group);
  $hide = 0;
  foreach ($arr_group as $value) {
    $hide_auth = preg_replace("/groupid/is",$value,$rff->fld_formfieldhideauth);
    $hide_auth = $this->db->query("select if($hide_auth,1,0) 'hide'");
    $hide_auth = $hide_auth->row();
    if($hide_auth->hide == 1) {
      $hide = $hide + 1;
    }


  }
#$hide_auth = preg_replace("/ctid/is",$ctid,$hide_auth);
  #$hide_auth = $this->db->query("select if($hide_auth,1,0) 'hide'");
  #$hide_auth = $hide_auth->row();
  if($hide == $x) {
    $rff->fld_formfieldshow = 0;
  }
}



?>
  <? if ($rff->fld_formfieldmdtr == 1)
     {
	$class = 'mandatory';
     }	else
     {
	  if ($rff->fld_formfieldtag == "text" || $rff->fld_formfieldtag == "date" ) {
	  $class = 'inputBox1';
	  }
	  else
	  {
	$class = 'default';
	  }
     }
     $ffname = $rff->fld_formfieldnm;
	      if ($mode == 'edit')
	      {
		$value = $rffval->$ffname;
	      } else
	      {
		$value =  $ffgval[$ffname];
	      }
	      if ($value == '') {

	      $value = $rff->fld_formfielddval;
		  if (preg_match('/^!/',$value)) {
		    $rvalue = substr($value,1);
		    $value = $this->session->userdata($rvalue);
		  }
		  if ($value == 'now!') {
		    $value = date ("Y-m-d H:i:s");
		  }
	      }

  ?>
  <?
    if ($rff->fld_formfieldhdr != '') {
  ?>
  <tr height='31' valign='bottom' cellspacing="2" cellpadding="2">
    <td colspan = "3" style="border-bottom-style:solid ; border-bottom-width:2px ;"><? echo $rff->fld_formfieldhdr; ?></td>
  <tr>
  <?
    }

    if ($rff->fld_formfieldshow != 1)
		      {
echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';
		      }
	      else
	      {
  ?>
<? if ($rff->fld_formfieldrbreak == 1) {
?>
<?
}
?>
    <td style="border-bottom: solid 1px black" nowrap><? if ($rff->fld_formfieldmdtr == 1) {?><span style="color: rgb(255, 0, 0);">*</span><?}?><?=$rff->fld_formfieldlbl?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
     <td nowrap>
	   <?
	      switch($rff->fld_formfieldtag)
	      {
		  case "text":
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => $value,
		    'maxlength'   => '100',
		    'size'        => $rff->fld_formfieldwidth,
		    'class'       => $class,
		    );
		    if ($rff->fld_formfieldronly == 1) {
		      $input_tag['readonly'] = 1;
		    }

		    if ($fld_formtyid != 1) {
                      $js = $rff->fld_formfieldjs . ' onkeyup=convertToUpper(this) ';
                    }
                    else {
                      $js = $rff->fld_formfieldjs;
                    }


		    echo form_input($input_tag,'',$js);
		    break;

                    case "money":
                    $value_dsc = '';
                    if ($value !='') {
                      $value_dsc = number_format($value,2,'.',',');
                    }
                    $input_tag = array
                    (
                    'name'        => $rff->fld_formfieldnm . "_dsc",
                    'id'          => $rff->fld_formfieldnm . "_dsc",
                    'value'       => $value_dsc,
                    'maxlength'   => '100',
                    'size'        => '15',
                    'class'       => $class,
                    );
                    if ($rff->fld_formfieldronly == 1) {
                      $input_tag['readonly'] = 1;
                    }
                   
                      $js = $rff->fld_formfieldjs . ' onkeyup=formatNumber(this) ';
                   

                    echo form_input($input_tag,'',$js);
                    echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';

                    break;


		    case "password":
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => $value,
		    'maxlength'   => '100',
		    'size'        => $rff->fld_formfieldwidth,
		    'class'       => $class,
		    );
		    if ($rff->fld_formfieldronly == 1) {
		    $input_tag['readonly'] = 1;
		    }
		    $js = $rff->fld_formfieldjs;
		    echo form_password($input_tag,'',$js);
		    break;

		    case "lookup":
		    $fld_querysql = $rff->fld_querysql . " having id = '$value'";
		    $gbind = $this->view->getbind($rff->fld_queryid);
		    $dbind = array();
		    if (count($gbind) > 0) {
		      $ctbind = 0;
		      foreach ($gbind as $rbind) {
			$bindname = $rbind->fld_querybindnm;
			$bindval =  $rbind->fld_querybindval;
			if (preg_match('/^!/',$bindval)) {
			  $bindval = substr($bindval,1);
			  $bindval =  $this->session->userdata($bindval);
			}
			if (substr($bindval,0,4) == 'fld_') {
			  #$bindval = '%';
                          if ($rffval->$bindval != "") {
			    $bindval = $rffval->$bindval;
			  }
			  else {
			    $bindval = '%';
			  }
			  $ctbind = $ctbind +1;
			  ${'bindfield' . $ctbind} = $bindname;
			}

			if ($this->input->get($bindname)) {
			  $bindval =  $this->input->get($bindname);
			}
		      $dbind [] =  $bindval;

		      }

		    }

		    $value_dsc = '';
		    $query = $this->db->query($fld_querysql,$dbind);
		    $lquery = $query->row();
		    if (count($lquery) > 0) {
		      $value_dsc = $lquery->name;
		    }
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm . "_dsc",
		    'id'          => $rff->fld_formfieldnm . "_dsc",
		    'value'       => $value_dsc,
		    'maxlength'   => '100',
		    'size'        => $rff->fld_formfieldwidth,
		    'class'       => $class,
		    );
		    if ($rff->fld_formfieldronly == 1) {
		    $input_tag['readonly'] = 1;
		    echo form_input($input_tag,'',$js_lookup);
		    }
		    else {
		    $js_lookup = 'onChange="popup_selector(12,13,document.getElementById(\'' . $rff->fld_formfieldnm . '_dsc\').value,\'' . base_url() . '\',\'' . $rff->fld_querynm .  '\',\'' . $rff->fld_formfieldnm . '\',' . '\'' . $bindfield1 . '\',\'' . $bindfield2 . '\',\'' .$bindfield3 . '\',\'' . $bindfield4 . '\',\'' . $bindfield5 . '\')"';
		    echo form_input($input_tag,'',$js_lookup);
		    echo '<a href="javascript:void(1)" onclick= "popup_selector(12,13,document.getElementById(\'' . $rff->fld_formfieldnm . '_dsc\').value,\'' . base_url() . '\',\'' . $rff->fld_querynm .  '\',\'' . $rff->fld_formfieldnm . '\',' . '\'' . $bindfield1 . '\',\'' . $bindfield2 . '\',\'' .$bindfield3 . '\',\'' . $bindfield4 . '\',\'' . $bindfield5 . '\')"><img src="' . base_url() .'/images/filefind.png" width="14" height="14" border="0"></a>';
		    }

		    echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';

                    break;

                    case "lookup2":
		    $fld_querysql = $rff->fld_querysql . " having id = '$value'";
		    $gbind = $this->view->getbind($rff->fld_queryid);
		    $dbind = array();
		    if (count($gbind) > 0) {
		      $ctbind = 0;
		      foreach ($gbind as $rbind) {
			$bindname = $rbind->fld_querybindnm;
			$bindval =  $rbind->fld_querybindval;
			if (preg_match('/^!/',$bindval)) {
			  $bindval = substr($bindval,1);
			  $bindval =  $this->session->userdata($bindval);
			}
			if (substr($bindval,0,4) == 'fld_') {
			  #$bindval = '%';
                          if ($rffval->$bindval != "") {
			    $bindval = $rffval->$bindval;
			  }
			  else {
			    $bindval = '%';
			  }
			  $ctbind = $ctbind +1;
			  ${'bindfield' . $ctbind} = $bindname;
			}

			if ($this->input->get($bindname)) {
			  $bindval =  $this->input->get($bindname);
			}
		      $dbind [] =  $bindval;

		      }

		    }

		    $value_dsc = '';
		    $query = $this->db->query($fld_querysql,$dbind);
		    $lquery = $query->row();
		    if (count($lquery) > 0) {
		      $value_dsc = $lquery->name;
		    }
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm . "_dsc",
		    'id'          => $rff->fld_formfieldnm . "_dsc",
		    'value'       => $value_dsc,
		    'maxlength'   => '100',
		    'size'        => $rff->fld_formfieldwidth,
		    'class'       => $class,
		    );
		    if ($rff->fld_formfieldronly == 1) {
		    $input_tag['readonly'] = 1;
		    echo form_input($input_tag,'',$js_lookup);
		    }
		    else {
		    $js_lookup = 'onChange="popup_selector2(document.getElementById(\'' . $rff->fld_formfieldnm . '_dsc\').value,\'' . base_url() . '\',\'' . $rff->fld_querynm .  '\',\'' . $rff->fld_formfieldnm . '\',' . '\'' . $bindfield1 . '\',\'' . $bindfield2 . '\',\'' .$bindfield3 . '\',\'' . $bindfield4 . '\',\'' . $bindfield5 . '\',\'' . $bindfield6 . '\',\'' .$bindfield7 . '\',\'' . $bindfield8 . '\',\'' . $bindfield9 . '\')"';
		    echo form_input($input_tag,'',$js_lookup);
		    echo '<a href="javascript:void(1)" onclick= "popup_selector2(document.getElementById(\'' . $rff->fld_formfieldnm . '_dsc\').value,\'' . base_url() . '\',\'' . $rff->fld_querynm .  '\',\'' . $rff->fld_formfieldnm . '\',' . '\'' . $bindfield1 . '\',\'' . $bindfield2 . '\',\'' .$bindfield3 . '\',\'' . $bindfield4 . '\',\'' . $bindfield5 . '\',\'' . $bindfield6 . '\',\'' .$bindfield7 . '\',\'' . $bindfield8 . '\',\'' . $bindfield9 . '\')"><img src="' . base_url() .'/images/filefind.png" width="14" height="14" border="0"></a>';
		    }

		    echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';

		    break;

		    case "query":
  ##Change Node to primary key
		    if ($mode == 'edit') {
		      $node = $rffval->fld_btid;
		    }
		    else {
		      $node = 0;
		    }
		    $fld_querysql = str_replace("!node",$node,$rff->fld_querysql);
		    $value_dsc = '';
		    $query = $this->db->query("$fld_querysql");
		    $lquery = $query->row();
		    if (count($lquery) > 0) {
		      $value_dsc = $lquery->result;
		    }
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm . "_dsc",
		    'id'          => $rff->fld_formfieldnm . "_dsc",
		    'value'       => $value_dsc,
		    'maxlength'   => '100',
		    'size'        => $rff->fld_formfieldwidth,
		    'class'       => 'lookup',
		    );

		    $input_tag['readonly'] = 1;
		    echo form_input($input_tag,'',$js_lookup);

		    break;

		    case "info":
		    $fld_querysql = $rff->fld_querysql;
		    $query = $this->db->query("$fld_querysql");
		    $lquery = $query->row();

		    echo $lquery->info ;
		    break;

		    case "autono":
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => $value,
		    'maxlength'   => '100',
		    'size'        => $rff->fld_formfieldwidth,
		    'class'       => $class,
		    );
		    if ($rff->fld_formfieldronly == 1) {
		    $input_tag['readonly'] = 1;
		    }
		    $js = $rff->fld_formfieldjs;
		    echo form_input($input_tag,'',$js);
		    break;

		    case "file":
                    $input_tag = array
                    (
                    'name'        => $rff->fld_formfieldnm,
                    'id'          => $rff->fld_formfieldnm,
                    'value'       => $value,
                    'maxlength'   => '100',
                    'size'        => $rff->fld_formfieldwidth,
                    'class'       => $class,
                    );
                    $input_tag['readonly'] = 1;
                    $js = $rff->fld_formfieldjs;
                    echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';
                    echo '<a href="javascript:void(1)" onclick= "unHideFile(\'' . $rff->fld_formfieldnm . '\',\'upload\')"><img src="' . base_url() .'/upload/file_attach.png" width="48" height="48" border="0" align="left" style="margin-top:5px"></a>';

                    echo "<div id='" . $rff->fld_formfieldnm . "_attach' style='margin-top:5px;display: none'>";
                    echo anchor_popup("upload?type=file&val=$rff->fld_formfieldnm", Upload , $atts);
                    echo "<br>";
                    if($value == "") {
                      echo "View/Download";
                    } else {
                      echo anchor(base_url() . "upload/$value","View/Download", attributes);
                    }
                    echo "<br>";
                    if($value == "") {
                      echo "Delete";
                    } else {
                      echo '<a href="javascript:void(1)" onclick= "unHideFile(\'' . $rff->fld_formfieldnm . '\',\'delete\')">Delete</a>';
                    }
                    echo "</div>";
                    break;

                    case "photo":
                    $input_tag = array
                    (
                    'name'        => $rff->fld_formfieldnm,
                    'id'          => $rff->fld_formfieldnm,
                    'value'       => $value,
                    'maxlength'   => '100',
                    'size'        => $rff->fld_formfieldwidth,
                    'class'       => $class,
                    );
                    $input_tag['readonly'] = 1;
                    $js = $rff->fld_formfieldjs;
                    if ( $value == "") {
						$value = "no_photo.jpg";
					}
                    echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';
                    echo anchor_popup("upload?type=photo&val=$rff->fld_formfieldnm", "<img src='" . base_url() . "/upload/photo/$value' width='110' height='140' border='0'>", $atts);
                    break;

		    case "date":
		     $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => $value,
		    'maxlength'   => '15',
		    'size'        => '9',
		    'class'       => $class,
		    );
		    if ($rff->fld_formfieldronly == 1) {
		      $input_tag['readonly'] = 1;
		      echo form_input($input_tag,'',$js);
		    }
		    else {
		      $js = $rff->fld_formfieldjs;
		      echo form_input($input_tag,'',$js);
		      echo '<a href="javascript:void(0)"  id="'. $rff->fld_formfieldnm .'-trigger" ><img src="' . base_url() .'/images/calendar.jpg" width="14" height="14" border="0"></a>';
		      echo '<script>
		      Calendar.setup({
		      dateFormat : "%Y-%m-%d",
                      trigger    : "' . $rff->fld_formfieldnm . '-trigger",
		      inputField : "' . $rff->fld_formfieldnm .'",
		      onSelect   : function() { this.hide() }
		      });
		      </script>';
		    }
		    break;

                    case "datetime":
                     $input_tag = array
                    (
                    'name'        => $rff->fld_formfieldnm,
                    'id'          => $rff->fld_formfieldnm,
                    'value'       => $value,
                    'maxlength'   => '15',
                    'size'        => '15',
                    'class'       => $class,
                    );
                    if ($rff->fld_formfieldronly == 1) {
                      $input_tag['readonly'] = 1;
                      echo form_input($input_tag,'',$js);
                    }
                    else {
                      $js = $rff->fld_formfieldjs;
                      echo form_input($input_tag,'',$js);
                      echo '<a href="javascript:void(0)"  id="'. $rff->fld_formfieldnm .'-trigger" ><img src="' . base_url() .'/images/calendar.jpg" width="14" height="14" border="0"></a>';
                      echo '<script>
                      Calendar.setup({
                      dateFormat : "%Y-%m-%d %H:%M",
                      showTime   : "True",
                      trigger    : "' . $rff->fld_formfieldnm . '-trigger",
                      inputField : "' . $rff->fld_formfieldnm .'",
                      onSelect   : function() { this.hide() }
                      });
                      </script>';
                    }
                    break;

		     case "getnow":
                     $input_tag = array
                    (
                    'name'        => $rff->fld_formfieldnm,
                    'id'          => $rff->fld_formfieldnm,
                    'value'       => $value,
                    'maxlength'   => '15',
                    'size'        => '15',
                    'class'       => $class,
                    );
                    if ($rff->fld_formfieldronly == 1) {
                      $input_tag['readonly'] = 1;
                      echo form_input($input_tag,'',$js);
                    }
                    else {
                      $js = $rff->fld_formfieldjs;
                      echo form_input($input_tag,'',$js);
                      echo '<a href="javascript:getnow(\'' . $rff->fld_formfieldnm . '\')"><img src="' . base_url() .'/images/calendar.jpg" width="14" height="14" border="0"></a>';	   }
                      echo "<script>
                      function getnow(field) {
                      var d = new Date,
                      dformat = [
		      d.getFullYear(),
		      d.getMonth()+1,
		      d.getDate()].join('-')+
		      ' ' +
		      [d.getHours(),
		      d.getMinutes(),
		      d.getSeconds()].join(':');
                      document.getElementById(field).value = dformat;
                      }
                      </script>";

                    break;

		    case "time":
                    echo "<select>";
		    for($i=0; $i<60; $i++) {
                      $selected = '';
                      if ($birthdayYear == $i) $selected = ' selected="selected"';
                      echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n" ;
                    }
                    echo "</select>";



                    break;

		    case "textarea":
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => $value,
		    'cols'        => $rff->fld_formfieldwidth,
		    'rows'	  => 5,
		    'class'       => $class,
		    );
		    if ($fld_formtyid != 1) {
		      $js = $rff->fld_formfieldjs . ' onkeyup=convertToUpper(this) ';
		    }
		    else {
		      $js = $rff->fld_formfieldjs;
		    }
		    if ($rff->fld_formfieldronly == 1) {
		    $input_tag['readonly'] = 1;
		    }
                    echo "<textarea name='$rff->fld_formfieldnm' cols='$rff->fld_formfieldwidth' rows='5' id='$rff->fld_formfieldnm' class='$class' $js>$value</textarea>";

		    break;

		    case "texteditor":
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => $value,
		    'cols'        => $rff->fld_formfieldwidth,
		    'class'       => 'mceEditor',
		    );
		    echo form_textarea($input_tag);
		    break;

		    case "dropdown":
		    if ($rff->fld_formfieldronly == 1) {
		      $fld_querysql = $rff->fld_querysql;
		      $value_dsc = '';
		      $query = $this->db->query("$fld_querysql having id = '$value'");
		      $lquery = $query->row();
		      if (count($lquery) > 0) {
			$value_dsc = $lquery->name;
		      }
		      $input_tag = array
		      (
		      'name'        => $rff->fld_formfieldnm . "_dsc",
		      'id'          => $rff->fld_formfieldnm . "_dsc",
		      'value'       => $value_dsc,
		      'maxlength'   => '100',
		      'size'        => $rff->fld_formfieldwidth,
		      'class'       => 'lookup',
		      );
		      $input_tag['readonly'] = 1;
		      echo form_input($input_tag,'',$js_lookup);
                      echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';

		    }
		    else {
		      $fld_querysql = $rff->fld_querysql;
		      $gbind = $this->view->getbind($rff->fld_queryid);
		    $dbind = array();
		    if (count($gbind) > 0) {
		      $ctbind = 0;
		      foreach ($gbind as $rbind) {
			$bindname = $rbind->fld_querybindnm;
			$bindval =  $rbind->fld_querybindval;
			if (preg_match('/^!/',$bindval)) {
			  $bindval = substr($bindval,1);
			  $bindval =  $this->session->userdata($bindval);
			}
			if (substr($bindval,0,4) == 'fld_') {
			  #$bindval = '%';
                          if ($rffval->$bindval != "") {
			    $bindval = $rffval->$bindval;
			  }
			  else {
			    $bindval = '%';
			  }
			  $ctbind = $ctbind +1;
			  ${'bindfield' . $ctbind} = $bindname;
			}

			if ($this->input->get($bindname)) {
			  $bindval =  $this->input->get($bindname);
			}
		      $dbind [] =  $bindval;

		      }

		    }
		      $gopt = $this->db->query($fld_querysql,$dbind);
		      $ddsize = $rff->fld_formfieldwidth;
		      if ($gopt->num_rows() > 0)
		      {
			$lopt = $gopt->result();
		      }
		      $options = array();
		      $options[0]= '[--Select--]';
		      foreach ($lopt as $ropt)
		      {
				  $options[$ropt->id]= $ropt->name;
		      }

		      echo form_dropdown($rff->fld_formfieldnm, $options, $value,"id='$rff->fld_formfieldnm' class='$class' ". $rff->fld_formfieldjs." ");
		      }

		      break;
		      case "bolean":

		       if ($rff->fld_formfieldronly == 1) {

			if ($value == 1) {
                        echo '<input type="radio"' . $rff->fld_formfieldjs . 'name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="1"' . 'checked' .'> Yes';
                        }
                        else {
                        echo '<input type="radio" '. $rff->fld_formfieldjs . 'name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="0"' . 'checked' . '> No';
                      }


			}
			else {
			if ($value == 1) {
		      	echo '<input type="radio"' . $rff->fld_formfieldjs . 'name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="1"' . 'checked' .'> Yes <input type="radio" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="0"' . $rff->fld_formfieldjs . '> No';
		      	}
		      	else {
		      	echo '<input type="radio" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="1"'  . $rff->fld_formfieldjs . '> Yes <input type="radio" '. $rff->fld_formfieldjs . 'name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="0"' . 'checked' . '> No';
		      }
		}
		    break;

		    case "custom":
		    echo "";
		    break;

		     case "checkbox":
		    $input_tag = array
		    (
		    'name'        => $rff->fld_formfieldnm,
		    'id'          => $rff->fld_formfieldnm,
		    'value'       => 1,
		    'checked'     => ($value == 1 ) ? TRUE : FALSE,
		     );
		    echo form_checkbox($input_tag);
		    break;

		    case "radio":
		    $fld_querysql = $rff->fld_querysql;
		    $gradio = $this->db->query("$fld_querysql");
		    $ddsize = $rff->fld_formfieldwidth;
		    if ($gradio->num_rows() > 0)
		    {
		      $lradio = $gradio->result();
		    }
		    $radioions = array();
		    foreach ($lradio as $rradio)
		    {
		      if ($rradio->id == $value) {
			$status = 'checked';
		      }
		      else {
			$status = 'unchecked';
		      }
		      echo "<input type='radio' name='$rff->fld_formfieldnm' value='$rradio->id'" .  $status . "> $rradio->name ";
		    }

		    break;

                    case "periode":
                    $startDate = strtotime(date('Y-m-d')  . '-15 month');
                    $currentDate   = strtotime(date('Y-m-d'));
                    if ($rff->fld_formfieldronly == 1) {
                        $value_dsc = $value;
                      $input_tag = array
                      (
                      'name'        => $rff->fld_formfieldnm . "_dsc",
                      'id'          => $rff->fld_formfieldnm . "_dsc",
                      'value'       => $value_dsc,
                      'maxlength'   => '100',
                      'size'        => $rff->fld_formfieldwidth,
                      'class'       => 'lookup',
                      );
                      $input_tag['readonly'] = 1;
                      echo form_input($input_tag,'',$js_lookup);
                      echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" id="' . $rff->fld_formfieldnm . '" value="' . $value . '" />';

                    }
                    else {
                      #$fld_querysql = $rff->fld_querysql;
                      #$gopt = $this->db->query($fld_querysql,$dbind);
                      $ddsize = $rff->fld_formfieldwidth;
                      $options = array();
                      $options[0]= '[--Select--]';
                      while ($currentDate >= $startDate) {
                        $options[date('Y-m',$currentDate)]= date('Y-m',$currentDate);
                        $currentDate = strtotime( date('Y/m/01/',$currentDate).' -1 month');
                      }
                      echo form_dropdown($rff->fld_formfieldnm, $options, $value,"id='$rff->fld_formfieldnm' class='$class'");
                      }

                      break;

                    case "getuser":
                     $input_tag = array
                    (
                    'name'        => $rff->fld_formfieldnm,
                    'id'          => $rff->fld_formfieldnm,
                    'value'       => $value,
                    'maxlength'   => '15',
                    'size'        => '15',
                    'class'       => $class,
                    );
                    if ($rff->fld_formfieldronly == 1) {
                      $input_tag['readonly'] = 1;
                      echo form_input($input_tag,'',$js);
                      echo '<a href="javascript:getuser(\'' . $rff->fld_formfieldnm . '\')"><img src="' . base_url() .'/images/icons/icon-user.png" width="14" height="14" border="0"></a>';
                    }
                    else {
                      $input_tag['readonly'] = 1;
                      $js = $rff->fld_formfieldjs;
                      echo form_input($input_tag,'',$js);
                      echo '<a href="javascript:getuser(\'' . $rff->fld_formfieldnm . '\')"><img src="' . base_url() .'/images/icons/icon-user.png" width="14" height="14" border="0"></a>';       }
                      $userid = $this->session->userdata('ctnm');
                      echo "<script>
                      function getuser(field) {
                      document.getElementById(field).value = '$userid';
                      }
                      </script>";

                    break;

	      }
	      }
?>
  </td>
<? if ($rff->fld_formfieldrbreak == 1) {
?>
  </tr>
  <tr>
<?
}
else
{
?>
<td>&nbsp;&nbsp;&nbsp;</td>
<? } ?>
<?php endforeach;?>
</table>
</td>
<td valign='top' >
      <table>
<?  if ($mode == 'edit' && isset($printout)) { ?>
	<tr>
	  <td>&nbsp;&nbsp;&nbsp;<b>Links :</b></td>
	</tr>
	<tr>
	  <td>
	<?
	    foreach ($printout as $rprintout)
	    {
              if($rprintout->fld_formprintsts == 1) {
	        echo "- " . "<a href=" . base_url() . "$rprintout->fld_formprinturl/$rffval->fld_btid  target='_blank'>" . $rprintout->fld_formprintnm  . "</a><br>";
              } else {
               $prn_url = str_replace('$node',$rffval->fld_btid,"$rprintout->fld_formprinturl");
               echo "- " . "<a href='" . base_url() . "$prn_url'>" . $rprintout->fld_formprintnm  . "</a><br>";
              }

	    }

	    	if($fld_formnm == '78000INVOICE'){
        echo "- " . "<a href='javascript:void(0)' onclick='reviseInvoice($rffval->fld_btid)'>Revise After Closing</a><br>";
      }

      if($fld_formnm == '78000INVOICE'){
        echo "- " . "<a href='javascript:void(0)' onclick='reviseInvoiceToday($rffval->fld_btid)'>Revise Invoice Monthly</a><br>";
      }

      if($fld_formnm == '78000POSTING_COMMISSION' && $rffval->fld_btid == 1366361){

        echo "- " . "<a href='" . base_url() . "index.php/page/automaticDriverBpjs/$rffval->fld_btid/' >BPJS Updater</a><br>";
      

        //echo "- " . "<a href='" . base_url() . "index.php/page/view/78000TRANSFER_BRI_API/$rffval->fld_btid' >Transfer BRI</a><br>";


    }
	?>

	  </td>
	</tr>
	<tr>
	  <td>&nbsp;</td>
	</tr>
<? } ?>
<?  if ($mode == 'edit' && isset($futrans)) { ?>
	<tr>
	  <td>&nbsp;&nbsp;&nbsp;<b>Follow Up Transaction :</b></td>
        <input type='hidden' id='btid' name='btid'>
        <input type='hidden' id='tynextid' name='tynextid'>
        <input type='hidden' id='tynextform' name='tynextform'>
        <input type='hidden' id='nextformid' name='nextformid'>
	</tr>
	<tr>
	  <td>
	<?
	    foreach ($futrans as $rfutrans)
	    {
	      if ($rfutrans->fld_count < $rfutrans->fld_workflowmax) {
		    echo "- " . "<a href='javascript:fup_action($rfutrans->fld_btid,$rfutrans->fld_bttyid,\"$rfutrans->fld_bttyform\",$rfutrans->nextformid,\"$fld_formnm\",\"fup\");' onclick= \" return confirm('Are you sure want to make follow up Transaction ?')\" " . ">" . $rfutrans->fld_bttynm . "</a>" . " (" . $rfutrans->fld_count . ")<br>";
	      }
	      else {
		    echo "- " .  $rfutrans->fld_bttynm . " (" . $rfutrans->fld_count . ")<br>";
	      }
	    }
	?>

	  </td>
	</tr>
	<? } ?>
         <tr>
          <td>&nbsp;&nbsp;&nbsp;<b>Transaction Map :</b></td>
        </tr>
        <tr>
          <td>
		 <?
            foreach ($trans_map as $rtrans_map)
            {
             if($rtrans_map->level == 'up') {
             echo "  - " . "<a href='" . base_url() . "index.php/page/form/" . $rtrans_map->fld_bttyform . "/edit/" . $rtrans_map->fld_btid . "'>" . $rtrans_map->fld_btno . "</a><br>";

            }
            }
        ?>
          </td>
        <tr>
	<td>- <?=$rffval->fld_btno;?></td>
	</tr>
        <tr>
	<td>
                 <?
            foreach ($trans_map as $rtrans_map)
            {
             if($rtrans_map->level == 'down') {
             echo "  - " . "<a href='" . base_url() . "index.php/page/form/" . $rtrans_map->fld_bttyform . "/edit/" . $rtrans_map->fld_btid . "'>" . $rtrans_map->fld_btno . "</a><br>";

            }
            }
        ?>
          </td>
       </tr>
	<tr>
	  <td><b>Approval Rule :</b></td>
	</tr>
	<?
	   $sql="select concat_ws(', ',a.fld_username, a.status,a.fld_aprvtktmoddt)status
		from(
		select a.fld_btid, b.fld_aprvruleroleord, b.fld_btid id, b.fld_userid, b.fld_aprvtktstat, b.fld_aprvtktmoddt, c.fld_username,
		a.fld_btstat, d.jumlah, b.fld_aprvroleid,
		CASE
		  WHEN b.fld_aprvruleroleord <= d.jumlah and a.fld_btstat=3 then e.fld_tyvalnm
	          WHEN b.fld_aprvruleroleord <= d.jumlah and a.fld_btstat=6 then e.fld_tyvalnm
		end status
		from tbl_bth a
		left join tbl_aprvtkt b on b.fld_btid=a.fld_btid
		left join tbl_user c on c.fld_userid=b.fld_userid
		left join (select fld_btid, count(fld_btid)jumlah
		from tbl_aprvtkt group by fld_btid)d on d.fld_btid=a.fld_btid
		left join tbl_tyval e on e.fld_tyvalcd=b.fld_aprvroleid and e.fld_tyid=3
		where a.fld_btid='$rffval->fld_btid' and a.fld_btstat > 1 and b.fld_aprvtktmoddt !='0000-00-00'
		order by a.fld_btid, b.fld_aprvruleroleord)a";
	//  print $sql;
	   $query=$this->db->query($sql);
	   //print $query->num_rows();
	  if ($query->num_rows() > 0 )
	  {
	    ?>
	      <tr>
	      <td>
		<?
		  foreach ($query->result() as $row)
		  {
		    print "- ".$row->status."<br/>";
		  }
		?>
		</td>
	      </tr>
	    <?
	}
	$query->free_result();
	?>
      </table>
</td>
</tr>
</table>
<br>

<!--<input type="button" name="back" class="BtnGrey" value="Back" id='backback' onclick='javascript: history.go(-1)'>-->
<?
if ((isset($issubform) &&  $issubform == 1) || (isset($isformview) &&  $isformview == 1)) {
  include ("subform_view.php");
}
?>
</form>
<br>
<br>
</html>
