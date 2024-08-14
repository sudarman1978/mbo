<?php
class Freelance_model extends CI_Model
{
  function __construct() {
      parent::__construct();
      $this->intranet = $this->load->database('intranet', TRUE);

    }

function getforminsert($tabelnm,$data){
  $this->intranet->insert($tabelnm, $data);
  $this->intranet->limit(1);
  return $this->intranet->affected_rows();
}
function getforminsertLastId($tabelnm,$data){
  $this->intranet->trans_begin();
    $this->intranet->insert($tabelnm,$data);

    $item_id = $this->intranet->insert_id();

    if( $this->intranet->trans_status() === FALSE )
    {
      $this->intranet->trans_rollback();
      return( 0 );
    }
    else
    {
      $this->intranet->trans_commit();
      return( $item_id );
    }
}

function getformupdate($tabelnm,$pkey,$data,$pkeyval) {
      $this->intranet->where($pkey, $pkeyval);
      $this->intranet->update($tabelnm, $data);
      $this->intranet->limit(1);
      return $this->intranet->affected_rows();
}
function getDataSumAppFreelance($free_id = null){
  $where = "";
  if($free_id !== null){
    $where = "WHERE fee.fld_btiid = $free_id";
  }

  $query = $this->intranet->query("
  SELECT
  IFNULL(fee.count_fee,0) AS 'total_project',
  IFNULL(pay.count_pay,0) AS 'total_payment',
ifnull(fee,0) AS 'revenue',
ifnull(pay,0) AS 'payment',
if(isnull(fee - pay),fee,fee - pay) AS 'balance' ,
fee.fld_btiid AS 'id_freelance',
fee.fld_tyvalnm AS 'freelance_name'
     FROM (
    (
	 SELECT
	 SUM(b.fld_btp17 * 100000 ) as fee,b.fld_btiid, t1.fld_tyvalnm, COUNT(b.fld_btid) AS count_fee
	 FROM tbl_bth b
	 				inner JOIN tbl_tyval t1 ON t1.fld_tyvalcd = b.fld_btiid AND t1.fld_tyid = 69 AND t1.fld_tyvalcfg = 3
	 				WHERE 	b.fld_bttyid=30
	AND b.fld_btp16 = 1
	AND if(b.fld_btstat = 3,1,b.fld_btp02 <> '')
	#and b.fld_btp33 <> 1
	GROUP BY b.fld_btiid
	) as fee
       LEFT JOIN
    (SELECT
	 SUM(b1.fld_btp17 * 100000 ) as pay,b1.fld_btiid, t1.fld_tyvalnm, COUNT(b1.fld_btid) AS count_pay
	 FROM tbl_bth b1
	 				inner JOIN tbl_tyval t1 ON t1.fld_tyvalcd = b1.fld_btiid AND t1.fld_tyid = 69 AND t1.fld_tyvalcfg = 3
	 				WHERE 	b1.fld_bttyid=30
	AND b1.fld_btp16 = 1
	AND if(b1.fld_btstat = 3,1,b1.fld_btp02 <> '')
	#AND b1.fld_btp33 <> 1
	AND b1.fld_btnoalt != ''
	GROUP BY b1.fld_btiid
	 ) as pay
     ON fee.fld_btiid = pay.fld_btiid)
    #WHERE fee.fld_btiid = 9001
       $where
  ");
  log_message('info', "naufal_query");
log_message('info', print_r($this->intranet->last_query(), TRUE));
  if($query->num_rows() > 0){
    return $query->result();
  }else{
    return 0;
  }


}
function getHistoricalFreelance($id,$type = null,$date = null){
  $where_type = "";
  if($type !== null && $type !== "all"){
    $where_type = "where res.type = $type";
  }
  $where_date = "";
  if($date !== null && $date !== "all"){
    if($type !== "all"){
        $where_date = "and res.start_date = '$date'";
    }else{
        $where_date = "where res.start_date = '$date'";
    }

  }
  $query = $this->intranet->query("
  SELECT
*
#SUM(res.amount) 'total_amount',
#SUM(res.amount) ''
 FROM(
		  SELECT
      t0.fld_btid 'id_trans',
		  t0.fld_btiid 'free_id',
        if(t0.fld_btp17 < 0,0,t0.fld_btp17) * 100000 'amount',
        if(1=1,CONCAT('Add Project For Transaction [',t0.fld_btno,']',
		  						if(t0.fld_btnoalt = t1.fld_btno,'[PAID]','')),'') 'type_desc',
        if(t0.fld_btnoalt = t1.fld_btno,3,1) 'type',
        t0.fld_btid 'crud',
        t6.fld_tyvalnm 'freelance_name',
        date_format(t0.fld_btdt,'%Y-%m-%d')'start_date',
        t4.fld_empnm 'employee',
		  t0.fld_btno 'number_trans',
        t0.fld_btdesc 'description',
        t2.fld_bedivnm 'division'
        from
        tbl_bth t0
        left join hris.tbl_emp t4 on t4.fld_empid=t0.fld_btp01
        inner join tbl_tyval t5 on t5.fld_tyvalcd = t0.fld_btstat and t5.fld_tyid = 2
        inner join tbl_tyval t6 on t6.fld_tyvalcd = t0.fld_btiid and t6.fld_tyid = 69
        left JOIN tbl_bth t1 ON t1.fld_btno = t0.fld_btnoalt
        left join hris.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
        where
        t0.fld_bttyid=30
        AND t0.fld_btp16 = 1
        and t0.fld_btiid = $id
        AND if(t0.fld_btstat = 3,1,t0.fld_btp02 <> '')
        and t0.fld_btp33 <> 1
  	union
		 SELECT
     if(t0.fld_btnoalt = t1.fld_btno,t1.fld_btid,t0.fld_btid) 'id_trans',
		  t0.fld_btiid 'free_id',
        if(t0.fld_btp17 < 0,0,t0.fld_btp17) * 100000 'amount',
        if(1=1,CONCAT('Add Payment For Transaction [',t0.fld_btno,']'),'') 'type_desc',
        2 'type',
        t1.fld_btid 'crud',
        t6.fld_tyvalnm 'freelance_name',
        DATE_FORMAT(t1.fld_btdt,'%Y-%m-%d')'start_date',
        t7.fld_empnm 'employee',
        if(t0.fld_btnoalt = t1.fld_btno,t0.fld_btnoalt,t0.fld_btno) 'number_trans',
        t0.fld_btdesc 'description',
        t2.fld_bedivnm 'division'
        from
        tbl_bth t0
        left join hris.tbl_emp t4 on t4.fld_empid=t0.fld_btp01
        inner join tbl_tyval t5 on t5.fld_tyvalcd = t0.fld_btstat and t5.fld_tyid = 2
        inner join tbl_tyval t6 on t6.fld_tyvalcd = t0.fld_btiid and t6.fld_tyid = 69
        inner JOIN tbl_bth t1 ON t1.fld_btno = t0.fld_btnoalt
        left join hris.tbl_emp t7 ON t7.fld_empid=t1.fld_btp01
        left join hris.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
        where
        t0.fld_bttyid=30
        AND t0.fld_btp16 = 1
        and t0.fld_btiid = $id
        AND if(t0.fld_btstat = 3,1,t0.fld_btp02 <> '')
        and t0.fld_btp33 <> 1
        AND t0.fld_btnoalt <> ''
        #GROUP BY t1.fld_btno
) AS res
$where_type
$where_date
ORDER BY res.start_date desc
  ");
  log_message('info', "naufal_query_log");
  log_message('info', $this->intranet->last_query());
  if($query->num_rows() > 0){
    return $query->result();
  }else{
    return 0;
  }
}
// function getDataAppFreelance($free_id,$type){
//
//   if($type == 1){
//     $cond = "AND t0.fld_btp02 <> ''";
//   }elseif($type == 2){
//     $cond = "AND t0.fld_btp02 = ''";
//   }else{
//     $cond = "";
//   }
//   $query = $this->dnxapps->query("
//   SELECT
//         t0.fld_btp17 'mandays',
//         t0.fld_btp33,
//         t0.fld_btid 'crud',
//         date_format(t0.fld_btdtsa,'%Y-%m-%d')'start_date',
//         date_format(t0.fld_btdtso,'%Y-%m-%d')'estimasi_finish',
//         if(datediff(date_format(t0.fld_btdtso,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d'))<0,'-',datediff(date_format(t0.fld_btdtso,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d'))) 'sisa_waktu',
//         if(t0.fld_btp02 = '',0,date_format(t0.fld_btp02,'%Y-%m-%d'))'actual_finish',
//         concat(substr(t0.fld_btp11,1,8),'...') 'qc_by',
//         t4.fld_empnm 'Employee',
//         t0.fld_btno 'no_req',
//         t1.fld_bedivnm 'Division',
//         t0.fld_btdesc 'Request_Detail',
//         t6.fld_tyvalnm  'pic',
//         if(t0.fld_btp05='',0,1)'Kadiv',
//         if(t0.fld_btp06='',0,1)'UAT',
//         t5.fld_tyvalnm 'trans_status'
//
//         from
//         intranet.tbl_bth t0
//         left join hris.tbl_bediv t1 on t1.fld_bedivid=t0.fld_baidc
//         left join hris.tbl_emp t2 on t2.fld_empid=t0.fld_btp11
//         left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_btiid and t3.fld_empdiv=5 and if(t3.fld_empid = 2505,1,t3.fld_empstat not in (4))
//         left join hris.tbl_emp t4 on t4.fld_empid=t0.fld_btp01
//         inner join intranet.tbl_tyval t5 on t5.fld_tyvalcd = t0.fld_btstat and t5.fld_tyid = 2
//         inner join intranet.tbl_tyval t6 on t6.fld_tyvalcd = t0.fld_btiid and t6.fld_tyid = 69
//         where
//         t0.fld_bttyid=30
//         AND t0.fld_btp16 = 1
//         and t0.fld_btiid = $free_id
//         $cond
//         and t0.fld_btp33 <> 1
//   ");
//   if($query->num_rows() > 0){
//     return $query->result();
//   }else{
//     return 0;
//   }
//
//
// }

}
