<?php
class Payload_modal extends CI_Model
{
  function __construct() {
      parent::__construct();
      $this->dbdnxapps = $this->load->database('dnxapps', TRUE);
    }

function getforminsert($tabelnm,$data){
  $this->dbdnxapps->insert($tabelnm, $data);
  $this->dbdnxapps->limit(1);
  return $this->dbdnxapps->affected_rows();
}
function getformupdate($tabelnm,$pkey,$data,$pkeyval) {
      $this->dbdnxapps->where($pkey, $pkeyval);
      $this->dbdnxapps->update($tabelnm, $data);
      $this->dbdnxapps->limit(1);
      return $this->dbdnxapps->affected_rows();
}
function deleteForm($pkeyval,$tabelnm,$pkey){
  $this->dbdnxapps->where($pkey, $pkeyval);
  $this->dbdnxapps->delete($tabelnm);
  $this->dbdnxapps->limit(1);
  return $this->dbdnxapps->affected_rows();
}
function getDataPayload(){
  $id_payload = null;
  if(isset($_POST['fld_id'])){
    $id_payload = $this->input->post('fld_id');
  }
  $this->dbdnxapps->select("t0.* ,t1.fld_empnm, t2.fld_bticd 'nopol'");
  $this->dbdnxapps->from("tbl_payload_truck as t0");
  $this->dbdnxapps->join("hris.tbl_emp as  t1", 't0.fld_h01 = t1.fld_empid', 'left');
  $this->dbdnxapps->join("tbl_bti as  t2", 't0.fld_btiid = t2.fld_btiid', 'left');
  if($id_payload !== null){
    $this->dbdnxapps->where('t0.fld_id', $id_payload);
  }
  $data = $this->dbdnxapps->get();
  return $data;
}
function getUnit($btiid = null){
  $where_btiid = "";
  if($btiid !== null){
    $where_btiid = 'and 0.fld_btiid = '. $btiid;
  }
  $getUnit = $this->dbdnxapps->query("
  select t0.fld_btiid 'id',if(t0.fld_bticid=2,concat (t0.fld_bticd,' [ ',t1.fld_tyvalnm,' / ',t2.fld_tyvalnm,' ] ','[TAHUN UNIT :',t0.fld_btip03,'][' , t3.fld_tyvalnm , ']'),t0.fld_btinm) 'name'
  from tbl_bti t0
  left join tbl_master_truck_type t1 on t1.fld_tyvalcd = t0.fld_btiflag and t1.fld_tyid=19
  left join tbl_tyval t2 on t2.fld_tyvalcd = t0.fld_btip02 and t2.fld_tyid=18
  left join tbl_tyval t3 on t3.fld_tyvalcd = t0.fld_btip12 and t3.fld_tyid=94
  #left join tbl_toh t4 on t4.fld_btid = t0.fld_btip31 and t4.fld_btflag =30
  where t0.fld_bticid in (2,8)
  and
  t0.fld_btistat in (1,3,4,7,8)
  $where_btiid
  ");
  return $getUnit->result();
}
}
