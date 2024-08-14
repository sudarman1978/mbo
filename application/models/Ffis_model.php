<?php
class Ffis_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function mkautono2 ($baido,$bttyid) {

		$date_trans = date("ym");
		$year_trans = date("y");
		$bacd = $this->db->query("select fld_bacd from tbl_ba where fld_baid='$baido'");
		$lbacd = $bacd->row();
		$bttycd = $this->db->query("select fld_bttycd from tbl_btty where fld_bttyid='$bttyid'");
		$lbttycd = $bttycd->row();
		$query = $this->db->query("select t0.fld_btno  from tbl_bth t0  where t0.fld_bttyid='$bttyid' and t0.fld_baido = '$baido' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
		foreach ($query->result() as $row) {
		}
		$get_seq_number = (substr($row->fld_btno,13,5)+1);
		$seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
		$vno = $lbacd->fld_bacd . "/" . $lbttycd->fld_bttycd . "/" . $date_trans . "/" . $seq_number;
		return $vno;

  }

  function message($message,$flag) {
    echo "<br><div align='center'><font size='5' color='red'>";
    $display_string = $message;
    echo $display_string;
    echo "</font></div>";
    echo "<br><div align='center'><font size='3' color='blue'>";
    if($flag ==2){
    echo "Click <a href=javascript:history.go(-2)>back</a> to continue the process ...";
    } else {
    echo "Click <a href=javascript:history.back()>back</a> to continue the process ...";
    }
    echo "</font></div>";

    exit();
  }


  function message2($message) {
    echo "<br><div align='center'><font size='5' color='red'>";
    $display_string = $message;
    echo $display_string;
    echo "</font></div>";
    echo "<br><div align='center'><font size='3' color='blue'>";
    echo "Click <a href=javascript:history.go(-2)>back</a> to continue the process ...";
    echo "</font></div>";

    exit();
  }

  function cekRemain ($fld_btid,$fld_btamt,$fld_btuamt,$fld_btp05,$fld_btp06) {
   //$cek = $this->db->query("select * from tbl_btr t0
   //                        left join tbl_bth t1 on t1.fld_btid = t0.fld_btrsrc
   //                        where t0.fld_btrdst = $fld_btid and t1.fld_bttyid = 9");
   //if ($cek->num_rows() == 0) {
     if($fld_btuamt>0 and $fld_btamt>0) {
       $remain=$fld_btuamt-$fld_btamt-$fld_btp05-$fld_btp06;
       $this->db->query("update tbl_bth set fld_btp04=$remain where fld_btid = $fld_btid");
     }
  // }
  }

  function cekBukpot ($fld_btid,$fld_baidc,$fld_btnoalt) {
   $cek = $this->db->query("select * from tbl_bth t0
                           where t0.fld_baidc = $fld_baidc and t0.fld_btnoalt = '$fld_btnoalt' and t0.fld_bttyid = 100");
   if ($cek->num_rows() > 0) {
        $this->message("Data Already Exist .....");


   }
  }


  function setTotalAmount ($fld_btid,$fld_bttyid,$fld_formnm,$fld_btflag) {

   if ($fld_formnm == '78000DRIVERCASH_ADVANCES_TRAILER') {
      $data = $this->db->query("select * from tbl_bti t0 where
                               t0.fld_btiid = $fld_btid ");
      $data = $data->row();

      $cek = $this->db->query("select count(1) 'cnt' from tbl_bti t0 where
                               t0.fld_bticid = 10
                               and t0.fld_btip01 = $data->fld_btip01
                               and t0.fld_btiflag = $data->fld_btiflag
                               and t0.fld_btip02 = $data->fld_btip02
                               and t0.fld_bticd = $data->fld_bticd
                               and t0.fld_btip07 = $data->fld_btip07");
      $cek = $cek->row();
      if($cek->cnt > 1) {
        $this->db->query("update tbl_bti t0  set t0.fld_btip01 = 0,t0.fld_btiflag=0,t0.fld_btip02=0  where t0.fld_btiid = $fld_btid limit 1");
        $this->message("Data Already Exist .....");
      }

      $this->db->query("update tbl_bti t0
                        left join tbl_bti t1 on t1.fld_btiid = $fld_btid
                        set
                        t0.fld_btip02 = round(t1.fld_btip03 / if(t0.fld_btip04 = 1,2.3,2),2),
                        t0.fld_btival = round(t0.fld_btival01 + t0.fld_btival02 + t0.fld_btival07 + t0.fld_btival04 + t0.fld_btival06 + t0.fld_btival08,-2)
                        where t0.fld_btiidp = $fld_btid");
    }





    //bpjs condition by naufal
    if($fld_bttyid == 119) {
    $this->db->query("
    update tbl_bth t0
    set
    t0.fld_btamt = if(t0.fld_btqty=2 and t0.fld_btuamt >= 156800 and date_format(t0.fld_btdtp, '%Y') < 2020,156800,
                      if(t0.fld_btqty=1 and t0.fld_btuamt >= 145960 and date_format(t0.fld_btdtp, '%Y') < 2020,145960,
                        if(t0.fld_btp04 in(1,5) and t0.fld_btuamt >= 170720 and date_format(t0.fld_btdtp, '%Y')=2020,170720,
                          if(t0.fld_btp04=2 and t0.fld_btuamt >= 183773 and date_format(t0.fld_btdtp, '%Y')=2020,183773,
                            if(t0.fld_btp04=1 and t0.fld_btuamt >= 176680 and date_format(t0.fld_btdtp, '%Y')>=2021,176680,
                              if(t0.fld_btp04=2 and t0.fld_btuamt >= 191960 and date_format(t0.fld_btdtp, '%Y')>=2021,191960,
                                if(t0.fld_btp04=5 and t0.fld_btuamt >= 116160 and date_format(t0.fld_btdtp, '%Y')>=2021,116160,t0.fld_btuamt)
                                  )
                                )
                              )
                            )
                          )
                        ),
                      t0.fld_btbalance = (t0.fld_btuamt - t0.fld_btamt)
                      where t0.fld_btid = $fld_btid limit 1");
    }

    ### Cash In / Cash Out
    if($fld_bttyid == 42 || $fld_bttyid == 46) {

     $data = $this->db->query("select t0.fld_btp03,t0.fld_btflag 'currency',if(t0.fld_btflag=1,2,1) 'kurs_type',
                               date_format(t0.fld_btdt,'%Y-%m-%d') 'date_trans'
                               from tbl_bth t0
                               where t0.fld_btid=$fld_btid");
     $header = $data->row();
     $currency = $header->currency;


     $kurs = $this->db->query("select * from tbl_kurs t0
                              where date_format( t0.fld_kursdtsa, '%Y-%m-%d' ) <= date_format( '$header->date_trans' , '%Y-%m-%d' )
                              and date_format( t0.fld_kursdtso, '%Y-%m-%d' ) >= date_format( '$header->date_trans' , '%Y-%m-%d' )
                              and t0.fld_kursty = 1
                              order by t0.fld_kursid desc limit 1");
     $kurs = $kurs->row();

      if($currency == 2 ) {
        $rate_amt = $kurs->fld_kursusd;
      } else if ($currency == 3 ) {
        $rate_amt = $kurs->fld_kursyen;
      }


     $this->db->query("update tbl_bth set
                       fld_btamt=(select sum(fld_btamt01) from tbl_btd_finance where fld_btidp = $fld_btid),
                       fld_btp03 = '$rate_amt'
                       where fld_btid = $fld_btid");
    }

    ## Settlement
    else if($fld_bttyid == 4){

    //update amount proforma (edc)
    $edc = $this->db->query("select * from tbl_btd_edc");
    $ops_edc = $this->db->query("select * from tbl_btd_cost where fld_btidp = '$fld_btid' and fld_bt03 !=0");

    foreach($ops_edc->result() as $rops_edc) {

        foreach($edc->result() as $redc) {
                if($rops_edc->fld_bt03 == $redc->fld_btid ) {
                        //cek no UBP sudah terpakai/tidak
                        //if($redc->fld_btflag == 1) {
                        //        $this->ffis->message("Proforma Number $redc->fld_btdesc has been used before. Please check again!");
                        //        exit();
                        //}
                        //else {
                        $this->db->query("update tbl_btd_cost set fld_btqty01 = 1,fld_btuamt01=$redc->fld_btamt01,fld_btamt01=$redc->fld_btamt01,
                                          fld_bt05='1',fld_costtype = 15,fld_btp01 = '9'
                                          where fld_btidp = '$fld_btid' and fld_bt03 = '$redc->fld_btid'");
                        //}
                }
        }

     }
     //beri flag no UBP terpakai
     $edc_settle = $this->db->query("select fld_bt03 from tbl_btd_cost where fld_btidp = '$fld_btid' group by fld_bt03");

     foreach($edc_settle->result() as $redc_settle) {

                        $this->db->query("update tbl_btd_edc set fld_btflag = 1
                                          where fld_btid = '$redc_settle->fld_bt03' limit 1");
     }


    //update amount deposit
    $deposit = $this->db->query("select * from tbl_btd_upload_deposit");
    $settle_deposit = $this->db->query("select * from tbl_btd_cost where fld_btidp = '$fld_btid' and fld_btp09 !=''");

    foreach($settle_deposit->result() as $rdeposit) {

        foreach($deposit->result() as $rdep) {
                if($rdeposit->fld_btp09 == $rdep->fld_btid) {

                        $this->db->query("update tbl_btd_cost set fld_btqty01 = 1,fld_btuamt01=$rdep->fld_btamt02,fld_btamt01=$rdep->fld_btamt02,
                                          fld_bt05='1'
                                          where fld_btidp = '$fld_btid' and fld_btp09 = '$rdep->fld_btid' limit 1");

                }
        }

     }



    //update total spent
    $sql="update tbl_bth set fld_btamt= (select sum(fld_btuamt01 * fld_btqty01) from tbl_btd_cost where fld_btidp='$fld_btid' and fld_currency=1) where fld_btid='$fld_btid'";
    $this->db->query($sql);
     $sql="update tbl_bth set fld_btp07=(select sum(fld_btuamt01 * fld_btqty01) from tbl_btd_cost where fld_btidp='$fld_btid' and fld_currency=2) where fld_btid='$fld_btid'";
    $this->db->query($sql);

    //update closing op
    $sql="update tbl_bth set fld_btp22= (select sum(fld_btamt02) from tbl_btd_over_payment where fld_btidp='$fld_btid') where fld_btid='$fld_btid'";
    $this->db->query($sql);

    $sql="update tbl_bth set fld_btp21= (select sum(fld_btamt01) from tbl_btd_over_payment where fld_btidp='$fld_btid') where fld_btid='$fld_btid'";
    $this->db->query($sql);


    //update total remain

    $payment = $this->db->query("select t1.fld_btp18 'payment'
                                 from tbl_btr t0
                                 left join tbl_bth t1 on t1.fld_btid = t0.fld_btrsrc
                                 where
                                 t0.fld_btrdst = $fld_btid
                                 and
                                 t0.fld_btrdsttyid = 4
                               ");

    $payment = $payment->row();




    }



    else if($fld_bttyid == 2) {

    $this->db->query("update tbl_bth set fld_btamt=(select sum(fld_btamt01) from tbl_btd_wo_job where fld_btidp = $fld_btid) where fld_btid = $fld_btid");

    }

  


  }

  function setTotalAmountGeneral ($fld_btid,$fld_bttyid) {
    ### Cash In / Cash Out
    if($fld_bttyid == 51 ) {
       $this->db->query("update tbl_bth set fld_btamt=(select sum(fld_btamt01) from tbl_btd_finance where fld_btidp = $fld_btid) where fld_btid = $fld_btid" );
    }

  }function exportCommission50($fld_btid) {
      $filename = 'Trucking-Commission-Summarry-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    $location = $this->session->userdata('location');
   $hdr = $this->db->query("select * from tbl_bth where fld_btid  = $fld_btid");
   $vehicle = $hdr->row()->fld_btp01;

   if($vehicle == 1) { ### Trailer
   $commission_query = $this->db->query("
    select
    t0.fld_empnm 'name',t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',sum(t0.fld_meal) 'standby',
    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',
    t0.fld_empjob,
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 1
    ),0) 'insurance',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 2
    ),0) 'jaminan',

    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',

    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (3) limit 1
    ),0) 'hutang',

     (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',

    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (8) limit 1
    ),0) 'BPJS',


    ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (4)  ),0) 'hutangCsr',

     ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (5)  ),0) 'cicilanHP',

    (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
    where
    tz0.fld_btidp = $fld_btid
    and
    tz0.fld_empid = t0.fld_empid
    ) 'Other',
    if(t4.fld_driverstat = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip',
    t4.fld_driverp04 'actBankId',
    t0.fld_empnm 'actBankNm',
    t4.fld_driverp05 'actBankNo',
    t4.fld_driverbank02 'actBankNmBRI',
    #t4.fld_driverbank01 'actBankNoBRI',
    concat('#',t4.fld_driverbank01) 'actBankNoBRI',
  	ifnull(t6.fld_empmail,'') 'email',
    500 'admBank',t0.fld_empid,t0.fld_driverid,
	ifnull(sum(t0.fld_point),0) 'point'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
    left join tbl_driver t4 on t4.fld_empid=t0.fld_empid
    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_driverjob
    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
    where
    t0.fld_postingid=$fld_btid
    and
    t0.fld_comm01 = 1
    and
    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_driverloc = 1, t4.fld_driverloc in (2,5))))
    and
    t4.fld_driverjob in (67,68,59)
    group by t0.fld_empid
    order by t0.fld_empnm
        ");
    } else {
   $commission_query = $this->db->query("
    select
    t0.fld_empnm 'name',t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',
    #sum(t0.fld_meal) 'standby',
    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',
    t0.fld_empjob,
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_driverid=t0.fld_driverid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 1
    ),0) 'insurance',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_driverid=t0.fld_driverid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 2
    ),0) 'jaminan',

    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',

    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     #where tx0.fld_driverid=t0.fld_driverid
     where tx0.fld_empid = t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag in (3,4)
    ),0) 'hutang',

    ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (4)  ),0) 'hutangCsr',

     ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (5)  ),0) 'cicilanHP',

    (select sum(tz0.fld_btamt01) from tbl_btd_driver_additional tz0
     where
     tz0.fld_btidp = $fld_btid
     and
     tz0.fld_empid = t0.fld_empid group by t0.fld_driverid
     ) 'Other',
    if(t4.fld_driverstat = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip',
    t4.fld_driverp04 'actBankId',
    t0.fld_empnm 'actBankNm',
    t4.fld_driverp05 'actBankNo',
    t4.fld_driverbank02 'actBankNmBRI',
    concat('#',t4.fld_driverbank01) 'actBankNoBRI',
    ifnull(t6.fld_empmail,'') 'email',
    500 'admBank'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
    left join tbl_driver t4 on t4.fld_driverid=t0.fld_driverid
    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_driverjob
    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
    where
    t0.fld_postingid=$fld_btid
    and
    t0.fld_comm01 = 1
    #and
    #if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_driverloc = 1, t4.fld_driverloc in (2,5))))
    and
    t0.fld_empjob in (67,68,59)
    and t4.fld_driverbank01 != ''
    group by t0.fld_empid
    order by t0.fld_empnm
        ");
    }

    $comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
   $this->load->library('cezpdf');
   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
   $this->cezpdf->ezSetMargins(10,5,10,5);
      $count = count($commission);
      $sum_commission = 0;
      $sum_standby = 0;
      $sum_point = 0;
  echo "No,Nama,Account,Amount\n";
      for ($i=0; $i<$count; ++$i) {
        $commission[$i]['count'] = $counteor;
        $nameDriver = $commission[$i]['name'];
        $email = $commission[$i]['email'];
        $actBank = $commission[$i]['actBankId'];
        $actBanknm = $commission[$i]['actBankNm'];
        $actBankno = $commission[$i]['actBankNo'];
        $actBankNmBRI = $commission[$i]['actBankNmBRI'];
        $actBankNoBRI = $commission[$i]['actBankNoBRI'];
        $fld_empnipDriver = $commission[$i]['fld_empnip'];
        $commissionDriver = $commission[$i]['commission'];
	$point = $commission[$i]['point'];
        $BPJS = $commission[$i]['BPJS'];
        $standbyDriver = $commission[$i]['standby'];
        $OtherDriver = $commission[$i]['Other'];
        $insuranceDriver = $commission[$i]['insurance'];
        $jaminanDriver = $commission[$i]['jaminan'];
        $hutangDriver = $commission[$i]['hutang'];
        $sum_commission = $sum_commission + $commission[$i]['commission'];
	$sum_point = $sum_point + $commission[$i]['point'];
        $sum_BPJS = $sum_BPJS + $commission[$i]['BPJS'];

        $sum_standby = $sum_standby + $commission[$i]['standby'];
        $sum_insurance = $sum_insurance + $commission[$i]['insurance'];
        $sum_other = $sum_other + $commission[$i]['Other'];
        $sum_jaminan = $sum_jaminan + $commission[$i]['jaminan'];
        $sum_hutang = $sum_hutang + $commission[$i]['hutang'];
        $sum_hutangCsr = $sum_hutangCsr + $commission[$i]['hutangCsr'];
        $sum_cicilanHP = $sum_cicilanHP + $commission[$i]['cicilanHP'];
        if( $commission[$i]['fld_empjob'] == 59 || $commission[$i]['fld_empjob'] == 67) {
           $commission[$i]['commission'] = $commission[$i]['commission'];
           $commission[$i]['total'] = $commission[$i]['commission'] + $commission[$i]['point'] + $commission[$i]['standby'] + $commission[$i]['Other'] - $commission[$i]['BPJS'] - $commission[$i]['insurance'] - $commission[$i]['jaminan'] - $commission[$i]['hutang'] - $commission[$i]['admBank'] - $commission[$i]['hutangCsr'] - $commission[$i]['cicilanHP'];
            $totalDriver = $commission[$i]['total'] . '00';
        } else {
          $commission[$i]['total'] = $commission[$i]['commission'] + $commission[$i]['point'] - $commission[$i]['BPJS'] - $commission[$i]['insurance'] - $commission[$i]['jaminan'] - $commission[$i]['hutang'] - $commission[$i]['admBank'];
          $totalDriver = $commission[$i]['total'] . '00';

        }

        if ($totalDriver >= 0) {
          $counteor = $counteor + 1;
          $sum_total = $sum_total + $commission[$i]['total'] ;
          echo "\"$counteor\",\"$actBankNmBRI\",\"$actBankNoBRI\",\"$totalDriver\",\"\"\n";
        }
      }
        $admBank = $counteor * $comm_data->admBank;
    #echo ",'Count',$sum_commission,$sum_standby,$sum_other,$sum_insurance,$sum_jaminan,$sum_hutang,$sum_total,\n";#
	echo ",COUNT,\"\",$counteor,\n";
	echo ",TOTAL,\"\",$sum_total,\n";
        echo ",Admin Bank,\"\",$admBank,\n";
}
function exportCommission51($fld_btid,$fld_btflag) {

    $filename = 'Trucking-Commission-Summarry-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

  $location = $this->session->userdata('location');
  $hdr = $this->db->query("select * from tbl_bth where fld_btid  = $fld_btid");
  $vehicle = $hdr->row()->fld_btp01;

  if($fld_btflag = "1"){
    $bonus_query = $this->db->query("
        select t0.fld_postingid,
        t0.fld_empid,
        t0.fld_driverid,
        t2.fld_driverbank02 'actBankNmBRI',
        concat('#',t2.fld_driverbank01) 'actBankNoBRI',
        if(t2.fld_driverbank01 = '',2,if(t2.fld_driverbank01 > 0 and t2.fld_driverp12 !=1,1,3))'pay_tp',
        (t0.fld_bonusamt-500) 'total'
        from tbl_bonus t0
        left join tbl_bth t1 on t1.fld_btid = t0.fld_postingid
        left join tbl_driver t2 on t2.fld_empid = t0.fld_empid
        where
        t0.fld_postingid = '$fld_btid'
        having pay_tp = 1
       ");
  }else{
  $bonus_query = $this->db->query("
        select t0.fld_postingid,
        t0.fld_empid,
        t0.fld_driverid,
        t2.fld_driverbank02 'actBankNmBRI',
        concat('#',t2.fld_driverbank01) 'actBankNoBRI',
        if(t2.fld_driverbank01 = '',2,if(t2.fld_driverbank01 > 0 and t2.fld_driverp12 !=1,1,3))'pay_tp',
        (t0.fld_bonusamt-500) 'total'
        from tbl_bonus t0
        left join tbl_bth t1 on t1.fld_btid = t0.fld_postingid
        left join tbl_driver t2 on t2.fld_driverid = t0.fld_driverid
        where
        t0.fld_postingid = '$fld_btid'
        having pay_tp = 1
       ");
  }
       $bonn_data = $bonus_query->row();

       $bonus = $bonus_query->result_array();
        $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
        $this->cezpdf->ezSetMargins(10,5,10,5);
           $count = count($bonus);
           $sum_bonus = 0;
           $counteor =0;
       echo "No,Nama,Account,Amount\n";
           for ($i=0; $i<$count; ++$i) {
             $actBankNmBRI = $bonus[$i]['actBankNmBRI'];
             $actBankNoBRI = $bonus[$i]['actBankNoBRI'];
             $totalDriver = $bonus[$i]['total']. '00' ;

             if ($totalDriver > 0) {
               $counteor = $counteor + 1;
               $sum_total = $sum_total + $bonus[$i]['total'];
               echo "\"$counteor\",\"$actBankNmBRI\",\"$actBankNoBRI\",\"$totalDriver\",\"\"\n";
             }
           }
             $admBank = $counteor * 500;
             echo ",COUNT,\"\",$counteor,\n";
             echo ",TOTAL,\"\",$sum_total,\n";
             echo ",Admin Bank,\"\",$admBank,\n";
}
  function setBalanceLolo ($fld_btid){
   $user_group=$this->session->userdata('group');
   if ($user_group == 13)
  {
  $this->db->query ("update tbl_bth t0 set t0.fld_btbalance = t0.fld_btp18 -t0.fld_btamt where t0.fld_btid=$fld_btid limit 1 ");
  }}

  function setPicture($fld_btid,$mode,$empid){
   $location = $this->session->userdata('location');
   $this->db->query("update tbl_bth t0 set t0.fld_btcmt= (select fld_driverpic from tbl_driver where fld_empid = '$empid' and fld_driverstat = 1 and fld_driverloc = '$location' limit 1) where fld_btid = '$fld_btid' limit 1 ");
  #  echo "driver =$empid ";
  #exit();
  }

  function PICReceive($fld_btid,$mode,$fld_btdtp,$fld_btp03){
    $ctid = $this->session->userdata('ctid');
    $this->db->query("update tbl_bth set fld_btp05=$ctid where fld_btid=$fld_btid limit 1");
  }

  function SUMTotalAmount($fld_btid,$mode){
    $total = $this->db->query("SELECT SUM(fld_btamt01) 'tot' FROM `tbl_trk_billing` WHERE `fld_btidp` = $fld_btid")->row();
    // exit();
    $this->db->query("update tbl_bth set fld_btamt='$total->tot' where fld_btid=$fld_btid limit 1");
  }


  function updateDO($fld_btid,$mode,$fld_empid){

  }
  function updateLoan ($fld_btid){
  $data_dtl =$this->db->query(" update  tbl_btd_driver_insurance t0
	left join tbl_driver t1 on t1.fld_empid = t0.fld_empid and t1.fld_driverstat = 1
         set t0.fld_driverid = t1.fld_driverid
	 where t0.fld_btidp = '$fld_btid' and t0.fld_btflag = 4 ");

  }

  function setDriverId($id){
    $this->db->query("update tbl_btd_driver_insurance t0
left join tbl_driver t1 on t1.fld_empid = t0.fld_empid
set t0.fld_driverid = t1.fld_driverid
WHERE t0.fld_btid = $id ");

    $this->db->query("update tbl_btd_driver_insurance t0
left join tbl_btd_driver_insurance t00 on t0.fld_btreffid = t00.fld_btid
left join tbl_driver t1 on t1.fld_empid = t00.fld_empid
set t0.fld_driverid = t00.fld_driverid,t0.fld_empid =t00.fld_empid
WHERE t00.fld_btid = $id and (t0.fld_driverid = 0 or t0.fld_empid = 0)");
   }

  function updatebookTruck($fld_btid){
  $this->db->query("update tbl_btd_truck set fld_btp07 = 0 where fld_btid = $fld_btid limit 1");
  $this->ffis->message("Succesfull! update Data Booking Truck",2);
  }

  function setTotalDeposit($fld_btid,$fld_bttyid,$fld_btp26) {

    if($fld_bttyid == 11) {

        $this->db->query("update tbl_bth t0 set
                         t0.fld_btamt=ifnull((select sum(tx0.fld_btamt01) from tbl_btd_cost tx0
                         left join tbl_bth tx1 on tx1.fld_btid=tx0.fld_btidp
                         left join tbl_bth tx2 on tx2.fld_btid=tx0.fld_bt01
                         where
                         tx1.fld_bttyid=4
                         and
                         tx1.fld_btstat=3
                         and
                         tx0.fld_costtype=21
                         and
                         tx0.fld_bt01=$fld_btp26),0),
                         t0.fld_btbalance=ifnull((select sum(tx0.fld_btamt01) from tbl_btd_cost tx0
                         left join tbl_bth tx1 on tx1.fld_btid=tx0.fld_btidp
                         left join tbl_bth tx2 on tx2.fld_btid=tx0.fld_bt01
                         where
                         tx1.fld_bttyid=4
                         and
                         tx1.fld_btstat=3
                         and
                         tx0.fld_costtype in(3607,3608)
                         and
                         tx0.fld_bt01=$fld_btp26),0)
                         where t0.fld_btid = $fld_btid");
     }

    $this->db->query("update tbl_bth t0 set t0.fld_btp23=ifnull(t0.fld_btp06)+ifnull(t0.fld_btp16)+ifnull(t0.fld_btp21)
                      where t0.fld_btid = $fld_btid limit 1");
    $this->db->query("update tbl_bth t0 set t0.fld_btp24=ifnull(t0.fld_btamt)-ifnull(t0.fld_btp23)
                      where t0.fld_btid = $fld_btid limit 1");

  }

    function printMemoInvoice($fld_btid ){
    $getData = $this->db->query("SELECT
                                t1.fld_btid,
                                t1.fld_btno 'spi',
                                t3.fld_journalno 'journalno',
                                t3.fld_journaldt,
                                t4.fld_bttynm,
                                t1.fld_btamt
                                FROM
                                tbl_btd_post t0
                                LEFT JOIN tbl_bth t1 ON t1.fld_btid = t0.fld_btiid
                                left join tbl_be t2 ON t2.fld_beid = t1.fld_baidc
                                LEFT JOIN tbl_journal t3 ON t3.fld_btid = t0.fld_btiid
                                left join tbl_btty t4 ON t4.fld_bttyid=t1.fld_bttyid
                                 WHERE
                                 t0.fld_btidp = '$fld_btid'
                                 GROUP BY t3.fld_journalno");
     $data = $getData->result();
    $counteor = 0;
     $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf(array(25,29),$orientation='portrait');
        $this->cezpdf->ezSetMargins(10,5,10,5);
    foreach ($data as $rdata) {
    $counteor++;
        $this->cezpdf->ezText("Page $counteor", 12, array('justification' => 'right'));
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,775,35);
        $this->cezpdf->addText(100,800,9,'PT.Dunia Express Transindo     ');
        $this->cezpdf->addText(100,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(100,780,9,'Jakarta Utara');
        $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
        $this->cezpdf->addText(12,710,10,'Journal Number');
        $this->cezpdf->addText(93,710,10,':');
        $this->cezpdf->addText(100,710,10,$rdata->journalno);
        $this->cezpdf->addText(12,700,10,'Date');
        $this->cezpdf->addText(93,700,10,':');
        $this->cezpdf->addText(100,700,10,$rdata->fld_journaldt);
        $this->cezpdf->addText(12,690,10,"Reff.Number");
        $this->cezpdf->addText(90,690,10," :");
        $this->cezpdf->addText(100,690,10,$rdata->spi);
        $this->cezpdf->addText(12,680,10,"Transaction Type");
        $this->cezpdf->addText(90,680,10," :");
        $this->cezpdf->addText(100,680,10,$rdata->fld_bttynm);

    $getDataDtl = $this->db->query("SELECT
                                    t0.fld_journalid 'jurnalid',
                                    t0.fld_journaldt,
                                    t0.fld_journaldesc 'desc',
                                    t0.fld_journalamt,
                                    format(t0.fld_journalamt,0) 'amount',
                                    t2.fld_bttynm,
                                    t1.fld_btno,
                                    t3.fld_coacd 'code',
                                    t3.fld_coanm 'name',
                                    t6.fld_tyvalnm 'freight',
                                    if(t0.fld_jo='',t0.fld_btdocreff,concat(t0.fld_jo,' - ',t0.fld_btdocreff)) 'reffno',
                                    t0.fld_journalno
                                    from  tbl_journal t0
                                    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
                                    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
                                    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
                                    left join tbl_bth t5 on t5.fld_btno=t0.fld_jo
                                    left join tbl_tyval t6 on t6.fld_tyvalcd=if(t5.fld_bttyid=1,t5.fld_btp13,t5.fld_btp04) and t6.fld_tyid =72
                                    where
                                    t1.fld_btid = '$rdata->fld_btid'
                                    order by t0.fld_journalamt ASC");
  $billing_data = $getDataDtl->row();
  $billing = $getDataDtl->result_array();
  $tot_amount = 0;
    $count = count($billing);
      for ($i=0; $i<$count; ++$i) {
        $tot_amount = $tot_amount + $billing[$i]['fld_journalamt'];
      }
  $this->load->library('cezpdf');
  $this->cezpdf->ezSetDy(-90);
  $this->cezpdf->ezTable($billing,array('code'=>'Code','name'=>'Name','desc'=>'Description','reffno'=>'Reff No/Doc',
    'freight'=>'Freight','amount'=>'Amount'),'',
        array('rowGap'=>'4','showLines'=>'2','xPos'=>15,'xOrientation'=>'right','width'=>1000,'shaded'=>0,'fontSize'=>'8',
          'cols'=>array(
          'code'=>array('width'=>50),
          'name'=>array('width'=>120),
          'desc'=>array('width'=>230),
          'reffno'=>array('width'=>150),
          'freight'=>array('width'=>60),
          'amount'=>array('width'=>80))));

    $this->cezpdf->ezSetDy(-20);
    $this->cezpdf->ezText('Total Balance ' . ' :                         ' . $tot_amount,14, array('justification' => 'right'));


    $this->cezpdf->ezSetDy(-15);
    $this->cezpdf->ezNewPage();
    $this->cezpdf->ezSetY(795);
  }

        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=Memo_Invoice.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");
        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

  function printCreditTerm($fld_btid) {
    $ctid=$this->session->userdata('ctid');
    $getData = $this->db->query("SELECT
                                t1.fld_btid,
                                t1.fld_btno 'crt',
                                t3.fld_journalno 'journalno',
                                t3.fld_journaldt,
                                t4.fld_bttynm,
                                t1.fld_btamt
                                FROM
                                tbl_btd_post t0
                                LEFT JOIN tbl_bth t1 ON t1.fld_btid = t0.fld_btiid
                                left join tbl_be t2 ON t2.fld_beid = t1.fld_baidc
                                LEFT JOIN tbl_journal t3 ON t3.fld_btid = t0.fld_btiid
                                left join tbl_btty t4 ON t4.fld_bttyid=t1.fld_bttyid
                                WHERE
                                t0.fld_btidp = '$fld_btid'
                                GROUP BY t3.fld_journalno");


    $data = $getData->result();
    $counteor = 0;
     $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf(array(25,29),$orientation='portrait');
        $this->cezpdf->ezSetMargins(10,5,10,5);
    foreach ($data as $rdata) {
    $counteor++;
        $this->cezpdf->ezText("Page $counteor", 12, array('justification' => 'right'));
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,775,35);
        $this->cezpdf->addText(100,800,9,'PT.Dunia Express Transindo     ');
        $this->cezpdf->addText(100,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(100,780,9,'Jakarta Utara');
        $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
        $this->cezpdf->addText(23,710,10,'Journal Number');
        $this->cezpdf->addText(103,710,10,':');
        $this->cezpdf->addText(110,710,10,$rdata->journalno);
        $this->cezpdf->addText(23,700,10,'Date');
        $this->cezpdf->addText(103,700,10,':');
        $this->cezpdf->addText(110,700,10,$rdata->fld_journaldt);
        $this->cezpdf->addText(23,690,10,"Reff.Number");
        $this->cezpdf->addText(100,690,10," :");
        $this->cezpdf->addText(110,690,10,$rdata->crt);
        $this->cezpdf->addText(23,680,10,"Transaction Type");
        $this->cezpdf->addText(100,680,10," :");
        $this->cezpdf->addText(110,680,10,$rdata->fld_bttynm);

        $getDataDtl = $this->db->query("SELECT
                                    t0.fld_journalid 'jurnalid',
                                    t0.fld_journaldt,
                                    t0.fld_journaldesc 'desc',
                                    t0.fld_journalamt,
                                    format(t0.fld_journalamt,0) 'amount',
                                    t2.fld_bttynm,
                                    t1.fld_btno,
                                    t3.fld_coacd 'code',
                                    t3.fld_coanm 'name',
                                    t6.fld_tyvalnm 'freight',
                                    if(t0.fld_jo='',t0.fld_btdocreff,concat(t0.fld_jo,' - ',t0.fld_btdocreff)) 'reffno',
                                    t0.fld_journalno
                                    from  tbl_journal t0
                                    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
                                    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
                                    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
                                    left join tbl_bth t5 on t5.fld_btno=t0.fld_jo
                                    left join tbl_tyval t6 on t6.fld_tyvalcd=if(t5.fld_bttyid=1,t5.fld_btp13,t5.fld_btp04) and t6.fld_tyid =72
                                    where
                                    t1.fld_btid = '$rdata->fld_btid'
                                    order by t0.fld_journalamt ASC");
  $billing_data = $getDataDtl->row();
  $billing = $getDataDtl->result_array();
  $tot_amount = 0;
    $count = count($billing);
      for ($i=0; $i<$count; ++$i) {
        $tot_amount = $tot_amount + $billing[$i]['fld_journalamt'];
      }
  $this->load->library('cezpdf');
  $this->cezpdf->ezSetDy(-90);
  $this->cezpdf->ezTable($billing,array('code'=>'Code','name'=>'Name','desc'=>'Description','reffno'=>'Reff No/Doc',
    'freight'=>'Freight','amount'=>'Amount'),'',
        array('rowGap'=>'4','showLines'=>'2','xPos'=>30,'xOrientation'=>'right','width'=>1000,'shaded'=>0,'fontSize'=>'8',
          'cols'=>array(
          'code'=>array('width'=>50),
          'name'=>array('width'=>110),
          'desc'=>array('width'=>210),
          'reffno'=>array('width'=>150),
          'freight'=>array('width'=>60),
          'amount'=>array('width'=>80))));

    $this->cezpdf->ezSetDy(-20);
    $this->cezpdf->ezText('Total Balance ' . ' :                         ' . $tot_amount,14, array('justification' => 'right'));


    $this->cezpdf->ezSetDy(-15);
    $this->cezpdf->ezNewPage();
    $this->cezpdf->ezSetY(795);
    }

    header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=Memo_CreditTerm.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");
        $output = $this->cezpdf->ezOutput();
        echo $output;
  }


  function printInvoiceMolten($fld_btid,$gtk){
    $ctid=$this->session->userdata('ctid');
    $getData = $this->db->query("SELECT
                                t1.fld_btid,
                                t1.fld_btno 'faktur_no' ,
                                t1.fld_btp23 'inv_no',
                                t2.fld_benm 'customer',
                                t3.fld_tyvalnm 'currency',
                                if($ctid=976,'BOYKE SIREGAR',if(t1.fld_btloc = 7,'EKO PRASETYO','ELLY DWIYANTI')) 'ttd',
                                if($ctid=976,'IMPORT COORDINATOR',if(t1.fld_btloc = 7,'FINANCE','FINANCE SUPERVISOR')) 'jabatan',
                                t1.fld_btamt
                                FROM
                                tbl_btd_post t0
                                LEFT JOIN tbl_bth t1 ON t1.fld_btid = t0.fld_btiid
                                left join dnxapps.tbl_be t2 ON t2.fld_beid = t1.fld_baidc
                                left join tbl_tyval t3 ON t3.fld_tyvalcd=t1.fld_btflag AND t3.fld_tyid = 39
                                WHERE
                                t0.fld_btidp = '$fld_btid'");
    $data = $getData->result();
    $counteor = 0;
     $this->load->library('cezpdf');
        $this->cezpdf->Cezpdf(array(23,29),$orientation='portrait');
        $this->cezpdf->ezSetMargins(10,5,10,5);
    foreach ($data as $rdata) {
    $counteor++;
    $this->cezpdf->addText(60,620,14,"KWITANSI");
    $this->cezpdf->addText(400,620,10,"Faktur No.");
    $this->cezpdf->addText(480,620,10,':'.$rdata->faktur_no);
    $this->cezpdf->addText(400,610,10,"Invoice No");
    $this->cezpdf->addText(480,610,10,':'.$rdata->inv_no);
    $this->cezpdf->addText(60,520,10,"Untuk Pembayaran");

    $this->cezpdf->ezSetDy(-185);
    $data = array(array('row1'=>'Nama Customer ', 'row2'=>$rdata->customer),);
      $this->cezpdf->ezTable($data,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>60,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>110),'row2'=>array('width'=>220))));

    $data = $getData->row();
    $currency = $data->currency;
    $getDataDtl = $this->db->query("
    SELECT
    t0.fld_btid ,
    t0.fld_btdesc 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp= '$rdata->fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    order by 1 ");

    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl_count = $getDataDtl->num_rows();
    $getDataDtl1 = $this->db->query("
    SELECT
    t0.fld_btid ,
    t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$rdata->fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    order by 1
    limit 0,12 ");

    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl1_count = $getDataDtl1->num_rows();
    $getDataDtl2 = $this->db->query("
    SELECT
    t0.fld_btid ,
    t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$rdata->fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    order by 1
    limit 12,30 ");

    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl2_count = $getDataDtl2->num_rows();
    $getDataDtlpen = $this->db->query("
    SELECT
    concat(1) 'count',
    t0.fld_btid ,
    t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    concat('')'unit',
    format(sum(t0.fld_btamt01),2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$rdata->fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    group by t0.fld_btidp ");

    $dataDtlpen_count = $getDataDtlpen->num_rows();
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $datadtlpen = $getDataDtlpen->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        $datadtl1[$i]['count'] = $counteor;
      }
    }

    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-40);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      if($flag == 1){
      $this->cezpdf->ezTable($datadtlpen,array('a'=>'','desc'=>'','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);
      }else
      {

      $this->cezpdf->ezTable($datadtl1,array('a'=>'','desc'=>'','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>212),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);
      }

      if($gtk == 100){
      $gtk_total = 0;
      foreach($getDataDtl->result() as $rdatadtl){
      $gtk_total = $gtk_total + $rdatadtl->fld_btamt01;
      }
      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'Rp' ,'row3'=>number_format($gtk_total,2,',','.')),
                  array('row1'=>''));
      }

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>430,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>75,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>'  ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));
      if($browser =  "Linux"){
          $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
      }else
      {
      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,155,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,135,200,10,$data->jabatan,'center');
      }
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));


    $this->cezpdf->ezSetDy(-15);
    $this->cezpdf->ezNewPage();
    $this->cezpdf->ezSetY(795);


    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=InvoiceMolten-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;
   }
}

function printInvoiceChemco($fld_btid){
     $getbtnoreff = $this->db->query("SELECT
                                      t0.fld_btiid,
                                      t1.fld_btno
                                      FROM tbl_btd_post t0
                                      LEFT JOIN tbl_bth t1 ON t1.fld_btid = t0.fld_btiid AND  t1.fld_bttyid IN (1,6)
                                      WHERE
                                      t0.fld_btidp = '$fld_btid'");

        $this->load->library("tcpdf/tcpdf.php");
        #$this->load->library("fpdi/src/autoload.php");
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}



foreach ($getbtnoreff->result() as $dataS) {

$pdf->SetFont('dejavusans', '', 10);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->AddPage();

$getData = $this->db->query("
    SELECT
    t0.fld_btno 'btno',
    concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t1.fld_benm) 'customer',
    t0.fld_baido 'comp',
    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp04 'AjuPEB',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp15 'forz',
    t0.fld_btp19 'ex_inv',
    t0.fld_btdesc 'remark',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t9.fld_beaddrplc != '',concat(t9.fld_beaddrplc,'\n'),'') , t9.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    t0.fld_btcmt 'note',
    t0.fld_btflag 'currency',
    t0.fld_btdesc 'for',
    t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
    t0.fld_btp04 'peb',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_baidp 'posted',
    t7.fld_empnm 'postedby',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
    if(t2.fld_tyvalnm ='IDR','Rupiah.','USD') 'curr_cd',
    if(t0.fld_btdt < '2022-04-01 00:00:00',if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%'),if(t0.fld_btp13 = 1, 'VAT 1.1%', 'VAT 11%')) 'vat_type',
    if(t0.fld_btp36 < 2, '', t10.fld_tyvalnm) 'reimburse',
    t0.fld_bttaxno 'tax_no'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1
    left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
    left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
    left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid = 72
    left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
    left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid = 173
    left join dnxapps.tbl_beaddr t9 on t9.fld_beid=t1.fld_beid and t9.fld_beaddrid=t0.fld_btp09 and t9.fld_beaddrstat=1
    left join tbl_tyval t10 on t10.fld_tyvalcd=t0.fld_btp36 and t10.fld_tyid = 115
    LEFT JOIN tbl_bth t11 ON t11.fld_btno = t0.fld_btnoreff AND t11.fld_bttyid IN (1,6)
    where
    t11.fld_btno = '$dataS->fld_btno'
    and
    t0.fld_bttyid = 41
    GROUP BY btno
    ");
    $datarow = $getData->row();
    $data = $getData->result();
    // create some HTML content
           $pdf->Cell(0, 15, "", 0, 1, 'R', 0, '', 0);
   if ($datarow->forz == 2) {
       $pdf->SetFont('times', 'B', 17,'',true);
       $pdf->Cell(0, 0, "KWITANSI", 0, 0, 'L', 0, '', 0);
       $pdf->SetFont('times', 'B', 11, '', true);
       $pdf->Cell(0, 0, "B/L NO : $datarow->bl", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Job No : $dataS->fld_btno", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Aju    : $datarow->AjuPEB", 0, 1, 'R', 0, '', 0);

       #$pdf->Cell(0, 0, "AJU    : ", 0, 1, 'R', 0, '', 0);
   }else if($datarow->forz == 1){
       $pdf->SetFont('times', 'B', 17,'',true);
       $pdf->Cell(0, 10, "KWITANSI", 0, 0, 'L', 0, '', 0);
       $pdf->SetFont('times', 'B', 11, '', true);
       $pdf->Cell(0, 0, "B/L NO : $datarow->bl", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Job No : $dataS->fld_btno", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "PEB    : $datarow->AjuPEB", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Invoice Number    : $datarow->inv_no", 0, 1, 'R', 0, '', 0);
   }
     $pdf->Cell(0, 0, "", 0, 1, 'L', 0, '', 0);
     $pdf->SetFont('times', 'B', 12,'',true);
   $pdf->Cell(0, 10, "Nama Customer : $datarow->customer", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 11,'',true);
          $no =0;
          $nos = 0;

   $pdf->Cell(0, 10, "Untuk Pembayaran : ", 0, 1, 'L', 0, '', 0);
      $pdf->SetFont('helvetica', '', 8,'',true);

      foreach ($data as $rdata) {
                   $no = $no + 1;
           $pdf->Cell(0, 0, "$no."." $rdata->btno", 0, 1, 'L', 0, '', 0);
            $getDatadetails = $this->db->query("SELECT
                                                t0.fld_btdesc 'desc',
                                                t0.fld_btamt01 'amt',
                                                t1.fld_btuamt 'vat'
                                                from tbl_btd_finance t0
                                                left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                                where
                                                t1.fld_btno = '$rdata->btno'");
            $dataDtl_count = $getDatadetails->num_rows();
            $getDatadetail = $getDatadetails->result();
            $nos = 0;
            foreach ($getDatadetail as $rdetail) {
            setlocale(LC_MONETARY, 'id_ID');
            $pdf->Cell(5);
            if($nos <=  $dataDtl_count){
              $nos++;

            }
                $pdf->Cell(0, 0, "$nos. "."$rdetail->desc", 0, 1, 'L', 0, '', 0);
                $pdf->Cell(100);
                    $pdf->Cell(0, 0, "$rdata->curr_code", 0, 0, 'C', 0, '', 0);
                $pdf->Cell(0, 0, number_format("$rdetail->amt", 2, ",", "."), 0, 1, 'R', 0, '', 0);

          }
       }


            $getDatajumlah = $this->db->query("SELECT
                                               t0.fld_btdesc 'desc' ,
                                               t0.fld_btamt01 'amt',
                                               t1.fld_btuamt 'vat'
                                               from tbl_btd_finance t0
                                               left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                               where
                                               t1.fld_btnoreff = '$dataS->fld_btno'
                                               order by t1.fld_btuamt desc")->row();

            $getTotal = $this->db->query("SELECT
                                         t0.fld_btdesc 'desc' ,
                                         sum(t0.fld_btamt01) 'amt',
                                         t1.fld_btuamt 'vat',
                                         sum(t0.fld_btamt01 + t1.fld_btuamt) 'tot'
                                         from tbl_btd_finance t0
                                         left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                         LEFT JOIN tbl_btd_post t2 ON t2.fld_btid = t0.fld_btreffid
                                         where
                                         t1.fld_btnoreff = '$dataS->fld_btno'")->row();

            $pdf->Cell(0, 8,"--------------------------", 0, 1, 'R', 0, '', 0);
            $pdf->Cell(80);
            $pdf->Cell(0, 0,"SUBTOTAL".'                                                     '."Rp.", 0, 0, 'L', 0, '', 0);
            $pdf->Cell(10);
            #$pdf->Cell(0, 0, "Rp.", 0, 0, 'C',0, '', 0);
            $pdf->Cell(0, 0, number_format("$getTotal->amt",2,",","."), 0, 1, 'R', 0, '', 0);
            $pdf->Cell(80);
            $pdf->Cell(0, 0,"$datarow->vat_type".'                                                         '."Rp.", 0, 0, 'L', 0, '', 0);
            $pdf->Cell(0, 0, number_format("$getDatajumlah->vat",2,",","."), 0, 1, 'R', 0, '', 0);
            $pdf->Cell(0, 8,"--------------------------", 0, 1, 'R', 0, '', 0);
            $pdf->Cell(80);
            $Totalbesar = $getTotal->amt + $getDatajumlah->vat;
            $pdf->Cell(0, 0,"TOTAL".'                                                             '."Rp.", 0, 0, 'L', 0, '', 0);
            $pdf->Cell(10);
            $pdf->Cell(0, 0, number_format("$Totalbesar",2,",","."), 0, 1, 'R', 0, '', 0);
            $pdf->Cell(0, 29, "", 0, 1, 'L', 0, '', 0);
            $pdf->Cell(0, 15, "", 0, 1, 'L', 0, '', 0);

            $pdf->Cell(300, 5, "ELLY DWIYANTI   ", 0, 1, 'C', 0, '', 0);
            $pdf->Cell(303, 0, "  FINANCE SUPERVISOR        ", 0, 1, 'C', 0, '', 0);
}


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
        ob_end_clean();
        ob_clean();
        $pdf->Output('ChemcoPrintInv.pdf');

}


  function printSK() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select A.*,
        concat(if(B.fld_beprefix > 0,concat(E.fld_tyvalnm, '. '),''), B.fld_benm) CUSTOMER,
        B.fld_beaddr ALAMAT, CONCAT_WS(' / ',C.fld_docnum,DATE_FORMAT(C.fld_docdt,'%d-%m-%Y')) DOKUMEN, D.fld_empnm StafOperasional
	FROM tbl_bth A
	LEFT JOIN
	tbl_be B
	ON B.fld_beid=A.fld_baidc
	LEFT JOIN tbl_btd_document C ON C.fld_btidp=A.fld_btid
	LEFT JOIN hris.tbl_emp D ON D.fld_empid=A.fld_baidp
        left join dnxapps.tbl_tyval E on E.fld_tyvalcd = B.fld_beprefix and E.fld_tyid = 173
	where A.fld_btid='$fld_btid'");
    $data = $getData->row();


    $this->load->library('cezpdf');
    $this->cezpdf->ezSetMargins(60,135,10,15);

	$this->cezpdf->ezText("SURAT KUASA" . "   ", 14, array('justification' => 'center'));
	$this->cezpdf->ezText("PENGAMBILAN DO" . "   ", 14, array('justification' => 'center'));
        //$this->cezpdf->addText(420,730,9,'No.    '.'        : '.'Form/PD/002/PURCH     ');
        //$this->cezpdf->addText(420,720,9,'Rev.  '.'        : '.'01     ');
        //$this->cezpdf->addText(420,710,9,'Tgl. Efektif'.' : '.'24 September 2013    ');
    $this->cezpdf->addText(70,730,9,'Kami yang bertanda tangan dibawah ini     :');
	//$this->cezpdf->addText(70,730,9,'');
	$this->cezpdf->addText(70,700,9,'Nama');
	$this->cezpdf->addText(160,700,8,':');
	$this->cezpdf->addText(170,700,8,$data->fld_btp07);

	$this->cezpdf->addText(70,685,9,'Jabatan');
	$this->cezpdf->addText(160,685,8,':');
	$this->cezpdf->addText(170,685,8,'Kuasa Direksi');

	$this->cezpdf->addText(70,670,9,'Nama Perusahaan');
	$this->cezpdf->addText(160,670,8,':');
	$this->cezpdf->addText(170,670,8,$data->CUSTOMER);

	$this->cezpdf->addText(70,655,9,'NPWP');
	$this->cezpdf->addText(160,655,8,':');
	$this->cezpdf->addText(170,655,8,$data->fld_btp08);

	$this->cezpdf->addText(70,640,9,'Alamat Perusahaan');
	$this->cezpdf->addText(160,640,8,':');
	$this->cezpdf->addText(170,640,8,$data->ALAMAT);

	$this->cezpdf->addText(70,625,9,'Telp/Fax');
	$this->cezpdf->addText(160,625,8,':');

	$this->cezpdf->addText(70,605,9,'Selanjutnya dalam surat kuasa ini disebut sebagai PEMBERI KUASA dengan ini memberi kuasa kepada :');

	$this->cezpdf->addText(70,580,9,'Nama');
	$this->cezpdf->addText(160,580,8,':');
	$this->cezpdf->addText(170,580,8,$data->StafOperasional);

	$this->cezpdf->addText(70,565,9,'Jabatan');
	$this->cezpdf->addText(160,565,8,':');
	$this->cezpdf->addText(170,565,8,'Operasional');

	$this->cezpdf->addText(70,550,9,'Nama Perusahaan');
	$this->cezpdf->addText(160,550,8,':');
	$this->cezpdf->addText(170,550,8,'PT. Dunia Express');

	$this->cezpdf->addText(70,535,9,'Alamat');
	$this->cezpdf->addText(160,535,8,':');
	$this->cezpdf->addText(170,535,8,'Jl. Agung Karya VII No.1 Jakarta Utara 14340');

	$this->cezpdf->addText(70,520,9,'Telepon/Fax');
	$this->cezpdf->addText(160,520,8,':');
	$this->cezpdf->addText(170,520,8,'6505603/6511041');

	$this->cezpdf->addText(70,500,9,'Untuk mengambil DO asli atas dokumen impor dengan data-data sebagai berikut :');
	/*
	$sql="SELECT A.fld_docnum, CONCAT_WS(' / ',A.fld_docnum,A.fld_docdt) DOKUMEN
	FROM tbl_btd_document A
	WHERE A.fld_doctype='705' AND A.fld_btid='$fld_btid'";
	$query = $this->db->query($sql)->row();
	$dokumen=$query->fld_docnum;
    //$dokumen = $dokumen->row();
	//$dokumen = $dokumen->DOKUMEN;
	//print $dokumen;
	*/
	$this->cezpdf->addText(70,480,9,'No. BL');
	$this->cezpdf->addText(160,480,8,':');
	$this->cezpdf->addText(170,480,8,$data->DOKUMEN);

	$this->cezpdf->addText(70,465,9,'Nama Kapal');
	$this->cezpdf->addText(160,465,8,':');
	$this->cezpdf->addText(170,465,8,$data->fld_btp03);

	if ($data->fld_btqty > 0 && $data->fld_btp06 > 0)
	{
		$JmlCon=$data->fld_btqty."x20, ".$data->fld_btp06."x40";
	}
	if ($data->fld_btqty == 0 && $data->fld_btp06 > 0)
	{
		$JmlCon=$data->fld_btp06."x40";
	}
	if ($data->fld_btqty > 0 && $data->fld_btp06 == 0)
	{
		$JmlCon=$data->fld_btqty."x20";
	}
	$this->cezpdf->addText(70,450,9,'Jumlah Kontainer');
	$this->cezpdf->addText(160,450,8,':');
	$this->cezpdf->addText(170,450,8,$JmlCon);

	$this->cezpdf->addText(70,430,9,'Demikian surat kuasa ini dibuat untuk dapat dipergunakan sebagaimana mestinya.');

	$this->cezpdf->addText(470,415,9,'Jakarta, '.date("d-M-Y").'');
	$this->cezpdf->addText(70,395,9,'Penerima Kuasa');
	$this->cezpdf->addText(470,395,9,'Pemberi Kuasa');

	$this->cezpdf->addText(470,330,8,'<c:uline>'.$data->fld_btp07.'</c:uline>');
	$this->cezpdf->addText(70,330,8,'<c:uline>'.$data->StafOperasional.'</c:uline>');
	$this->cezpdf->addText(70,315,9,'OPERASIONAL');
	$this->cezpdf->addText(470,315,9,'KUASA DIREKSI');

	$this->cezpdf->ezNewPage();

	$this->cezpdf->ezText("SURAT TUGAS PENGAMBILAN DO" . "   ", 14, array('justification' => 'center'));

        //$this->cezpdf->addText(420,730,9,'No.    '.'        : '.'Form/PD/002/PURCH     ');
        //$this->cezpdf->addText(420,720,9,'Rev.  '.'        : '.'01     ');
        //$this->cezpdf->addText(420,710,9,'Tgl. Efektif'.' : '.'24 September 2013    ');
    $this->cezpdf->addText(70,730,9,'Kami yang bertanda tangan dibawah ini     :');
	//$this->cezpdf->addText(70,730,9,'');
	$this->cezpdf->addText(70,700,9,'Nama');
	$this->cezpdf->addText(160,700,8,':');
	$this->cezpdf->addText(170,700,8,'HK. BUDIWANTO');

	$this->cezpdf->addText(70,685,9,'Jabatan');
	$this->cezpdf->addText(160,685,8,':');
	$this->cezpdf->addText(170,685,8,'Manager Exim');

	$this->cezpdf->addText(70,670,9,'Nama Perusahaan');
	$this->cezpdf->addText(160,670,8,':');
	$this->cezpdf->addText(170,670,8,'PT. DUNIA EXPRESS');

	$this->cezpdf->addText(70,655,9,'NPWP');
	$this->cezpdf->addText(160,655,8,':');
	$this->cezpdf->addText(170,655,8,'02.238.557.9-046.000');

	$this->cezpdf->addText(70,640,9,'Alamat Perusahaan');
	$this->cezpdf->addText(160,640,8,':');
	$this->cezpdf->addText(170,640,8,'Jl. Agung Karya VII No.1 Jakarta Utara 14340');

	$this->cezpdf->addText(70,625,9,'Telp/Fax');
	$this->cezpdf->addText(160,625,8,':');
	$this->cezpdf->addText(170,625,8,'6505603/6511041');

	$this->cezpdf->addText(70,605,9,'Dengan ini menugaskan pegawai kami dibawah ini :');

	$this->cezpdf->addText(70,580,9,'Nama');
	$this->cezpdf->addText(160,580,8,':');
	$this->cezpdf->addText(170,580,8,$data->StafOperasional);

	$this->cezpdf->addText(70,565,9,'Jabatan');
	$this->cezpdf->addText(160,565,8,':');
	$this->cezpdf->addText(170,565,8,'Operasional');

	$this->cezpdf->addText(70,550,9,'Nama Perusahaan');
	$this->cezpdf->addText(160,550,8,':');
	$this->cezpdf->addText(170,550,8,'PT. Dunia Express');

	$this->cezpdf->addText(70,535,9,'Alamat');
	$this->cezpdf->addText(160,535,8,':');
	$this->cezpdf->addText(170,535,8,'Jl. Agung Karya VII No.1 Jakarta Utara 14340');

	$this->cezpdf->addText(70,520,9,'Telepon/Fax');
	$this->cezpdf->addText(160,520,8,':');
	$this->cezpdf->addText(170,520,8,'6505603/6511041');

	$this->cezpdf->addText(70,500,9,'Untuk mengambil DO asli atas dokumen impor dengan data-data sebagai berikut :');
	/*
	$sql="SELECT A.fld_docnum, CONCAT_WS(' / ',A.fld_docnum,A.fld_docdt) DOKUMEN
	FROM tbl_btd_document A
	WHERE A.fld_doctype='705' AND A.fld_btid='$fld_btid'";
	$query = $this->db->query($sql)->row();
	$dokumen=$query->fld_docnum;
    //$dokumen = $dokumen->row();
	//$dokumen = $dokumen->DOKUMEN;
	//print $dokumen;
	*/
	$this->cezpdf->addText(70,480,9,'Nama Consignee');
	$this->cezpdf->addText(160,480,8,':');
	$this->cezpdf->addText(170,480,8,$data->fld_btp09);

	$this->cezpdf->addText(70,465,9,'No. BL');
	$this->cezpdf->addText(160,465,8,':');
	$this->cezpdf->addText(170,465,8,$data->DOKUMEN);

	$this->cezpdf->addText(70,450,9,'Nama Kapal');
	$this->cezpdf->addText(160,450,8,':');
	$this->cezpdf->addText(170,450,8,$data->fld_btp03);

	$this->cezpdf->addText(70,410,9,'Demikian surat kuasa ini dibuat untuk dapat dipergunakan sebagaimana mestinya.');

	$this->cezpdf->addText(470,390,9,'Jakarta, '.date("d-M-Y").'');
	$this->cezpdf->addText(70,375,9,'Penerima Tugas');
	$this->cezpdf->addText(470,375,9,'Pemberi Tugas');

	$this->cezpdf->addText(470,320,8,'<c:uline>HK. BUDIWANTO</c:uline>');
	$this->cezpdf->addText(70,320,8,'<c:uline>'.$data->StafOperasional.'</c:uline>');
	$this->cezpdf->addText(70,305,9,'OPERASIONAL');
	$this->cezpdf->addText(470,305,9,'MANAGER EXIM');
	//$this->cezpdf->line(70,328,300,328);
	//$this->cezpdf->line(578,328,100,328);
	//$this->cezpdf->setLineStyle(1);
	//$this->cezpdf->line(470,328,100,328);
	//$this->cezpdf->addText(135,730,8,$data->fld_btno . "   ");
	/*
	$this->cezpdf->addText(70,710,9,'Division            :');
	$this->cezpdf->addText(47,675,9,'No   ');
	$this->cezpdf->addText(140,675,9,'Item ');
	$this->cezpdf->addText(280,675,9,'Quantity   ');
         $this->cezpdf->addText(380,675,9,'Remaks ');
	$this->cezpdf->addText(470,675,9,'Description');
    $this->cezpdf->addText(460,675,9,' ');
    $this->cezpdf->addText(135,730,8,$data->fld_btno . "   ");
    $this->cezpdf->addText(135,720,8,$data->pr_date. "   ");
    $this->cezpdf->addText(135,710,8,$data->fld_bedivnm . "   ");

	if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-80);
  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
    #$this->cezpdf->line(578,627,10,627);
    #$this->cezpdf->line(578,641,10,641);
   $this->cezpdf->ezTable($datadtl,array('count'=>'','item'=>'','fld_btqty01'=>'','fld_unitnm'=>'','btdesc'=>''),'',
   array('rowGap'=>'4','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>180,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),
   'item'=>array('width'=>200),
   'fld_btqty01'=>array('width'=>70,'justification'=>'center'),
   'fld_unitnm'=>array('width'=>120,'justification'=>'center'),
   'btdesc'=>array('width'=>110,'justification'=>'center'),
    )));
     $this->cezpdf->ezSetDy(-40);
	 $acc = array(array('row1'=>'Jakarta,'.$data->date_now),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>''),'',array
	 ('rowGap'=>'0','xPos'=>450,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>120,'justification' => 'left'),
	 )));
	  $this->cezpdf->ezSetDy(-10);
	 $acc = array(array('row1'=>'Applicant','row2'=>'Division Head','row3'=>'Aknowledge','row4'=>'Purchasing Manager'),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
	 ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>120,'justification' => 'center'),
	 'row2'=>array('width'=>120,'justification' => 'center'),
	 'row3'=>array('width'=>120,'justification' => 'center'),
	 'row4'=>array('width'=>120,'justification' => 'center'),
	 )));
    $this->cezpdf->ezSetDy(-20);
	 $acc1 = array(array('row1'=>$data->posted,'row2'=>'TONI WIJAYA','row3'=>'YOHANES','row4'=>'TJUNG SIAT FHA'),
                );
     $this->cezpdf->ezTable($acc1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
	 ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>120,'justification' => 'center'),
	 'row2'=>array('width'=>120,'justification' => 'center'),
	 'row3'=>array('width'=>120,'justification' => 'center'),
	 'row4'=>array('width'=>120,'justification' => 'center'),
	 )));
    $this->cezpdf->ezSetY(385);

	}
	*/

        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=pr-$data->pr_date.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

  function ImportJobOrder($fld_btid) {
    $this->load->helper('utiliti');
    $sql="select a.fld_baidc,a.fld_btnoalt, b.fld_tyvalnm,a.fld_btloc from tbl_bth a left join tbl_tyval b on b.fld_tyvalcd=a.fld_btiid and b.fld_tyid=15 where a.fld_btid='$fld_btid'";
    $data = $this->db->query($sql)->row();
    if($data->fld_btloc == 4){
     $this->ffis->message("Warning! JO BOSB can't import automaticly data PIB and must be Manual Entry data");
    }else
    {
    $tbl_bth=$data->fld_btnoalt;
    $DocType=$data->fld_tyvalnm;
    if ($DocType=='BC 2.3') {
      #$connection = odbc_connect("PIB23", "", "");
      #$result = odbc_exec($connection, "SELECT TOP 1 * FROM tblBC23Hdr WHERE CAR LIKE '*$tbl_bth*' order by BC23Tg desc");
      $header = $this->db->query("select * from tbl_BC23Hdr where CAR like '%$tbl_bth%' order by BC23Tg desc");
      #$data_h = odbc_fetch_array($result);
      $data_h = $header->row();
      $year_bc23 = substr(($data_h->CAR),12,4);
      $month_bc23 = substr(($data_h->CAR),16,2);
      $day_bc23 = substr(($data_h->CAR),18,2);
      $date_bc23 = $year_bc23 . '-' . $month_bc23 . '-' . $day_bc23;

        $update_h = $this->db->query(
        "update tbl_bth t0
	set t0.fld_btp01 = '$data_h->TmpTbn',
	t0.fld_btuamt = '$data_h->Bruto',
	t0.fld_btp03 = '$data_h->AngkutNama',
	t0.fld_btp02 = '$data_h->PasokNama',
	t0.fld_btp04 = '$data_h->AngkutNo',
	t0.fld_btp22 = '$data_h->Namattd',
        t0.fld_btp21 = '$data_h->UsahaNpwp',
	t0.fld_btp20 = '$data_h->NoPhone',
	t0.fld_btp19 = '$data_h->UsahaAlmt',
        t0.fld_btp17 = '$data_h->DokTupTg',
	t0.fld_btnoalt = '$data_h->CAR',
        t0.fld_btp29 = '$data_h->PelMuat',
        t0.fld_btp31 = '$date_bc23'
        where t0.fld_btid = $fld_btid limit 1
	");

        $sql="select fld_btnoalt from tbl_bth where fld_btid='$fld_btid'";
        $query=$this->db->query($sql)->row();
	$aju=$query->fld_btnoalt;
	//$this->db->query("delete from tbl_btd_container where fld_btidp='$fld_btid'");

        $result = $this->db->query("SELECT * FROM tbl_BC23Cont where CAR='$aju'");
	foreach($result->result() as $rdata)  {
          $fld_contnum=$rdata->ContNo;
	  $fld_conttype=$rdata->ContTipe;
	  $fld_contsize=$rdata->ContUkur;
	  $sql = "INSERT IGNORE INTO tbl_btd_container(fld_btidp, fld_contnum, fld_conttype, fld_contsize)
                  value ($fld_btid,TRIM('$fld_contnum'),'$fld_conttype',$fld_contsize)";
	  $this->db->query($sql);
          //print $i."<br>.";
          //$i++;
	}
//	print odbc_num_rows($result);
	//exit();
	//$this->db->query("delete from tbl_btd_document where fld_btidp='$fld_btid'");
	$result2 = $this->db->query("SELECT  * FROM tbl_BC23Dok WHERE CAR ='$aju'");
	foreach ($result2->result() as $rdoc) {
	  $fld_docnum=$rdoc->DokNo;
	  $fld_doctype=$rdoc->DokKd;
	  $fld_docdt=$rdoc->DokTg;

	  $sql = "INSERT IGNORE INTO tbl_btd_document(fld_btidp, fld_docnum, fld_doctype, fld_docdt)
          value ($fld_btid,'$fld_docnum','$fld_doctype','$fld_docdt')";
	  $this->db->query($sql);
	}

	$result3 = $this->db->query("SELECT * FROM tbl_BC23Rpn where CAR='$aju' AND RESKD='300'");
	$data3 = $result3->row();
        $update_rpn = $this->db->query(
        "update tbl_bth t0
         set fld_btp28 = '$data3->TgRespon'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //$result = odbc_exec($connection, $query);
	//while ($data=odbc_fetch_array($result)){
	//  $TglRespon=mysql_date($data['TgRespon']);
	//}

	//$data=array(
	//  'fld_btp28' => $TglRespon
        //);
	//$this->db->where('fld_btid', $fld_btid);
	//$this->db->update('tbl_bth', $data);
      }
      elseif ($DocType=='BC 2.0') {

        if($data->fld_baidc == 5125) //bc20 HPM
        {
        $header = $this->db->query("select * from tbl_BC20HPMHdr where CAR like '%$tbl_bth%' order by PibTg desc");
        $data_h = $header->row();
        $year_bc23_hpm = substr(($data_h->CAR),12,4);
        $month_bc23_hpm = substr(($data_h->CAR),16,2);
        $day_bc23_hpm = substr(($data_h->CAR),18,2);
        $date_bc23_hpm = $year_bc23_hpm . '-' . $month_bc23_hpm . '-' . $day_bc23_hpm;
        $update_h = $this->db->query(
        "update tbl_bth t0
                set fld_btp01 = '$data_h->TmpTbn',
                fld_btuamt = '$data_h->Bruto',
                fld_btp03 = '$data_h->AngkutNama',
                fld_btp02 = '$data_h->PasokNama',
                fld_btp04 = '$data_h->AngkutNo',
                fld_btp22 = '$data_h->impNama',
                fld_btnoalt = '$data_h->CAR',
                fld_btp29 = '$data_h->PelMuat',
                fld_btp31 = '$date_bc23_hpm'
         where t0.fld_btid = $fld_btid limit 1
         ");

		 $sql="select fld_btnoalt from tbl_bth where fld_btid='$fld_btid'";
        $query=$this->db->query($sql)->row();
        $aju=$query->fld_btnoalt;

        $result = $this->db->query("SELECT * FROM tbl_BC20HPMCont where CAR='$aju'");
        foreach($result->result() as $rdata){
          $fld_contnum=$rdata->ContNo;
          $fld_conttype=$rdata->ContTipe;
          $fld_contsize=$rdata->ContUkur;
          $sql = "INSERT IGNORE INTO tbl_btd_container(fld_btidp, fld_contnum, fld_conttype, fld_contsize)
          value ($fld_btid,TRIM('$fld_contnum'),'$fld_conttype',$fld_contsize)";
          $this->db->query($sql);
        }
		 $result2 = $this->db->query("SELECT  * FROM tbl_BC20HPMDok WHERE CAR ='$aju'");
        foreach ($result2->result() as $rdoc) {
          $fld_docnum=$rdoc->DokNo;
          $fld_doctype=$rdoc->DokKd;
          $fld_docdt=$rdoc->DokTg;

          $sql = "INSERT IGNORE INTO tbl_btd_document(fld_btidp, fld_docnum, fld_doctype, fld_docdt)
          value ($fld_btid,'$fld_docnum','$fld_doctype','$fld_docdt')";
          $this->db->query($sql);
        }

        //SPPB
        $result3 = $this->db->query("SELECT * FROM tbl_BC20HPMRpn where CAR='$aju' AND RESKD='300'");
        $data3 = $result3->row();
        $update_rpn = $this->db->query(
        "update tbl_bth t0
         set fld_btp28 = '$data3->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //PJM
        $result4 = $this->db->query("SELECT * FROM tbl_BC20HPMRpn where CAR='$aju' AND (RESKD='400' OR RESKD='450')");
        $data4 = $result4->row();
        $update_rpn1 = $this->db->query(
        "update tbl_bth t0
         set fld_btp16 = '$data4->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //PJK
        $result5 = $this->db->query("SELECT * FROM tbl_BC20HPMRpn where CAR='$aju' AND RESKD='420'");
        $data5 = $result5->row();
        $update_rpn2 = $this->db->query(
        "update tbl_bth t0
         set fld_btp24 = '$data3->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        }

        else {
        $header = $this->db->query("select * from tbl_BC20Hdr where CAR like '%$tbl_bth%' order by PibTg desc");
       //echo $query;
        //exit();
        $data_h = $header->row();
        $year_bc20 = substr(($data_h->CAR),12,4);
        $month_bc20 = substr(($data_h->CAR),16,2);
        $day_bc20 = substr(($data_h->CAR),18,2);
        $date_bc20 = $year_bc20 . '-' . $month_bc20 . '-' . $day_bc20;
        $update_h = $this->db->query(
        "update tbl_bth t0
	 set fld_btp01 = '$data_h->TmpTbn',
	 fld_btuamt = '$data_h->Bruto',
	 fld_btp03 = '$data_h->AngkutNama',
	 fld_btp02 = '$data_h->PasokNama',
	 fld_btp04 = '$data_h->AngkutNo',
	 fld_btp22 = '$data_h->impNama',
	 fld_btnoalt = '$data_h->CAR',
         fld_btp29 = '$data_h->PelMuat',
         fld_btp31 = '$date_bc20'
         where t0.fld_btid = $fld_btid limit 1
	 ");

	//}
	$sql="select fld_btnoalt from tbl_bth where fld_btid='$fld_btid'";
        $query=$this->db->query($sql)->row();
        $aju=$query->fld_btnoalt;
	//$this->db->query("delete from tbl_btd_container where fld_btidp='$fld_btid'");

        //$result = "SELECT * FROM tbl_bc20_container WHERE fld_btno='$aju'";
	$result = $this->db->query("SELECT * FROM tbl_BC20Cont where CAR='$aju'");
        foreach($result->result() as $rdata){
	  $fld_contnum=$rdata->ContNo;
          $fld_conttype=$rdata->ContTipe;
          $fld_contsize=$rdata->ContUkur;
	  $sql = "INSERT IGNORE INTO tbl_btd_container(fld_btidp, fld_contnum, fld_conttype, fld_contsize)
          value ($fld_btid,TRIM('$fld_contnum'),'$fld_conttype',$fld_contsize)";
	  $this->db->query($sql);
	}
	//$this->db->query("delete from tbl_btd_document where fld_btidp='$fld_btid'");

        $result2 = $this->db->query("SELECT  * FROM tbl_BC20Dok WHERE CAR ='$aju'");
        foreach ($result2->result() as $rdoc) {
          $fld_docnum=$rdoc->DokNo;
          $fld_doctype=$rdoc->DokKd;
          $fld_docdt=$rdoc->DokTg;

          $sql = "INSERT IGNORE INTO tbl_btd_document(fld_btidp, fld_docnum, fld_doctype, fld_docdt)
          value ($fld_btid,'$fld_docnum','$fld_doctype','$fld_docdt')";
          $this->db->query($sql);
	}

        //SPPB
        $result3 = $this->db->query("SELECT * FROM tbl_BC20Rpn where CAR='$aju' AND RESKD='300'");
        $data3 = $result3->row();
        $update_rpn = $this->db->query(
        "update tbl_bth t0
         set fld_btp28 = '$data3->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //PJM
        $result4 = $this->db->query("SELECT * FROM tbl_BC20Rpn where CAR='$aju' AND (RESKD='400' OR RESKD='450')");
        $data4 = $result4->row();
        $update_rpn1 = $this->db->query(
        "update tbl_bth t0
         set fld_btp16 = '$data4->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //PJK
        $result5 = $this->db->query("SELECT * FROM tbl_BC20Rpn where CAR='$aju' AND RESKD='420'");
        $data5 = $result5->row();
        $update_rpn2 = $this->db->query(
        "update tbl_bth t0
         set fld_btp24 = '$data3->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
       }
      }

      elseif ($DocType=='BC 2.0 HPM') {
        $header = $this->db->query("select * from tbl_BC20HPMHdr where CAR like '%$tbl_bth%' order by PibTg desc");
        $data_h = $header->row();
        $year_bc23_hpm = substr(($data_h->CAR),12,4);
        $month_bc23_hpm = substr(($data_h->CAR),16,2);
        $day_bc23_hpm = substr(($data_h->CAR),18,2);
        $date_bc23_hpm = $year_bc23_hpm . '-' . $month_bc23_hpm . '-' . $day_bc23_hpm;
        $update_h = $this->db->query(
        "update tbl_bth t0
		set fld_btp01 = '$data_h->TmpTbn',
		fld_btuamt = '$data_h->Bruto',
		fld_btp03 = '$data_h->AngkutNama',
		fld_btp02 = '$data_h->PasokNama',
		fld_btp04 = '$data_h->AngkutNo',
		fld_btp22 = '$data_h->impNama',
		fld_btnoalt = '$data_h->CAR',
                fld_btp29 = '$data_h->PelMuat',
                fld_btp31 = '$date_bc23_hpm'
         where t0.fld_btid = $fld_btid limit 1
	 ");

	$sql="select fld_btnoalt from tbl_bth where fld_btid='$fld_btid'";
        $query=$this->db->query($sql)->row();
        $aju=$query->fld_btnoalt;

	$result = $this->db->query("SELECT * FROM tbl_BC20HPMCont where CAR='$aju'");
        foreach($result->result() as $rdata){
	  $fld_contnum=$rdata->ContNo;
          $fld_conttype=$rdata->ContTipe;
          $fld_contsize=$rdata->ContUkur;
	  $sql = "INSERT IGNORE INTO tbl_btd_container(fld_btidp, fld_contnum, fld_conttype, fld_contsize)
          value ($fld_btid,TRIM('$fld_contnum'),'$fld_conttype',$fld_contsize)";
	  $this->db->query($sql);
	}


        $result2 = $this->db->query("SELECT  * FROM tbl_BC20HPMDok WHERE CAR ='$aju'");
        foreach ($result2->result() as $rdoc) {
          $fld_docnum=$rdoc->DokNo;
          $fld_doctype=$rdoc->DokKd;
          $fld_docdt=$rdoc->DokTg;

          $sql = "INSERT IGNORE INTO tbl_btd_document(fld_btidp, fld_docnum, fld_doctype, fld_docdt)
          value ($fld_btid,'$fld_docnum','$fld_doctype','$fld_docdt')";
          $this->db->query($sql);
	}

        //SPPB
        $result3 = $this->db->query("SELECT * FROM tbl_BC20HPMRpn where CAR='$aju' AND RESKD='300'");
        $data3 = $result3->row();
        $update_rpn = $this->db->query(
        "update tbl_bth t0
         set fld_btp28 = '$data3->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //PJM
        $result4 = $this->db->query("SELECT * FROM tbl_BC20HPMRpn where CAR='$aju' AND (RESKD='400' OR RESKD='450')");
        $data4 = $result4->row();
        $update_rpn1 = $this->db->query(
        "update tbl_bth t0
         set fld_btp16 = '$data4->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");
        //PJK
        $result5 = $this->db->query("SELECT * FROM tbl_BC20HPMRpn where CAR='$aju' AND RESKD='420'");
        $data5 = $result5->row();
        $update_rpn2 = $this->db->query(
        "update tbl_bth t0
         set fld_btp24 = '$data3->RESTG'
         where t0.fld_btid = $fld_btid limit 1
        ");

      }
      $sql="SELECT A.fld_btidp,
      MAX(IF(A.fld_contsize = '20', A.JUMLAH, 0)) as 'JmlCon20',
      MAX(IF(A.fld_contsize = '40', A.JUMLAH, 0)) as 'JmlCon40'
      FROM(
      SELECT A.fld_btidp, A.fld_contsize, COUNT(A.fld_contsize) JUMLAH
      FROM tbl_btd_container A
      WHERE A.fld_btidp='$fld_btid'
      GROUP BY A.fld_btidp, A.fld_contsize)A";
      $query=$this->db->query($sql);
      foreach ($query->result() as $row) {
        $JmlCon20=$row->JmlCon20;
	$JmlCon40=$row->JmlCon40;
      }
      $query->free_result();
      $data = array(
            		'fld_btqty' => $JmlCon20,
			'fld_btp06' => $JmlCon40
		);
      $this->db->where('fld_btid', $fld_btid);
      $this->db->update('tbl_bth', $data);
     }
    }

    function CetakNota($id)
    {
	$sql="select b.fld_btno, c.name, f.fld_tyvalnm, a.fld_btamt01,
              concat(if(d.fld_beprefix > 0,concat(g.fld_tyvalnm, '. '),''), d.fld_benm) 'fld_benm',
              b.fld_btnoreff, e.fld_empnm
	      from tbl_btd_cost a
	      left join tbl_bth b on a.fld_btidp=b.fld_btid
	      left join
	      (select t0.fld_btiid 'id',
	      concat(' [',t0.fld_btinm,'] Type [',t0.fld_btip01,'] Currency [',t0.fld_btip02,'] ') 'name'
	      from tbl_bti t0
	      where t0.fld_bticid = 1)c on c.id=a.fld_costtype
	      left join tbl_be d on d.fld_beid=b.fld_baidc
	      LEFT JOIN hris.tbl_emp e ON e.fld_empid=b.fld_baidp
	      left join tbl_tyval f ON a.fld_currency=f.fld_tyvalcd and f.fld_tyid=39
              left join dnxapps.tbl_tyval g on g.fld_tyvalcd = d.fld_beprefix and g.fld_tyid = 173
	      where a.fld_btidp=$id";
	      $query=$this->db->query($sql);
	      $data=$query->row();
	      $this->load->library('cezpdf');
	      $this->cezpdf->ezSetMargins(60,135,10,15);
	      $this->cezpdf->ezText("ADVANCE REQUEST" . "   ", 14, array('justification' => 'center'));
	      $this->cezpdf->setLineStyle(1);
	      $this->cezpdf->addText(70,700,9,'Dept');
	      $this->cezpdf->addText(115,700,8,':');
	      $this->cezpdf->addText(130,700,8,'ALL');

	      $this->cezpdf->addText(370,700,9,'Date');
	      $this->cezpdf->addText(415,700,8,':');
	      $this->cezpdf->addText(430,700,8,date('d-M-Y'));

	      $this->cezpdf->addText(70,715,9,'No. Adv');
	      $this->cezpdf->addText(115,715,8,':');
	      $this->cezpdf->addText(130,715,8,$data->fld_btno);

	      $this->cezpdf->addText(370,715,9,'Name');
	      $this->cezpdf->addText(415,715,8,':');
	      $this->cezpdf->addText(430,715,8,$data->fld_empnm);

	      $this->cezpdf->ezSetDy(-80);
	      $detail = $query->result_array();
	      $this->cezpdf->ezTable($detail,array('fld_btnoreff'=>'JO','name'=>'DESCRIPTION','fld_btamt01'=>'Amount','fld_benm'=>'Customer'),'',
	   	array('rowGap'=>'0','showLines'=>'2','xPos'=>30,'xOrientation'=>'right','width'=>550,'shaded'=>0,'fontSize'=>'9',
	   	'cols'=>array('fld_btnoreff'=>array('width'=>85),'name'=>array('width'=>200),'fld_btamt01'=>array('width'=>60,'justification'=>'right'),'fld_			benm'=>array('width'=>70))));

	   foreach ($query->result() as $row)
	   {
		$total=$total+$row->fld_btamt01;
	   }
	   $tabel = array(
	    array('label'=>'Total','rp'=>'RP','total'=>$total,'eu'=>'0'),
	    array('label'=>'Total','rp'=>'US','total'=>'0','eu'=>'')
	  );
	  $this->cezpdf->ezTable($tabel,array('label'=>'', 'rp'=>'','total'=>'','eu'=>''),''
	  ,array('showHeadings'=>0,'shaded'=>0,'rowGap'=>0,'showLines'=>0,'xPos'=>45,'xOrientation'=>'right','width'=>500,'fontSize'=>'9'
	  ,'cols'=>array('label'=>array('width'=>230,'justification'=>'right'),'rp'=>array('width'=>50,'justification'=>'right'),'total'=>array('width'=>76,'          justification'=>'right'),'eu'=>array('width'=>85,'justification'=>'right'))));

	  $this->cezpdf->addText(70,395,9,'Made BY');
	  $this->cezpdf->addText(470,395,9,'Receive By');
	  $this->cezpdf->addText(470,330,8,'<c:uline>'.$data->fld_empnm.'</c:uline>');
	  $this->cezpdf->addText(70,330,8,'<c:uline>'.$data->fld_empnm.'</c:uline>');
	  $this->cezpdf->addText(260,330,8,'<c:uline>Approve By</c:uline>');
		//$this->cezpdf->ezStream();
	  header("Content-type: application/pdf");
          header("Content-Disposition: attachment; filename=pr-$data->pr_date.pdf");
          header("Pragma: no-cache");
          header("Expires: 0");

          $output = $this->cezpdf->ezOutput();
          echo $output;
    }

    function CetakNota1($id)
    {
	$sql="select b.fld_btno, tipe 'desc', g.fld_tyvalnm 'tipe', f.fld_tyvalnm 'currency', a.fld_btqty01 'qty', a.fld_btamt01 'amount',
              concat(if(d.fld_beprefix > 0,concat(h.fld_tyvalnm, '. '),''), d.fld_benm) 'fld_benm',
	b.fld_btnoreff, e.fld_empnm, a.fld_btqty01 * a.fld_btamt01 total from tbl_btd_cost a
	left join tbl_bth b on a.fld_btidp=b.fld_btid left join
	(select t0.fld_btiid 'id', t0.fld_btinm  'tipe', t0.fld_btip01 'Currency', t0.fld_btip02 'name'
	from tbl_bti t0 where t0.fld_bticid = 1)c on c.id=a.fld_costtype
	left join tbl_be d on d.fld_beid=b.fld_baidc
	LEFT JOIN hris.tbl_emp e ON e.fld_empid=b.fld_baidp
	left join tbl_tyval f ON a.fld_currency=f.fld_tyvalcd and f.fld_tyid=39
	left join tbl_tyval g ON c.name=g.fld_tyvalcd and g.fld_tyid=67
        left join dnxapps.tbl_tyval h on h.fld_tyvalcd = d.fld_beprefix and h.fld_tyid = 173
	where a.fld_btidp='$id'";
	$query=$this->db->query($sql);
	$data=$query->row();
	?>
	    <table width="100%" border="0">
		<tr>
		    <td>No. Adv </td>
		    <td>: <?=$data->fld_btnoreff;?></td>
		    <td>Name</td>
		    <td>: <?=$data->fld_empnm;?></td>
		</tr>
		<tr>
		    <td>Dept</td>
		    <td>: All</td>
		    <td>Date</td>
		    <td>: <? print date('d-M-Y');?></td>
		</tr>
</table>
	<?
    }
    function CetakSettlement($id)
    {
	$sql="select  t0.fld_btid,
	date_format(t0.fld_btdt,'%d-%m-%Y') 'SettlementDate', t6.fld_btno ,
	concat(if(t3.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t3.fld_benm) 'fld_benm' ,
        t0.fld_btnoalt ,  t0.fld_btamt ,
	t0.fld_btp12 ,t0.fld_btp13 ,
	t4.fld_empnm, t7.fld_bedivnm
	from tbl_bth t0
	left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_btstat and t1.fld_tyid=4
	left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid=53
	left join dnxapps.tbl_be t3 on t3.fld_beid=t0.fld_baidc
	left join hris.tbl_emp t4 on t4.fld_empid=t0.fld_baidp
	left join tbl_btr t5 on t5.fld_btrdst=t0.fld_btid
	left join tbl_bth t6 on t6.fld_btid=t5.fld_btrsrc
        left join tbl_bediv t7 on t7.fld_bedivid=t0.fld_baidv
        left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t3.fld_beprefix and t8.fld_tyid = 173
	where t0.fld_bttyid=4 AND t0.fld_btid='$id' ";
	      $query=$this->db->query($sql);
	      $data=$query->row();
	      $this->load->library('cezpdf');
	      $this->cezpdf->ezSetMargins(60,135,10,15);
	      $this->cezpdf->ezText("SETTLEMENT" . "   ", 14, array('justification' => 'center'));
	      $this->cezpdf->setLineStyle(1);
	      $this->cezpdf->addText(70,715,9,'Dept');
	      $this->cezpdf->addText(135,715,8,':');
	      $this->cezpdf->addText(140,715,8,'Import');

	      $this->cezpdf->addText(370,715,9,'Date');
	      $this->cezpdf->addText(415,715,8,':');
	      $this->cezpdf->addText(430,715,8,date('d-M-Y'));

	      $this->cezpdf->addText(70,730,9,'No. Settlement');
	      $this->cezpdf->addText(135,730,8,':');
	      $this->cezpdf->addText(140,730,8,$data->fld_btno);

	      $this->cezpdf->addText(370,730,9,'Name');
	      $this->cezpdf->addText(415,730,8,':');
	      $this->cezpdf->addText(430,730,8,$data->fld_empnm);

 	      $this->cezpdf->addText(70,700,9,'Customer');
              $this->cezpdf->addText(135,700,8,':');
              $this->cezpdf->addText(140,700,8,$data->fld_benm);

	      $sql="select t1.fld_btinm, t2.fld_tyvalnm, t0.fld_btqty01, t0.fld_btuamt01, t0.fld_btamt01
	      from tbl_btd_cost t0
	      left join tbl_bti t1 on t1.fld_btiid=t0.fld_costtype
	      left join tbl_tyval t2 on t0.fld_currency=t2.fld_tyvalcd and t2.fld_tyid=39
              where t0.fld_btidp='$id'";
	      $query=$this->db->query($sql);
	      $this->cezpdf->ezSetDy(-100);
	      $detail = $query->result_array();
	      $this->cezpdf->ezTable($detail,array('fld_btinm'=>'Cost Type','fld_btqty01'=>'Quantity','fld_tyvalnm'=>'Currency','fld_btuamt01'=>'Amount','fld_btamt01'=>'Total'),'',
	   	array('rowGap'=>'0','showLines'=>'2','xPos'=>30,'xOrientation'=>'right','width'=>550,'shaded'=>0,'fontSize'=>'9',
	   	'cols'=>array('fld_btqty01'=>array('width'=>70),'fld_tyvalnm'=>array('width'=>70),'fld_btuamt01'=>array('width'=>60,'justification'=>'right'),'fld_btamt01'=>array('width'=>60,'justification'=>'right'),'fld_benm'=>array('width'=>70))));

	   foreach ($query->result() as $row)
	   {
		$total=$total+$row->fld_btamt01;
	   }
	   $tabel = array(
	    array('label'=>'Total','rp'=>'RP','total'=>$total,'eu'=>''),
	    array('label'=>'Total','rp'=>'US','total'=>'','eu'=>'')
	  );
	  $this->cezpdf->ezTable($tabel,array('label'=>'', 'rp'=>'','total'=>'','eu'=>''),''
	  ,array('showHeadings'=>0,'shaded'=>0,'rowGap'=>0,'showLines'=>0,'xPos'=>45,'xOrientation'=>'right','width'=>500,'fontSize'=>'9'
	  ,'cols'=>array('label'=>array('width'=>300,'justification'=>'right'),'rp'=>array('width'=>125,'justification'=>'right'),'total'=>array('width'=>60,'          justification'=>'right'),'eu'=>array('width'=>50,'justification'=>'right'))));

	  $this->cezpdf->addText(70,395,9,'Made BY');
	  $this->cezpdf->addText(470,395,9,'Receive By');
	  $this->cezpdf->addText(470,330,8,'<c:uline>'.$data->fld_empnm.'</c:uline>');
	  $this->cezpdf->addText(70,330,8,'<c:uline>'.$data->fld_empnm.'</c:uline>');
	  $this->cezpdf->addText(260,330,8,'<c:uline>Approve By</c:uline>');
		//$this->cezpdf->ezStream();
	  header("Content-type: application/pdf");
          header("Content-Disposition: attachment; filename=pr-$data->pr_date.pdf");
          header("Pragma: no-cache");
          header("Expires: 0");

          $output = $this->cezpdf->ezOutput();
          echo $output;
    }

    function CetakDO($id)
    {

      $sql="select t0.*, concat(if(t1.fld_beprefix > 0,concat(t3.fld_tyvalnm, '. '),''), t1.fld_benm) 'fld_benm',
      t2.fld_tyvalnm, t3.fld_tyvalnm 'activity'
      from tbl_bth t0
      left join tbl_be t1 on t1.fld_beid=t0.fld_baidc
      left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btp07 and t2.fld_tyid=65
      left join tbl_tyval t3 on t3.fld_tyvalcd=t0.fld_btflag and t3.fld_tyid=66
      left join dnxapps.tbl_tyval t4 on t4.fld_tyvalcd = t1.fld_beprefix and t4.fld_tyid = 173
      where t0.fld_btid='$id'";

      $query=$this->db->query($sql)->row();
      $this->load->library('cezpdf');
      //$this->cezpdf->ezSetMargins(125,5,55,15);
      $this->cezpdf->ezText("SURAT JALAN" . "   ", 14, array('justification' => 'center'));

      $this->cezpdf->addText(70,700,9,'Exportir/Importir');
      $this->cezpdf->addText(150,700,8,':');
      $this->cezpdf->addText(155,700,8,$query->fld_benm);
      $this->cezpdf->addText(370,700,9,'No');
      $this->cezpdf->addText(415,700,8,':');
      $this->cezpdf->addText(430,700,8,'.............');

      $this->cezpdf->addText(70,685,9,'Gudang/Lapangan');
      $this->cezpdf->addText(150,685,8,':');
      $this->cezpdf->addText(155,685,8,$query->fld_btp01);

      $this->cezpdf->addText(70,670,9,'Vessel');
      $this->cezpdf->addText(150,670,8,':');
      $this->cezpdf->addText(155,670,8,$query->fld_btp03);

      $this->cezpdf->addText(70,655,9,'B/L & D/O');
      $this->cezpdf->addText(150,655,8,':');
      $this->cezpdf->addText(155,655,8,$query->fld_tyvalnm." / ".$query->fld_btp08);

      $this->cezpdf->addText(70,640,9,'Pelayaran');
      $this->cezpdf->addText(150,640,8,':');
      $this->cezpdf->addText(155,640,8,$query->fld_btp02);

      $this->cezpdf->addText(70,625,9,'Tujuan');
      $this->cezpdf->addText(150,625,8,':');
      $this->cezpdf->addText(155,625,8,$query->fld_btp05);

      $this->cezpdf->addText(70,610,9,'Truck No');
      $this->cezpdf->addText(150,610,8,':');
      $this->cezpdf->addText(155,610,8,$query->fld_btp04);

      $this->cezpdf->addText(70,595,9,'Pemilik Angkutan');
      $this->cezpdf->addText(150,595,8,':');
      $this->cezpdf->addText(155,595,8,$query->fld_btp09);

      $this->cezpdf->addText(70,580,9,'Jenis Kegiatan');
      $this->cezpdf->addText(150,580,8,':');
      $this->cezpdf->addText(155,580,8,$query->activity);

      $this->cezpdf->addText(370,610,9,'Dikirim Kepada,');
      $this->cezpdf->addText(370,595,8,$query->fld_btp06);
      $this->cezpdf->addText(370,580,9,'Di .................');

      //$query->free_result();

      $sql="select concat_ws(', ',a.fld_contnum,a.fld_conttype,a.fld_contsize) container from tbl_btd_container a where a.fld_btidp='$id'";
      $this->cezpdf->ezSetDy(-230);
      $query=$this->db->query($sql);
      $detail = $query->result_array();
      //$this->cezpdf->ezTable($detail,array('container'=>'Merk No. /Count.No. /Size','name'=>'','fld_btamt01'=>'Jenis Barang','fld_benm'=>'Jumlah Barang','ket'=>'Keterangan'),'', array('rowGap'=>'0','showLines'=>'0','xPos'=>30,'xOrientation'=>'right','width'=>550,'shaded'=>0,'fontSize'=>'9',
//	   	'cols'=>array('fld_btnoreff'=>array('width'=>85),'name'=>array('width'=>150),'fld_btamt01'=>array('width'=>70,'justification'=>'left'),'fld_			benm'=>array('width'=>70))));

      $this->cezpdf->line(50, 565, 550, 565);
      $this->cezpdf->line(300, 550, 400, 550);
      $this->cezpdf->line(50, 535, 550, 535);
      $this->cezpdf->line(50, 249, 550, 249);
      $this->cezpdf->line(50, 249, 50, 565);
      $this->cezpdf->line(200, 249, 200, 565);
      $this->cezpdf->line(300, 249, 300, 565);
      $this->cezpdf->line(350, 249, 350, 550);
      $this->cezpdf->line(400, 249, 400, 565);
      $this->cezpdf->line(550, 249, 550, 565);

      $this->cezpdf->addText(70,550,9,'Merk No. /Count No. /Size');
      $this->cezpdf->addText(220,550,9,'Jenis Barang');
      $this->cezpdf->addText(310,555,9,'Jumlah Barang');

      header("Content-type: application/pdf");
      header("Content-Disposition: attachment; filename=pr-$data->pr_date.pdf");
      header("Pragma: no-cache");
      header("Expires: 0");
      $output = $this->cezpdf->ezOutput();
      echo $output;
    }

    function setInvoiceTax($fld_btid) {

      $detail = $this->db->query("select * from tbl_btd_finance where fld_btidp=$fld_btid");
      $tax_amount = 0;
      $tax_record = 0;
      $total_amount = 0;
      foreach($detail->result() as $rdetail) {
        if($rdetail->fld_btflag == 1) {
          $tax_amount = $tax_amount + ($rdetail->fld_btamt01 * 0.1);
        }
        if($rdetail->fld_coaid == 1179) {
          $tax_record = $rdetail->fld_btid;
        }
      }
      if($tax_record > 0) {
        $this->db->query("update tbl_btd_finance set fld_btamt01='$tax_amount' where fld_btid='$tax_record' limit 1");
      } else {
        $this->db->query("insert into tbl_btd_finance (fld_btidp,fld_btdesc,fld_btqty01,fld_btuamt01,fld_btamt01,fld_coaid) values($fld_btid,'VAT',1,'$tax_amount','$tax_amount','1179')");
      }
      ### Update Total Amount
      $detail2 = $this->db->query("select * from tbl_btd_finance where fld_btidp=$fld_btid");
      foreach($detail2->result() as $rdetail2) {
        $total_amount = $total_amount + $rdetail2->fld_btamt01;
      }
      $this->db->query("update tbl_bth set fld_btamt='$total_amount' where fld_btid='$fld_btid' limit 1");
   }

   function printINV_old($fld_btid) {
    $getData = $this->db->query("
    select
    t0.fld_btno 'btno',
    t1.fld_benm 'customer',
    t0.fld_baido 'comp',
    t1.fld_beid 'address',
    t0.fld_btcmt 'note',
    t0.fld_btamt'amt',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    t0.fld_baidp 'posted'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baidc
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();

    $getDataDtl = $this->db->query("
    select
        t0.fld_btid ,
        t0.fld_btdesc 'desc',
	t0.fld_btuamt01 'unit',
        t0.fld_btamt01 'subtotal',
	t0.fld_btqty01 'qty',
	t0.fld_btnoreff 'noreff',
	t0.fld_coaid 'coaid'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    ");

    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,770,40);
        $this->cezpdf->addText(95,800,9,'PT.Dunia Express Transindo     ');
        $this->cezpdf->addText(95,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(95,780,9,'Jakarta Utara');
    $this->cezpdf->ezSetMargins(100,5,10,15);
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl[$i]['count'] = $counteor;
        $total_cost = $total_cost + $datadtl1[$i]['fld_btuamt01'];
        $total_hour = $total_hour + $datadtl1[$i]['fld_btqty01'];
      }
    }

//    $this->cezpdf->ezText("INVOICE", 30, array('justification' => 'right','right'=>50));
    $this->cezpdf->addText(408,690,10,"No.");
    $this->cezpdf->addText(433,690,10," :");
    $this->cezpdf->addText(443,690,10,$data->btno);
    $this->cezpdf->addText(408,680,10,"Date");
    $this->cezpdf->addText(433,680,10," : ");
    $this->cezpdf->addText(443,680,10,$data->date);

    $this->cezpdf->addText(54,535,9,'No   ');
    $this->cezpdf->addText(138,535,9,'Item Description');
    $this->cezpdf->addText(305,535,9,'Currency');
    $this->cezpdf->addText(360,535,9,'Unit Amount ');
    $this->cezpdf->addText(440,535,9,'Qty   ');
    $this->cezpdf->addText(500,535,9,'Total');

$this->cezpdf->line(50, 545, 550, 545);
$this->cezpdf->line(50, 530, 550, 530);
$this->cezpdf->line(50, 340, 550, 340);
$this->cezpdf->line(50, 340, 50, 545);
$this->cezpdf->line(68, 340, 68, 545);
$this->cezpdf->line(300, 340, 300, 545);
$this->cezpdf->line(350, 340, 350, 545);
$this->cezpdf->line(420, 340, 420, 545);
$this->cezpdf->line(480, 290, 480, 545);
$this->cezpdf->line(550, 290, 550, 545);
$this->cezpdf->line(480, 290, 550, 290);
  $this->cezpdf->ezSetDy(-70);
    $data_prn = array(array('row1'=>'Bill To :', 'row2'=>'For :'),
                  array('row1'=>$data->customer,'row2'=>$data->note),
		  array('row1'=>$data->Address,'row2'=>''),

			  );

	$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
	  array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
	  'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));
 if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDY(-60);
  #Print Detail
   $this->cezpdf->setStrokeColor(0,0,0);
   $this->cezpdf->setLineStyle(1);
   $this->cezpdf->ezTable($datadtl1,array('count'=>'','desc'=>'','currency'=>'','unit'=>'','qty'=>'','subtotal'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
    'cols'=>array('counteor'=>array('width'=>30), 'desc'=>array('width'=>240),'currency'=>array('width'=>40),'unit'=>array('width'=>75, 'justification'=>'right'),'qty'=>array('width'=>45, 'justification'=>'right'),'subtotal'=>array('width'=>80, 'justification'=>'right'))));
   $this->cezpdf->ezSetY(340);

    $data_sum = array(
		  array('row1'=>'' . "  " ,'row2'=>$data->amt),
		  array('row1'=>''),
		  array('row1'=>''),
		  array('row1'=>''),
		  array('row1'=>'','0'),
                  array('row1'=>'',''),
		  array('row1'=>''));

		  $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>''),'',
	  array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
	  'cols'=>array('row1'=>array('width'=>420),'row2'=>array('width'=>85,'justification'=>'right') )));

    $this->cezpdf->ezSetY(25);
	         $this->cezpdf->addText(420,328,10,'SubTotal'.'');
		 $this->cezpdf->addText(420,318,10,'Discount'.'');
		 $this->cezpdf->addText(420,308,10,'Sales Tax'.'');
		 $this->cezpdf->addText(420,298,10,'Total'.'');
                 $this->cezpdf->addText(410,85,10,'JOHAN');
		 $this->cezpdf->addText(380,70,10,'FINANCE MANAGER');

}
    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=po-$data->date.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;

  }
  function number_to_words($number) {
    $before_comma = trim($this->to_word($number));
    $after_comma = trim($this->comma($number));
    if($after_comma == "") {
      $koma = "";
    } else {
      $koma = "koma" . $after_comma;
    }
    return ucwords($results = $before_comma . $koma);
  }

  function to_word($number) {
    $words = "";
    $arr_number = array(
                "",
                "satu",
                "dua",
                "tiga",
                "empat",
                "lima",
                "enam",
                "tujuh",
                "delapan",
                "sembilan",
                "sepuluh",
                "sebelas");
    if($number<12) {
      $words = " ".$arr_number[$number];
    } else if($number<20) {
      $words = $this->to_word($number-10)." belas";
    } else if($number<100) {
      $words = $this->to_word($number/10)." puluh ".$this->to_word($number%10);
    } else if($number<200) {
      $words = "seratus ".$this->to_word($number-100);
    } else if($number<1000) {
      $words = $this->to_word($number/100)." ratus ".$this->to_word($number%100);
    } else if($number<2000) {
      $words = "seribu ".$this->to_word($number-1000);
    } else if($number<1000000) {
      $words = $this->to_word($number/1000)." ribu ".$this->to_word($number%1000);
                }
                else if($number<1000000000)
                {
                        $words = $this->to_word($number/1000000)." juta ".$this->to_word($number%1000000);
                }
   		else
                {
                        $words = "undefined";
                }
                return $words;
        }

        function comma($number)
        {
                $after_comma = stristr($number,',');
                $arr_number = array(
                "nol",
                "satu",
                "dua",
                "tiga",
                "empat",
                "lima",
                "enam",
                "tujuh",
                "delapan",
                "sembilan");

                $results = "";
                $length = strlen($after_comma);
                $i = 1;
                while($i<$length)
                {
                        $get = substr($after_comma,$i,1);
                        $results .= " ".$arr_number[$get];
                        $i++;
                }
                return $results;
        }

function batchprint_inv($fld_btid,$fld_btnoreff)
  {

    $getbtnoreff = $this->db->query("select fld_btnoreff from tbl_bth where fld_btid = $fld_btid")->row();
     $btnoreff = $getbtnoreff->fld_btnoreff;
        $this->load->library("tcpdf/tcpdf.php");
        #$this->load->library("fpdi/src/autoload.php");
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
// set JPEG quality

// add a page
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
$getData = $this->db->query("
    select
    t0.fld_btno 'btno',

    concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t1.fld_benm) 'customer',
    t0.fld_baido 'comp',
    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp04 'AjuPEB',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp15 'forz',
    t0.fld_btp19 'ex_inv',
    t0.fld_btdesc 'remark',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t9.fld_beaddrplc != '',concat(t9.fld_beaddrplc,'\n'),'') , t9.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    t0.fld_btcmt 'note',
    t0.fld_btflag 'currency',
    t0.fld_btdesc 'for',
    t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
    t0.fld_btp04 'peb',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_baidp 'posted',
    t7.fld_empnm 'postedby',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
    if(t2.fld_tyvalnm ='IDR','Rupiah.','USD') 'curr_cd',
    if(t0.fld_btdt < '2022-04-01 00:00:00',if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%'),if(t0.fld_btp13 = 1, 'VAT 1.1%', 'VAT 11%')) 'vat_type',
    if(t0.fld_btp36 < 2, '', t10.fld_tyvalnm) 'reimburse',
    t0.fld_bttaxno 'tax_no'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1
    left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
    left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
    left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid = 72
    left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
    left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid = 173
    left join dnxapps.tbl_beaddr t9 on t9.fld_beid=t1.fld_beid and t9.fld_beaddrid=t0.fld_btp09 and t9.fld_beaddrstat=1
    left join tbl_tyval t10 on t10.fld_tyvalcd=t0.fld_btp36 and t10.fld_tyid = 115
    where
    t0.fld_btnoreff = '$btnoreff'
    and
    t0.fld_bttyid = 41
    GROUP BY btno
    ");
    $datarow = $getData->row();
    $data = $getData->result();
    // create some HTML content
           $pdf->Cell(0, 15, "", 0, 1, 'R', 0, '', 0);
   if ($datarow->forz == 2) {
       $pdf->SetFont('times', 'B', 17,'',true);
       $pdf->Cell(0, 0, "KWITANSI", 0, 0, 'L', 0, '', 0);
       $pdf->SetFont('times', 'B', 11, '', true);
       $pdf->Cell(0, 0, "B/L NO : $datarow->bl", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Job No : $btnoreff", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Aju    : $datarow->AjuPEB", 0, 1, 'R', 0, '', 0);

       #$pdf->Cell(0, 0, "AJU    : ", 0, 1, 'R', 0, '', 0);
   }else if($datarow->forz == 1){
       $pdf->SetFont('times', 'B', 17,'',true);
       $pdf->Cell(0, 10, "KWITANSI", 0, 0, 'L', 0, '', 0);
       $pdf->SetFont('times', 'B', 11, '', true);
       $pdf->Cell(0, 0, "B/L NO : $datarow->bl", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Job No : $btnoreff", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "PEB    : $datarow->AjuPEB", 0, 1, 'R', 0, '', 0);
       $pdf->Cell(0, 0, "Invoice Number    : $datarow->inv_no", 0, 1, 'R', 0, '', 0);
   }
     $pdf->Cell(0, 0, "", 0, 1, 'L', 0, '', 0);
     $pdf->SetFont('times', 'B', 12,'',true);
   $pdf->Cell(0, 10, "Nama Customer : $datarow->customer", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 11,'',true);
          $no =0;
          $nos = 0;

   $pdf->Cell(0, 10, "Untuk Pembayaran : ", 0, 1, 'L', 0, '', 0);
      $pdf->SetFont('helvetica', '', 8,'',true);

       foreach ($data as $rdata) {
                   $no = $no + 1;
           $pdf->Cell(0, 0, "$no."." $rdata->btno", 0, 1, 'L', 0, '', 0);
            $getDatadetails = $this->db->query("select t0.fld_btdesc 'desc' ,t0.fld_btamt01 'amt',t1.fld_btuamt 'vat'  from tbl_btd_finance t0
            left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
            where
            t1.fld_btno = '$rdata->btno'");
            $dataDtl_count = $getDatadetails->num_rows();
            $getDatadetail = $getDatadetails->result();
            $nos = 0;
            foreach ($getDatadetail as $rdetail) {
             #$nos = $nos + 1;
             // let's print the international format for the en_US locale
            setlocale(LC_MONETARY, 'id_ID');
            $pdf->Cell(5);
            if($nos <=  $dataDtl_count){

              $nos++;

            }
                $pdf->Cell(0, 0, "$nos. "."$rdetail->desc", 0, 1, 'L', 0, '', 0);
                #$pdf->Cell(0, 8, "$rdetail->desc -". money_format("%.2n"," $rdetail->amt"), 0, 1, 'L', 0, '', 0);
                $pdf->Cell(100);
                    $pdf->Cell(0, 0, "$rdata->curr_code", 0, 0, 'C', 0, '', 0);
                $pdf->Cell(0, 0, number_format("$rdetail->amt", 2, ",", "."), 0, 1, 'R', 0, '', 0);

          }
       }
            $getDatajumlah = $this->db->query("select t0.fld_btdesc 'desc' ,t0.fld_btamt01 'amt',t1.fld_btuamt 'vat' from tbl_btd_finance t0
            left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
            where
            t1.fld_btnoreff = '$btnoreff'
            order by t1.fld_btuamt desc")->row();
            $getTotal = $this->db->query("select t0.fld_btdesc 'desc' ,sum(t0.fld_btamt01) 'amt',t1.fld_btuamt 'vat',sum(t0.fld_btamt01 + t1.fld_btuamt) 'tot' from tbl_btd_finance t0
            left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
            where
            t1.fld_btnoreff = '$btnoreff'")->row();

            $pdf->Cell(0, 8,"--------------------------", 0, 1, 'R', 0, '', 0);
            $pdf->Cell(80);
            $pdf->Cell(0, 0,"SUBTOTAL".'                                                     '."Rp.", 0, 0, 'L', 0, '', 0);
            $pdf->Cell(10);
            #$pdf->Cell(0, 0, "Rp.", 0, 0, 'C',0, '', 0);
            $pdf->Cell(0, 0, number_format("$getTotal->amt",2,",","."), 0, 1, 'R', 0, '', 0);
            $pdf->Cell(80);
            $pdf->Cell(0, 0,"$datarow->vat_type".'                                                         '."Rp.", 0, 0, 'L', 0, '', 0);
            $pdf->Cell(0, 0, number_format("$getDatajumlah->vat",2,",","."), 0, 1, 'R', 0, '', 0);
            $pdf->Cell(0, 8,"--------------------------", 0, 1, 'R', 0, '', 0);
            $pdf->Cell(80);
            $Totalbesar = $getTotal->amt + $getDatajumlah->vat;
            $pdf->Cell(0, 0,"TOTAL".'                                                             '."Rp.", 0, 0, 'L', 0, '', 0);
            $pdf->Cell(10);
            #$pdf->Cell(0, 0, "Rp.", 0, 0, 'C',0, '', 0);
            $pdf->Cell(0, 0, number_format("$Totalbesar",2,",","."), 0, 1, 'R', 0, '', 0);
            $pdf->Cell(0, 29, "", 0, 1, 'L', 0, '', 0);
            $pdf->Cell(0, 15, "", 0, 1, 'L', 0, '', 0);

            $pdf->Cell(300, 5, "ELLY DWIYANTI   ", 0, 1, 'C', 0, '', 0);
            $pdf->Cell(303, 0, "  FINANCE SUPERVISOR        ", 0, 1, 'C', 0, '', 0);



   $pdf -> SetXY(4,1);


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
        ob_end_clean();
        ob_clean();
        //PDF::Output(base_url('upload/test.pdf'),'F');
        //$pdf->Output(base_url()."upload/test164.pdf", 'F');
        $pdf->Output('batchPrintInv.pdf');
    }



  function printINV($fld_btid,$gtk) {
    $stat = $this->db->query("select fld_btstat 'stat' from tbl_bth where fld_btid = '$fld_btid' limit 1")->row()->stat;
    if($stat !=3){
    $this->message("Transaction Status Must be Approved !</p>");
    }

    $flag = $this->db->query("select fld_btp38 'flag' from tbl_bth where fld_btid = '$fld_btid'")->row()->flag;
      $browser =  $_SERVER['HTTP_USER_AGENT'];
    if (strpos($browser,'Linux') !== false) {
      $browser =  'Linux';
    }

    if ($fld_btid == '1299889' || $fld_btid == '1233428') {
      $countrow = 8;
    } else {
      $countrow = 12;
    }

    $ctid=$this->session->userdata('ctid');
    $getData = $this->db->query("
    select
    t0.fld_btno 'btno',
    concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t1.fld_benm) 'customer',
    t0.fld_baido 'comp',
    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp19 'ex_inv',
    ifnull(t11.fld_tyvalnm,t0.fld_btdesc) 'remark',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t9.fld_beaddrplc != '',concat(t9.fld_beaddrplc,'\n'),'') , t9.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    if(t0.fld_btp13=5,concat(t0.fld_btcmt,' ','PPN DIBEBASKAN ATAS JASA'),t0.fld_btcmt) 'note',
    t0.fld_btdesc 'for',
    t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
    if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '','',format(t0.fld_btuamt,2))'vat_cstm',
    t0.fld_btp04 'peb',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_baidp 'posted',
    t7.fld_empnm 'postedby',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
    if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '','',if(t2.fld_tyvalnm ='IDR','Rp.','$'))'curr_code_cstm',
    if(t2.fld_tyvalnm ='IDR','Rupiah.','USD') 'curr_cd',
    if($ctid=976,'BOYKE SIREGAR',if(t0.fld_btloc = 7,'EKO PRASETYO','ELLY DWIYANTI')) 'ttd',
    if($ctid=976,'IMPORT COORDINATOR',if(t0.fld_btloc = 7,'FINANCE','FINANCE SUPERVISOR')) 'jabatan',
    if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%') 'vat_type',
    #if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '' ,'',if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%')) 'vat_type_cstm',
    if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '' ,'',if(t0.fld_btdt < '2022-04-01 00:00:00',if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%'),if(t0.fld_btp13 = 1, 'VAT 1.1%', 'VAT 11%'))) 'vat_type_cstm',
    if('$flag' =1,'',t6.fld_tyvalnm) 'freight',
    if(t0.fld_btp36 < 2, '', t10.fld_tyvalnm) 'reimburse',
    t0.fld_bttaxno 'tax_no'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1
    left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
    left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
    left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid = 72
    left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
    left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid = 173
    left join dnxapps.tbl_beaddr t9 on t9.fld_beid=t1.fld_beid and t9.fld_beaddrid=t0.fld_btp09 and t9.fld_beaddrstat=1
    left join tbl_tyval t10 on t10.fld_tyvalcd=t0.fld_btp36 and t10.fld_tyid = 115
    left join tbl_tyval t11 on t11.fld_tyvalcd=t0.fld_btp40 and t11.fld_tyid = 117
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $currency = $data->currency;
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'

    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701) or t0.fld_btflag = 1 ,1)
    order by 1

    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl_count = $getDataDtl->num_rows();
    $getDataDtl1 = $this->db->query("
    select
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701) or t0.fld_btflag = 1 ,1)
    order by 1
    limit 0,$countrow
    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl1_count = $getDataDtl1->num_rows();
    $getDataDtl2 = $this->db->query("
    select
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701) or t0.fld_btflag = 1 ,1)
    order by 1
    limit $countrow,30
    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl2_count = $getDataDtl2->num_rows();
    $getDataDtlpen = $this->db->query("
    select
    concat(1) 'count',
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    concat('')'unit',
    format(sum(t0.fld_btamt01),2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701) or t0.fld_btflag = 1 ,1)
    group by t0.fld_btidp

    ");

    $dataDtlpen_count = $getDataDtlpen->num_rows();

    $this->load->library('cezpdf');

   if ($browser == "Linux"){
   $h = 28.0;
  # $h1 =680;
  # $h2 =670;
  # $h3 =660;

   }else
   {
   $h =30.5;
  # $h1 =710;
  # $h2 =700;
  # $h3 =690;
   }

    $this->cezpdf->Cezpdf(array(21.5,$h),$orientation='portrait');
    if($browser == "Linux"){
   $this->cezpdf->ezSetMargins(90,5,10,15);
   $h1 =670;
   $h2 =660;
   $h3 =650;
    }else
    {
    $this->cezpdf->ezSetMargins(100,5,10,15);
   $h1 =710;
   $h2 =700;
   $h3 =690;
    }


    if ($dataDtl_count > $countrow && $flag != 1) {
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl1->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
      }
    }

    $this->cezpdf->addText(250,750,12,$data->reimburse);

    $this->cezpdf->addText(380,$h1,10,"No.");
    $this->cezpdf->addText(443,$h1,10,':'.$data->btno);
    $this->cezpdf->addText(380,$h2,10,"Date");
    $this->cezpdf->addText(443,$h2,10,':'.$data->date);
    $this->cezpdf->addText(380,$h3,10,"INV Type.");
    $this->cezpdf->addText(443,$h3,10,':'.$data->inv_type);

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>''),
                  array('row1'=>$data->customer,'row2'=>''),
                  array('row1'=>$data->address,'row2'=>''),

                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));

    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
          array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
          array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
          array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
          array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>': '.$data->do_no),
          array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>': '.$data->si_no),
                  array('row1'=>'Freight','row2'=>': '.$data->freight,'row3'=>'TAX Number','row4'=>': '.$data->tax_no),
                     );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl1,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10',
      'cols'=>array('count'=>array('width'=>30), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>100, 'justification'=>'right'),
      'curr_code'=>array('width'=>30, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);

      /*$data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'................................'),
                  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
                  array('row1'=>$data->vat_type ,'row2'=>$data->curr_code ,'row3'=>$data->vat),
                  array('row1'=> '','row3'=>'................................'),
                  array('row1'=>'TOTAL ' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>415,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>100,'justification'=>'right') )));
*/
      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      #$this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
      #$this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,115,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,10,'IDR   ');
      $this->cezpdf->addText(50,85,10,'USD   ');
      $this->cezpdf->addText(85,105,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      //$this->cezpdf->addText(85,95,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      //$this->cezpdf->addText(50,135,10,'Terbilang #'.$terbilang.' '.$data->curr_code.' #');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));
    #page 2
    $this->cezpdf->ezSetY(296);
    $this->cezpdf->ezNewPage();
    if ($getDataDtl->num_rows() > 0) {
      $datadtl2 = $getDataDtl2->result_array();
      $count = count($datadtl2);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl2[$i]['count'] = $counteor;
      }
    }

    $this->cezpdf->addText(250,750,12,$data->reimburse);

    $this->cezpdf->addText(380,$h1,10,"No.");
    $this->cezpdf->addText(443,$h1,10,':'.$data->btno);
    $this->cezpdf->addText(380,$h2,10,"Date");
    $this->cezpdf->addText(443,$h2,10,':'.$data->date);
    $this->cezpdf->addText(380,$h3,10,"INV Type.");
    $this->cezpdf->addText(443,$h3,10,':'.$data->inv_type);

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>''),
                  array('row1'=>$data->customer,'row2'=>''),
                  array('row1'=>$data->address,'row2'=>''),

                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));

    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
          array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
          array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
          array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
          array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>': '.$data->do_no),
          array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>': '.$data->si_no),
                  array('row1'=>'Freight','row2'=>': '.$data->freight,'row3'=>'TAX Number','row4'=>' :'.$data->tax_no),
                       );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl2,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>100, 'justification'=>'right'),
      'curr_code'=>array('width'=>30, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);

      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'.....................'),
                  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
                  array('row1'=>$data->vat_type ,'row2'=>$data->curr_code ,'row3'=>$data->vat),
                  array('row1'=> '','row3'=>'.....................'),
                  array('row1'=>'TOTAL ' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>438,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>75,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,115,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,10,'IDR   ');
      $this->cezpdf->addText(50,85,10,'USD   ');
      $this->cezpdf->addText(85,105,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      #$this->cezpdf->addText(85,95,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,135,10,'Terbilang #'.$terbilang.' '.$data->curr_cd.'#');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));


    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;

/*    $this->cezpdf->addText(380,710,10,"Under Construction");
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;
*/
    }
     else {


    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $datadtlpen = $getDataDtlpen->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
      }
    }

     $this->cezpdf->addText(250,750,12,$data->reimburse);


    $this->cezpdf->addText(380,$h1,10,"No.");
    $this->cezpdf->addText(443,$h1,10,':'.$data->btno);
    $this->cezpdf->addText(380,$h2,10,"Date");
    $this->cezpdf->addText(443,$h2,10,':'.$data->date);
    $this->cezpdf->addText(380,$h3,10,"INV Type.");
    $this->cezpdf->addText(443,$h3,10,':'.$data->inv_type);

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>''),
                  array('row1'=>$data->customer,'row2'=>''),
                  array('row1'=>$data->address,'row2'=>''),

                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));

    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
          array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
          array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
          array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
          array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>': '.$data->do_no),
          array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>': '.$data->si_no),
                  array('row1'=>'Freight','row2'=>': '.$data->freight,'row3'=>'TAX Number','row4'=>': '.$data->tax_no),
                          );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      if($flag == 1){
      /*$this->cezpdf->ezTable($datadtlpen,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right')))); ## upd t1215 */

      $this->cezpdf->ezTable($datadtl1,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);
      }else
      {

      $this->cezpdf->ezTable($datadtl1,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);
      }

      if($gtk == 100){
      $gtk_total = 0;
      foreach($getDataDtl->result() as $rdatadtl){
      $gtk_total = $gtk_total + $rdatadtl->fld_btamt01;


      }
      $data_sum = array(
                  array('row1'=>'SUBTOTAL' ,'row2'=>'' ,'row3'=>number_format($gtk_total,2,',','.')),
                  array('row1'=>''));

      } else {
      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'.....................'),
                  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
                  array('row1'=>$data->vat_type_cstm ,'row2'=>$data->curr_code_cstm ,'row3'=>$data->vat_cstm),
                  array('row1'=> '','row3'=>'.....................'),
                  array('row1'=>'TOTAL. ' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));
      }
      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'11',
          'cols'=>array('row1'=>array('width'=>430,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>85,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));
      if($browser =  "Linux"){
          $this->cezpdf->ezSetY(35);
      if($fld_btid == 1292939) {
      $this->cezpdf->addTextWrap(400,135,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,115,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,75,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,65,10,'IDR   ');
      $this->cezpdf->addText(50,45,10,'USD   ');
      $this->cezpdf->addText(85,65,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      //$this->cezpdf->addText(85,75,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,45,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,85,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,95,10,'Terbilang #'.$terbilang.''.$data->curr_cd.'#');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);


      } else {
      $this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,115,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,10,'IDR   ');
      $this->cezpdf->addText(50,85,10,'USD   ');
      $this->cezpdf->addText(85,105,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      //$this->cezpdf->addText(85,75,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,135,10,'Terbilang #'.$terbilang.''.$data->curr_cd.'#');

      $this->cezpdf->addText(410,40,8,'Created By : ' . $data->postedby);
       }
      }else
      {
      $this->cezpdf->ezSetY(35);
       if($fld_btid == 1292939) {
      $this->cezpdf->addTextWrap(400,135,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,115,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,75,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,65,10,'IDR   ');
      $this->cezpdf->addText(50,45,10,'USD   ');
      $this->cezpdf->addText(85,65,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      //$this->cezpdf->addText(85,75,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,45,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,85,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,95,10,'Terbilang #'.$terbilang.''.$data->curr_cd.'#');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);


      } else {

      $this->cezpdf->addTextWrap(400,155,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,135,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,95,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,85,10,'IDR   ');
      $this->cezpdf->addText(50,65,10,'USD   ');
      $this->cezpdf->addText(85,85,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  |  (665) 158645 (MUFG Bank,Ltd)');
      //$this->cezpdf->addText(85,75,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,65,10,'(665) 900452 (MUFG Bank,Ltd)');
      $this->cezpdf->addText(50,105,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,115,10,'Terbilang #'.$terbilang.''.$data->curr_cd.'#');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);
            }
      }
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;
   }
  }



   function printINV_mapi($fld_btid,$gtk) {
    $stat = $this->db->query("select fld_btstat 'stat' from tbl_bth where fld_btid = '$fld_btid' limit 1")->row()->stat;
    if($stat !=3){
    $this->message("Transaction Status Must be Approved !</p>");
    }

    $flag = $this->db->query("select fld_btp38 'flag' from tbl_bth where fld_btid = '$fld_btid'")->row()->flag;
	  $browser =  $_SERVER['HTTP_USER_AGENT'];
    if (strpos($browser,'Linux') !== false) {
      $browser =  'Linux';
    }

    $ctid=$this->session->userdata('ctid');
    $getData = $this->db->query("
    select
    t0.fld_btno 'btno',
    concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t1.fld_benm) 'customer',
    t0.fld_baido 'comp',
    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp19 'ex_inv',
    ifnull(t11.fld_tyvalnm,t0.fld_btdesc) 'remark',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t9.fld_beaddrplc != '',concat(t9.fld_beaddrplc,'\n'),'') , t9.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    t0.fld_btcmt 'note',
    t0.fld_btdesc 'for',
    t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
    t0.fld_btp04 'peb',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_baidp 'posted',
    t7.fld_empnm 'postedby',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
    if(t2.fld_tyvalnm ='IDR','Rupiah.','USD') 'curr_cd',
    if($ctid=976,'BOYKE SIREGAR',if(t0.fld_btloc = 7,'EKO PRASETYO','ELLY DWIYANTI')) 'ttd',
    if($ctid=976,'IMPORT COORDINATOR',if(t0.fld_btloc = 7,'FINANCE','FINANCE SUPERVISOR')) 'jabatan',
    if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%') 'vat_type',
    if('$flag' =1,'',t6.fld_tyvalnm) 'freight',
    if(t0.fld_btp36 < 2, '', t10.fld_tyvalnm) 'reimburse',
    t0.fld_bttaxno 'tax_no'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1
    left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
    left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
    left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid = 72
    left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
    left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid = 173
    left join dnxapps.tbl_beaddr t9 on t9.fld_beid=t1.fld_beid and t9.fld_beaddrid=t0.fld_btp09 and t9.fld_beaddrstat=1
    left join tbl_tyval t10 on t10.fld_tyvalcd=t0.fld_btp36 and t10.fld_tyid = 115
    left join tbl_tyval t11 on t11.fld_tyvalcd=t0.fld_btp40 and t11.fld_tyid = 117
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $currency = $data->currency;
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid ,
	t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'

    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    order by 1

    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl_count = $getDataDtl->num_rows();
    $getDataDtl1 = $this->db->query("
    select
    t0.fld_btid ,
	t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    order by 1
    limit 0,12
    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl1_count = $getDataDtl1->num_rows();
    $getDataDtl2 = $this->db->query("
    select
    t0.fld_btid ,
	t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
   # if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    order by 1
    limit 12,30
    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl2_count = $getDataDtl2->num_rows();
    $getDataDtlpen = $this->db->query("
    select
    concat(1) 'count',
    t0.fld_btid ,
	t0.fld_btdesc 'desc',
    #concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    concat('')'unit',
    format(sum(t0.fld_btamt01),2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    t0.fld_btamt01,
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'
    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and if('$gtk' = 100,t0.fld_coaid in (701,786) or t0.fld_btflag = 1 ,1)
    group by t0.fld_btidp

    ");

    $dataDtlpen_count = $getDataDtlpen->num_rows();

    $this->load->library('cezpdf');

   if ($browser == "Linux"){
   $h = 28.0;
  # $h1 =680;
  # $h2 =670;
  # $h3 =660;

   }else
   {
   $h =30.5;
  # $h1 =710;
  # $h2 =700;
  # $h3 =690;
   }

    $this->cezpdf->Cezpdf(array(21.5,$h),$orientation='portrait');
	if($browser == "Linux"){
   $this->cezpdf->ezSetMargins(90,5,10,15);
   $h1 =620;
   $h2 =610;
   $h3 =600;
	}else
	{
    $this->cezpdf->ezSetMargins(100,5,10,15);
   $h1 =660;
   $h2 =650;
   $h3 =640;
	}
    if ($dataDtl_count > 12 && $flag != 1) {
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl1->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
      }
    }

    $this->cezpdf->addText(250,750,12,$data->reimburse);

    $this->cezpdf->addText(380,$h1,10,"No.");
    $this->cezpdf->addText(443,$h1,10,':'.$data->btno);
    $this->cezpdf->addText(380,$h2,10,"Date");
    $this->cezpdf->addText(443,$h2,10,':'.$data->date);
    $this->cezpdf->addText(380,$h3,10,"INV Type.");
    $this->cezpdf->addText(443,$h3,10,':'.$data->inv_type);

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>''),
                  array('row1'=>$data->customer,'row2'=>''),
                  array('row1'=>$data->address,'row2'=>''),

                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));

    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
		  array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
		  array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
		  array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
		  array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>': '.$data->do_no),
		  array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>': '.$data->si_no),
                  array('row1'=>'Freight','row2'=>': '.$data->freight,'row3'=>'TAX Number','row4'=>': '.$data->tax_no),
                     );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl1,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10',
      'cols'=>array('count'=>array('width'=>30), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>100, 'justification'=>'right'),
      'curr_code'=>array('width'=>30, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);

      /*$data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'................................'),
                  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
                  array('row1'=>$data->vat_type ,'row2'=>$data->curr_code ,'row3'=>$data->vat),
                  array('row1'=> '','row3'=>'................................'),
                  array('row1'=>'TOTAL ' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>415,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>100,'justification'=>'right') )));
*/
      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      #$this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
      #$this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,115,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,10,'IDR   ');
      $this->cezpdf->addText(50,85,10,'USD   ');
      $this->cezpdf->addText(85,105,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      //$this->cezpdf->addText(85,95,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      //$this->cezpdf->addText(50,135,10,'Terbilang #'.$terbilang.' '.$data->curr_code.' #');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));
    #page 2
    $this->cezpdf->ezSetY(296);
    $this->cezpdf->ezNewPage();
    if ($getDataDtl->num_rows() > 0) {
      $datadtl2 = $getDataDtl2->result_array();
      $count = count($datadtl2);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl2[$i]['count'] = $counteor;
      }
    }

    $this->cezpdf->addText(250,750,12,$data->reimburse);

    $this->cezpdf->addText(380,$h1,10,"No.");
    $this->cezpdf->addText(443,$h1,10,':'.$data->btno);
    $this->cezpdf->addText(380,$h2,10,"Date");
    $this->cezpdf->addText(443,$h2,10,':'.$data->date);
    $this->cezpdf->addText(380,$h3,10,"INV Type.");
    $this->cezpdf->addText(443,$h3,10,':'.$data->inv_type);

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>''),
                  array('row1'=>$data->customer,'row2'=>''),
                  array('row1'=>$data->address,'row2'=>''),

                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));

    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
		  array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
		  array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
		  array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
		  array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>': '.$data->do_no),
		  array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>': '.$data->si_no),
                  array('row1'=>'Freight','row2'=>': '.$data->freight,'row3'=>'TAX Number','row4'=>' :'.$data->tax_no),
                       );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl2,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>100, 'justification'=>'right'),
      'curr_code'=>array('width'=>30, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);

      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'.....................'),
                  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
                  array('row1'=>$data->vat_type ,'row2'=>$data->curr_code ,'row3'=>$data->vat),
                  array('row1'=> '','row3'=>'.....................'),
                  array('row1'=>'TOTAL ' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>438,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>75,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
      $this->cezpdf->addText(50,115,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,10,'IDR   ');
      $this->cezpdf->addText(50,85,10,'USD   ');
      $this->cezpdf->addText(85,105,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      #$this->cezpdf->addText(85,95,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,135,10,'Terbilang #'.$terbilang.' '.$data->curr_cd.'#');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));


    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;

/*    $this->cezpdf->addText(380,710,10,"Under Construction");
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;
*/
    }
     else {


    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $datadtlpen = $getDataDtlpen->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
      }
    }

     #$this->cezpdf->addText(250,750,12,$data->reimburse);
   # $this->cezpdf->addJpegFromFile('images/logo.jpg',200,670,250);
    $this->cezpdf->addText(50,$h1,14,"KWITANSI");
    $this->cezpdf->addText(380,$h1,10,"Faktur No.");
    $this->cezpdf->addText(443,$h1,10,':'.$data->btno);
    $this->cezpdf->addText(380,$h2,10,"Invoice No");
    $this->cezpdf->addText(443,$h2,10,':'.$data->inv_no);
    $this->cezpdf->addText(50,558,10,"Untuk Pembayaran");

    $this->cezpdf->ezSetDy(-120);
    $data_prn = array(array('row1'=>'Nama Customer ', 'row2'=>$data->customer),


                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>110),'row2'=>array('width'=>220))));

  /*  $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
		  array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
		  array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
		  array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
		  array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>': '.$data->do_no),
		  array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>': '.$data->si_no),
                  array('row1'=>'Freight','row2'=>': '.$data->freight,'row3'=>'TAX Number','row4'=>': '.$data->tax_no),
                          );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));*/
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-40);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      if($flag == 1){
      $this->cezpdf->ezTable($datadtlpen,array('a'=>'','desc'=>'','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>312),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);
      }else
      {

      $this->cezpdf->ezTable($datadtl1,array('a'=>'','desc'=>'','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9.5',
      'cols'=>array('count'=>array('width'=>25), 'desc'=>array('width'=>212),'currency'=>array('width'=>1),
      'unit'=>array('width'=>110, 'justification'=>'right'),
      'curr_code'=>array('width'=>25, 'justification'=>'right'),'subtotal'=>array('width'=>75, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);
      }

      if($gtk == 100){
      $gtk_total = 0;
      foreach($getDataDtl->result() as $rdatadtl){
      $gtk_total = $gtk_total + $rdatadtl->fld_btamt01;


      }
      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'Rp' ,'row3'=>number_format($gtk_total,2,',','.')),
                  array('row1'=>''));

      } else {
      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'.....................'),
                  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
                  array('row1'=>$data->vat_type ,'row2'=>$data->curr_code ,'row3'=>$data->vat),
                  array('row1'=> '','row3'=>'.....................'),
                  array('row1'=>'TOTAL ' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));
      }
      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>430,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>75,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>'  ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));
      if($browser =  "Linux"){
		  $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,175,200,10,$data->ttd,'center');
	  $this->cezpdf->addTextWrap(400,155,200,10,$data->jabatan,'center');
     /* $this->cezpdf->addText(50,115,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,10,'IDR   ');
      $this->cezpdf->addText(50,85,10,'USD   ');
      $this->cezpdf->addText(85,105,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
      //$this->cezpdf->addText(85,75,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,10,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      #$this->cezpdf->addText(50,135,10,'Terbilang #'.$terbilang.''.$data->curr_cd.'#');*/

     # $this->cezpdf->addText(410,40,8,'Created By : ' . $data->postedby);
	  }else
	  {
      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,155,200,10,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,135,200,10,$data->jabatan,'center');
      /*$this->cezpdf->addText(50,95,10,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,85,10,'IDR   ');
      $this->cezpdf->addText(50,65,10,'USD   ');
      $this->cezpdf->addText(85,85,10,'4281360889 (BCA BANK CAB. SUNTER MALL)  |  (665) 158645 (MUFG Bank,Ltd)');
      //$this->cezpdf->addText(85,75,10,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,65,10,'(665) 900452 (MUFG Bank,Ltd)');
      $this->cezpdf->addText(50,105,10,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));*/
      #$this->cezpdf->addText(50,115,10,'Terbilang #'.$terbilang.''.$data->curr_cd.'#');

      #$this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);
	  }
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;
   }
  }



  function printINVMerge($fld_btid)
  {
      $stat = $this->db->query("select fld_btstat 'stat' from tbl_bth where fld_btid = '$fld_btid' limit 1")->row()->stat;
      if ($stat != 3) {
          $this->message("Transaction Status Must be Approved !</p>");
      }

      $flag = $this->db->query("select fld_btp38 'flag' from tbl_bth where fld_btid = '$fld_btid'")->row()->flag;
      $browser =  $_SERVER['HTTP_USER_AGENT'];
      if (strpos($browser, 'Linux') !== false) {
          $browser =  'Linux';
      }

      $ctid = $this->session->userdata('ctid');
      $getData = $this->db->query("
      select
      t0.fld_btno 'btno',
      concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t1.fld_benm) 'customer',
      t0.fld_baido 'comp',
      t0.fld_btp16 'qty',
      t0.fld_btp24 'vessel',
      t0.fld_btnoalt 'bl',
      t0.fld_btp11 'measure',
      t0.fld_btp17 'cont_no',
      t0.fld_btp19 'ex_inv',
      ifnull(t11.fld_tyvalnm,t0.fld_btdesc) 'remark',
      t0.fld_btp23 'inv_no',
      t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
      t0.fld_btp14 'reff_no',
      concat(if(t9.fld_beaddrplc != '',concat(t9.fld_beaddrplc,'\n'),'') , t9.fld_beaddrstr) 'address',
      t0.fld_btp22 'comm',
      t0.fld_btp18 'do_no',
      t0.fld_btp20 'si_no',
      t0.fld_btp06 'pol',
      t0.fld_btcmt 'note',
      t0.fld_btdesc 'for',
      t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
      format(t0.fld_btamt,2)'subamt',
      format(t0.fld_btuamt,2)'vat',
      t0.fld_btp04 'peb',
      date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
      date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
      date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
      t0.fld_baidp 'posted',
      t7.fld_empnm 'postedby',
      t2.fld_tyvalnm 'currency',
      if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
      if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
      if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
      if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
      if(t2.fld_tyvalnm ='IDR','Rupiah.','USD') 'curr_cd',
      if($ctid=976,'BOYKE SIREGAR',if(t0.fld_btloc = 7,'EKO PRASETYO','ELLY DWIYANTI')) 'ttd',
      if($ctid=976,'IMPORT COORDINATOR',if(t0.fld_btloc = 7,'FINANCE','FINANCE SUPERVISOR')) 'jabatan',
      if(t0.fld_btdt < '2022-04-01 00:00:00',if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%'),if(t0.fld_btp13 = 1, 'VAT 1.1%', 'VAT 11%')) 'vat_type',
      if('$flag' =1,'',t6.fld_tyvalnm) 'freight',
      if(t0.fld_btp36 < 2, '', t10.fld_tyvalnm) 'reimburse',
      t0.fld_bttaxno 'tax_no'
      from tbl_bth t0
      left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
      left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1
      left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
      left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
      left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
      left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid = 72
      left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
      left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid = 173
      left join dnxapps.tbl_beaddr t9 on t9.fld_beid=t1.fld_beid and t9.fld_beaddrid=t0.fld_btp09 and t9.fld_beaddrstat=1
      left join tbl_tyval t10 on t10.fld_tyvalcd=t0.fld_btp36 and t10.fld_tyid = 115
      left join tbl_tyval t11 on t11.fld_tyvalcd=t0.fld_btp40 and t11.fld_tyid = 117
      where
      t0.fld_btid='$fld_btid'
      ");
      $data = $getData->row();
      $currency = $data->currency;
      $getDataDtl = $this->db->query("
      select
      t0.fld_btid ,
      GROUP_CONCAT(concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) SEPARATOR ' + ') 'desc',
      if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(SUM(t0.fld_btuamt01),2),' x (',t0.fld_btqty01,')'),'') 'unit',
      format(SUM(t0.fld_btamt01),2) 'subtotal',
      t0.fld_btnoreff 'noreff',
      t0.fld_coaid 'coaid',
      if('$currency' = 'IDR','Rp.','$') 'curr_code'

      from tbl_btd_finance t0
      where
      t0.fld_btidp='$fld_btid' AND
      t0.fld_btp06!=''
      GROUP BY t0.fld_btp06

      ");
      $total_cost = 0;
      $counteor = 0;
      $no = 0;
      $dataDtl_count = $getDataDtl->num_rows();
      $getDataDtl1 = $this->db->query("
      select
      t0.fld_btid ,
      GROUP_CONCAT(concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) SEPARATOR ' + ') 'desc',
      if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(SUM(t0.fld_btuamt01),2),' x (',t0.fld_btqty01,')'),'') 'unit',
      format(SUM(t0.fld_btamt01),2) 'subtotal',
      t0.fld_btnoreff 'noreff',
      t0.fld_coaid 'coaid',
      if('$currency' = 'IDR','Rp.','$') 'curr_code'

      from tbl_btd_finance t0
      where
      t0.fld_btidp='$fld_btid' AND
      t0.fld_btp06!=''
      GROUP BY t0.fld_btp06
      limit 0,12
      ");
      $total_cost = 0;
      $counteor = 0;
      $no = 0;
      $dataDtl1_count = $getDataDtl1->num_rows();
      $getDataDtl2 = $this->db->query("
      select
      t0.fld_btid ,
      GROUP_CONCAT(concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) SEPARATOR ' + ') 'desc',
      if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(SUM(t0.fld_btuamt01),2),' x (',t0.fld_btqty01,')'),'') 'unit',
      format(SUM(t0.fld_btamt01),2) 'subtotal',
      t0.fld_btnoreff 'noreff',
      t0.fld_coaid 'coaid',
      if('$currency' = 'IDR','Rp.','$') 'curr_code'

      from tbl_btd_finance t0
      where
      t0.fld_btidp='$fld_btid' AND
      t0.fld_btp06!=''
      GROUP BY t0.fld_btp06
      limit 12,30
      ");
      $total_cost = 0;
      $counteor = 0;
      $no = 0;
      $dataDtl2_count = $getDataDtl2->num_rows();
      $getDataDtlpen = $this->db->query("
      select
      concat(1) 'count',
      t0.fld_btid ,
      concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
      #if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
      concat('')'unit',
      format(sum(t0.fld_btamt01),2) 'subtotal',
      #t0.fld_btqty01 'qty',
      t0.fld_btnoreff 'noreff',
      t0.fld_coaid 'coaid',
      #ifnull('$currency','Unknown') 'currency',
      if('$currency' = 'IDR','Rp.','$') 'curr_code'
      from tbl_btd_finance t0
      where
      t0.fld_btidp='$fld_btid'
      group by t0.fld_btidp
      ");

      $dataDtlpen_count = $getDataDtlpen->num_rows();

      $this->load->library('cezpdf');

      if ($browser == "Linux") {
          $h = 28.0;
      } else {
          $h = 30.5;
      }

      $this->cezpdf->Cezpdf(array(21.5, $h), $orientation = 'portrait');
      if ($browser == "Linux") {
          $this->cezpdf->ezSetMargins(90, 5, 10, 15);
          $h1 = 670;
          $h2 = 660;
          $h3 = 650;
      } else {
          $this->cezpdf->ezSetMargins(100, 5, 10, 15);
          $h1 = 710;
          $h2 = 700;
          $h3 = 690;
      }
      if ($dataDtl_count > 12 && $flag != 1) {
          if ($getDataDtl->num_rows() > 0) {
              $datadtl1 = $getDataDtl1->result_array();
              $count = count($datadtl1);
              for ($i = 0; $i < $count; ++$i) {
                  $counteor = $counteor + 1;
                  $no = $no + 1;
                  ###Prepare Data
                  $datadtl1[$i]['count'] = $counteor;
              }
          }

          $this->cezpdf->addText(250, 750, 12, $data->reimburse);

          $this->cezpdf->addText(380, $h1, 10, "No.");
          $this->cezpdf->addText(443, $h1, 10, ':' . $data->btno);
          $this->cezpdf->addText(380, $h2, 10, "Date");
          $this->cezpdf->addText(443, $h2, 10, ':' . $data->date);
          $this->cezpdf->addText(380, $h3, 10, "INV Type.");
          $this->cezpdf->addText(443, $h3, 10, ':' . $data->inv_type);

          $this->cezpdf->ezSetDy(-20);
          $data_prn = array(
              array('row1' => 'Messrs :', 'row2' => ''),
              array('row1' => $data->customer, 'row2' => ''),
              array('row1' => $data->address, 'row2' => ''),

          );
          $this->cezpdf->ezTable(
              $data_prn,
              array('row1' => '', 'row2' => ''),
              '',
              array(
                  'rowGap' => '0', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'xPos' => 50, 'xOrientation' => 'right', 'width' => 460, 'fontSize' => '10',
                  'cols' => array('row1' => array('width' => 270), 'row2' => array('width' => 230))
              )
          );

          $this->cezpdf->ezSetDy(-25);
          $data_cst = array(
              array('row1' => 'Job Number', 'row2' => ': ' . $data->inv_reff, 'row3' => 'Invoice Number', 'row4' => ': ' . $data->inv_no),
              array('row1' => 'Names of Goods ', 'row2' => ': ' . $data->comm, 'row3' => 'Measurement', 'row4' => ': ' . $data->measure),
              array('row1' => 'Quantity', 'row2' => ': ' . $data->qty, 'row3' => 'Remarks', 'row4' => ': ' . $data->remark),
              array('row1' => 'Ex Vessel', 'row2' => ': ' . $data->vessel, 'row3' => $data->doc_type2, 'row4' => ': ' . $data->pol),
              array('row1' => 'B/L No', 'row2' => ': ' . $data->bl, 'row3' => 'B/L Date', 'row4' => ': ' . $data->bl_date),
              array('row1' => $data->doc_type, 'row2' => ': ' . $data->peb, 'row3' => $data->doc_type1, 'row4' => ': ' . $data->pib_date),
              array('row1' => 'Cont. No', 'row2' => ': ' . $data->cont_no, 'row3' => 'D/O Number', 'row4' => ': ' . $data->do_no),
              array('row1' => 'EX.Invoice', 'row2' => ': ' . $data->ex_inv, 'row3' => 'S/I Number', 'row4' => ': ' . $data->si_no),
              array('row1' => 'Freight', 'row2' => ': ' . $data->freight, 'row3' => 'TAX Number', 'row4' => ': ' . $data->tax_no),
          );
          $this->cezpdf->ezTable(
              $data_cst,
              array('row1' => '', 'row2' => '', 'row3' => '', 'row4' => ''),
              '',
              array(
                  'rowGap' => '0', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '10',
                  'cols' => array('row1' => array('width' => 90), 'row2' => array('width' => 180), 'row3' => array('width' => 90), 'row4' => array('width' => 180))
              )
          );
          if ($dataDtl_count < 250) {
              $this->cezpdf->ezSetDY(-20);
              #Print Detail
              $this->cezpdf->setStrokeColor(0, 0, 0);
              $this->cezpdf->setLineStyle(1);
              $this->cezpdf->ezTable(
                  $datadtl1,
                  array('count' => '', 'desc' => 'Description of Cost', 'currency' => '', 'unit' => '', 'curr_code' => '', 'subtotal' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'shaded' => 0, 'fontSize' => '10',
                      'cols' => array(
                          'count' => array('width' => 30), 'desc' => array('width' => 312), 'currency' => array('width' => 1),
                          'unit' => array('width' => 100, 'justification' => 'right'),
                          'curr_code' => array('width' => 30, 'justification' => 'right'), 'subtotal' => array('width' => 75, 'justification' => 'right')
                      )
                  )
              );
              $this->cezpdf->ezSetDY(-5);

              $data_notes = array(
                  array('row1' => ' Note : ', 'row2' => $data->note)
              );

              $this->cezpdf->ezTable(
                  $data_notes,
                  array('row1' => '', 'row2' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '9',
                      'cols' => array('row1' => array('width' => 50, 'justification' => 'left'), 'row2' => array('width' => 300, 'justification' => 'left'))
                  )
              );

              $this->cezpdf->ezSetY(35);
              $this->cezpdf->addText(50, 115, 10, 'PLEASE TRANSFER TO OUR ACCOUNT : ');
              $this->cezpdf->addText(50, 105, 10, 'IDR   ');
              $this->cezpdf->addText(50, 85, 10, 'USD   ');
              $this->cezpdf->addText(85, 105, 10, '4281360889 (BCA BANK CAB. SUNTER MALL)  ');
              $this->cezpdf->addText(85, 85, 10, '4281929369 ( BCA BANK CAB. SUNTER MALL )');
              $this->cezpdf->addText(50, 125, 10, 'Revised invoice (1) week after received');
              $terbilang = ucwords($this->number_to_words($data->subtotal));

              $this->cezpdf->addText(410, 20, 8, 'Created By : ' . $data->postedby);
          }

          $this->cezpdf->ezTable(
              $data_summary,
              array('row1' => '', 'row2' => ''),
              '',
              array(
                  'rowGap' => '0', 'xPos' => 150, 'xOrientation' => 'right', 'width' => 450, 'shaded' => 0, 'fontSize' => '10', 'showLines' => 0,
                  'cols' => array('row1' => array('width' => 140), 'row2' => array('width' => 100, 'justification' => 'right'))
              )
          );
          #page 2
          $this->cezpdf->ezSetY(296);
          $this->cezpdf->ezNewPage();
          if ($getDataDtl->num_rows() > 0) {
              $datadtl2 = $getDataDtl2->result_array();
              $count = count($datadtl2);
              for ($i = 0; $i < $count; ++$i) {
                  $counteor = $counteor + 1;
                  $no = $no + 1;
                  ###Prepare Data
                  $datadtl2[$i]['count'] = $counteor;
              }
          }

          $this->cezpdf->addText(250, 750, 12, $data->reimburse);

          $this->cezpdf->addText(380, $h1, 10, "No.");
          $this->cezpdf->addText(443, $h1, 10, ':' . $data->btno);
          $this->cezpdf->addText(380, $h2, 10, "Date");
          $this->cezpdf->addText(443, $h2, 10, ':' . $data->date);
          $this->cezpdf->addText(380, $h3, 10, "INV Type.");
          $this->cezpdf->addText(443, $h3, 10, ':' . $data->inv_type);

          $this->cezpdf->ezSetDy(-20);
          $data_prn = array(
              array('row1' => 'Messrs :', 'row2' => ''),
              array('row1' => $data->customer, 'row2' => ''),
              array('row1' => $data->address, 'row2' => ''),
          );
          $this->cezpdf->ezTable(
              $data_prn,
              array('row1' => '', 'row2' => ''),
              '',
              array(
                  'rowGap' => '0', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'xPos' => 50, 'xOrientation' => 'right', 'width' => 460, 'fontSize' => '10',
                  'cols' => array('row1' => array('width' => 270), 'row2' => array('width' => 230))
              )
          );

          $this->cezpdf->ezSetDy(-25);
          $data_cst = array(
              array('row1' => 'Job Number', 'row2' => ': ' . $data->inv_reff, 'row3' => 'Invoice Number', 'row4' => ': ' . $data->inv_no),
              array('row1' => 'Names of Goods ', 'row2' => ': ' . $data->comm, 'row3' => 'Measurement', 'row4' => ': ' . $data->measure),
              array('row1' => 'Quantity', 'row2' => ': ' . $data->qty, 'row3' => 'Remarks', 'row4' => ': ' . $data->remark),
              array('row1' => 'Ex Vessel', 'row2' => ': ' . $data->vessel, 'row3' => $data->doc_type2, 'row4' => ': ' . $data->pol),
              array('row1' => 'B/L No', 'row2' => ': ' . $data->bl, 'row3' => 'B/L Date', 'row4' => ': ' . $data->bl_date),
              array('row1' => $data->doc_type, 'row2' => ': ' . $data->peb, 'row3' => $data->doc_type1, 'row4' => ': ' . $data->pib_date),
              array('row1' => 'Cont. No', 'row2' => ': ' . $data->cont_no, 'row3' => 'D/O Number', 'row4' => ': ' . $data->do_no),
              array('row1' => 'EX.Invoice', 'row2' => ': ' . $data->ex_inv, 'row3' => 'S/I Number', 'row4' => ': ' . $data->si_no),
              array('row1' => 'Freight', 'row2' => ': ' . $data->freight, 'row3' => 'TAX Number', 'row4' => ' :' . $data->tax_no),
          );
          $this->cezpdf->ezTable(
              $data_cst,
              array('row1' => '', 'row2' => '', 'row3' => '', 'row4' => ''),
              '',
              array(
                  'rowGap' => '0', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '10',
                  'cols' => array('row1' => array('width' => 90), 'row2' => array('width' => 180), 'row3' => array('width' => 90), 'row4' => array('width' => 180))
              )
          );
          if ($dataDtl_count < 250) {
              $this->cezpdf->ezSetDY(-20);
              #Print Detail
              $this->cezpdf->setStrokeColor(0, 0, 0);
              $this->cezpdf->setLineStyle(1);
              $this->cezpdf->ezTable(
                  $datadtl2,
                  array('count' => '', 'desc' => 'Description of Cost', 'currency' => '', 'unit' => '', 'curr_code' => '', 'subtotal' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'shaded' => 0, 'fontSize' => '10',
                      'cols' => array(
                          'count' => array('width' => 25), 'desc' => array('width' => 312), 'currency' => array('width' => 1),
                          'unit' => array('width' => 100, 'justification' => 'right'),
                          'curr_code' => array('width' => 30, 'justification' => 'right'), 'subtotal' => array('width' => 75, 'justification' => 'right')
                      )
                  )
              );
              $this->cezpdf->ezSetDY(-5);

              $data_sum = array(
                  array('row1' => '', 'row2' => '', 'row3' => '.....................'),
                  array('row1' => 'SUBTOTAL', 'row2' => $data->curr_code, 'row3' => $data->subamt),
                  array('row1' => $data->vat_type, 'row2' => $data->curr_code, 'row3' => $data->vat),
                  array('row1' => '', 'row3' => '.....................'),
                  array('row1' => 'TOTAL ', 'row2' => $data->curr_code, 'row3' => number_format($data->subtotal, 2, ',', '.')),
                  array('row1' => '', '0'),
                  array('row1' => '', ''),
                  array('row1' => '')
              );

              $this->cezpdf->ezTable(
                  $data_sum,
                  array('row1' => '', 'row2' => '', 'row3' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '10',
                      'cols' => array(
                          'row1' => array('width' => 438, 'justification' => 'right'), 'row2' => array('width' => 30, 'justification' => 'right'),
                          'row3' => array('width' => 75, 'justification' => 'right')
                      )
                  )
              );

              $data_notes = array(
                  array('row1' => ' Note : ', 'row2' => $data->note)
              );

              $this->cezpdf->ezTable(
                  $data_notes,
                  array('row1' => '', 'row2' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '9',
                      'cols' => array('row1' => array('width' => 50, 'justification' => 'left'), 'row2' => array('width' => 300, 'justification' => 'left'))
                  )
              );

              $this->cezpdf->ezSetY(35);
              $this->cezpdf->addTextWrap(400, 175, 200, 10, $data->ttd, 'center');
              $this->cezpdf->addTextWrap(400, 155, 200, 10, $data->jabatan, 'center');
              $this->cezpdf->addText(50, 115, 10, 'PLEASE TRANSFER TO OUR ACCOUNT : ');
              $this->cezpdf->addText(50, 105, 10, 'IDR   ');
              $this->cezpdf->addText(50, 85, 10, 'USD   ');
              $this->cezpdf->addText(85, 105, 10, '4281360889 (BCA BANK CAB. SUNTER MALL)  ');
              $this->cezpdf->addText(85, 85, 10, '4281929369 ( BCA BANK CAB. SUNTER MALL )');
              $this->cezpdf->addText(50, 125, 10, 'Revised invoice (1) week after received');
              $terbilang = ucwords($this->number_to_words($data->subtotal));
              $this->cezpdf->addText(50, 135, 10, 'Terbilang #' . $terbilang . ' ' . $data->curr_cd . '#');

              $this->cezpdf->addText(410, 20, 8, 'Created By : ' . $data->postedby);
          }

          $this->cezpdf->ezTable(
              $data_summary,
              array('row1' => '', 'row2' => ''),
              '',
              array(
                  'rowGap' => '0', 'xPos' => 150, 'xOrientation' => 'right', 'width' => 450, 'shaded' => 0, 'fontSize' => '10', 'showLines' => 0,
                  'cols' => array('row1' => array('width' => 140), 'row2' => array('width' => 100, 'justification' => 'right'))
              )
          );


          header("Content-type: application/pdf");
          header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
          header("Pragma: no-cache");
          header("Expires: 0");

          $output = $this->cezpdf->ezOutput();
          echo $output;
      } else {


          if ($getDataDtl->num_rows() > 0) {
              $datadtl1 = $getDataDtl->result_array();
              $datadtlpen = $getDataDtlpen->result_array();
              $count = count($datadtl1);
              for ($i = 0; $i < $count; ++$i) {
                  $counteor = $counteor + 1;
                  $no = $no + 1;
                  ###Prepare Data
                  $datadtl1[$i]['count'] = $counteor;
              }
          }

          $this->cezpdf->addText(250, 750, 12, $data->reimburse);


          $this->cezpdf->addText(380, $h1, 10, "No.");
          $this->cezpdf->addText(443, $h1, 10, ':' . $data->btno);
          $this->cezpdf->addText(380, $h2, 10, "Date");
          $this->cezpdf->addText(443, $h2, 10, ':' . $data->date);
          $this->cezpdf->addText(380, $h3, 10, "INV Type.");
          $this->cezpdf->addText(443, $h3, 10, ':' . $data->inv_type);

          $this->cezpdf->ezSetDy(-20);
          $data_prn = array(
              array('row1' => 'Messrs :', 'row2' => ''),
              array('row1' => $data->customer, 'row2' => ''),
              array('row1' => $data->address, 'row2' => ''),

          );
          $this->cezpdf->ezTable(
              $data_prn,
              array('row1' => '', 'row2' => ''),
              '',
              array(
                  'rowGap' => '0', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'xPos' => 50, 'xOrientation' => 'right', 'width' => 460, 'fontSize' => '10',
                  'cols' => array('row1' => array('width' => 270), 'row2' => array('width' => 230))
              )
          );

          $this->cezpdf->ezSetDy(-25);
          $data_cst = array(
              array('row1' => 'Job Number', 'row2' => ': ' . $data->inv_reff, 'row3' => 'Invoice Number', 'row4' => ': ' . $data->inv_no),
              array('row1' => 'Names of Goods ', 'row2' => ': ' . $data->comm, 'row3' => 'Measurement', 'row4' => ': ' . $data->measure),
              array('row1' => 'Quantity', 'row2' => ': ' . $data->qty, 'row3' => 'Remarks', 'row4' => ': ' . $data->remark),
              array('row1' => 'Ex Vessel', 'row2' => ': ' . $data->vessel, 'row3' => $data->doc_type2, 'row4' => ': ' . $data->pol),
              array('row1' => 'B/L No', 'row2' => ': ' . $data->bl, 'row3' => 'B/L Date', 'row4' => ': ' . $data->bl_date),
              array('row1' => $data->doc_type, 'row2' => ': ' . $data->peb, 'row3' => $data->doc_type1, 'row4' => ': ' . $data->pib_date),
              array('row1' => 'Cont. No', 'row2' => ': ' . $data->cont_no, 'row3' => 'D/O Number', 'row4' => ': ' . $data->do_no),
              array('row1' => 'EX.Invoice', 'row2' => ': ' . $data->ex_inv, 'row3' => 'S/I Number', 'row4' => ': ' . $data->si_no),
              array('row1' => 'Freight', 'row2' => ': ' . $data->freight, 'row3' => 'TAX Number', 'row4' => ': ' . $data->tax_no),
          );
          $this->cezpdf->ezTable(
              $data_cst,
              array('row1' => '', 'row2' => '', 'row3' => '', 'row4' => ''),
              '',
              array(
                  'rowGap' => '0', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => 0, 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '10',
                  'cols' => array('row1' => array('width' => 90), 'row2' => array('width' => 180), 'row3' => array('width' => 90), 'row4' => array('width' => 180))
              )
          );
          if ($dataDtl_count < 250) {
              $this->cezpdf->ezSetDY(-20);
              #Print Detail
              $this->cezpdf->setStrokeColor(0, 0, 0);
              $this->cezpdf->setLineStyle(1);
              if ($flag == 1) {
                  $this->cezpdf->ezTable(
                      $datadtlpen,
                      array('count' => '', 'desc' => 'Description of Cost', 'currency' => '', 'unit' => '', 'curr_code' => '', 'subtotal' => ''),
                      '',
                      array(
                          'rowGap' => '0.3', 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'shaded' => 0, 'fontSize' => '9.5',
                          'cols' => array(
                              'count' => array('width' => 25), 'desc' => array('width' => 312), 'currency' => array('width' => 1),
                              'unit' => array('width' => 110, 'justification' => 'right'),
                              'curr_code' => array('width' => 25, 'justification' => 'right'), 'subtotal' => array('width' => 75, 'justification' => 'right')
                          )
                      )
                  );
                  $this->cezpdf->ezSetDY(-5);
              } else {

                  $this->cezpdf->ezTable(
                      $datadtl1,
                      array('count' => '', 'desc' => 'Description of Cost', 'currency' => '', 'unit' => '', 'curr_code' => '', 'subtotal' => ''),
                      '',
                      array(
                          'rowGap' => '0.3', 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'shaded' => 0, 'fontSize' => '9.5',
                          'cols' => array(
                              'count' => array('width' => 25), 'desc' => array('width' => 312), 'currency' => array('width' => 1),
                              'unit' => array('width' => 110, 'justification' => 'right'),
                              'curr_code' => array('width' => 25, 'justification' => 'right'), 'subtotal' => array('width' => 75, 'justification' => 'right')
                          )
                      )
                  );
                  $this->cezpdf->ezSetDY(-5);
              }
              $data_sum = array(
                  array('row1' => '', 'row2' => '', 'row3' => '.....................'),
                  array('row1' => 'SUBTOTAL', 'row2' => $data->curr_code, 'row3' => $data->subamt),
                  array('row1' => $data->vat_type, 'row2' => $data->curr_code, 'row3' => $data->vat),
                  array('row1' => '', 'row3' => '.....................'),
                  array('row1' => 'TOTAL ', 'row2' => $data->curr_code, 'row3' => number_format($data->subtotal, 2, ',', '.')),
                  array('row1' => '', '0'),
                  array('row1' => '', ''),
                  array('row1' => '')
              );

              $this->cezpdf->ezTable(
                  $data_sum,
                  array('row1' => '', 'row2' => '', 'row3' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '10',
                      'cols' => array(
                          'row1' => array('width' => 438, 'justification' => 'right'), 'row2' => array('width' => 30, 'justification' => 'right'),
                          'row3' => array('width' => 75, 'justification' => 'right')
                      )
                  )
              );

              $data_notes = array(
                  array('row1' => ' Note : ', 'row2' => $data->note)
              );

              $this->cezpdf->ezTable(
                  $data_notes,
                  array('row1' => '', 'row2' => ''),
                  '',
                  array(
                      'rowGap' => '0.3', 'showHeadings' => 0, 'shaded' => 0, 'showLines' => '0', 'xPos' => 50, 'xOrientation' => 'right', 'width' => 500, 'fontSize' => '9',
                      'cols' => array('row1' => array('width' => 50, 'justification' => 'left'), 'row2' => array('width' => 300, 'justification' => 'left'))
                  )
              );
              if ($browser =  "Linux") {
                  $this->cezpdf->ezSetY(35);
                  $this->cezpdf->addTextWrap(400, 175, 200, 10, $data->ttd, 'center');
                  $this->cezpdf->addTextWrap(400, 155, 200, 10, $data->jabatan, 'center');
                  $this->cezpdf->addText(50, 115, 10, 'PLEASE TRANSFER TO OUR ACCOUNT : ');
                  $this->cezpdf->addText(50, 105, 10, 'IDR   ');
                  $this->cezpdf->addText(50, 85, 10, 'USD   ');
                  $this->cezpdf->addText(85, 105, 10, '4281360889 (BCA BANK CAB. SUNTER MALL)  ');
                  $this->cezpdf->addText(85, 85, 10, '4281929369 ( BCA BANK CAB. SUNTER MALL )');
                  $this->cezpdf->addText(50, 125, 10, 'Revised invoice (1) week after received');
                  $terbilang = ucwords($this->number_to_words($data->subtotal));
                  $this->cezpdf->addText(50, 135, 10, 'Terbilang #' . $terbilang . '' . $data->curr_cd . '#');

                  $this->cezpdf->addText(410, 40, 8, 'Created By : ' . $data->postedby);
              } else {
                  $this->cezpdf->ezSetY(35);
                  $this->cezpdf->addTextWrap(400, 155, 200, 10, $data->ttd, 'center');
                  $this->cezpdf->addTextWrap(400, 135, 200, 10, $data->jabatan, 'center');
                  $this->cezpdf->addText(50, 95, 10, 'PLEASE TRANSFER TO OUR ACCOUNT : ');
                  $this->cezpdf->addText(50, 85, 10, 'IDR   ');
                  $this->cezpdf->addText(50, 65, 10, 'USD   ');
                  $this->cezpdf->addText(85, 85, 10, '4281360889 (BCA BANK CAB. SUNTER MALL)  |  (665) 158645 (MUFG Bank,Ltd)');
                  $this->cezpdf->addText(85, 65, 10, '(665) 900452 (MUFG Bank,Ltd)');
                  $this->cezpdf->addText(50, 105, 10, 'Revised invoice (1) week after received');
                  $terbilang = ucwords($this->number_to_words($data->subtotal));
                  $this->cezpdf->addText(50, 115, 10, 'Terbilang #' . $terbilang . '' . $data->curr_cd . '#');

                  $this->cezpdf->addText(410, 20, 8, 'Created By : ' . $data->postedby);
              }
          }

          $this->cezpdf->ezTable(
              $data_summary,
              array('row1' => '', 'row2' => ''),
              '',
              array(
                  'rowGap' => '0', 'xPos' => 150, 'xOrientation' => 'right', 'width' => 450, 'shaded' => 0, 'fontSize' => '10', 'showLines' => 0,
                  'cols' => array('row1' => array('width' => 140), 'row2' => array('width' => 100, 'justification' => 'right'))
              )
          );

          header("Content-type: application/pdf");
          header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
          header("Pragma: no-cache");
          header("Expires: 0");

          $output = $this->cezpdf->ezOutput();
          echo $output;
      }
  }



  function PostingInvoiceDepo($fld_btid,$dtsa,$dtso,$fld_btiid) {
    $fld_baidp =  $location = $this->session->userdata('ctid');
    $this->db->query("update tbl_btd_faktur set fld_btreffid = 0, fld_flagid = 0 where fld_btreffid = $fld_btid");
    $data = $this->db->query("select t0.fld_btid,t1.fld_btdt,
			     t0.fld_btreffid,t0.fld_dt01
                             from tbl_btd_faktur t0
			     left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp and t1.fld_bttyid=88
                             where

			     date_format(t1.fld_btdt,'%Y-%m-%d') between date_format('$dtsa','%Y-%m-%d')
                             and date_format('$dtso','%Y-%m-%d')
                             ");
    $data = $data->result();
    foreach ($data as $rdata) {
          $this->db->query("update tbl_btd_faktur set fld_btreffid=$fld_btid,fld_flagid=1 where fld_btid=$rdata->fld_btid limit 1 ");
    }
   }


  function printINV2($fld_btid) {
    $getData = $this->db->query("
    select
    t0.fld_btno 'btno',
    concat(if(t1.fld_beprefix > 0,concat(t4.fld_tyvalnm,'. '),''),t1.fld_benm) 'customer',
    t0.fld_baido 'comp',
    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp19 'ex_inv',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t3.fld_beaddrplc != '',concat(t3.fld_beaddrplc,'\n'),'') , t3.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    t0.fld_btcmt 'note',
    t0.fld_btdesc 'for',
    t0.fld_btbalance'subtotal',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
    t0.fld_btp04 'peb',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_baidp 'posted',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
    if(t2.fld_tyvalnm ='IDR','Rupiah.','USD') 'currcode',
    ('ELLY DWIYANTI') 'ttd',
    ('FINANCE SUPERVISOR') 'jabatan',
    if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%') 'vat_type'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1
    left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join dnxapps.tbl_tyval t4 on t4.fld_tyvalcd = t1.fld_beprefix and t4.fld_tyid=173
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $currency = $data->currency;
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) 'desc',
    if(t0.fld_btflag = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    t0.fld_btamt01,
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'

    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
    and t0.fld_btp04 = 1
    ORDER BY t0.fld_btid ASC
    ");
    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $date_trans = date("ym");
    $seq_number = (substr($data->btno,13,5)+90000);
    $fld_btno = "DET/INV/" . $date_trans . "/" . $seq_number;
    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,30.5),$orientation='portrait');
    $this->cezpdf->ezSetMargins(100,5,10,15);
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
        $subtotal = $subtotal + $datadtl1[$i]['fld_btamt01'];
      }
    }

    $this->cezpdf->addText(408,710,10,"No.");
    $this->cezpdf->addText(433,710,10," :");
    $this->cezpdf->addText(443,710,10,$fld_btno);
    $this->cezpdf->addText(408,700,10,"Date");
    $this->cezpdf->addText(433,700,10," : ");
    $this->cezpdf->addText(443,700,10,$data->date);

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>''),
                  array('row1'=>$data->customer),
                  array('row1'=>$data->address,'row2'=>''),

                          );
$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));

    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'', 'row2'=>'','row3'=>'','row4'=>' '),
                  array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),
                  array('row1'=>'','row2'=>'','row3'=>'','row4'=>' '),
		  array('row1'=>'','row2'=>'','row3'=>'','row4'=>' '),
		  array('row1'=>'','row2'=>' ','row3'=>'','row4'=>' '),
		  array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),
		  array('row1'=>'','row2'=>' ','row3'=>'','row4'=>''),
		  array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),
                          );
$this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));
    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDY(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl1,array('count'=>'','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10',
      'cols'=>array('count'=>array('width'=>30), 'desc'=>array('width'=>275),'currency'=>array('width'=>1),
      'unit'=>array('width'=>94, 'justification'=>'right'),
      'curr_code'=>array('width'=>45, 'justification'=>'right'),'subtotal'=>array('width'=>100, 'justification'=>'right'))));
      $this->cezpdf->ezSetDY(-5);

      $data_sum = array(
                  array('row1'=>'' ,'row2'=>'' ,'row3'=>'................................'),
                  array('row1'=>'TOTAL ' ,'row2'=>$data->curr_code ,'row3'=>number_format($subtotal,2,',','.')),
                  array('row1'=>'','0'),
                  array('row1'=>'',''),
                  array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>415,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>100,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'10',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,120,200,10,'JOHAN','center');
      $this->cezpdf->addTextWrap(400,100,200,10,'( FINANCE MANAGER )','center');
    }

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-DET-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;

  }

   function print_journal($fld_btid) {
    $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
    select distinct
    t0.fld_journalid,
    t0.fld_journalno,
    date_format(t0.fld_journaldt,'%d/%m/%Y') 'journal_date',
    t0.fld_journaldesc 'desc',
    concat(t0.fld_journaldesc,'-',t0.fld_jo) 'desc2',
    t0.fld_btdocreff 'reff',
    if(t0.fld_journalamt>0,format(t0.fld_journalamt,2),0)'dbt',
    if(t0.fld_journalamt<0,format(abs(t0.fld_journalamt),2),0)'kdt',
    if(t0.fld_journalamt>0,t0.fld_journalamt,0)'debet',
    if(t0.fld_journalamt<0,abs(t0.fld_journalamt),0)'kredit',

    #if(t3.fld_coagrp not in(1,2),format(abs(t0.fld_journalamt),0),'') 'dbt',
    #if(t3.fld_coagrp in(1,2),format(abs(t0.fld_journalamt),0),'') 'kdt',
    #if(t3.fld_coagrp not in(1,2),abs(t0.fld_journalamt),'') 'debet',
    #if(t3.fld_coagrp in(1,2),abs(t0.fld_journalamt),'') 'kredit',

    t2.fld_bttynm,
    t1.fld_btno,
    t1.fld_btdesc notes,
    t3.fld_coacd,
    t3.fld_coanm,
    format(t0.fld_journalrate,0) 'rate',
    concat(if(t4.fld_beprefix > 0,concat(t14.fld_tyvalnm,'. '),''),t4.fld_benm) 'cust',
    t1.fld_btp23 'usercomp'
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    left join dnxapps.tbl_be t4 on t4.fld_beid = t1.fld_baidc
    left join dnxapps.tbl_tyval t14 on t14.fld_tyvalcd = t4.fld_beprefix and t14.fld_tyid=173
    where
    t0.fld_btid='$fld_btid'
    order by t3.fld_coacd desc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_debet = 0;
    $tot_kredit = 0;
    $count = count($detail);
      for ($i=0; $i<$count; ++$i) {
        $tot_debet = $tot_debet + $detail[$i]['debet'];
         $tot_kredit = $tot_kredit + $detail[$i]['kredit'];
      }


    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
    if($data->usercomp != 1) {
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,335,35);
        $this->cezpdf->addText(80,360,9,'PT.Dunia Express     ');
        $this->cezpdf->addText(80,350,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,340,9,'Jakarta Utara');
    }
    else {
	$this->cezpdf->addText(80,360,9,'PT.Rema Logistik Indonesia     ');
        $this->cezpdf->addText(80,350,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,340,9,'Jakarta Utara');

    }

    $this->cezpdf->ezSetMargins(0,15,10,5);

    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
    array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'shaded','xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));

        $this->cezpdf->line(10, 275, 600, 275);
         $this->cezpdf->line(10, 255, 600, 255);
        $this->cezpdf->line(450, 50, 600, 50);
        $this->cezpdf->line(10, 275, 10, 75);
         $this->cezpdf->line(85, 275, 85, 75);
         $this->cezpdf->line(345, 275, 345, 75);
         $this->cezpdf->line(450, 275, 450, 50);
        $this->cezpdf->line(10, 75, 600, 75);
         $this->cezpdf->line(527, 275, 527, 50);
        $this->cezpdf->line(600, 275, 600, 50);


        $this->cezpdf->addText(30,310,10,'Journal Number');
        $this->cezpdf->addText(110,310,10,':');
        $this->cezpdf->addText(120,310,10,$data->fld_journalno);
        $this->cezpdf->addText(300,280,10,'Customer');
        $this->cezpdf->addText(370,280,10,':');
        $this->cezpdf->addText(380,280,10,$data->cust);

        $this->cezpdf->addText(30,300,10,'Date');
        $this->cezpdf->addText(110,300,10,':');
        $this->cezpdf->addText(120,300,10,$data->journal_date);
        $this->cezpdf->addText(30,290,10,'Reff. Number');
        $this->cezpdf->addText(110,290,10,':');
        $this->cezpdf->addText(120,290,10,$data->fld_btno);
 $this->cezpdf->addText(30,280,10,'Transaction Type');
        $this->cezpdf->addText(110,280,10,':');
        $this->cezpdf->addText(120,280,10,$data->fld_bttynm);

        $this->cezpdf->addText(15,260,10,'Account Code');
        $this->cezpdf->addText(380,260,10,'Reff No');
        $this->cezpdf->addText(190,260,10,'Description');
        $this->cezpdf->addText(480,260,10,'Debet');
        $this->cezpdf->addText(550,260,10,'Kredit');
        $this->cezpdf->addText(380,60,10,'Total Balance');
        $this->cezpdf->addText(470,60,9,number_format($tot_debet, 0, ',', ','));
        $this->cezpdf->addText(540,60,9,number_format($tot_kredit, 0, ',', ','));
        $this->cezpdf->addText(30,60,9,'Rate:');
        $this->cezpdf->addText(60,60,9,$data->rate);
        $this->cezpdf->addText(30,50,8,'Notes:');
        $this->cezpdf->addText(60,50,8,$data->notes);


        $this->cezpdf->ezSetDy(-75);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'','desc2'=>'','reff'=>'','dbt'=>'','kdt'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>30,'xOrientation'=>'right','width'=>780,'shaded'=>0,'fontSize'=>'9',
    'cols'=>array('fld_coacd'=>array('width'=>70), 'desc2'=>array('width'=>255), 'reff'=>array('width'=>90),'dbt'=>array('width'=>80, 'justification'=>'right'),'kdt'=>array('width'=>80, 'justification'=>'right'),)));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }
 function print_bo_journal($fld_btid) {
    $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
    select distinct
    t0.fld_journalid,
    t0.fld_journalno,
    date_format(t0.fld_journaldt,'%d/%m/%Y') 'journal_date',
    t0.fld_journaldesc 'desc',
    t0.fld_btdocreff 'reff',
    if(t0.fld_journalamt>0,format(t0.fld_journalamt,2),0)'dbt',
    if(t0.fld_journalamt<0,format(abs(t0.fld_journalamt),2),0)'kdt',
    if(t0.fld_journalamt>0,t0.fld_journalamt,0)'debet',
    if(t0.fld_journalamt<0,abs(t0.fld_journalamt),0)'kredit',

    #if(t3.fld_coagrp not in(1,2),format(abs(t0.fld_journalamt),0),'') 'dbt',
    #if(t3.fld_coagrp in(1,2),format(abs(t0.fld_journalamt),0),'') 'kdt',
    #if(t3.fld_coagrp not in(1,2),abs(t0.fld_journalamt),'') 'debet',
    #if(t3.fld_coagrp in(1,2),abs(t0.fld_journalamt),'') 'kredit',

    t2.fld_bttynm,
    t1.fld_btno,
    t1.fld_btdesc notes,
    t3.fld_coacd,
    t3.fld_coanm,
    format(t0.fld_journalrate,0) 'rate',
    concat(if(t4.fld_beprefix > 0,concat(t5.fld_tyvalnm, '. '),''), t4.fld_benm) 'cust',
    t1.fld_btp23 'usercomp'
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    left join dnxapps.tbl_be t4 on t4.fld_beid = t1.fld_baidc
    left join dnxapps.tbl_tyval t5 on t5.fld_tyvalcd = t4.fld_beprefix and t5.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    order by t3.fld_coacd desc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_debet = 0;
    $tot_kredit = 0;
    $count = count($detail);
      for ($i=0; $i<$count; ++$i) {
        $tot_debet = $tot_debet + $detail[$i]['debet'];
         $tot_kredit = $tot_kredit + $detail[$i]['kredit'];
      }


    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
    if($data->usercomp != 1) {
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,335,35);
        $this->cezpdf->addText(80,360,9,'PT.Dunia Express     ');
        $this->cezpdf->addText(80,350,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,340,9,'Jakarta Utara');
    }
    else {
        $this->cezpdf->addText(80,360,9,'PT.Rema Logistik Indonesia     ');
        $this->cezpdf->addText(80,350,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,340,9,'Jakarta Utara');

    }
    $this->cezpdf->ezSetMargins(0,15,10,5);

    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
    array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'shaded','xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));

        $this->cezpdf->line(10, 275, 600, 275);
         $this->cezpdf->line(10, 255, 600, 255);
        $this->cezpdf->line(450, 50, 600, 50);
        $this->cezpdf->line(10, 275, 10, 75);
         $this->cezpdf->line(85, 275, 85, 75);
         $this->cezpdf->line(345, 275, 345, 75);
         $this->cezpdf->line(450, 275, 450, 50);
        $this->cezpdf->line(10, 75, 600, 75);
         $this->cezpdf->line(527, 275, 527, 50);
        $this->cezpdf->line(600, 275, 600, 50);


        $this->cezpdf->addText(30,310,10,'Journal Number');
        $this->cezpdf->addText(110,310,10,':');
        $this->cezpdf->addText(120,310,10,$data->fld_journalno);
        $this->cezpdf->addText(300,280,10,'Customer');
        $this->cezpdf->addText(370,280,10,':');
        $this->cezpdf->addText(380,280,10,$data->cust);

        $this->cezpdf->addText(30,300,10,'Date');
        $this->cezpdf->addText(110,300,10,':');
        $this->cezpdf->addText(120,300,10,$data->journal_date);
        $this->cezpdf->addText(30,290,10,'Reff. Number');
        $this->cezpdf->addText(110,290,10,':');
        $this->cezpdf->addText(120,290,10,$data->fld_btno);
 $this->cezpdf->addText(30,280,10,'Transaction Type');
        $this->cezpdf->addText(110,280,10,':');
        $this->cezpdf->addText(120,280,10,$data->fld_bttynm);

        $this->cezpdf->addText(15,260,10,'Account Code');
        $this->cezpdf->addText(380,260,10,'Reff No');
        $this->cezpdf->addText(190,260,10,'Description');
        $this->cezpdf->addText(480,260,10,'Debet');
        $this->cezpdf->addText(550,260,10,'Kredit');
        $this->cezpdf->addText(380,60,10,'Total Balance');
        $this->cezpdf->addText(470,60,9,number_format($tot_debet, 0, ',', ','));
        $this->cezpdf->addText(540,60,9,number_format($tot_kredit, 0, ',', ','));
        $this->cezpdf->addText(30,60,9,'Rate:');
        $this->cezpdf->addText(60,60,9,$data->rate);
        $this->cezpdf->addText(30,50,8,'Notes:');
        $this->cezpdf->addText(60,50,8,$data->notes);


        $this->cezpdf->ezSetDy(-75);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'','desc'=>'','reff'=>'','dbt'=>'','kdt'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>30,'xOrientation'=>'right','width'=>780,'shaded'=>0,'fontSize'=>'9',
    'cols'=>array('fld_coacd'=>array('width'=>70), 'desc'=>array('width'=>255), 'reff'=>array('width'=>90),'dbt'=>array('width'=>80, 'justification'=>'right'),'kdt'=>array('width'=>80, 'justification'=>'right'),)));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }

 function print_journal2($fld_btid) {
    $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
    select distinct
    t0.fld_journalid,
    t0.fld_journalno,
    date_format(t0.fld_journaldt,'%d/%m/%Y') 'journal_date',
    t0.fld_journaldesc 'desc',
    t0.fld_btdocreff 'reff',
    if(t0.fld_journalamt>0,format(t0.fld_journalamt,2),0)'dbt',
    if(t0.fld_journalamt<0,format(abs(t0.fld_journalamt),2),0)'kdt',
    if(t0.fld_journalamt>0,t0.fld_journalamt,0)'debet',
    if(t0.fld_journalamt<0,abs(t0.fld_journalamt),0)'kredit',

    #if(t3.fld_coagrp not in(1,2),format(abs(t0.fld_journalamt),0),'') 'dbt',
    #if(t3.fld_coagrp in(1,2),format(abs(t0.fld_journalamt),0),'') 'kdt',
    #if(t3.fld_coagrp not in(1,2),abs(t0.fld_journalamt),'') 'debet',
    #if(t3.fld_coagrp in(1,2),abs(t0.fld_journalamt),'') 'kredit',

    t2.fld_bttynm,
    t1.fld_btno,
    t1.fld_btdesc notes,
    t3.fld_coacd,
    t3.fld_coanm,
    format(t0.fld_journalrate,0) 'rate',
    concat(if(t4.fld_beprefix > 0,concat(t5.fld_tyvalnm, '. '),''), t4.fld_benm) 'cust'
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    left join dnxapps.tbl_be t4 on t4.fld_beid = t1.fld_baidc
    left join dnxapps.tbl_tyval t5 on t5.fld_tyvalcd = t4.fld_beprefix and t5.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    order by t3.fld_coacd desc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_debet = 0;
    $tot_kredit = 0;
    $count = count($detail);
      for ($i=0; $i<$count; ++$i) {
        $tot_debet = $tot_debet + $detail[$i]['debet'];
         $tot_kredit = $tot_kredit + $detail[$i]['kredit'];
      }


    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.5),$orientation='portrait');
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,775,35);
        $this->cezpdf->addText(80,800,9,'PT.Dunia Express     ');
        $this->cezpdf->addText(80,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,780,9,'Jakarta Utara');
    $this->cezpdf->ezSetMargins(0,15,10,5);

    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));



        $this->cezpdf->addText(30,750,10,'Journal Number');
        $this->cezpdf->addText(110,750,10,':');
        $this->cezpdf->addText(120,750,10,$data->fld_journalno);
        $this->cezpdf->addText(300,720,10,'Customer');
        $this->cezpdf->addText(370,720,10,':');
        $this->cezpdf->addText(380,720,10,$data->cust);

        $this->cezpdf->addText(30,740,10,'Date');
        $this->cezpdf->addText(110,740,10,':');
        $this->cezpdf->addText(120,740,10,$data->journal_date);
        $this->cezpdf->addText(30,730,10,'Reff. Number');
        $this->cezpdf->addText(110,730,10,':');
        $this->cezpdf->addText(120,730,10,$data->fld_btno);
 $this->cezpdf->addText(30,720,10,'Transaction Type');
        $this->cezpdf->addText(110,720,10,':');
        $this->cezpdf->addText(120,720,10,$data->fld_bttynm);


        $this->cezpdf->ezSetDy(-75);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'Account Code','desc'=>'Description','reff'=>'Reff No','dbt'=>'Debet','kdt'=>'Kredit'),'',
   array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>780,'shaded'=>0,'fontSize'=>'9',
    'cols'=>array('fld_coacd'=>array('width'=>60), 'desc'=>array('width'=>255), 'reff'=>array('width'=>90),'dbt'=>array('width'=>80, 'justification'=>'right'),'kdt'=>array('width'=>80, 'justification'=>'right'),)));
    $data_sum = array(
                         array('row1'=>'Total','row2'=>number_format($tot_debet,2,',','.'),'row3'=>number_format($tot_kredit,2,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'8',
        'cols'=>array('row1'=>array('width'=>405,'justification'=>'center'),
        'row2'=>array('width'=>80,'justification'=>'right'),
        'row3'=>array('width'=>80,'justification'=>'right'))));


    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }

/*

  function print_journal($fld_btid) {
    $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
    select distinct
    t0.fld_journalid,
    t0.fld_journalno,
    date_format(t0.fld_journaldt,'%d/%m/%Y') 'journal_date',
    t0.fld_journaldesc 'desc',
    t0.fld_btdocreff 'reff',
    t0.fld_journalamt,
    if(t3.fld_coagrp not in(1,2),format(abs(t0.fld_journalamt),0),'') 'dbt',
    if(t3.fld_coagrp in(1,2),format(abs(t0.fld_journalamt),0),'') 'kdt',
    #if(t3.fld_coagrp not in(1,2),abs(t0.fld_journalamt),'') 'debet',
    #if(t3.fld_coagrp in(1,2),abs(t0.fld_journalamt),'') 'kredit',
    if(t0.fld_journalamt > 0,'',abs(t0.fld_journalamt)) 'debet',
    if(t0.fld_journalamt < 0,'',abs(t0.fld_journalamt)) 'kredit'
    t2.fld_bttynm,
    t1.fld_btno,
    t1.fld_btdesc notes,
    t3.fld_coacd,
    t3.fld_coanm,
    format(t0.fld_journalrate,0) 'rate',
    t4.fld_benm 'cust'
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    left join dnxapps.tbl_be t4 on t4.fld_beid = t1.fld_baidc
    where
    t0.fld_btid='$fld_btid'
    order by t3.fld_coacd desc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_debet = 0;
    $tot_kredit = 0;
    $count = count($detail);
      for ($i=0; $i<$count; ++$i) {
        $tot_debet = $tot_debet + $detail[$i]['debet'];
        $tot_kredit = $tot_kredit + $detail[$i]['kredit'];
      }
//    print_r($tot_debet);
 //   exit();

    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,335,35);
        $this->cezpdf->addText(80,360,9,'PT.Dunia Express     ');
        $this->cezpdf->addText(80,350,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,340,9,'Jakarta Utara');
    $this->cezpdf->ezSetMargins(0,15,10,5);

    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
    array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'shaded','xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));

	$this->cezpdf->line(10, 275, 595, 275);
         $this->cezpdf->line(10, 255, 595, 255);
	$this->cezpdf->line(460, 50, 595, 50);
	$this->cezpdf->line(10, 275, 10, 75);
         $this->cezpdf->line(85, 275, 85, 75);
         $this->cezpdf->line(365, 275, 365, 75);
         $this->cezpdf->line(460, 275, 460, 50);
	$this->cezpdf->line(10, 75, 595, 75);
         $this->cezpdf->line(527, 275, 527, 50);
	$this->cezpdf->line(595, 275, 595, 50);


        $this->cezpdf->addText(30,310,10,'Journal Number');
        $this->cezpdf->addText(110,310,10,':');
        $this->cezpdf->addText(120,310,10,$data->fld_journalno);
        $this->cezpdf->addText(300,280,10,'Customer');
        $this->cezpdf->addText(370,280,10,':');
        $this->cezpdf->addText(380,280,10,$data->cust);

        $this->cezpdf->addText(30,300,10,'Date');
        $this->cezpdf->addText(110,300,10,':');
        $this->cezpdf->addText(120,300,10,$data->journal_date);
        $this->cezpdf->addText(30,290,10,'Reff. Number');
        $this->cezpdf->addText(110,290,10,':');
        $this->cezpdf->addText(120,290,10,$data->fld_btno);
        $this->cezpdf->addText(30,280,10,'Transaction Type');
        $this->cezpdf->addText(110,280,10,':');
        $this->cezpdf->addText(120,280,10,$data->fld_bttynm);

        $this->cezpdf->addText(15,260,10,'Account Code');
        $this->cezpdf->addText(380,260,10,'Reff No');
        $this->cezpdf->addText(190,260,10,'Description');
        $this->cezpdf->addText(480,260,10,'Debet');
        $this->cezpdf->addText(550,260,10,'Kredit');
        $this->cezpdf->addText(380,60,10,'Total Balance');
        $this->cezpdf->addText(470,60,9,number_format($tot_debet, 0, ',', ','));
        $this->cezpdf->addText(540,60,9,number_format($tot_kredit, 0, ',', ','));
        $this->cezpdf->addText(30,60,9,'Rate:');
        $this->cezpdf->addText(60,60,9,$data->rate);
        $this->cezpdf->addText(30,50,8,'Notes:');
        $this->cezpdf->addText(60,50,8,$data->notes);


        $this->cezpdf->ezSetDy(-75);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'','desc'=>'','reff'=>'','dbt'=>'','kdt'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>30,'xOrientation'=>'right','width'=>780,'shaded'=>0,'fontSize'=>'9',
    'cols'=>array('fld_coacd'=>array('width'=>70), 'desc'=>array('width'=>275), 'reff'=>array('width'=>90),'dbt'=>array('width'=>65, 'justification'=>'right'),'kdt'=>array('width'=>65, 'justification'=>'right'),)));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }
*/

/*
   function print_journal($fld_btid) {
    $getData =$this->db->query("
    select
    t0.fld_journalid,
    t0.fld_journaldt,
    t0.fld_journaldesc,
    t0.fld_journalamt,
    format(t0.fld_journalamt,2) 'amount',
    t2.fld_bttynm,
    t1.fld_btno,
    t3.fld_coacd,
    t3.fld_coanm,
    t0.fld_journalno
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    where
    t0.fld_btid='$fld_btid'
    order by t0.fld_journalamt desc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_amount = 0;
    $count = count($detail);
      for ($i=0; $i<$count; ++$i) {
        $tot_amount = $tot_amount + $detail[$i]['fld_journalamt'];
      }
    $this->load->library('cezpdf');
    //$this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,775,35);
        $this->cezpdf->addText(80,800,9,'PT.Dunia Express Transindo     ');
        $this->cezpdf->addText(80,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,780,9,'Jakarta Utara');
    $this->cezpdf->ezSetMargins(10,15,10,25);

    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
    array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'shaded','xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));

        $this->cezpdf->addText(30,750,10,'Journal Number');
        $this->cezpdf->addText(110,750,10,':');
        $this->cezpdf->addText(120,750,10,$data->fld_journalno);
        $this->cezpdf->addText(30,740,10,'Date');
        $this->cezpdf->addText(110,740,10,':');
        $this->cezpdf->addText(120,740,10,$data->fld_journaldt);
        $this->cezpdf->addText(30,730,10,'Reff. Number');
        $this->cezpdf->addText(110,730,10,':');
        $this->cezpdf->addText(120,730,10,$data->fld_btno);
        $this->cezpdf->addText(30,720,10,'Transaction Type');
        $this->cezpdf->addText(110,720,10,':');
        $this->cezpdf->addText(120,720,10,$data->fld_bttynm);

        $this->cezpdf->addText(15,702,10,'Code');
        $this->cezpdf->addText(150,702,10,'Name');
        $this->cezpdf->addText(360,702,10,'Description');
        $this->cezpdf->addText(535,702,10,'Amount');


        $this->cezpdf->ezSetDy(-75);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'','fld_coanm'=>'','fld_journaldesc'=>'','amount'=>''),'',
   array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>680,'shaded'=>0,'fontSize'=>'8',
    'cols'=>array('fld_coacd'=>array('width'=>40), 'fld_coanm'=>array('width'=>200),'fld_journaldesc'=>array('width'=>260),'amount'=>array('width'=>70, 'justification'=>'right'))));
        $this->cezpdf->ezText('Total Balance ' . ' :                                   ' . $tot_amount,14, array('justification' => 'right'));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }
*/

  function print_journal_spi($fld_btid) {
    $getData =$this->db->query("
    select distinct
    t0.fld_journalid,
    t0.fld_journaldt,
    t0.fld_journaldesc,
    t0.fld_journalamt,
    format(t0.fld_journalamt,2) 'amount',
    if(t1.fld_bttyid=55 and t1.fld_btp01=1,'Payment To Shipping Line',t2.fld_bttynm) 'trans',
    t1.fld_btno,
    t3.fld_coacd,
    t0.fld_btdocreff,
    t3.fld_coanm,
    t0.fld_journalno,
    t6.fld_tyvalnm 'freight',
    if(t0.fld_jo='',t0.fld_btdocreff,concat(t0.fld_jo,' - ',t0.fld_btdocreff)) 'reffno',
    t1.fld_btp23 'usercomp'
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    left join tbl_btd_finance t4 on t4.fld_btidp=t1.fld_btid
    left join tbl_bth t5 on t5.fld_btno=t0.fld_jo
    left join tbl_tyval t6 on t6.fld_tyvalcd=if(t5.fld_bttyid=1,t5.fld_btp13,t5.fld_btp04) and t6.fld_tyid =72
    where
    t0.fld_btid='$fld_btid'
    order by t0.fld_journalamt asc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_amount = 0;
    $count = count($detail);

    $balance = $this->db->query("
    	select sum(fld_journalamt)'balance' from tbl_journal where fld_btid='$fld_btid'
    ");
    $data1 = $balance->row();
    $tot_balance = $data1->balance;

      for ($i=0; $i<$count; ++$i) {
        $tot_amount = $tot_amount + $detail[$i]['fld_journalamt'];
      }
    $this->load->library('cezpdf');
    //$this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
    if($data->usercomp != 1) {
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,775,35);
        $this->cezpdf->addText(80,800,9,'PT.Dunia Express    ');
        $this->cezpdf->addText(80,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,780,9,'Jakarta Utara');
    }
    else {
        $this->cezpdf->addText(80,800,9,'PT.Rema Logistik Indonesia    ');
        $this->cezpdf->addText(80,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,780,9,'Jakarta Utara');

    }
    $this->cezpdf->ezSetMargins(10,15,10,25);
       for ($i=0; $i<1; ++$i) {
        $freight = $data->freight;
      }
    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
    array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'shaded','xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));

    $this->cezpdf->addText(30,750,10,'Journal Number');
        $this->cezpdf->addText(110,750,10,':');
        $this->cezpdf->addText(120,750,10,$data->fld_journalno);
        $this->cezpdf->addText(30,740,10,'Date');
        $this->cezpdf->addText(110,740,10,':');
        $this->cezpdf->addText(120,740,10,$data->fld_journaldt);
        $this->cezpdf->addText(30,730,10,'Reff.Number');
        $this->cezpdf->addText(110,730,10,':');
        $this->cezpdf->addText(120,730,10,$data->fld_btno);
        $this->cezpdf->addText(30,720,10,'Transaction Type');
        $this->cezpdf->addText(110,720,10,':');
        $this->cezpdf->addText(120,720,10,$data->trans);
       // $this->cezpdf->addText(350,750,10,'Freight');
        //$this->cezpdf->addText(390,750,10,':');
   //     $this->cezpdf->addText(400,750,10,$freight);

    //    $this->cezpdf->addText(20,702,10,'Code');
  //      $this->cezpdf->addText(150,702,10,'Name');
//        $this->cezpdf->addText(360,702,10,'Description');
//        $this->cezpdf->addText(535,702,10,'Amount');


        $this->cezpdf->ezSetDy(-75);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'Code','fld_coanm'=>'Name','fld_journaldesc'=>'Description','reffno'=>'Reff No/Doc',
                                             'freight'=>'Freight','amount'=>'Amount'),'',
   array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>680,'shaded'=>0,'fontSize'=>'8',
    'cols'=>array('fld_coacd'=>array('width'=>40), 'fld_coanm'=>array('width'=>150),'fld_journaldesc'=>array('width'=>140),'reffno'=>array('width'=>120),
    'freight'=>array('width'=>50),'amount'=>array('width'=>70, 'justification'=>'right'))));
        $this->cezpdf->ezText('Total Balance ' . ' :                                   ' . $tot_balance,14, array('justification' => 'right'));

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }

  function printTruckBilling ($fld_btid,$src) {
     $filename = 'Trucking-Billing-Summarry-'.date('Ymd') . '.xls';
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("
                  SELECT
                  t3.fld_btno 'bill_num',
                  t3.fld_btnoalt 'fld_btnoalt',
                  t3.fld_btdt 'fld_btdt',
                  t4.fld_benm 'customer',
                  t6.fld_tyvalnm 'vehicle',
                  t9.fld_bticd 'vehicle_number',
                  concat(t11.fld_areanm , '  ' , ' > ' ,'  ' , t12.fld_areanm) 'route',
                  t2.fld_btno 'container',
                  t0.fld_btp05 'overnight_days',
                  t0.fld_btamt02 'overnight_charge',
                  t3.fld_btbalance 'price',
                  t3.fld_btnoreff 'fld_btnoreff',
                  t0.fld_btp01 'blnumber',
                  t0.fld_btp03 'quonumber',
                  t14.fld_btno 'invoice'
                  FROM tbl_trk_billing t0
                  LEFT JOIN tbl_bth t1 ON t1.fld_btid=t0.fld_btidp
                  LEFT JOIN tbl_btd t2 ON t2.fld_btid=t0.fld_btreffid
                  LEFT JOIN tbl_bth t3 ON t3.fld_btid=t2.fld_btidp
                  left join dnxapps.tbl_be t4 on t4.fld_beid=t1.fld_baidc
                  LEFT JOIN tbl_bth t5 ON t5.fld_btno=t3.fld_btnoalt
                  left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd=t5.fld_btflag and t6.fld_tyid=19
                  left join tbl_route t7 on t7.fld_routeid =t5.fld_btp09
                  left join tbl_btd_finance t8 on t8.fld_btreffid=t5.fld_btid
                  left join dnxapps.tbl_bti t9 on t9.fld_btiid=t5.fld_btp12
                  LEFT JOIN dnxapps.tbl_route t10 ON t5.fld_btp09=t10.fld_routeid
                  left join dnxapps.tbl_area t11 on t11.fld_areaid=t10.fld_routefrom
                  left join dnxapps.tbl_area t12 on t12.fld_areaid=t10.fld_routeto
                  left join tbl_btr t13 on t13.fld_btrsrc=t0.fld_btidp
                  left join tbl_bth t14 on t14.fld_btid=t13.fld_btrdst
                  WHERE
                  t0.fld_btidp=$fld_btid
                  group by t3.fld_btno
    ");
   echo "<table border=1 width=100%>
     <tr>
             <td nowrap>No</td>
             <td nowrap>Billing Number</td>
             <td nowrap>WO Number</td>
             <td nowrap>Date</td>
             <td nowrap>Customer</td>
             <td nowrap>Route</td>
             <td nowrap>Container No.</td>
             <td nowrap>Vehicle</td>
             <td nowrap>Vehicle No.</td>
             <td nowrap>Overnight Days</td>
             <td nowrap>Overnight Charge</td>
             <td nowrap>Selling Price</td>
             <td nowrap>B/L number</td>
             <td nowrap>Exim Job Number</td>
             <td nowrap>Quotation Number</td>
             <td nowrap>Invoice No.</td>
           </tr>";
   $no = 0;
   $total = 0;
   foreach($data->result() as $rdata) {
     $no = $no + 1;
     echo "<tr>";
     echo "<td nowrap>" . $no . "</td>";
    echo "<td nowrap>" . $rdata->bill_num . "</td>";
     echo "<td nowrap>" . $rdata->fld_btnoalt . "</td>";
     echo "<td nowrap>" . $rdata->fld_btdt . "</td>";
     echo "<td nowrap>" . $rdata->customer . "</td>";
     echo "<td nowrap>" . $rdata->route . "</td>";
      echo "<td nowrap>" . $rdata->container . "</td>";
     echo "<td nowrap>" . $rdata->vehicle . "</td>";
     echo "<td nowrap>" . $rdata->vehicle_number . "</td>";
     echo "<td nowrap>" . $rdata->overnight_days . "</td>";
     echo "<td nowrap>" . $rdata->overnight_charge . "</td>";
     echo "<td nowrap>" . $rdata->price . "</td>";
     echo "<td nowrap>" . $rdata->blnumber . "</td>";
     echo "<td nowrap>" . $rdata->fld_btnoreff . "</td>";
     echo "<td nowrap>" . $rdata->fld_btp03 . "</td>";
     echo "<td nowrap>" . $rdata->invoice . "</td>";
     echo "</tr>";
     $total = $total + $rdata->price;
     // $overnight = $overnight + $rdata->overnight_charge;
   }
   echo "<tr>";
   echo "<td colspan=11 align='center'>Total</td>";
   echo "<td>" . $total . "</td>";
   echo "<td></td>";
   echo "</tr>";
   echo "</table>";
  }


  function ProsesImport($FormName, $id) {
    require_once('system/shared/excel_reader2.php');
    require_once('system/shared/PHPExcel.php');
    require_once ('system/shared/PHPExcel/IOFactory.php');
    switch ($FormName) {
      case "78000TRUCKING_BILLING":
        $FileName=$this->db->query("select fld_btcmt from tbl_bth where fld_btid='$id'")->row()->fld_btcmt;
        $objPHPExcel = PHPExcel_IOFactory::load("upload/".$FileName."");
        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $row = count($allDataInSheet);
        $total_price = 0;
        for ($i=2; $i<=$row; $i++) {
          $do=$allDataInSheet[$i]["B"];
          $wo = $this->db->query("select t0.fld_btid,t1.fld_btnoreff
                                  from
                                  tbl_btd t0
                                  left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                  where t1.fld_bttyid = 80
                                  and t1.fld_btnoalt='$do'
          ")->result();
          $price = $allDataInSheet[$i]["C"];
          $bl = $allDataInSheet[$i]["D"];
          foreach ($wo as $rwo) {
            $sql="insert into tbl_trk_billing (fld_btidp, fld_btreffid, fld_btamt01, fld_btno, fld_btp02)
                  value ($id,$rwo->fld_btid,$price,'$bl','$rwo->fld_btnoreff')";
            $query=$this->db->query($sql);
            $total_price = $total_price + $price;
          }
        }
        $this->db->query("update tbl_bth set fld_btamt = $total_price where fld_btid = $id limit 1");
      break;
    }
  }


  function SumCostOrder($id)
  {
    $div=$this->session->userdata('divid');
    $sql="select t1.fld_trfamt, t0.fld_btid, t1.fld_trfsa, t1.fld_trfso
    from tbl_btd_cost t0
    left join tbl_trf t1 on t1.fld_btid=t0.fld_costtype and t1.fld_trfp01=t0.fld_currency
    and t1.fld_trfamt > 0 and t1.fld_trfp03='$div' and t1.fld_trfsa <= CURDATE() and t1.fld_trfso >= CURDATE()
    where t0.fld_btidp='$id'";
    $query=$this->db->query($sql);
    $data=$query->result();
    foreach ($data as $row)
    {
      $nilai=$row->fld_trfamt;
      $btid=$row->fld_btid;
          if ($row->fld_trfamt > 0)
      {
        $sql="UPDATE tbl_btd_cost SET fld_btuamt01 = (
        select fld_trfamt from (
        select t0.fld_btid, t1.fld_trfamt
        from tbl_btd_cost t0
        left join tbl_trf t1 on t1.fld_btid=t0.fld_costtype and t1.fld_trfp01=t0.fld_currency AND t1.fld_trfp02=t0.fld_btp01 and t1.fld_trfamt > 0
        where t0.fld_btidp='$id')a where a.fld_btid=tbl_btd_cost.fld_btid),
        fld_btamt01 = (
        select total from (
        select t0.fld_btid, t1.fld_trfamt * t0.fld_btqty01 total
        from tbl_btd_cost t0
        left join tbl_trf t1 on t1.fld_btid=t0.fld_costtype and t1.fld_trfp01=t0.fld_currency AND t1.fld_trfp02=t0.fld_btp01
        where t0.fld_btidp='$id')a where a.fld_btid=tbl_btd_cost.fld_btid)
        where fld_btidp='$id' and fld_btid=$btid";
        $this->db->query($sql);
      } else {
        $sql="update tbl_btd_cost set fld_btamt01 = fld_btuamt01 * fld_btqty01 where fld_btidp='$id' and fld_btid=$btid";
        $this->db->query($sql);
      }
    }
    $sql="update tbl_bth set fld_btamt= (select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=1) where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btp11= (select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=2) where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_btd_cost set fld_bt01='$id' where fld_btidp='$id'";
    $this->db->query($sql);

    //print $sql;
  }

  function SumAdCashOrder($id) {
    $division=$this->session->userdata('divid');
    $data = $this->db->query("select t0.fld_btid,t1.fld_trfamt,t0.fld_btqty01,
                              t1.fld_trfamt01,
                              t1.fld_trftyid, t0.fld_costtype, t0.fld_btp01
                              from tbl_btd_cost t0
                              left join tbl_bti t01 on t01.fld_btiid=t0.fld_costtype
                              left join tbl_trf t1 on t1.fld_btid = t01.fld_btiid and t1.fld_trfp01 = t0.fld_currency
                              and if(t0.fld_btp01 > 0,t1.fld_trfp02 = t0.fld_btp01,1)
                              and t1.fld_trfp03='$division'
			      and date_format(now(),'%Y-%m-%d') between t1.fld_trfsa and t1.fld_trfso
                              where t0.fld_btidp='$id'");
    $data = $data->result();
    foreach ($data as $row) {
      if ($row->fld_trfamt > 0) {
        if ($row->fld_trftyid == 2) {
          $amount = $row->fld_trfamt + ($row->fld_trfamt01 * ($row->fld_btqty01 - 1));
          $this->db->query("UPDATE tbl_btd_cost SET fld_btuamt01=$row->fld_trfamt, fld_btamt01=$amount where fld_btid=$row->fld_btid");
        } else {
          $amount = $row->fld_trfamt * $row->fld_btqty01;
          $this->db->query("UPDATE tbl_btd_cost SET fld_btuamt01=$row->fld_trfamt, fld_btamt01=$amount where fld_btid=$row->fld_btid");
        }
	if ($row->fld_costtype == 1500000000000) {
           if ($row->fld_btp01 == 1) {
	     $sql="UPDATE tbl_btd_cost A SET fld_btqty01=
                   (SELECT fld_btqty
                   FROM tbl_bth  WHERE tbl_bth.fld_btid=fld_bt01)
                   WHERE fld_btid='$row->fld_btid' AND fld_costtype=15 AND fld_btp01='$row->fld_btp01'";
           }
	   if ($row->fld_btp01 == 2) {
                $sql="UPDATE tbl_btd_cost A SET fld_btqty01=
                      (SELECT fld_btp06 FROM tbl_bth  WHERE tbl_bth.fld_btid=fld_bt01)
                      WHERE fld_btid='$row->fld_btid' AND fld_costtype=15 AND fld_btp01='$row->fld_btp01'";
           }
	   $this->db->query($sql);
	   $sql="UPDATE tbl_btd_cost A SET fld_btuamt01=$row->fld_trfamt +
                 (SELECT A.Amount
                 FROM v_ops_cost A WHERE A.DivID='$division' AND A.TypeID='$row->fld_btp01' AND A.CurrID=1 AND A.id=3490)
                 WHERE fld_btid='$row->fld_btid' AND fld_costtype=15 AND fld_btp01='$row->fld_btp01'";
           $this->db->query($sql);
           $this->db->query("UPDATE tbl_btd_cost SET fld_btamt01 = fld_btuamt01 * fld_btqty01 *
		(TO_DAYS(fld_btdt) - (SELECT TO_DAYS(fld_btp17) FROM tbl_bth  WHERE tbl_bth.fld_btid=fld_bt01) +1 ) where fld_btid='$row->fld_btid'");
        }
      } else {
        $sql="update tbl_btd_cost set fld_btamt01 = fld_btuamt01 * fld_btqty01 where fld_btidp='$id' and fld_btid=$row->fld_btid";
        $this->db->query($sql);
      }
    }
    $sql="update tbl_bth set fld_btamt= (select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=1)
          where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btp06= (select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=2)
          where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btamt01=((select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=1)-
          ifnull((select sum(fld_btamt02) from tbl_btd_over_payment where fld_btidp='$id'),0))
          where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btamt02=((select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=2)-
          ifnull((select sum(fld_btamt01) from tbl_btd_over_payment where fld_btidp='$id'),0))
          where fld_btid='$id'";
    $this->db->query($sql);
  }

function SumAdvanceRepo($id){
    $sql="update tbl_bth set fld_btamt01= fld_btamt
          where fld_btid='$id'";
    $this->db->query($sql);

}
function SumBankIn($id){
    $sql="update tbl_bth set fld_btamt=(select sum(fld_btamt01)from tbl_btd_finance where fld_btidp = '$id'),fld_btp01=fld_btuamt-fld_btamt
        where fld_btid='$id' limit 1";
    $this->db->query($sql);

}

function PostingAdvSell($btid,$dtsa,$dtso){
          #echo "$btid<br>";
          #echo"$dtsa";
          #exit();
     $sql ="update tbl_btd_advprice t0 left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp and t1.fld_bttyid = 97 set t0.fld_btnoreff = '$btid', t0.fld_btflag = 1
		where t1.fld_bttyid =97 and t0.fld_btflag = 0 and date_format(t1.fld_btdt,'%Y-%m-%d') between date_format('$dtsa','%Y-%m-%d') and date_format('$dtso','%Y-%m-%d')  ";
     $this->db->query($sql);
     $url = base_url() ."index.php/page/form/78000PST_ADVSELL_PRICE/edit/$btid?act=edit";
    redirect($url);

     }

/*
  function SumSetRelease($id) {
    $sql="update tbl_bth set fld_btamt= (select sum(fld_btuamt01 * fld_btqty01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=1) where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btp13=IFNULL(fld_btp12,0) - IFNULL(fld_btamt,0) - IFNULL(fld_btp05,0)  where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btp07=(select sum(fld_btuamt01 * fld_btqty01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=2) where fld_btid='$id'";
    $this->db->query($sql);
    $sql="update tbl_bth set fld_btp09=IFNULL(fld_btp08,0) - IFNULL(fld_btp07,0)  - IFNULL(fld_btp06,0) where fld_btid='$id'";
    $this->db->query($sql);

    //print $sql;
    //exit();
  }
*/
   function print_advPrc2 ($fld_btid) {
     $filename = 'Posting_Adv_Price'.date('Ymd') . '.xls';
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    $getData =$this->db->query("select t0.fld_btno,date_format(t0.fld_btdt,'%Y-%m-%d')'date',
                                concat(date_format(t0.fld_btdtsa,'%Y-%m-%d'), 'Until',date_format(t0.fld_btdtso,'%Y-%m-%d'))'periode',t1.fld_empnm 'post' from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid =t0.fld_baidp
                                 where t0.fld_btid = '$fld_btid'");
   $dataH = $getData->row();

    $data = $this->db->query ("select
t0.fld_btid,
t3.fld_btno 'TransactionNumber',
concat(if(t5.fld_beprefix > 0,concat(t7.fld_tyvalnm, '. '),''), t5.fld_benm) 'Customer',
t4.fld_btno 'JobNumber',
t1.fld_bedivnm 'Division',
t2.fld_btdesc'desc',
t2.fld_btamt01'Amount'

from
tbl_btd_advprice t0
left join hris.tbl_bediv t1 on t1.fld_bedivid = t0.fld_btp01
left join tbl_btd_finance t2 on t2.fld_btid = t0.fld_btiid
left join tbl_bth t3 on t3.fld_btid = t2.fld_btidp and t3.fld_bttyid in(46,95)
left join tbl_bth t4 on t4.fld_btno =t2.fld_btnoreff and t4.fld_bttyid in (1,6)
left join dnxapps.tbl_be t5 on t5.fld_beid=t4.fld_baidc
left join tbl_bth t6 on t6.fld_btid =t0.fld_btidp
left join dnxapps.tbl_tyval t7 on t7.fld_tyvalcd = t5.fld_beprefix and t7.fld_tyid = 173
where
t0.fld_btnoreff = '$fld_btid' and t0.fld_btflag in (1,2)
order by t0.fld_btp02

    ");
    echo "<table>";
    echo"<tr>";
    echo"<td>Posting Number :</td>";
    echo"<td>" . $dataH->fld_btno ."</td>";
    echo"</tr>";
    echo"<tr>";
    echo"<td>Posting Date :</td>";
    echo"<td>" . $dataH->date ."</td>";
    echo"</tr>";
     echo"</table>";
    echo "<table border=1 width=100%>
           <tr>
             <td nowrap>No</td>
             <td nowrap>Description</td>
             <td nowrap>Customer</td>
             <td nowrap>Document number</td>
             <td nowrap>Job number</td>
             <td nowrap>Division </td>
             <td nowrap>Amount </td>
           </tr>";
   $no = 0;
   $total = 0;
   foreach($data->result() as $rdata) {
     $no = $no + 1;
     echo "<tr>";
     echo "<td nowrap>" . $no . "</td>";
     echo "<td nowrap>" . $rdata->desc . "</td>";
     echo "<td nowrap>" . $rdata->Customer . "</td>";
     echo "<td nowrap>" . $rdata->TransactionNumber ."</td>";
     echo "<td nowrap>" . $rdata->JobNumber . "</td>";
     echo "<td nowrap>" . $rdata->Division . "</td>";
     echo "<td nowrap>" . $rdata->Amount . "</td>";
     echo "</tr>";
     $total2 = $total2 + $rdata->Amount;
   }
    echo "<tr>";
   echo "<td colspan=5 align='center'>Total</td>";
   echo "<td>" . $total2 . "</td>";
echo "</tr>";
  echo"</table>";
   echo"<table>";
   echo "<tr>";
   echo"<td colspan=5 align='left'>Receive by</td>";
   echo"<td>Prepare by</td>";
   echo"</tr>";
   echo "<tr>";
   echo "</tr>";
   echo "<tr>";
   echo "</tr>";
   echo "<tr>";
   echo"<td colspan=5 align='right'></td>";
   echo"<td>" . $dataH->post . " </td>";
   echo"</tr>";
   echo "</table>";
  }


   function print_advPrc ($fld_btid){
   $getData =$this->db->query("select t0.fld_btno,date_format(t0.fld_btdt,'%Y-%m-%d')'date',
                                concat(date_format(t0.fld_btdtsa,'%Y-%m-%d'), 'Until',date_format(t0.fld_btdtso,'%Y-%m-%d'))'periode',t1.fld_empnm 'post' from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid =t0.fld_baidp
                                 where t0.fld_btid = '$fld_btid'");
   $data = $getData->row();
   $getdetailData =$this->db->query("select
t0.fld_btid,
t3.fld_btno 'TransactionNumber',
concat(if(t5.fld_beprefix > 0,concat(t7.fld_tyvalnm, '. '),''), t5.fld_benm) 'Customer',
t4.fld_btno 'JobNumber',
t1.fld_bedivnm 'Division',
t2.fld_btdesc'desc',
format(t2.fld_btamt01,2)'Amount'

from
tbl_btd_advprice t0
left join hris.tbl_bediv t1 on t1.fld_bedivid = t0.fld_btp01
left join tbl_btd_finance t2 on t2.fld_btid = t0.fld_btiid
left join tbl_bth t3 on t3.fld_btid = t2.fld_btidp and t3.fld_bttyid in(46,95)
left join tbl_bth t4 on t4.fld_btno =t2.fld_btnoreff and t4.fld_bttyid in (1,6)
left join dnxapps.tbl_be t5 on t5.fld_beid=t4.fld_baidc
left join tbl_bth t6 on t6.fld_btid =t0.fld_btidp
left join dnxapps.tbl_tyval t7 on t7.fld_tyvalcd = t5.fld_beprefix and t7.fld_tyid = 173
where
t0.fld_btnoreff = '$fld_btid' and t0.fld_btflag in (1,2)
order by t0.fld_btp02");
   $counteor=0;
  #$dataDtl_count = $getdetailData->num_rows();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,30.5),$orientation='portrait');
    $this->cezpdf->ezText("Advance Price" . "   ", 16, array('justification' => 'center'));
    $this->cezpdf->ezSetMargins(100,5,10,15);
    if ( $getdetailData->num_rows() > 0 )  {
    $datadtl = $getdetailData->result_array();
    $count = count($datadtl);
    for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        ###Prepare Data
        $datadtl[$i]['count'] = $counteor;
      }
    $this->cezpdf->addText(50,800,10,"Posting Number.");
    $this->cezpdf->addText(150,800,10,':'.$data->fld_btno);
    $this->cezpdf->addText(50,790,10,"Date");
    $this->cezpdf->addText(150,790,10,':'.$data->date);
    $this->cezpdf->addText(50,780,10,"Periode.");
    $this->cezpdf->addText(150,780,10,':'.$data->periode);
      $this->cezpdf->ezSetDY(-10);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl,array('count'=>'No','desc'=>'Description','Customer'=>'Customer','TransactionNumber'=>'Document No','Division'=>'Division','Amount'=>'Amount'),'',
      array('rowGap'=>'0.3','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>550,'shaded'=>0,'fontSize'=>'9',
      'cols'=>array('counteor'=>array('width'=>10), 'desc'=>array('width'=>210),'Customer'=>array('width'=>100),
      'TransactionNumber'=>array('width'=>100, 'justification'=>'center'),
      'Division'=>array('width'=>50, 'justification'=>'center'),'Amount'=>array('width'=>60, 'justification'=>'right'))));
     $this->cezpdf->ezSetDy(-20);
         $acc1 = array(array('row3'=>'','row4'=>'Posted by'),
                        array('row3'=>'','row4'=>''),
                        array('row3'=>'','row4'=>''),
                        array('row3'=>'','row4'=>$data->post),
                );
     $this->cezpdf->ezTable($acc1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>120,'justification' => 'center'),
         'row2'=>array('width'=>120,'justification' => 'center'),
         'row3'=>array('width'=>120,'justification' => 'center'),
         'row4'=>array('width'=>120,'justification' => 'center'),
         )));


     }
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;

    }


   function SumSetRelease($id) {
    //update total spent
    $sql="update tbl_bth set fld_btamt= (select sum(fld_btuamt01 * fld_btqty01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=1) where fld_btid='$id'";
    $this->db->query($sql);
     $sql="update tbl_bth set fld_btp07=(select sum(fld_btuamt01 * fld_btqty01) from tbl_btd_cost where fld_btidp='$id' and fld_currency=2) where fld_btid='$id'";
    $this->db->query($sql);

    //update closing op
    $sql="update tbl_bth set fld_btp22= (select sum(fld_btamt02) from tbl_btd_over_payment where fld_btidp='$id') where fld_btid='$id'";
    $this->db->query($sql);

    $sql="update tbl_bth set fld_btp21= (select sum(fld_btamt01) from tbl_btd_over_payment where fld_btidp='$id') where fld_btid='$id'";
    $this->db->query($sql);


    //update total remain
    $sql="update tbl_bth set fld_btp13= IFNULL(fld_btp12,0) - IFNULL(fld_btamt,0) - IFNULL(fld_btp05,0)  where fld_btid='$id'";
    $this->db->query($sql);

    $sql="update tbl_bth set fld_btp09= IFNULL(fld_btp08,0) - IFNULL(fld_btp07,0)  - IFNULL(fld_btp06,0) where fld_btid='$id'";
    $this->db->query($sql);


  }


  function GetDataSK($id='')
  {
    $sql="select A.*, concat(if(B.fld_beprefix > 0,concat(F.fld_tyvalnm, '. '),''), B.fld_benm) CUSTOMER, B.fld_beaddr ALAMAT,
	CASE
		WHEN A.fld_btp08 <> '' THEN CONCAT_WS(' / ',A.fld_btp08,DATE_FORMAT(C.fld_docdt,'%d-%m-%Y'))
		WHEN A.fld_btp07 <> '' THEN CONCAT_WS(' / ',A.fld_btp07,DATE_FORMAT(C.fld_docdt,'%d-%m-%Y'))
		ELSE CONCAT_WS(' / ',C.fld_docnum,DATE_FORMAT(C.fld_docdt,'%d-%m-%Y'))
	END DOKUMEN,
	D.fld_empnm StafOperasional,
        E.fld_benm
    FROM tbl_bth A
    LEFT JOIN
    dnxapps.tbl_be B
    ON B.fld_beid=A.fld_baidc
    LEFT JOIN tbl_btd_document C ON C.fld_btidp=A.fld_btid AND C.fld_doctype=705
    LEFT JOIN hris.tbl_emp D ON D.fld_empid=A.fld_baidp
    LEFT JOIN tbl_be E ON E.fld_beid = A.fld_btp15 and E.fld_betyid=8
    left join dnxapps.tbl_tyval F on F.fld_tyvalcd = B.fld_beprefix and F.fld_tyid = 173
    where A.fld_btid='$id'";
    $query=$this->db->query($sql);
    return $query;
  }

    function GetDataSTE($id='')
  {
    $sql="select A.*, concat(if(B.fld_beprefix > 0,concat(F.fld_tyvalnm, '. '),''), B.fld_benm)  CUSTOMER,
          B.fld_beaddr ALAMAT,
        CONCAT_WS(' / ',A.fld_btp08,DATE_FORMAT(C.fld_docdt,'%d-%m-%Y')) mbl,
        CONCAT_WS(' / ',A.fld_btp07,DATE_FORMAT(C.fld_docdt,'%d-%m-%Y')) hbl,
        D.fld_empnm StafOperasional,
        E.fld_benm
    FROM tbl_bth A
    LEFT JOIN
    dnxapps.tbl_be B
    ON B.fld_beid=A.fld_baidc
    LEFT JOIN tbl_btd_document C ON C.fld_btidp=A.fld_btid AND C.fld_doctype=705
    LEFT JOIN hris.tbl_emp D ON D.fld_empid=A.fld_baidp
    LEFT JOIN tbl_be E ON E.fld_beid = A.fld_btp15 and E.fld_betyid=8
     left join dnxapps.tbl_tyval F on F.fld_tyvalcd = B.fld_beprefix and F.fld_tyid = 173
    where A.fld_btid='$id'";
    $query=$this->db->query($sql);
    return $query;
  }

   function GetDataCashAdvance($id='')
  {
        $sql="select b.fld_btno, tipe 'desc', g.fld_tyvalnm 'tipe', f.fld_tyvalnm 'currency', j.fld_tyvalnm payment, a.fld_btqty01 'qty',
        a.fld_btuamt01 'amount', d.fld_benm,
        b.fld_btnoreff, e.fld_empnm, a.fld_btqty01 * a.fld_btuamt01 total , h.fld_btno jo,
        concat(if(t12.fld_beprefix > 0,concat(t16.fld_tyvalnm, '. '),''), t12.fld_benm) 'customer',
        k.fld_empnm 'ops_staff',l.fld_bedivnm,
        date_format(b.fld_btdt,'%Y-%m-%d') 'req_date', t.fld_empnm staff, o.fld_bedivnm,
   #case
   #     when h.fld_btp08 != '' then h.fld_btp08
   #     when h.fld_btp07 != '' then h.fld_btp07
   #     else null
   #end bl,
   concat(h.fld_btp07,'/',h.fld_btp08) 'bl',
   t10.fld_benm 'shipping_line',b.fld_btp13 'totalopidr',b.fld_btp14 'totalopusd',b.fld_btdesc 'remark',b.fld_btp18 'paytype',
   t13.fld_benm 'shipping',t15.fld_tyvalnm 'bank_name',t14.fld_becredno 'bank_acc', t17.fld_usercomp 'usercomp'
        from tbl_btd_cost a
        left join tbl_bth b on a.fld_btidp=b.fld_btid left join
        (select t0.fld_btiid 'id', t0.fld_btinm  'tipe', t0.fld_btip01 'Currency', t0.fld_btip02 'name'
        from tbl_bti t0 where t0.fld_bticid = 1)c on c.id=a.fld_costtype
        left join tbl_be d on d.fld_beid=b.fld_baidc
        LEFT JOIN hris.tbl_emp_exim e ON e.fld_empid=b.fld_baidp
        left join tbl_tyval f ON a.fld_currency=f.fld_tyvalcd and f.fld_tyid=39
        left join tbl_tyval g ON a.fld_btp01=g.fld_tyvalcd and g.fld_tyid=67
        left join tbl_bth h ON h.fld_btid=a.fld_bt01
        left join tbl_be i on i.fld_beid=a.fld_bt02 and i.fld_betyid=5
        left join tbl_tyval j ON b.fld_btp18=j.fld_tyvalcd and j.fld_tyid=66
        left join hris.tbl_emp_exim k ON k.fld_empid=b.fld_btp11
        left join hris.tbl_bediv l on l.fld_bedivid=e.fld_empdiv
        left join hris.tbl_emp_exim t ON t.fld_empid=b.fld_btp11
        left join tbl_bediv o on o.fld_bedivid=b.fld_baidv
        left join tbl_be t10 on t10.fld_beid=h.fld_btp15 and t10.fld_betyid = 8
        left join tbl_bth t11 on t11.fld_btid=a.fld_bt01
        left join dnxapps.tbl_be t12 on t12.fld_beid=t11.fld_baidc
        left join tbl_be t13 on t13.fld_beid = b.fld_btp15 and t13.fld_betyid = 8
        left join tbl_becred t14 on t14.fld_beid = b.fld_btp15 and t14.fld_becredid = b.fld_btp19
        left join tbl_tyval t15 on t15.fld_tyvalcd = t14.fld_btp01 and t15.fld_tyid = 95
        left join dnxapps.tbl_tyval t16 on t16.fld_tyvalcd = t12.fld_beprefix and t16.fld_tyid = 173
        left join tbl_user t17 on t17.fld_userid = b.fld_btp23
        where a.fld_btidp='$id'";
        $query=$this->db->query($sql);
        return $query;
  }

   function GetDataCashAdvanceCO($id='')
  {
        $sql="select b.fld_btno, tipe 'desc', g.fld_tyvalnm 'tipe', f.fld_tyvalnm 'currency', j.fld_tyvalnm payment, a.fld_btqty01 'qty',
        a.fld_btuamt01 'amount', d.fld_benm,
        b.fld_btnoreff, e.fld_empnm, a.fld_btqty01 * a.fld_btuamt01 total , h.fld_btno jo,
        concat(if(t12.fld_beprefix > 0,concat(t13.fld_tyvalnm, '. '),''), t12.fld_benm) 'customer',k.fld_empnm 'ops_staff',l.fld_bedivnm,
        date_format(b.fld_btdt,'%Y-%m-%d') 'req_date', t.fld_empnm staff, o.fld_bedivnm,
   case
        when h.fld_btp08 != '' then h.fld_btp08
        when h.fld_btp07 != '' then h.fld_btp07
        else null
   end bl,
   h.fld_btp01 'invoice',a.fld_btp06 'co',t10.fld_benm 'shipping_line',b.fld_btp13 'totalopidr',b.fld_btp14 'totalopusd',b.fld_btdesc 'remark'
        from tbl_btd_cost a
        left join tbl_bth b on a.fld_btidp=b.fld_btid left join
        (select t0.fld_btiid 'id', t0.fld_btinm  'tipe', t0.fld_btip01 'Currency', t0.fld_btip02 'name'
        from tbl_bti t0 where t0.fld_bticid = 1)c on c.id=a.fld_costtype
        left join tbl_be d on d.fld_beid=b.fld_baidc
        LEFT JOIN hris.tbl_emp e ON e.fld_empid=b.fld_baidp
        left join tbl_tyval f ON a.fld_currency=f.fld_tyvalcd and f.fld_tyid=39
        left join tbl_tyval g ON a.fld_btp01=g.fld_tyvalcd and g.fld_tyid=67
        left join tbl_bth h ON h.fld_btid=a.fld_bt01
        left join tbl_be i on i.fld_beid=a.fld_bt02 and i.fld_betyid=5
        left join tbl_tyval j ON b.fld_btp18=j.fld_tyvalcd and j.fld_tyid=66
        left join hris.tbl_emp k ON k.fld_empid=b.fld_btp11
        left join hris.tbl_bediv l on l.fld_bedivid=e.fld_empdiv
        left join hris.tbl_emp t ON t.fld_empid=b.fld_btp11
        left join tbl_bediv o on o.fld_bedivid=b.fld_baidv
        left join tbl_be t10 on t10.fld_beid=h.fld_btp15
        left join tbl_bth t11 on t11.fld_btid=a.fld_bt01
        left join dnxapps.tbl_be t12 on t12.fld_beid=t11.fld_baidc
         left join dnxapps.tbl_tyval t13 on t13.fld_tyvalcd = t12.fld_beprefix and t13.fld_tyid = 173
        where a.fld_btidp='$id'";
        $query=$this->db->query($sql);
        return $query;
  }



   function GetDataCashAdvance11($id='')
  {
        $sql="select b.fld_btno, tipe 'desc', g.fld_tyvalnm 'tipe', f.fld_tyvalnm 'currency', j.fld_tyvalnm payment, a.fld_btqty01 'qty',
        a.fld_btuamt01 'amount', d.fld_benm,
        b.fld_btnoreff, e.fld_empnm, a.fld_btqty01 * a.fld_btuamt01 total , h.fld_btno jo, t12.fld_benm 'customer',k.fld_empnm 'ops_staff',l.fld_bedivnm,
        date_format(b.fld_btdt,'%Y-%m-%d') 'req_date', t.fld_empnm staff, o.fld_bedivnm,
   case
        when h.fld_btp08 != '' then h.fld_btp08
        when h.fld_btp07 != '' then h.fld_btp07
        else null
   end bl,
   t10.fld_benm 'shipping_line',b.fld_btp13 totalopidr,b.fld_btp14 totalopusd
        from tbl_btd_cost a
        left join tbl_bth b on a.fld_btidp=b.fld_btid
        left join
        (select t0.fld_btiid 'id', t0.fld_btinm  'tipe', t0.fld_btip01 'Currency', t0.fld_btip02 'name'
        from tbl_bti t0 where t0.fld_bticid = 1)c on c.id=a.fld_costtype
        left join tbl_be d on d.fld_beid=b.fld_baidc
        left join hris.tbl_emp e ON e.fld_empid=b.fld_baidp
        left join tbl_tyval f ON a.fld_currency=f.fld_tyvalcd and f.fld_tyid=39
        left join tbl_tyval g ON a.fld_btp01=g.fld_tyvalcd and g.fld_tyid=67
        left join tbl_bth h ON h.fld_btid=a.fld_bt01
        left join tbl_be i on i.fld_beid=a.fld_bt02 and i.fld_betyid=5
        left join tbl_tyval j ON a.fld_paytype=j.fld_tyvalcd and j.fld_tyid=66
        left join hris.tbl_emp k ON k.fld_empid=b.fld_btp11
        left join hris.tbl_bediv l on l.fld_bedivid=e.fld_empdiv
        left join hris.tbl_emp t ON t.fld_empid=b.fld_btp11
        left join tbl_bediv o on o.fld_bedivid=b.fld_baidv
        left join tbl_be t10 on t10.fld_beid=h.fld_btp15
        left join tbl_bth t11 on t11.fld_btid=a.fld_bt01
        left join dnxapps.tbl_be t12 on t12.fld_beid=t11.fld_baidc
        where a.fld_btidp='$id'
        UNION
        select '' ,concat('Over Payment'),'','','','1',if(x.fld_btamt01=0,x.fld_btamt02,x.fld_btamt01) op_amount,'','','',if(x.fld_btamt01=0,x.fld_btamt02,x.fld_btamt01) op_amount,'','','','','','','',concat(x.fld_btno),'','',''
        from tbl_btd_over_payment x
        where x.fld_btidp='$id'";

        $query=$this->db->query($sql);
        return $query;

  }

   function GetDataAdvanceRepo($id='')
  {
        $sql="select a.fld_btno,b.fld_bedivnm ,date_format(a.fld_btdt,'%Y-%m-%d') 'req_date',a.fld_btamt 'amount_repo',c.fld_empnm 'ops_staff',
              d.fld_empnm staff
              from tbl_bth a
	      left join tbl_bediv b on b.fld_bedivid=a.fld_baidv
              left join hris.tbl_emp c ON c.fld_empid=a.fld_btp11
              left join hris.tbl_emp d ON d.fld_empid=a.fld_btp11

              where a.fld_btid='$id'";
        $query=$this->db->query($sql);
        return $query;
  }

   function GetDataReimburse($id='')
  {
        $sql="select a.fld_btno,b.fld_bedivnm ,date_format(a.fld_btdt,'%d/%m/%Y') 'req_date',a.fld_btamt 'total',concat(c.fld_empnm,' [',c.fld_empnip,']') 'ops_staff',a.fld_btdesc 'note',
              f.fld_tyvalnm 'cost_name',e.fld_btdesc 'desc',e.fld_btnoreff 'noreff',e.fld_btdocreff 'docreff',e.fld_btamt01 'amount',
              date_format(a.fld_btp02,'%d/%m/%Y') 'from',date_format(a.fld_btp03,'%d/%m/%Y') 'to',g.fld_tyvalnm 'coo_type',h.fld_tyvalnm 'pay_type',
              e.fld_btp04 'ska_num',i.fld_usercomp 'company'
              from tbl_bth a
              left join tbl_bediv b on b.fld_bedivid=a.fld_baidv
              left join hris.tbl_emp_exim c ON c.fld_empid=a.fld_btp11
              left join tbl_btd_finance e on e.fld_btidp = a.fld_btid
              left join tbl_tyval f on f.fld_tyvalcd = e.fld_btiid and f.fld_tyid = 79
              left join tbl_tyval g on g.fld_tyvalcd = e.fld_btp01 and g.fld_tyid = 80
              left join tbl_tyval h on h.fld_tyvalcd = e.fld_btp03 and h.fld_tyid = 81
              left join tbl_user i on i.fld_userid = a.fld_btp23
              where a.fld_btid='$id'";

        $query=$this->db->query($sql);
        return $query;
  }


  function GetDataSettlement($id='')
  {
	$sql="SELECT fld_btno, jo, customer, division, bl, advance, settle_date,alamat,npwp,
	MAX(IF(fld_tyvalnm = 'IDR', A.release, 0)) AS 'releaseidr',
	MAX(IF(fld_tyvalnm = 'USD', A.release, 0)) AS 'releaseusd',
	MAX(IF(fld_tyvalnm = 'IDR', A.terpakai, 0)) AS 'terpakaiidr',
	MAX(IF(fld_tyvalnm = 'USD', A.terpakai, 0)) AS 'terpakaiusd',
	fld_empnm,fld_btnoalt,paytype,usercomp
	FROM(
	SELECT b.fld_btno,date_format(b.fld_btdt,'%d/%m/%Y') settle_date, h.fld_btno jo,
        concat(if(i.fld_beprefix > 0,concat(n.fld_tyvalnm, '. '),''), i.fld_benm) customer, concat(h.fld_btp07,'/',h.fld_btp08) bl, b.fld_btnoalt, k.fld_btno advance,m.fld_tyvalnm paytype,
	f.fld_tyvalnm, c.name,
	CASE
		WHEN f.fld_tyvalnm='USD' THEN b.fld_btp08
		WHEN f.fld_tyvalnm='IDR' THEN b.fld_btp12
		ELSE 0
	END 'release',
	IFNULL(SUM(a.fld_btuamt01 * a.fld_btqty01),0) terpakai,
	CASE
		WHEN f.fld_tyvalnm='USD' THEN b.fld_btp08 - IFNULL(SUM(a.fld_btuamt01 * a.fld_btqty01),0)
		WHEN f.fld_tyvalnm='IDR' THEN b.fld_btp12 - IFNULL(SUM(a.fld_btuamt01 * a.fld_btqty01),0)
		ELSE 0
	END selisih, e.fld_empnm,
  a.fld_btp07 'npwp',
		   a.fld_btp08 'alamat',
        date_format(b.fld_btdt,'%Y-%m-%d') 'date',l.fld_bedivnm division,o.fld_usercomp 'usercomp'
		FROM tbl_btd_cost a
		LEFT JOIN tbl_bth b ON a.fld_btidp=b.fld_btid LEFT JOIN
		(SELECT t0.fld_btiid 'id', t0.fld_btinm  'tipe', t0.fld_btip01 'Currency', t0.fld_btip02 'name'
		FROM tbl_bti t0 WHERE t0.fld_bticid = 1)c ON c.id=a.fld_costtype
		LEFT JOIN dnxapps.tbl_be d ON d.fld_beid=b.fld_baidc
		LEFT JOIN hris.tbl_emp_exim e ON e.fld_empid=b.fld_baidp
		LEFT JOIN tbl_tyval f ON a.fld_currency=f.fld_tyvalcd AND f.fld_tyid=39
		LEFT JOIN tbl_tyval g ON c.name=g.fld_tyvalcd AND g.fld_tyid=67
		LEFT JOIN tbl_bth h ON h.fld_btid=a.fld_bt01 AND h.fld_bttyid in (1,65)
		LEFT JOIN dnxapps.tbl_be i ON i.fld_beid=h.fld_baidc
		LEFT JOIN tbl_btr j ON j.fld_btrdst='$id'
		LEFT JOIN tbl_bth k ON k.fld_btid =j.fld_btrsrc
		LEFT JOIN tbl_bediv l on l.fld_bedivid=b.fld_baidv
                LEFT JOIN tbl_tyval m ON m.fld_tyvalcd=k.fld_btp18 AND m.fld_tyid=66
                left join dnxapps.tbl_tyval n on n.fld_tyvalcd = i.fld_beprefix and n.fld_tyid = 173
                left join tbl_user o on o.fld_userid = b.fld_btp23
		WHERE a.fld_btidp='$id'
		GROUP BY b.fld_btno, h.fld_btno, i.fld_benm, f.fld_tyvalnm)A
		GROUP BY customer, jo";
	$query=$this->db->query($sql);
	if ($query->num_rows() <= 0)
	{
		$sql="SELECT a.fld_btno, c.fld_btno advance, d.fld_bedivnm division, date_format(a.fld_btdt,'%d/%m/%Y') settle_date, e.fld_empnm
		FROM tbl_bth a
		LEFT JOIN tbl_btr b ON b.fld_btrdst='$id'
		LEFT JOIN tbl_bth c ON c.fld_btid =b.fld_btrsrc
		LEFT JOIN tbl_bediv d on d.fld_bedivid=a.fld_baidv
		LEFT JOIN hris.tbl_emp e ON e.fld_empid=a.fld_baidp
		WHERE a.fld_btid='$id'";
		$query=$this->db->query($sql);
	}
	return $query;
  }

  function GetDataSettlementExp($id='')
  {
        $sql="SELECT fld_btno, jo, customer, division, bl, advance,
        MAX(IF(fld_tyvalnm = 'IDR', A.release, 0)) AS 'releaseidr',
        MAX(IF(fld_tyvalnm = 'USD', A.release, 0)) AS 'releaseusd',
        MAX(IF(fld_tyvalnm = 'IDR', A.terpakai, 0)) AS 'terpakaiidr',
        MAX(IF(fld_tyvalnm = 'USD', A.terpakai, 0)) AS 'terpakaiusd',
        fld_empnm,fld_btnoalt
        FROM(
        SELECT b.fld_btno, h.fld_btno jo, concat(if(i.fld_beprefix > 0,concat(m.fld_tyvalnm, '. '),''), i.fld_benm) customer,
        h.fld_btp08 bl, b.fld_btnoalt, k.fld_btno advance,
        f.fld_tyvalnm, c.name,
        CASE
                WHEN f.fld_tyvalnm='USD' THEN b.fld_btp08
                WHEN f.fld_tyvalnm='IDR' THEN b.fld_btp12
                ELSE 0
        END 'release',
        IFNULL(SUM(a.fld_btuamt01 * a.fld_btqty01),0) terpakai,
        CASE
                WHEN f.fld_tyvalnm='USD' THEN b.fld_btp08 - IFNULL(SUM(a.fld_btuamt01 * a.fld_btqty01),0)
                WHEN f.fld_tyvalnm='IDR' THEN b.fld_btp12 - IFNULL(SUM(a.fld_btuamt01 * a.fld_btqty01),0)
                ELSE 0
        END selisih, e.fld_empnm,
        date_format(b.fld_btdt,'%Y-%m-%d') 'date',l.fld_bedivnm division
                FROM tbl_btd_cost a
                LEFT JOIN tbl_bth b ON a.fld_btidp=b.fld_btid LEFT JOIN
                (SELECT t0.fld_btiid 'id', t0.fld_btinm  'tipe', t0.fld_btip01 'Currency', t0.fld_btip02 'name'
                FROM tbl_bti t0 WHERE t0.fld_bticid = 1)c ON c.id=a.fld_costtype
                LEFT JOIN dnxapps.tbl_be d ON d.fld_beid=b.fld_baidc
                LEFT JOIN hris.tbl_emp_exim e ON e.fld_empid=b.fld_baidp
                LEFT JOIN tbl_tyval f ON a.fld_currency=f.fld_tyvalcd AND f.fld_tyid=39
                LEFT JOIN tbl_tyval g ON c.name=g.fld_tyvalcd AND g.fld_tyid=67
                LEFT JOIN tbl_bth h ON h.fld_btid=a.fld_bt01 AND h.fld_bttyid in (6,10)
                LEFT JOIN dnxapps.tbl_be i ON i.fld_beid=h.fld_baidc AND i.fld_betyid=5
                LEFT JOIN tbl_btr j ON j.fld_btrdst='$id'
                LEFT JOIN tbl_bth k ON k.fld_btid =j.fld_btrsrc
                LEFT JOIN tbl_bediv l on l.fld_bedivid=b.fld_baidv
                left join dnxapps.tbl_tyval m on m.fld_tyvalcd = i.fld_beprefix and m.fld_tyid = 173
                WHERE a.fld_btidp='$id'
                GROUP BY b.fld_btno, h.fld_btno, i.fld_benm, f.fld_tyvalnm)A
                GROUP BY customer, jo";
        $query=$this->db->query($sql);
        return $query;
  }

  function SumParty($fld_btid)
  {
      $sql="SELECT A.fld_btidp,
      MAX(IF(A.fld_contsize = '20', A.JUMLAH, 0)) as 'JmlCon20',
      MAX(IF(A.fld_contsize = '40', A.JUMLAH, 0)) as 'JmlCon40'
      FROM(
      SELECT A.fld_btidp, A.fld_contsize, COUNT(A.fld_contsize) JUMLAH
      FROM tbl_btd_container A
      WHERE A.fld_btidp='$fld_btid'
      GROUP BY A.fld_btidp, A.fld_contsize)A";
      $query=$this->db->query($sql);
      foreach ($query->result() as $row) {
        $JmlCon20=$row->JmlCon20;
	$JmlCon40=$row->JmlCon40;
      }
      $query->free_result();
      $data = array(
  	'fld_btqty' => $JmlCon20,
	'fld_btp06' => $JmlCon40
      );
      $this->db->where('fld_btid', $fld_btid);
      $this->db->update('tbl_bth', $data);
  }

  function insertJOProgress($fld_btid) {

   $this->db->query("insert into tbl_btd_progress (fld_btidp,fld_dt01,fld_dt02,fld_dt03,fld_dt04,fld_dt05,fld_dt06,fld_dt07,fld_dt08)
                     values ($fld_btid,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','0000-00-00','0000-00-00','0000-00-00','0000-00-00')");

  }

  function insertImpDoc($fld_btid) {
   $location = $this->session->userdata('location');
   $cek = $this->db->query("select fld_bttax 'imp',fld_btiid 'plb' from tbl_bth where fld_btid=$fld_btid");
   $cek = $cek->row();


   //Air Document
   if($location !=4 and $cek->plb !=5){
   if($cek->imp == 5) {

   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'741',UUID_SHORT())");  //Master AWB
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'1003',UUID_SHORT())"); //House AWB
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'380',UUID_SHORT())"); //Invoice
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'217',UUID_SHORT())"); //Packing List
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'1004',UUID_SHORT())"); //Asuransi

   }

   //Sea Document
   else{

   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'704',UUID_SHORT())");  //Master BL
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'1002',UUID_SHORT())"); //House BL
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'380',UUID_SHORT())"); //Invoice
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'217',UUID_SHORT())"); //Packing List
   $this->db->query("insert into tbl_btd_document (fld_btidp,fld_doctype,fld_docnum)
                     values ($fld_btid,'1004',UUID_SHORT())"); //Asuransi


   }
  }

  }



  function insertContExp($fld_btid,$fld_btqty,$fld_btp06,$fld_btuamt){

    $cek = $this->db->query("select fld_btidp 'idp' from tbl_btd_container where fld_btidp=$fld_btid");
    $cek = $cek->row();
    if ($cek->idp < 1) {

    $x = 0;
    $y = 300;
    $z = 600;
    $sum_cont = 0;
    $sum_cont = $fld_btqty+$fld_btp06+$fld_btuamt;


    if ($sum_cont > 0) {
         //insert 20FT
         if ($fld_btqty > 0) {
             for($x=1; $x<=$fld_btqty; $x++){
             $this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_contsize) values ($fld_btid,$x,1)");
         }
        }

          //insert 40FT
         if ($fld_btp06 > 0) {
             for($x=1; $x<=$fld_btp06; $x++){

             $this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_contsize) values ($fld_btid,$y,2)");
             $y++;
         }
        }
 //insert 40HC
         if ($fld_btuamt > 0) {
             for($x=1; $x<=$fld_btuamt; $x++){
             $this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_contsize) values ($fld_btid,$z,3)");
             $z++;
         }
        }

    }
   }
   else {

   $sum_20 = $fld_btqty;
   $sum_40 = $fld_btp06;
   $sum_40hc = $fld_btuamt;

   $cek_dtl_20 = $this->db->query("select *  from tbl_btd_container t0
                                   where t0.fld_btidp = $fld_btid and t0.fld_contsize = 1");

   $sum_dtl_20 = $cek_dtl_20->num_rows();
   $cek_dtl_20 = $cek_dtl_20->row();

   if($sum_dtl_20  > $sum_20){
    $diff_20 =  $sum_dtl_20  - $sum_20;
    $del_20 = $this->db->query("delete from tbl_btd_container
                                where fld_btidp = $fld_btid and fld_contsize = 1 and fld_btp10 ='' and length('$cek_dtl_20->fld_contnum')!=11
                                limit $diff_20  ");
   }

   else {
   //insert detail container
   $max20 = $this->db->query("select (MAX(fld_contnum)+1) 'max20' from tbl_btd_container t0
                              where t0.fld_btidp = $fld_btid and t0.fld_contsize = 1 and length(fld_contnum)!=11")->row()->max20;
   if ($max20 == 1 || $max20 == null) {
      $max20=700;
   }
   $diff_20 =  $sum_20  - $sum_dtl_20;
      if ($diff_20 > 0) {
             for($x=1; $x<=$diff_20; $x++){
               $this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_contsize) values ($fld_btid,$max20,1)");
               $max20++;
             }

	 }

   }

   $cek_dtl_40 = $this->db->query("select *  from tbl_btd_container t0
                                   where t0.fld_btidp = $fld_btid and t0.fld_contsize = 2");

   $sum_dtl_40 = $cek_dtl_40->num_rows();
   $cek_dtl_40 = $cek_dtl_40->row();

   if($sum_dtl_40 > $sum_40){
    $diff_40 =  $sum_dtl_40 - $sum_40;
    $del_40 = $this->db->query("delete from tbl_btd_container
                                where fld_btidp = $fld_btid and fld_contsize = 2 and fld_btp10 ='' and length('$cek_dtl_40->fld_contnum')!=11
                                limit $diff_40  ");
   }

   else {
   //insert detail container
   $max40 = $this->db->query("select (MAX(fld_contnum)+1) 'max40' from tbl_btd_container t0
                              where t0.fld_btidp = $fld_btid and t0.fld_contsize = 2 and length(fld_contnum)!=11")->row()->max40;

   if ($max40 == 1 || $max40 == null) {
      $max40=800;
   }

   $diff_40 =  $sum_40  - $sum_dtl_40;
      if ($diff_40 > 0) {
             for($y=1; $y<=$diff_40; $y++){
               $this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_contsize) values ($fld_btid,$max40,2)");
               $max40++;
             }
     }

   }


   $cek_dtl_40hc = $this->db->query("select *  from tbl_btd_container t0
                                   where t0.fld_btidp = $fld_btid and t0.fld_contsize = 3");

   $sum_dtl_40hc = $cek_dtl_40hc->num_rows();
   $cek_dtl_40hc = $cek_dtl_40hc->row();

  if($sum_dtl_40hc > $sum_40hc){
    $diff_40hc =  $sum_dtl_40hc  - $sum_40hc;
    $del_40hc = $this->db->query("delete from tbl_btd_container
                                  where fld_btidp = $fld_btid and fld_contsize = 3 and fld_btp10 ='' and length('$cek_dtl_40hc->fld_contnum')!=11
                                  limit $diff_40hc  ");
   }

   else {
   //insert detail container
   $max40hc = $this->db->query("select(MAX(fld_contnum)+1) 'max40hc' from tbl_btd_container t0
                              where t0.fld_btidp = $fld_btid and t0.fld_contsize = 3 and length(fld_contnum)!=11 ")->row()->max40hc;
   #echo $max40hc->max40hc ;
    #exit();

   if ($max40hc == 1 || $max40hc == null) {
      # echo $max40hc;
      #exit();
       $max40hc=900;
   }
   $diff_40hc =  $sum_40hc  - $sum_dtl_40hc;

      if ($diff_40hc > 0) {
             for($z=1; $z<=$diff_40hc; $z++){
               $this->db->query("insert  into tbl_btd_container (fld_btidp,fld_contnum,fld_contsize) values ($fld_btid,$max40hc,3)");
               $max40hc++;
             }
     }

   }

  }

 }

  function CopyBookingData($id)
  {
   //copy data booking to detail job order export
  $get_cont = $this->db->query("select * from tbl_btd_truck t0 where t0.fld_btidp=$id");

       if ($get_cont->num_rows() > 0){
           foreach ($get_cont->result() as $row) {


           $update_cont_20 = $this->db->query("update tbl_btd_container t0
                                               set t0.fld_btp09 = '$row->fld_bt06',t0.fld_btp01 = '$row->fld_bt03',t0.fld_btp10 = $row->fld_btid,
                                               t0.fld_btpdt02 = '$row->fld_btdt'
                                               where t0.fld_btidp = '$row->fld_bt01' and t0.fld_contsize = 1 and t0.fld_btp10 = '' limit $row->fld_bt04");


           $update_cont_40 = $this->db->query("update tbl_btd_container t0
                                               set t0.fld_btp09 = '$row->fld_bt06',t0.fld_btp01 = '$row->fld_bt03',t0.fld_btp10 = $row->fld_btid,
                                               t0.fld_btpdt02 = '$row->fld_btdt'
                                               where t0.fld_btidp = '$row->fld_bt01' and t0.fld_contsize = 2 and t0.fld_btp10 = '' limit $row->fld_bt10");


           $update_cont_40hc = $this->db->query("update tbl_btd_container t0
                                               set t0.fld_btp09 = '$row->fld_bt06',t0.fld_btp01 = '$row->fld_bt03',t0.fld_btp10 = $row->fld_btid,
                                               t0.fld_btpdt02 = '$row->fld_btdt'
                                               where t0.fld_btidp = '$row->fld_bt01' and t0.fld_contsize = 3 and t0.fld_btp10 = '' limit $row->fld_bt05");
           }

       }
  }

  function ExportDataTruck($id)
  {
  $data=$this->db->query("select fld_baidv from tbl_bth where fld_btid = $id");
  $data=$data->row();
  if ($data->fld_baidv == 13){ //EXPORT
     $sql="insert into DBDNX.Trs_PlanExp select 'JKT' cbng,
        #(SELECT IFNULL(max(PlanNo),0) +1 PlanNo FROM DBDNX.Trs_PlanExp WHERE Period = month(now())
        #AND YearPeriod = year(now())) PlanNo,
        t0.fld_btid PlanNo,
        month(now()) Period,
        year(now()) yearPeriod,
        10 jobCode,
        t2.fld_btno,
        concat(if(t1.fld_beprefix > 0,concat(t10.fld_tyvalnm, '. '),''), t1.fld_benm) customer,
        t5.fld_btdt,
        t0.fld_bt07,
        0 Combo,
        t0.fld_bt04 c20,
        t0.fld_bt10,
        t0.fld_bt05,
        t0.fld_bt11,
        t0.fld_bt09,
        0 QtyPackages,
        null Facility,
        t7.fld_tyvalnm DGType,
        null Response, if(t9.fld_tyvalnm IS NULL,'INT',t9.fld_tyvalnm) LoadStatus, t8.fld_tyvalnm Waiting, 0 OPSCC, 0 OPSCL, 0 OPSBL, 0 OPSINS,
        t4.fld_empnm UserCode, t3.fld_btdt EntryDate, 0 deleted,
        null DateDeleted, 0 Truck, t3.fld_btid
        from tbl_btd_truck t0
        left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
        left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
        left join tbl_bth t3 on t3.fld_btid=t0.fld_btidp
        left join hris.tbl_emp t4 on t4.fld_empid=t3.fld_baidp
        left join tbl_btd_stuffing t5 on t5.fld_btid=t0.fld_btp02
        left join tbl_be t6 on t6.fld_beid = t2.fld_btp17 and t6.fld_betyid=4
        left join tbl_tyval t7 on t7.fld_tyvalcd = t2.fld_btiid and t7.fld_tyid=68
        left join tbl_tyval t8 on t8.fld_tyvalcd = t2.fld_btflag and t8.fld_tyid=71
        left join tbl_tyval t9 on t9.fld_tyvalcd = t2.fld_btidp and t9.fld_tyid=51
        left join dnxapps.tbl_tyval t10 on t10.fld_tyvalcd = t1.fld_beprefix and t10.fld_tyid = 173
        where t0.fld_btidp='$id' and t0.fld_bt03='DET'";
     $this->db->query($sql);

     $sql="insert into DBDNXDE.Trs_PlanExp select 'JKT' cbng,
        #(SELECT IFNULL(max(PlanNo),0) +1 PlanNo FROM DBDNXDE.Trs_PlanExp WHERE Period = month(now())
        #AND YearPeriod = year(now())) PlanNo,
        t0.fld_btid PlanNo,
        month(now()) Period,
        year(now()) yearPeriod,
        10 jobCode,
        t2.fld_btno,
        t1.fld_benm customer,
        t5.fld_btdt,
        t0.fld_bt07,
        0 Combo,
        t0.fld_bt04 c20,
        t0.fld_bt10,
        t0.fld_bt05,
        t0.fld_bt11,
        t0.fld_bt09,
        0 QtyPackages,
        null Facility,
        t7.fld_tyvalnm DGType,
        null Response, if(t9.fld_tyvalnm IS NULL,'INT',t9.fld_tyvalnm) LoadStatus, t8.fld_tyvalnm Waiting, 0 OPSCC, 0 OPSCL, 0 OPSBL, 0 OPSINS,
        t4.fld_empnm UserCode, t3.fld_btdt EntryDate, 0 deleted,
        null DateDeleted, 0 Truck, t3.fld_btid
        from tbl_btd_truck t0
        left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
        left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
        left join tbl_bth t3 on t3.fld_btid=t0.fld_btidp
        left join hris.tbl_emp t4 on t4.fld_empid=t3.fld_baidp
        left join tbl_btd_stuffing t5 on t5.fld_btid=t0.fld_btp02
        left join tbl_be t6 on t6.fld_beid = t2.fld_btp17 and t6.fld_betyid=4
        left join tbl_tyval t7 on t7.fld_tyvalcd = t2.fld_btiid and t7.fld_tyid=68
        left join tbl_tyval t8 on t8.fld_tyvalcd = t2.fld_btflag and t8.fld_tyid=71
        left join tbl_tyval t9 on t9.fld_tyvalcd = t2.fld_btidp and t9.fld_tyid=51
        where t0.fld_btidp='$id' and t0.fld_bt03='DE'";
     $this->db->query($sql);

  }
  else if ($data->fld_baidv == 14){  //IMPORT
     $sql="insert into DBDNX.Trs_PlanImp select 'JKT' cbng,
        #(SELECT IFNULL(max(PlanNo),0) +1 PlanNo FROM DBDNX.Trs_PlanImp WHERE Period = month(now())
        #AND YearPeriod = year(now())) PlanNo,
        t0.fld_btid PlanNo,
        month(now()) Period,
        year(now()) yearPeriod,
        11 jobCode,
        t3.fld_btdtsa,
        t2.fld_btno,
        t0.fld_bt02 PIBNo,
        t4.fld_empnm,
        t1.fld_benm customer, t0.fld_bt06 delivery,
        t0.fld_bt04 c20,
        t0.fld_bt05 c40, 0 C40HC, 0 C45FT, 0 LCL, t2.fld_btuamt gross, 0 Measurement,
        now() Tila, t2.fld_btp01 terminal, null depo, t2.fld_btdtso demorage, null SSLine, null 'Field',
        0 OPSCC, 0 OPSCL, 0 OPSBL, 0 OPSINS, t4.fld_empnm UserCode, t3.fld_btdt EntryDate, 0 deleted,
        null DateDeleted, 0 Truck, t3.fld_btid
        from tbl_btd_truck t0
        left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
        left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
        left join tbl_bth t3 on t3.fld_btid=t0.fld_btidp
        left join hris.tbl_emp t4 on t4.fld_empid=t3.fld_baidp
        where t0.fld_btidp='$id' and t0.fld_bt03='DET'";
     $this->db->query($sql);

     $sql="insert into DBDNXDE.Trs_PlanImp select 'JKT' cbng,
        #(SELECT IFNULL(max(PlanNo),0) +1 PlanNo FROM DBDNXDE.Trs_PlanImp WHERE Period = month(now())
        #AND YearPeriod = year(now())) PlanNo,
        t0.fld_btid PlanNo,
        month(now()) Period,
        year(now()) yearPeriod,
        11 jobCode,
        t3.fld_btdtsa,
        t2.fld_btno,
        t0.fld_bt02 PIBNo,
        t4.fld_empnm,
        t1.fld_benm customer, t0.fld_bt06 delivery,
        t0.fld_bt04 c20,
        t0.fld_bt05 c40, 0 C40HC, 0 C45FT, 0 LCL, t2.fld_btuamt gross, 0 Measurement,
        now() Tila, t2.fld_btp01 terminal, null depo, t2.fld_btdtso demorage, null SSLine, null 'Field',
        0 OPSCC, 0 OPSCL, 0 OPSBL, 0 OPSINS, t4.fld_empnm UserCode, t3.fld_btdt EntryDate, 0 deleted,
        null DateDeleted, 0 Truck, t3.fld_btid
        from tbl_btd_truck t0
        left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
        left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
        left join tbl_bth t3 on t3.fld_btid=t0.fld_btidp
        left join hris.tbl_emp t4 on t4.fld_empid=t3.fld_baidp
        where t0.fld_btidp='$id' and t0.fld_bt03='DE'";
     $this->db->query($sql);
  }
  else{
  $sql='';
  }

  }

  function ExportDataTruckExp($id)
  {
     $sql="insert into DBDNX.Trs_PlanExp select 'JKT' cbng,
        (SELECT IFNULL(max(PlanNo),0) +1 PlanNo FROM DBDNX.Trs_PlanExp WHERE Period = month(now())
        AND YearPeriod = year(now())) PlanNo,
        month(now()) Period,
        year(now()) yearPeriod,
        10 jobCode,
        t2.fld_btno,
        t1.fld_benm customer,
        t5.fld_btdt,
        t6.fld_benm,
        0 Combo,
        t0.fld_bt04 c20,
        t0.fld_bt10,
        t0.fld_bt05,
        t0.fld_bt11,
        t0.fld_bt09,
        0 QtyPackages,
        null Facility,
        null DGType,
        null Response, null LoadStatus, null Waiting, 0 OPSCC, 0 OPSCL, 0 OPSBL, 0 OPSINS,
        t4.fld_empnm UserCode, t3.fld_btdt EntryDate, 0 deleted,
        null DateDeleted, 0 Truck, t3.fld_btid
        from tbl_btd_truck t0
        left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
        left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
        left join tbl_bth t3 on t3.fld_btid=t0.fld_btidp
        left join hris.tbl_emp t4 on t4.fld_empid=t3.fld_baidp
        left join tbl_btd_stuffing t5 on t5.fld_btidp=t2.fld_btid
        left join tbl_be t6 on t6.fld_beid = t2.fld_btp17 and t6.fld_betyid=4
        where t0.fld_btidp='$id' and t0.fld_bt03='DET'";
     $this->db->query($sql);

     $sql="insert into DBDNXDE.Trs_PlanExp select 'JKT' cbng,
        (SELECT IFNULL(max(PlanNo),0) +1 PlanNo FROM DBDNXDE.Trs_PlanExp WHERE Period = month(now())
        AND YearPeriod = year(now())) PlanNo,
        month(now()) Period,
        year(now()) yearPeriod,
        10 jobCode,
        t2.fld_btno,
        t1.fld_benm customer,
        t5.fld_btdt,
        t6.fld_benm,
        0 Combo,
        t0.fld_bt04 c20,
        t0.fld_bt10,
        t0.fld_bt05,
        t0.fld_bt11,
        t0.fld_bt09,
        0 QtyPackages,
        null Facility,
        null DGType,
        null Response, null LoadStatus, null Waiting, 0 OPSCC, 0 OPSCL, 0 OPSBL, 0 OPSINS,
        t4.fld_empnm UserCode, t3.fld_btdt EntryDate, 0 deleted,
        null DateDeleted, 0 Truck, t3.fld_btid
        from tbl_btd_truck t0
        left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
        left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
        left join tbl_bth t3 on t3.fld_btid=t0.fld_btidp
        left join hris.tbl_emp t4 on t4.fld_empid=t3.fld_baidp
        left join tbl_btd_stuffing t5 on t5.fld_btidp=t2.fld_btid
        left join tbl_be t6 on t6.fld_beid = t2.fld_btp17 and t6.fld_betyid=4
        where t0.fld_btidp='$id' and t0.fld_bt03='DE'";
     $this->db->query($sql);


  }

  function UpdJOExp($id,$Mbl){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $sql="update tbl_btd_cost set fld_bt01 = (select fld_btidp from tbl_btd_document where fld_btid='$Mbl') where fld_btidp='$id' and fld_bt04='$Mbl'";
    $this->db->query($sql);
  }

  function GetCostAmount($id,$div,$tipe='',$curr,$krani='') {
    $sql='';
    $division=$this->session->userdata('divid');
	$sql .="SELECT * FROM v_ops_cost where id='$id'";
	$sql .=" and export='1'";
	$sql .=" and DivID='$division'";
	if (!empty($tipe))
	{
		$sql .=" and TypeID='$tipe'";
	}
	$sql .=" and CurrID='$curr'";
	if (!empty($krani))
	{
		$sql .=" and KraniID='$krani'";
	}
	$sql .=" and ValidStart <= curdate() and ValidUntil >= curdate()";
	$query=$this->db->query($sql);
	return $query->row();
  }

  function cekJobData($btid,$housebl,$masterbl,$type,$f20,$f40,$co_doc){
    if ($co_doc == 1){
    $i = 1;
    if(empty($_POST["78000JOB_DOCUMENTfld_doctype".$i])){
    $this->ffis->message("Update Data Failed!! detail Certificate of Origin (CO) must be insert When Document CO Enabled");
   }
    while (isset($_POST["78000JOB_DOCUMENTfld_doctype".$i])) {
    $dtl = $this->db->query("select count(1) from tbl_btd_document where fld_btidp = '$btid' and fld_doctype = 861 ");
    $dtl = $dtl->num_rows();
    $doctype=$_POST["78000JOB_DOCUMENTfld_doctype".$i];
    if ($doctype != 861 && $dtl == 0)
        {
           $this->ffis->message("Update Data Failed!! Certificate of Origin (CO) type must be insert When Document CO Enabled");

        }
    $i++;
         }

    }

    $data = $this->db->query("select count(1) 'counting' from tbl_bth where fld_bttyid in(1,65) and fld_btp07='$housebl' or fld_btp08='$masterbl'");
    $data = $data->row();
    if($data->counting > 0) {
    echo "HOUSE B/L OR MASTER B/L IS ALREADY EXIST<br>CLICK BACK TO CONTINUE";
   # exit();
    }
  }

  function AdvanceApproval($id='') {
    $query=$this->db->query("select t0.fld_btno,
                             t2.fld_bedivnm,date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                             t3.fld_empnm 'krani',
                             t4.fld_btno 'jo',
                             ifnull(t4.fld_btp08,t4.fld_btp07) 'bl',
                             t5.fld_benm 'shipping_line',
                             t1.fld_btamt05 'idr',
                             t1.fld_btamt06 'usd',
                             t0.fld_btp02 'tot_idr',
                             t0.fld_btp03 'tot_usd',
                             t0.fld_btamt 'advidr',
                             t0.fld_btuamt 'advusd',
                             t6.fld_empnm 'kasir',
                             t7.fld_benm 'shipping',
                             t8.fld_becredno 'bank_acc',
			     t10.fld_tyvalnm 'pay',
                             t9.fld_tyvalnm 'bank_name',
                             t0.fld_btp23 'userid'
                             from tbl_bth t0
                             left join tbl_btd_advaprv t1 on t1.fld_btidp = t0.fld_btid
                             left join hris.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidv
                             left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_btp01
                             left join tbl_bth t4 on t4.fld_btid=t1.fld_btreffid
                             left join tbl_be t5 on t5.fld_beid=t4.fld_btp15
                             left join hris.tbl_emp t6 on t6.fld_empid=t0.fld_baidp

                             left join tbl_be t7 on t7.fld_beid = t4.fld_btp15 and t7.fld_betyid = 8
                             left join tbl_becred t8 on t8.fld_beid = t4.fld_btp15 and t8.fld_becredid = t4.fld_btp19
                             left join tbl_tyval t9 on t9.fld_tyvalcd = t8.fld_btp01 and t9.fld_tyid = 95
			     left join tbl_tyval t10 on t10.fld_tyvalcd =t0.fld_btp18 and t10.fld_tyid = 66
                             where t0.fld_btid='$id'");
    return $query;
  }

  function SettlementApproval($id='') {
    $query=$this->db->query("select
                             t0.*,
                             t0.fld_btno 'settnum',
                             t1.*,
                             t2.fld_bedivnm,date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                             t3.fld_empnm 'krani',
                             t4.fld_btno 'jo',
                             ifnull(t4.fld_btp08,t4.fld_btp07) 'bl',
                             t5.fld_benm 'shipping_line',
                             t6.fld_empnm 'kasir',
                             t7.fld_usercomp 'usercomp'
                             from tbl_bth t0
                             left join tbl_btd_advaprv t1 on t1.fld_btidp = t0.fld_btid
                             left join hris.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidv
                             left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_btp01
                             left join tbl_bth t4 on t4.fld_btid=t1.fld_btreffid
                             left join tbl_be t5 on t5.fld_beid=t4.fld_btp15
                             left join hris.tbl_emp t6 on t6.fld_empid=t0.fld_baidp
                             left join tbl_user t7 on t7.fld_userid = t0.fld_btp23
                             where t0.fld_btid='$id'");
    return $query;
  }

  function SettlementReceipt($id='') {
    $query=$this->db->query("select
                             t0.*,
                             t0.fld_btno 'settnum',
                             t1.*,date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                             t3.fld_empnm 'krani',
                             t4.fld_btno 'jo',
                             ifnull(t4.fld_btp08,t4.fld_btp07) 'bl',
                             t5.fld_benm 'shipping_line',
                             t6.fld_empnm 'kasir',
                             t7.fld_usercomp 'usercomp',
                             t11.fld_btno 'jocno',
                             t16.fld_btno 'apvno',
                             t14.fld_tyvalnm 'PaymentType'
                             from tbl_bth t0
                             left join tbl_btd_receipt t1 on t1.fld_btidp = t0.fld_btid
                             left join hris.tbl_emp_exim t3 on t3.fld_empid=t0.fld_baidp
                             left join tbl_bth t4 on t4.fld_btid=t1.fld_btreffid
                             left join tbl_be t5 on t5.fld_beid=t4.fld_btp15
                             left join hris.tbl_emp t6 on t6.fld_empid=t0.fld_baidp
                             left join tbl_user t7 on t7.fld_userid = t0.fld_btp23
                             left join tbl_btr t10 on t10.fld_btrdst=t4.fld_btid
                             left join tbl_bth t11 on t11.fld_btid=t10.fld_btrsrc and t11.fld_bttyid=2
                             left join tbl_tyval t14 on t14.fld_tyvalcd = t1.fld_btiid and t14.fld_tyid=66
                             left join tbl_btd_advaprv t15 on t15.fld_btreffid=t11.fld_btid
                             left join tbl_bth t16 on t16.fld_btid=t15.fld_btidp and t16.fld_bttyid=8
                             where t0.fld_btid='$id'");
    return $query;
  }

  function AdvanceSubmit($id='') {
    $query=$this->db->query("select
                             t0.*,
                             t0.fld_btno 'settnum',
                             t1.*,date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                             t3.fld_empnm 'krani',
                             t4.fld_btno 'jo',
                             t4.fld_bttyid 'tyid',
                             ifnull(t4.fld_btp08,t4.fld_btp07) 'bl',
                             t5.fld_benm 'shipping_line',
                             t6.fld_empnm 'kasir',
                             t7.fld_usercomp 'usercomp',
                             t11.fld_btno 'jocno',
                             t16.fld_btno 'apvno',
                             t4.fld_btamt01 'Advance',
                                if(t4.fld_bttyid=4, t4.fld_btamt, '0') 'Spent',
                                if(t4.fld_bttyid=4, t4.fld_btp05, '0') 'OverPayment',
                                if(t4.fld_bttyid=4, t4.fld_btp13, '0') 'Remain',
                                if(t4.fld_bttyid=4, t4.fld_btamt02, '0') 'PaymentUSD',
                                t14.fld_tyvalnm 'PaymentType'
                             from tbl_bth t0
                             left join tbl_btd_doc t1 on t1.fld_btidp = t0.fld_btid
                             left join hris.tbl_emp_exim t3 on t3.fld_empid=t0.fld_baidp
                             left join tbl_bth t4 on t4.fld_btid=t1.fld_btiid
                             left join tbl_be t5 on t5.fld_beid=t4.fld_btp15
                             left join hris.tbl_emp t6 on t6.fld_empid=t0.fld_baidp
                             left join tbl_user t7 on t7.fld_userid = t0.fld_btp23
                             left join tbl_btr t10 on t10.fld_btrdst=t4.fld_btid
                             left join tbl_bth t11 on t11.fld_btid=t10.fld_btrsrc and t11.fld_bttyid=2
                             left join tbl_tyval t14 on t14.fld_tyvalcd = t4.fld_btp18 and t14.fld_tyid=66
                             left join tbl_btd_advaprv t15 on t15.fld_btreffid=t11.fld_btid
                             left join tbl_bth t16 on t16.fld_btid=t15.fld_btidp and t16.fld_bttyid=8
                             where t0.fld_btid='$id'");
    return $query;
  }


  function approvalSummary ($fld_btid,$userid,$groupid,$group_add) {
    $data = $this->db->query("select t1.fld_btid from tbl_btd_advaprv t0
			      left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid where t0.fld_btidp = $fld_btid");
    $data = $data->result();
    foreach ($data as $rdata) {
      $query = $this->db->query("update tbl_bth set fld_btstat=3,fld_btdtsa =now() where fld_btid=$rdata->fld_btid");
      $query1 = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=2 , fld_userid=$userid ,fld_aprvtktmoddt = now()
                                  where fld_btid=$rdata->fld_btid and fld_aprvroleid=3 and fld_usergrpid in (ifnull('$groupid',0),ifnull('$group_add',0))");
    }
  }


  function approvalSummaryTrf ($fld_btid,$userid,$groupid,$group_add) {
    $data = $this->db->query("SELECT t0.fld_btreffid 'fld_btid' FROM tbl_btd_finance t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                              WHERE
                              t1.fld_btid = $fld_btid group by t0.fld_btreffid");
    $data = $data->result();
    foreach ($data as $rdata) {
      #query = $this->db->query("update tbl_bth set fld_btstat=3,fld_btdtsa =now() where fld_btid=$rdata->fld_btid");
      $query1 = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=2 , fld_userid=$userid ,fld_aprvtktmoddt = now()
                                  where fld_btid=$rdata->fld_btid and fld_aprvroleid=3 and fld_usergrpid in (ifnull('$groupid',0),ifnull('$group_add',0))");
    }
  }


  function insertJournalSID($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $journaldt = date('Y-m-d');
    $count = 0;

    $header = $this->db->query("select
 				t0.fld_btid,
                                t0.fld_btdesc 'fld_btdesc',
                                t0.fld_btno 'reff',
                                t0.fld_baidp,
                                t0.fld_btloc 'locid',
                                (t0.fld_btamt * -1) 'amount',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btamt,
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                t3.fld_coaid 'fld_coaid',
                                                                t4.fld_bttycd
                                from tbl_bth t0
                                left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                                                left join dnxapps.tbl_be t2 on t2.fld_beid=t0.fld_baidc
                                left join tbl_coa t3 on t3.fld_coacd=t2.fld_beacc2
                                                                left join tbl_btty t4 on t4.fld_bttyid=t0.fld_bttyid
                                where t0.fld_btid=$fld_btid");

    $detail = $this->db->query("select
 				t0.fld_btamt01 'amount',
                                t0.fld_btp02 'account',
                                t0.fld_btp01,
				t0.fld_locid 'locid',
                                t3.fld_btnoreff 'reff',
                                t3.fld_btnoalt 'bl',
                                t3.fld_btno 'inv_no'
                                from tbl_btd_invdel t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_btp02
				left join tbl_bth t3 on t3.fld_btid = t0.fld_btiid
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();

    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;
    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                      fld_journalori,fld_journalrate,fld_btdocreff,fld_empid,fld_baidp,fld_locid) values
                      ('$journalno'," . $this->db->escape($header->fld_btdesc) . ",'$header->date','$header->amount','$fld_btid','$header->fld_coaid','',
                      '$header->amount','1','$header->reff','','$header->fld_baidp','$header->locid')");
    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;

        foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,
                        fld_journalori,fld_journalrate,fld_btdocreff,fld_jo,fld_empid,fld_baidp,fld_locid) values
                        ('$journalno',concat(" . $this->db->escape($rdetail->fld_btp01) . " ,'-'," . $this->db->escape($rdetail->inv_no) . "),
                         '$header->date','$rdetail->amount','$fld_btid','$rdetail->account',
                         '$rdetail->amount','1','$rdetail->bl','$rdetail->reff','','$header->fld_baidp','$rdetail->locid')");

    }
    $this->cekBalanceJournal($fld_btid);

 	### Check Journal Item
        $cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();
        $count = $count + $headerrow + $detailrow;

        if ($cekjurnal->countjurnal > $count) {
                 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
                 exit();
        }
  }




  function insertJournalInvoice($fld_btid) {
    //check complete date and doc
    $this->insertJournalLog($fld_btid);
    $cek_complete = $this->db->query("select fld_btp32,fld_btp33 from tbl_bth where fld_btid = $fld_btid");
    $query1=$cek_complete->row();
       #if ($query1->fld_btp32 == '0000-00-00 00:00:00' || $query1->fld_btp33 == 0 || $query1->fld_btp32 == '') {
        if ($query1->fld_btp33 == 0 ) {
           $this->ffis->message("Cannot approve! Please check complete date or complete doc field!");
           exit();
       }

    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $count = 0;
    $countvat = 0;

    $header = $this->db->query("select t0.fld_btid,
  				if(t0.fld_btp10 = 1 and t0.fld_baidc = 5114,880, t2.fld_coaid) 'fld_coaid',
				t0.fld_btdesc,
                                t0.fld_baidv,
				t0.fld_btbalance,
                                t0.fld_btnoreff 'jo',
                                t0.fld_btp03,
				t0.fld_btuamt,
				t4.fld_bttycd,
				t0.fld_btloc 'locid',
                                t0.fld_btp13,
                                if(t0.fld_btp13 = 1,746,744) 'vat_coa',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
				floor(if(t0.fld_btflag = 2,t0.fld_btuamt * if(t0.fld_btp03 > 0,t0.fld_btp03,1) ,t0.fld_btuamt)) 'vat',
				if(t0.fld_btp03 > 0,t0.fld_btp03,1) 'rate',
                                if(t0.fld_btp03 > 0,concat('( ',t5.fld_tyvalnm, ' ', t0.fld_btbalance,' )'), '') 'rate_desc',
                                if(t0.fld_btp03 > 0,concat('( ',t5.fld_tyvalnm, ' ', t0.fld_btuamt,' )'), '') 'rate_desc_vat',
                                t0.fld_btno,
				concat(if(t1.fld_beprefix > 0,concat(t6.fld_tyvalnm, '. '),''), t1.fld_benm) 'fld_benm',
				if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date'
				from tbl_bth t0
				left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
				left join tbl_coa t2 on t2.fld_coacd=t1.fld_beacc2
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
				left join tbl_btty t4 on t4.fld_bttyid=t0.fld_bttyid
                                left join tbl_tyval t5 on t5.fld_tyvalcd=t0.fld_btflag and t5.fld_tyid=39
                                left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = t1.fld_beprefix and t6.fld_tyid = 173
           			where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select t0.fld_btid,t0.fld_btdesc,floor(t0.fld_btamt01 * if(t1.fld_btflag = 2,t1.fld_btp03,1)) * -1 'amount' ,t0.fld_coaid,
				if(t1.fld_btp03 > 0,concat('( ',t4.fld_tyvalnm, ' ', t0.fld_btamt01,' )'), '') 'rate_desc',t1.fld_baidv,t0.fld_btamt01,
                                t0.fld_btnoreff,t0.fld_locid 'locid'
				from tbl_btd_finance t0
				left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
				left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_tyval t4 on t4.fld_tyvalcd=t1.fld_btflag and t4.fld_tyid=39
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();
    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;

    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    $detail_amt = 0;

    #$cust = str_replace("'", "",$header->fld_benm);

    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
			fld_journalori,fld_journalrate,fld_btnoreff,fld_jo,fld_locid,fld_btreffid)
                        values ('$journalno',concat(" . $this->db->escape($header->fld_benm) . " ,' '," . $this->db->escape($rdetail->fld_btdesc) . ", ' ', '$rdetail->rate_desc'),'$header->date','$rdetail->amount','$fld_btid',
                        '$rdetail->fld_coaid','$header->fld_baidv','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$header->jo','$rdetail->locid','$rdetail->fld_btid')");
      $detail_amt = $detail_amt + $rdetail->amount;
      ### Check COA Number
      if($rdetail->fld_coaid == 0) {
        echo "<p>ERORR !!! Account Number cannot be blank ...</p>";
        $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
        $this->db->query("update tbl_bth set fld_btstat = 1 where fld_btid=$fld_btid limit 1");
        exit();
      }

    }

    ### Insert Header To Journal
    $detail_amt = abs($detail_amt);
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,fld_journalori,
                      fld_journalrate,fld_jo,fld_locid) values ('$journalno',concat('A/R',' ', " . $this->db->escape($header->fld_benm) . " ,' ',
                      'INVOICE NO.',' ', '$header->fld_btno',' ',
                      '$header->rate_desc'),'$header->date',
                      if('$header->fld_btp13' = 5,$detail_amt,$detail_amt + ifnull($header->vat,0)),'$fld_btid','$header->fld_coaid','$header->fld_baidv','$header->fld_btbalance',
                      '$header->fld_btp03','$header->jo','$header->locid')");
    ### Insert VAT
    if($header->vat > 0 && $header->fld_btp13 != 5) {
      $vat_amt = $header->vat * -1;
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
			fld_journalori,fld_journalrate,fld_jo,fld_locid)
                        values
                        ('$journalno',concat('VAT',' ', " . $this->db->escape($header->fld_benm) . ",' ','INVOICE NO.',' ', '$header->fld_btno',' ',
                          '$header->rate_desc_vat'),
                         '$header->date','$vat_amt',
                        '$fld_btid','$header->vat_coa','$header->fld_baidv','$header->fld_btuamt','$header->fld_btp03','$header->jo','$header->locid')");
    }
    $date = date('Y-m-d H-i-s');
    $this->db->query("update tbl_bth set fld_btp32 = '$date' where fld_btid = $fld_btid and fld_bttyid in (41,44) limit 1");
    $this->cekBalanceJournal($fld_btid);
     ### Check Journal Item
        $cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();
        if($header->vat > 0) {
                $countvat = 1;
        }
        else{
                $countvat = 0;
        }
        $count = $count + $headerrow + $detailrow + $countvat;
        if ($cekjurnal->countjurnal > $count) {
                 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
                 exit();
        }

  }

  function insertJournalInvoiceRev($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $count = 0;
    $countvat = 0;

    $header = $this->db->query("select t0.fld_btid,
  				if(t0.fld_btp10 = 1 and t0.fld_baidc = 5114,880, t2.fld_coaid) 'fld_coaid',
				t0.fld_btdesc,
                                t0.fld_baidv,
				t0.fld_btbalance,
                                t0.fld_btp03,
				t0.fld_btuamt,
                                t0.fld_btnoreff 'jo',
				t4.fld_bttycd,t0.fld_btloc 'locid',
                                if(t0.fld_btp13 = 1,746,744) 'vat_coa',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
				floor(if(t0.fld_btflag = 2,t0.fld_btuamt * if(t0.fld_btp03 > 0,t0.fld_btp03,1) ,t0.fld_btuamt)) 'vat',
				if(t0.fld_btp03 > 0,t0.fld_btp03,1) 'rate',
                                if(t0.fld_btp03 > 0,concat('( ',t5.fld_tyvalnm, ' ', t0.fld_btbalance,' )'), '') 'rate_desc',
                                if(t0.fld_btp03 > 0,concat('( ',t5.fld_tyvalnm, ' ', t0.fld_btuamt,' )'), '') 'rate_desc_vat',
                                t0.fld_btno,
				concat(if(t1.fld_beprefix > 0,concat(t7.fld_tyvalnm, '. '),''), t1.fld_benm) 'fld_benm',
				if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                t6.fld_btrsrc, t0.fld_btp13
				from tbl_bth t0
				left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
				left join tbl_coa t2 on t2.fld_coacd=t1.fld_beacc2
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
				left join tbl_btty t4 on t4.fld_bttyid=t0.fld_bttyid
                                left join tbl_tyval t5 on t5.fld_tyvalcd=t0.fld_btflag and t5.fld_tyid=39
                                left join tbl_btr t6 on t6.fld_btrdst = t0.fld_btid
                                left join dnxapps.tbl_tyval t7 on t7.fld_tyvalcd = t1.fld_beprefix and t7.fld_tyid = 173
           			where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select t0.fld_btid,t0.fld_btdesc,floor(t0.fld_btamt01 * if(t1.fld_btflag = 2,t1.fld_btp03,1)) * -1 'amount' ,t0.fld_coaid,t0.fld_locid 'locid',
				if(t1.fld_btp03 > 0,concat('( ',t4.fld_tyvalnm, ' ', t0.fld_btamt01,' )'), '') 'rate_desc',t1.fld_baidv,t0.fld_btamt01,
                                t0.fld_btnoreff
				from tbl_btd_finance t0
				left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
				left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_tyval t4 on t4.fld_tyvalcd=t1.fld_btflag and t4.fld_tyid=39
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();
    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno2 = $journalno + 1;
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;
    $journalno2 = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno2;

    #GET JO INVOICE ORI
    $headerinvori = $this->db->query("SELECT fld_btrsrc 'idhdrori' FROM tbl_btr WHERE fld_btrdst = $fld_btid AND fld_btrdsttyid = 67 limit 1 ");
    $headerinvori = $headerinvori->row();

    $joinvori =  $this->db->query("SELECT fld_btnoreff 'jo_ori' FROM tbl_bth
                                   WHERE fld_btid = $headerinvori->idhdrori  limit 1 ");
    $joinvori = $joinvori->row();


    ### Buat Pembalik Invoice lama
    $this->db->query("insert into tbl_journal (fld_journalno, fld_journalflag, fld_journaldt, fld_bedivid, fld_vehicleid, fld_empid, fld_btiid, fld_journaldesc, fld_journalori, fld_journalrate, fld_journalamt,
                      fld_journalcmt, fld_btid, fld_currency, fld_coaid, fld_journalp01, fld_lup,fld_locid,fld_jo)
                      select
                      '$journalno',
                      fld_journalflag,'$header->date', fld_bedivid, fld_vehicleid, fld_empid, fld_btiid, fld_journaldesc,
                      if(fld_journalori > 0,fld_journalori * -1, abs(fld_journalori)),
                      fld_journalrate,
                      if(fld_journalamt > 0,fld_journalamt * -1, abs(fld_journalamt)),
                      fld_journalcmt, $fld_btid, fld_currency, fld_coaid, 1, fld_lup,'$header->locid','$joinvori->jo_ori'
                      from
                      tbl_journal
                      where
                      fld_btid = $header->fld_btrsrc
                      and fld_journalp01 != 1");

    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    $detail_amt = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
			fld_journalori,fld_journalrate,fld_jo,fld_locid,fld_btreffid)
                        values ('$journalno',concat('$header->fld_benm' , ' ','$rdetail->fld_btdesc', ' ', '$rdetail->rate_desc'),'$header->date','$rdetail->amount','$fld_btid',
                        '$rdetail->fld_coaid','$header->fld_baidv','$rdetail->fld_btamt01','$header->fld_btp03','$header->jo','$rdetail->locid','$rdetail->fld_btreffid')");
      $detail_amt = $detail_amt + $rdetail->amount;
      ### Check COA Number
      if($rdetail->fld_coaid == 0) {
        echo "<p>ERROR !!! Account Number cannot be blank ...</p>";
        $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
        $this->db->query("update tbl_bth set fld_btstat = 1 where fld_btid=$fld_btid limit 1");
        exit();
      }

    }

    ### Insert Header To Journal
    $detail_amt = abs($detail_amt);
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,fld_journalori,
                      fld_journalrate,fld_jo,fld_locid) values ('$journalno','A/R $header->fld_benm INVOICE NO. $header->fld_btno  $header->rate_desc','$header->date',
                      if($header->fld_btp13 = 5,$detail_amt,$detail_amt + ifnull($header->vat,0)),'$fld_btid','$header->fld_coaid','$header->fld_baidv','$header->fld_btp04','$header->fld_btp03','$header->jo','$header->locid')");
    ### Insert VAT
    if($header->vat > 0  && $header->fld_btp13 != 5) {
      $vat_amt = $header->vat * -1;
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
			fld_journalori,fld_journalrate,fld_jo,fld_locid)
                        values
                        ('$journalno','VAT $header->fld_benm INVOICE NO. $header->fld_btno $header->rate_desc_vat','$header->date','$vat_amt',
                        '$fld_btid','$header->vat_coa','$header->fld_baidv','$header->fld_btuamt','$header->fld_btp03','$header->jo','$header->locid')");
    }
    $this->db->query("update tbl_bth set fld_btp27 = 1 where fld_btid = $header->fld_btrsrc limit 1");


    $this->cekBalanceJournal($fld_btid);

    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();

	if($header->vat > 0) {
		$countvat = 2;
	}
	else{
		$countvat = 0;
	}
	$count = $count + ($headerrow*2) + ($detailrow*2) + $countvat;

	//if ($cekjurnal->countjurnal > $count) {
	//	 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 //echo $count;
        //         exit();
	//}
  }

  function insertJournalCash($fld_btid,$fld_company) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $journaldt = date('Y-m-d');
    $count = 0;

    $header = $this->db->query("select t0.fld_btid,
                                t0.fld_btiid,
                                if(t0.fld_btdesc !='',t0.fld_btdesc,if(t0.fld_btflag=2,concat(substr(t3.fld_benm,4,8),'-',t2.fld_coanm,' $',t0.fld_btamt),if(t0.fld_baidc=0,t2.fld_coanm,concat(substr(t3.fld_benm,4,8),'-',t2.fld_coanm)))) 'fld_btdesc',
                                t0.fld_btp03,
                                if(t0.fld_btnoalt='','--',t0.fld_btnoalt) 'reff',
                                t0.fld_btp01,
                                t0.fld_baidp,
                                ((t0.fld_btamt * if(t0.fld_btp03 > 0,t0.fld_btp03,1)) * if(t0.fld_bttyid = 42,1,-1)) 'amount',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btamt,
                                #if(t0.fld_btdtsa > '0000-00-00',date_format(t0.fld_btdt,'%Y-%m-%d'),'$journaldt')'date' ,
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                t1.fld_bttycd,t0.fld_btloc 'locid'
                                from tbl_bth t0
                                left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_btiid and t2.fld_coagrp in(1,2)
                                left join dnxapps.tbl_be t3 on t3.fld_beid = t0.fld_baidc
                                where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select if(t1.fld_btp03 > 0,concat(t0.fld_btdesc,' $',t0.fld_btamt01),t0.fld_btdesc) 'fld_btdesc',
                                ((t0.fld_btamt01 * if(t1.fld_btp03 > 0,t1.fld_btp03,1)) * if(t1.fld_bttyid = 42,-1,1)) 'amount' ,
                                t0.fld_coaid,
                                t0.fld_btp03,
                                t0.fld_btamt01,
                                t0.fld_btnoreff,
                                t0.fld_btdocreff,
                                t0.fld_empid,
                                t0.fld_bedivid,
                                t0.fld_btp01,
                                t0.fld_btp04,t0.fld_btp11,t0.fld_locid 'locid'
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();

    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;


    if($fld_company !=1) {
    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                      fld_journalori,fld_journalrate,fld_btdocreff,fld_empid,fld_baidp,fld_locid) values
                      ('$journalno'," . $this->db->escape($header->fld_btdesc) . ",'$header->date','$header->amount','$fld_btid','$header->fld_btiid','',
                      '$header->fld_btamt','$header->fld_btp03','$header->reff','$header->fld_btp01','$header->fld_baidp','$header->locid')");
    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btnoreff,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_sell_price,fld_journalp02,fld_flag,fld_locid) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date','$rdetail->amount','$fld_btid','$rdetail->fld_coaid',
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$rdetail->fld_btdocreff',
                        '$rdetail->fld_empid','$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btp01','$rdetail->fld_btp04','$rdetail->fld_btp11','$rdetail->locid')");

    }
    }

    else {
    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btnoreff,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_sell_price,fld_journalp02,fld_flag,
                        fld_locid) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date','$rdetail->amount','$fld_btid','$rdetail->fld_coaid',
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$rdetail->fld_btdocreff',
                        '$rdetail->fld_empid','$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btp01','$rdetail->fld_btp04','$rdetail->fld_btp11','$rdetail->locid')");

       $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btnoreff,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_sell_price,fld_journalp02,fld_flag,fld_locid) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date',$rdetail->amount * -1,'$fld_btid',1313,
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$rdetail->fld_btdocreff',
                        '$rdetail->fld_empid','$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btp01','$rdetail->fld_btp04','$rdetail->fld_btp11','$rdetail->locid')");


        }
    }

    $this->cekBalanceJournal($fld_btid);
    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();
	$count = $count + $headerrow + $detailrow;

	if ($cekjurnal->countjurnal > $count && $fld_company !=1) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}
  }
  function insertJournalBank($fld_btid,$act) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $journaldt = date('Y-m-d');
    $count = 0;

    $header = $this->db->query("select t0.fld_btid,
                                t0.fld_btiid,
                                if(t0.fld_btdesc !='',t0.fld_btdesc,if(t0.fld_btflag=2,concat(substr(t3.fld_benm,4,8),'-',t2.fld_coanm,' $',t0.fld_btamt),
								if(t0.fld_baidc=0,t2.fld_coanm,concat(substr(t3.fld_benm,4,8),'-',t2.fld_coanm)))) 'fld_btdesc',
                                t0.fld_btp03,
                                if(t0.fld_btnoalt='','--',t0.fld_btnoalt) 'reff',
                                t0.fld_btp01,
                                t0.fld_baidp,
                                ((t0.fld_btamt * if(t0.fld_btp03 > 0,t0.fld_btp03,1)) * if(t0.fld_bttyid = 94,1,-1)) 'amount',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btamt,
                                #if(t0.fld_btdtsa > '0000-00-00',date_format(t0.fld_btdt,'%Y-%m-%d'),'$journaldt')'date' ,
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
				t0.fld_btloc 'locid',
                                t1.fld_bttycd
                                from tbl_bth t0
                                left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_btiid and t2.fld_coagrp in(2)
                                left join dnxapps.tbl_be t3 on t3.fld_beid = t0.fld_baidc
                                where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select if(t1.fld_btp03 > 0,concat(t0.fld_btdesc,' $',t0.fld_btamt01),t0.fld_btdesc) 'fld_btdesc',
                                ((t0.fld_btamt01 * if(t1.fld_btp03 > 0,t1.fld_btp03,1)) * if(t1.fld_bttyid = 94,-1,1)) 'amount' ,
                                t0.fld_coaid,
                                t0.fld_btp03,
                                t0.fld_btamt01,
                                t0.fld_btnoreff,
                                t0.fld_btdocreff,
                                t0.fld_empid,
                                t0.fld_bedivid,
                                t0.fld_btp01,
                                t0.fld_locid 'locid',
                                t0.fld_btp04
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();

    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;
    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                      fld_journalori,fld_journalrate,fld_btdocreff,fld_empid,fld_baidp,fld_locid) values
                      ('$journalno'," . $this->db->escape($header->fld_btdesc) . ",'$header->date','$header->amount','$fld_btid','$header->fld_btiid','',
                      '$header->fld_btamt','$header->fld_btp03','$header->reff','$header->fld_btp01','$header->fld_baidp','$header->locid')");
    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btnoreff,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_sell_price,fld_journalp02,fld_locid) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date','$rdetail->amount','$fld_btid','$rdetail->fld_coaid',
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$rdetail->fld_btdocreff',
                        '$rdetail->fld_empid','$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btp01','$rdetail->fld_btp04','$rdetail->locid')");

    }
    $this->db->query("update tbl_btd_finance t0
		left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid
		set t1.fld_btstat = 3
		WHERE t0.fld_btidp ='$fld_btid' and t1.fld_bttyid = 4 and t1.fld_btstat =6");

    $this->cekBalanceJournal($fld_btid);
    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();
	$count = $count + $headerrow + $detailrow;

	if ($cekjurnal->countjurnal > $count) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}
  }
  function insertGeneralJournal($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $journaldt = date('Y-m-d');
    $count = 0;

    $header = $this->db->query("select t0.fld_btid,
                                t0.fld_btiid,
                             #   if(t0.fld_btflag=2,concat(t2.fld_coanm,' $',t0.fld_btamt),t2.fld_coanm) 'fld_btdesc',
                                t0.fld_btp03,
                                if(t0.fld_btnoalt='','--',t0.fld_btnoalt) 'reff',
                                t0.fld_btp01,
                                t0.fld_baidp,
                                ((t0.fld_btamt * if(t0.fld_btp03 > 0,t0.fld_btp03,1)) * if(t0.fld_bttyid = 46,1,-1)) 'amount',
                                date_format(t0.fld_btdtp,'%y%m') 'table',t0.fld_btamt,
                                #if(t0.fld_btdtsa > '0000-00-00',date_format(t0.fld_btdt,'%Y-%m-%d'),'$journaldt')'date' ,
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                t0.fld_btloc 'locid',
                                t1.fld_bttycd
                                from tbl_bth t0
                                left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_btiid and t2.fld_coagrp in(1,2)
                                where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select
                                t0.fld_btid,
                                if(t1.fld_bttyid =51,t0.fld_btdesc,if(t1.fld_btp03 > 0,concat(t0.fld_btdesc,' $',t0.fld_btamt01),t0.fld_btdesc)) 'fld_btdesc',
                                ((t0.fld_btamt01 * if(t1.fld_btp01 > 0,t1.fld_btp01,1)) * if(t1.fld_bttyid = 46,-1,1)) 'amount' ,
                                t0.fld_coaid,
                                t0.fld_btp03,
                                t0.fld_btamt01,
                                t0.fld_btnoreff,
                                t0.fld_btdocreff,
                                t0.fld_empid,
			        t0.fld_locid 'locid',
                                t0.fld_bedivid,
                                t0.fld_btnodoc
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                where t0.fld_btidp=$fld_btid");
    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();

    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;
    ### Insert Header To Journal
    #$this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
    #                  fld_journalori,fld_journalrate,fld_btdocreff,fld_empid,fld_baidp,fld_locid) values
    #                  ('$journalno'," . $this->db->escape($header->fld_btdesc) . ",'$header->date','$header->amount','$fld_btid','$header->fld_btiid','',
    #                  '$header->fld_btamt','$header->fld_btp03','$header->reff','$header->fld_btp01','$header->fld_baidp','$header->locid')");
    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    foreach($detail->result() as $rdetail) {

      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_btnoreff,fld_locid,fld_btreffid,fld_btnodoc) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date','$rdetail->amount','$fld_btid',
                        '$rdetail->fld_coaid',
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp01','$rdetail->fld_btdocreff','$rdetail->fld_empid',
                        '$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btnoreff','$rdetail->locid','$rdetail->fld_btid',
                        '$rdetail->fld_btnodoc')");

    }
    $this->cekBalanceJournal($fld_btid);

    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();

	$count = $count + $detailrow;

	if ($cekjurnal->countjurnal > $count) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}

  }

  function insertJournalAP($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $count = 0;
    $countvat = 0;
    $countPPH23 = 0;
    $countPPH4 = 0;
    $countPPH21 = 0;
    $countPPH25 = 0;
    $header = $this->db->query("select t0.fld_btid,
				t0.fld_btno,
                                t0.fld_btnoalt,
                                t0.fld_btiid,
                                t0.fld_btdesc,
                                t0.fld_baidp 'posted',
                                t2.fld_benm 'Supplier',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btflag 'curr',
                                if(t0.fld_btflag=1,'',concat(' - ',t3.fld_tyvalnm,' ',t0.fld_btamt)) 'cr',
                                if(t0.fld_btflag=1,'',concat(' - ',t3.fld_tyvalnm,' ',t0.fld_btuamt)) 'cr_vat',
                                t0.fld_btp07 'rate',
                                if(t0.fld_btflag=1,t0.fld_btamt,t0.fld_btp08) * -1  'amount',
                                if(t0.fld_btflag=1,0,t0.fld_btamt) * -1 'amount_ori',
                                ifnull(t0.fld_btp02,0) * -1  'PPH23',
				ifnull(t0.fld_btp04,0) * -1  'PPH4',
				ifnull(t0.fld_btp06,0) * -1  'PPH21',
                                ifnull(t0.fld_btp10,0) * -1  'PPH25',
                                if(t0.fld_btflag=1,t0.fld_btuamt,floor(t0.fld_btuamt * t0.fld_btp07)) 'vat',
                                ifnull(t0.fld_btuamt,0) 'vat_ori',
                                t0.fld_btidp 'vat_coa',
                                t0.fld_btp11 'acc_credit',
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                #if(t0.fld_btdtp > '0000-00-00',date_format(t0.fld_btdt,'%Y-%m-%d'),'$journaldt')'date' ,
                                t1.fld_bttycd,t0.fld_btloc 'locid'
                                from tbl_bth t0
				left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join dnxapps.tbl_be t2 on t2.fld_beid=t0.fld_baidv
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t0.fld_btflag and t3.fld_tyid=39
                                where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select t0.fld_btdesc,
                                if(t1.fld_btflag=1,t0.fld_btamt01,t0.fld_btuamt01) 'amount' ,
                                if(t1.fld_btflag=1,0,t0.fld_btamt01) 'amount_ori' ,
                                if(t1.fld_btflag=1,'',concat(' - ',t4.fld_tyvalnm,' ',t0.fld_btamt01)) 'cr',
                                concat(t0.fld_btcmt,' - ',t0.fld_btnodoc) 'doc',
                                t0.fld_coaid,
                                t0.fld_btiid,t0.fld_locid'locid'
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_tyval t4 on t4.fld_tyvalcd=t1.fld_btflag and t4.fld_tyid=39
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();
    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;

    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_baidp,fld_btdocreff,fld_locid)
    values ('$journalno',concat('$header->fld_btdesc','$header->cr'),'$header->date',$header->amount,$header->amount_ori,'$fld_btid',$header->acc_credit,$header->posted,'$header->fld_btnoalt','$header->locid')");

    ### PPH23
    if($header->PPH23 != 0) {
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_baidp,fld_locid)
    values ('$journalno',concat('PPH23 ',  '$header->Supplier ' ,'$header->fld_btno'),'$header->date','$header->PPH23','$fld_btid',742,$header->posted,$header->locid)");
    }
    ### PPH4
    if($header->PPH4 != 0) {
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_baidp,fld_locid)
    values ('$journalno',concat('PPH4(2) ','$header->Supplier ' ,'$header->fld_btno'),'$header->date','$header->PPH4','$fld_btid',742,$header->posted,$header->locid)");
    }
    ### PPH21
    if($header->PPH21 != 0) {
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_baidp,fld_locid)
    values ('$journalno',concat('PPH 21 ','$header->Supplier ' , '$header->fld_btno'),'$header->date','$header->PPH21','$fld_btid',740,$header->posted,$header->locid)");
    }
    ### PPH25
    if($header->PPH25 != 0) {
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_baidp,fld_locid)
    values ('$journalno',concat('PPH 25 ','$header->Supplier ' , '$header->fld_btno'),'$header->date','$header->PPH25','$fld_btid',743,$header->posted,$header->locid)");
    }
    ### VAT IN
    if($header->vat != 0) {
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_baidp,fld_locid)
    values ('$journalno',concat('VAT IN ','$header->Supplier ' ,'$header->fld_btno','$header->cr_vat'),'$header->date',ifnull($header->vat,0) ,ifnull($header->vat_ori,0),'$fld_btid',$header->vat_coa,$header->posted,$header->locid)");
    }

    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_vehicleid,fld_baidp,fld_btdocreff,fld_locid) values ('$journalno',concat('$rdetail->fld_btdesc','$rdetail->cr'),'$header->date','$rdetail->amount','$rdetail->amount_ori','$fld_btid','$rdetail->fld_coaid','$rdetail->fld_btiid',$header->posted,'$rdetail->doc','$rdetail->locid')");

    }
    $this->cekBalanceJournal($fld_btid);

    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();

	if($header->vat > 0) {
		$countvat = 1;
	}
	else{
		$countvat = 0;
	}

	if($header->PPH23 != 0) {
		$countPPH23 = 1;
	}
	else{
		$countPPH23 = 0;
	}

	if($header->PPH4 != 0) {
		$countPPH4 = 1;
	}
	else{
		$countPPH4 = 0;
	}

	if($header->PPH21 != 0) {
		$countPPH21 = 1;
	}
	else{
		$countPPH21 = 0;
	}

	if($header->PPH25 != 0) {
		$countPPH25 = 1;
	}
	else{
		$countPPH25 = 0;
	}

	$count = $count + $headerrow + $detailrow + $countvat + $countPPH23 + $countPPH4 + $countPPH21 + $countPPH25;

	if ($cekjurnal->countjurnal > $count) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}

  }


  function insertJournalCRT($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $count = 0;
    $header = $this->db->query("select t0.fld_btid,
				t0.fld_btno,
                                t0.fld_btnoalt,
                                t0.fld_btiid,
                                t0.fld_btdesc,
                                t0.fld_baidp 'posted',
                                t2.fld_benm 'shipping',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btflag 'curr',
                                if(t0.fld_btflag=1,'',concat(' - ',t3.fld_tyvalnm,' ',t0.fld_btamt)) 'cr',
                                #if(t0.fld_btflag=1,'',concat(' - ',t3.fld_tyvalnm,' ',t0.fld_btuamt)) 'cr_vat',
                                t0.fld_btp07 'rate',
                                if(t0.fld_btflag=1,t0.fld_btamt,t0.fld_btp08)* -1  'amount',
                                if(t0.fld_btflag=1,0,t0.fld_btamt)* -1 'amount_ori',
                                #if(t0.fld_btflag=1,t0.fld_btuamt,floor(t0.fld_btuamt * t0.fld_btp07)) 'vat',
                                #ifnull(t0.fld_btuamt,0) 'vat_ori',
                                #t0.fld_btidp 'vat_coa',
                                t0.fld_btp11 'acc_credit',
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                #if(t0.fld_btdtp > '0000-00-00',date_format(t0.fld_btdt,'%Y-%m-%d'),'$journaldt')'date' ,
                                t1.fld_bttycd,t0.fld_btloc 'locid'
                                from tbl_bth t0
			        left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join tbl_be t2 on t2.fld_beid=t0.fld_baidv and t2.fld_betyid=8
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t0.fld_btflag and t3.fld_tyid=39
                                where t0.fld_btid=$fld_btid");

    $detail = $this->db->query("select t0.fld_btdesc,
                                round(if(t1.fld_btflag=1,t0.fld_btamt01,t0.fld_btuamt01)) 'amount' ,
                                if(t1.fld_btflag=1,0,t0.fld_btamt01) 'amount_ori' ,
                                if(t1.fld_btflag=1,'',concat(' - ',t4.fld_tyvalnm,' ',t0.fld_btamt01)) 'cr',
                                t0.fld_btcmt 'doc',
                                t0.fld_btnodoc,
                                t0.fld_btnoreff,
                                t0.fld_coaid,
                                t0.fld_btiid,
				t0.fld_locid 'locid'
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_tyval t4 on t4.fld_tyvalcd=t1.fld_btflag and t4.fld_tyid=39
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();
    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;

    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_baidp,fld_btdocreff,fld_locid)
    values ('$journalno',concat('$header->fld_btdesc','$header->cr'),'$header->date',$header->amount,$header->amount_ori,'$fld_btid',$header->acc_credit,$header->posted,'$header->fld_btnoalt','$header->locid')");

    $total_amount = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_baidp,fld_btdocreff,fld_btnoreff,fld_jo,fld_locid) values ('$journalno',concat('$rdetail->fld_btdesc','$rdetail->cr'),'$header->date','$rdetail->amount','$rdetail->amount_ori','$fld_btid','$rdetail->fld_coaid',$header->posted,'$rdetail->fld_btnodoc','$rdetail->fld_btnoreff','$rdetail->fld_btnoreff','$rdetail->locid')");

    }
    $this->cekBalanceJournal($fld_btid);

    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();

	$count = $count + $headerrow + $detailrow ;
	if ($cekjurnal->countjurnal > $count) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}
  }

  function insertJournalPayment($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");

    $header = $this->db->query("select t0.fld_btid,
                                #t2.fld_coaid 'coaid1',
                                if(t0.fld_btp07 = 1 and t0.fld_baidc = 5114,880, t2.fld_coaid) 'coaid1',
                                t4.fld_coaid 'coaid2',
                                if(t0.fld_btflag!=1,concat(t7.fld_tyvalnm,' ',t0.fld_btamt),'') 'ori_amt1',
				if(t0.fld_btflag!=1,concat(t7.fld_tyvalnm,' ',t0.fld_btuamt),'') 'ori_amt2',
                                if(t0.fld_btflag!=1,concat(t7.fld_tyvalnm,' ',t0.fld_btp01),'') 'ori_amt3',
                                if(t0.fld_btflag!=1,concat(t7.fld_tyvalnm,' ',t0.fld_btp02),'') 'ori_amt4',
                                (if(t0.fld_btflag=1,t0.fld_btamt,t0.fld_btamt) * -1) 'amount1',
                                if(t0.fld_btflag=1,t0.fld_btuamt,t0.fld_btuamt) 'amount2',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btp01 'pph23',
                                t0.fld_btp02 'pphfinal',
                                date_format(t0.fld_btdt,'%Y-%m-%d')'date',
				t6.fld_bttycd,
				t1.fld_benm,
				t0.fld_btno,
				t0.fld_btloc 'locid'
                                from tbl_bth t0
                                left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
                                left join tbl_coa t2 on t2.fld_coacd=t1.fld_beacc2
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_coa t4 on t4.fld_coaid=t0.fld_btiid
                                left join tbl_tyval t5 on t5.fld_tyvalcd=t4.fld_coaty and t5.fld_tyid = 31
				left join tbl_btty t6 on t6.fld_bttyid=t0.fld_bttyid
                                left join tbl_tyval t7 on t7.fld_tyvalcd=t0.fld_btflag and t7.fld_tyid = 39
                                where t0.fld_btid=$fld_btid");
    $detail2 = $this->db->query("select if(t1.fld_btflag!=1,concat(t4.fld_tyvalnm,' $',t0.fld_btamt01),
                                t0.fld_btdesc) 'fld_btdesc',t0.fld_btamt01,t0.fld_coaid,
				t0.fld_btp01,t0.fld_btp02,if(t1.fld_btflag = 1,1,t0.fld_btqty02)'rate',t0.fld_locid 'locid'
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_tyval t4 on t4.fld_tyvalcd=t1.fld_btflag and t4.fld_tyid = 39
                                where t0.fld_btidp=$fld_btid");


    $header = $header->row();

    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;

    $amount = 0;
    $pph_23 = 0;
    $pph_final = 0;
    $x = 0;
    $tot_rate = 0;
    $payment_add = 0;

    foreach($detail2->result() as $rdetail2) {
      $x = $x + 1;
      $tot_rate = $tot_rate + $rdetail2->rate;
      $amount = $amount + floor($rdetail2->fld_btamt01 * $rdetail2->rate);
      $pph_23 = $pph_23 + floor($rdetail2->fld_btp01 * $rdetail2->rate);
      $pph_final = $pph_final + floor($rdetail2->fld_btp02 * $rdetail2->rate);
    }
    $rate_avg = $tot_rate / $x;

    $detail = $this->db->query("select  if(t1.fld_btflag=2,concat(' $',t0.fld_btamt01),'') 'ori_amt',t0.fld_btdesc,t0.fld_coaid,
                                floor(t0.fld_btamt01 * if(t1.fld_btflag=1,1,$rate_avg)) 'amount',t0.fld_btamt01
                                from tbl_btd_addpayment t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                where t0.fld_btidp=$fld_btid");

    foreach($detail->result() as $rdetail) {
      $payment_add = $payment_add + $rdetail->amount;
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_journalori,fld_journalrate,fld_locid)
      values ('$journalno','$rdetail->fld_btdesc  $header->fld_benm  $header->fld_btno $rdetail->ori_amt','$header->date','$rdetail->amount','$fld_btid',
      '$rdetail->fld_coaid','$rdetail->fld_btamt01','$rate_avg','$rdetail->locid')");
    }

    $bank_receive = $amount - $pph_23 - $pph_final - $payment_add;
    $amount2 = $amount * -1;

    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_journalori,fld_journalrate,fld_locid)
		      values ('$journalno','PAYMENT INVOICE $header->fld_benm  $header->fld_btno $header->ori_amt1','$header->date','$amount2','$fld_btid',
		      '$header->coaid1','$header->amount1','$rdetail2->fld_btqty02','$header->locid')");

    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_journalori,fld_journalrate,fld_locid)
                      values ('$journalno','PAYMENT INVOICE $header->fld_benm  $header->fld_btno $header->ori_amt2','$header->date','$bank_receive','$fld_btid',
                      '$header->coaid2','$header->amount2','$rdetail2->fld_btqty02','$header->locid')");
    ### insert PPH 23
    if($header->pph23 > 0) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_journalori,fld_journalrate,fld_locid)
                        values
                        ('$journalno','PPH 23 $header->fld_benm  $header->fld_btno $header->ori_amt3','$header->date','$pph_23','$fld_btid','693',
                        '$header->pph23',
                        '$rdetail2->fld_btqty02','$header->locid')");
    }
    ### insert PPH Final
    if($header->pphfinal > 0) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_journalori,fld_journalrate,fld_locid)
                        values
		        ('$journalno','PPH FINAL $header->fld_benm  $header->fld_btno $header->ori_amt4','$header->date','$pph_final','$fld_btid','1140',
                        '$header->pphfinal',
                        '$rdetail2->fld_btqty02','$header->locid')");
    }

    $this->cekBalanceJournal($fld_btid);

  }

  function insertJournalPaymentAP($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
    $date_trans = date("ym");
    $year_trans = date("y");
    $count = 0;

    $header = $this->db->query("select t0.fld_btid,
				t0.fld_btno,
                                t0.fld_btiid,
                                t0.fld_btdesc,
                                round(t0.fld_btbalance)* -1 'amount',
                                round(t0.fld_btbalance),
                                t0.fld_baidp 'posted',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                if(t0.fld_btflag=1,'',concat(' - ',t2.fld_tyvalnm,' ',round(t0.fld_btbalance,2))) 'cr',
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                t1.fld_bttycd,
                                t0.fld_btflag,
				t0.fld_btloc 'locid'
                                from tbl_bth t0
				left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid=39
                                where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select t0.fld_btdesc,if(t1.fld_btflag=1,t0.fld_btamt01,if(t4.fld_bttyid=51,round(t4.fld_btamt*t4.fld_btp01),round(t4.fld_btp08))) 'amount',
                                if(t1.fld_btflag=1,'',concat(' - ',t5.fld_tyvalnm,' ',t0.fld_btamt01)) 'cr',
                                t0.fld_btamt01,
                                t0.fld_btnodoc,
                                t0.fld_coaid,
				t0.fld_locid 'locid'
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join tbl_bth t4 on t4.fld_btid=t0.fld_btreffid
                                left join tbl_tyval t5 on t5.fld_tyvalcd=t1.fld_btflag and t5.fld_tyid=39
                                where t0.fld_btidp=$fld_btid");

    $detail2 = $this->db->query("select t0.fld_btdesc,t0.fld_btamt01 'amount' ,t0.fld_coaid,t0.fld_locid 'locid'
                                from tbl_btd_addpayment t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $detailrow2 = $detail2->num_rows();

    $header = $header->row();
    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;

    foreach($detail->result() as $rdetail) {
      $tic = $tic + $rdetail->amount * -1;
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_baidp,fld_btdocreff,fld_locid)
      values ('$journalno',concat('$rdetail->fld_btdesc','$rdetail->cr'), '$header->date','$rdetail->amount','$rdetail->fld_btamt01','$fld_btid','$rdetail->fld_coaid',$header->posted,'$rdetail->fld_btnodoc','$rdetail->locid')");
    }

     foreach($detail2->result() as $rdetail2) {

      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_baidp,fld_locid) values ('$journalno','$rdetail2->fld_btdesc','$header->date','$rdetail2->amount','$fld_btid','$rdetail2->fld_coaid',$header->posted,'$rdetail->locid')");

    }
    if($header->fld_btflag == 1) {
      $amount = $header->amount;
    } else {
      $amount = $tic;
    }
    ### Insert Header To Journal
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_journalori,fld_btid,fld_coaid,fld_baidp,fld_locid)
    values ('$journalno',concat('$header->fld_btdesc','$header->cr'),'$header->date',$amount,'$header->fld_btbalance','$fld_btid',$header->fld_btiid,$header->posted,'$rdetail->locid')");

   $this->cekBalanceJournal($fld_btid);
   ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();

	$count = $count + $headerrow + $detailrow + $$detailrow2;

	if ($cekjurnal->countjurnal > $count) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}

  }

  function insertJournalDeposit($fld_btid) {
    $this->insertJournalLog($fld_btid);
    $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");

    $date_trans = date("ym");
    $year_trans = date("y");
    $journaldt = date('Y-m-d');
    $count = 0;

    $header = $this->db->query("select t0.fld_btid,
                                t0.fld_btiid,
                                if(t0.fld_btdesc !='',t0.fld_btdesc,
                                if(t0.fld_btflag=2,concat(t2.fld_coanm,' $',t0.fld_btamt),
                                t2.fld_coanm)) 'fld_btdesc',
                                t0.fld_btp03,
                                t0.fld_btp04 'refund',
                                t0.fld_btp06 'flag_journal',
                                if(t0.fld_btnoalt='','--',t0.fld_btnoalt) 'reff',
                                t0.fld_btp01,
                                t0.fld_baidp,
                                ((t0.fld_btamt * if(t0.fld_btp03 > 0,t0.fld_btp03,1)) * 1) 'amount',
                                date_format(t0.fld_btdtp,'%y%m') 'table',
                                t0.fld_btamt,
                                #if(t0.fld_btdtsa > '0000-00-00',date_format(t0.fld_btdt,'%Y-%m-%d'),'$journaldt')'date' ,
                                if(date_format(t0.fld_btdtp,'%Y-%m-%d') > '0000-00-00', date_format(t0.fld_btdtp,'%Y-%m-%d'),
                                date_format(t0.fld_btdt,'%Y-%m-%d')) 'date',
                                t1.fld_bttycd,
                                t0.fld_btp05 'jo',t0.fld_btloc 'locid',
                                (select if(length(tx0.fld_btp08)>0,tx0.fld_btp08,tx0.fld_btp07) from tbl_bth tx0 where tx0.fld_btno = t0.fld_btp05) 'bl'
                                from tbl_bth t0
                                left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_btiid and t2.fld_coagrp in(1,2)
                                left join dnxapps.tbl_be t3 on t3.fld_beid = t0.fld_baidc
                                where t0.fld_btid=$fld_btid");
    $detail = $this->db->query("select
                                #concat(substr(t4.fld_benm,4,8),'-',t0.fld_btdesc) 'fld_btdesc',
                                t0.fld_btdesc 'fld_btdesc',
                                (t0.fld_btamt01 * -1) 'amount' ,
                                t0.fld_btamt01 'amount2',
                                t0.fld_coaid,
                                t0.fld_btp03,
                                t0.fld_btamt01,
                                t0.fld_btnoreff,
                                t0.fld_btdocreff,
                                t0.fld_empid,
                                t0.fld_bedivid,
                                t0.fld_btp01,t0.fld_locid 'locid'
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                                left join tbl_coa t2 on t2.fld_coaid=t0.fld_coaid
                                left join tbl_tyval t3 on t3.fld_tyvalcd=t2.fld_coaty and t3.fld_tyid = 31
                                left join dnxapps.tbl_be t4 on t4.fld_beid = t1.fld_baidc
                                where t0.fld_btidp=$fld_btid");

    $headerrow = $header->num_rows();
    $detailrow = $detail->num_rows();
    $header = $header->row();

    $journal = $this->db->query("select ifnull(fld_journalno,0) 'number'
                                 from tbl_journal t0 where MID(t0.fld_journalno, 9, 4 )='$header->table' order by t0.fld_journalid DESC limit 1");
    $journal = $journal->row();

    $journalno =  (substr($journal->number,13,5)+1);
    $journalno = str_pad($journalno, 5, "0", STR_PAD_LEFT);
    $journalno = "JNL/" .$header->fld_bttycd . "/" . $header->table . "/" . $journalno;

    if ($header->flag_journal !=1) {
     	if($header->fld_btiid == 1313) {
	 foreach($detail->result() as $rdetail) {
      	 $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btnoreff,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_sell_price,fld_locid) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date','$rdetail->amount2','$fld_btid','$header->fld_btiid',
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$rdetail->fld_btdocreff',
                        '$rdetail->fld_empid','$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btp01','$rdetail->locid')");

         }


    }
    else {
    $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                      fld_journalori,fld_journalrate,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_locid) values
                      ('$journalno'," . $this->db->escape($header->fld_btdesc) . ",'$header->date','$header->amount','$fld_btid','$header->fld_btiid','',
                      '$header->fld_btamt','$header->fld_btp03','$header->bl','$header->fld_btp01','$header->fld_baidp','$header->jo','$header->locid')");
    }
    $tax_amount = 0;
    $tax_record = 0;
    $total_amount = 0;
    foreach($detail->result() as $rdetail) {
      $this->db->query("insert into tbl_journal (fld_journalno,fld_journaldesc,fld_journaldt,fld_journalamt,fld_btid,fld_coaid,fld_bedivid,
                        fld_journalori,fld_journalrate,fld_btnoreff,fld_btdocreff,fld_empid,fld_baidp,fld_jo,fld_sell_price,fld_locid) values
                        ('$journalno'," . $this->db->escape($rdetail->fld_btdesc) . ",'$header->date','$rdetail->amount','$fld_btid','$rdetail->fld_coaid',
                        '$rdetail->fld_bedivid','$rdetail->fld_btamt01','$header->fld_btp03','$rdetail->fld_btnoreff','$rdetail->fld_btdocreff',
                        '$rdetail->fld_empid','$header->fld_baidp','$rdetail->fld_btnoreff','$rdetail->fld_btp01','$rdetail->locid')");

   	 }

    }
    $this->cekBalanceJournal($fld_btid);
    ### Check Journal Item
	$cekjurnal = $this->db->query("select count(*) AS countjurnal
                                 from tbl_journal t0 where t0.fld_btid = $fld_btid");
        $cekjurnal = $cekjurnal->row();
	$count = $count + $headerrow + $detailrow;

	if ($cekjurnal->countjurnal > $count) {
		 echo "<p>ERROR !!! JOURNAL ITEM NOT MATCH WITH DETAIL TRANSACTION, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
		 exit();
	}

  }


  function cekBalanceJournal($fld_btid) {
    ### Check Balance
    $balance = $this->db->query("select sum(fld_journalamt) 'balance' from tbl_journal where fld_btid=$fld_btid");
    $balance = $balance->row();
    if ($balance->balance > 0 or $balance->balance < 0) {
      echo "<p>ERROR !!! AMOUNT DOESN`T MATCH, PLEASE CHECK AGAIN YOUR TRANSACTION</p>";
      #$this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
      exit();
    }
  }

  function cekCurrency($fld_btid) {
    ### Check Currency
    $curr = $this->db->query("select fld_currency from tbl_btd_cost where fld_btidp=$fld_btid and fld_currency = 0");
    if ($curr->num_rows() > 0)  {
      echo "<p>Please check detail CURRENCY! One or more currency is EMPTY</p>";
      #$this->db->query("delete from tbl_journal where fld_btid=$fld_btid");
      exit();
    }
  }

  function updateJO($fld_btid,$act,$flag) {
       $cek_jo = $this->db->query("select t0.fld_btnoreff from tbl_bth t0 where t0.fld_btid = $fld_btid");
       $query1=$cek_jo->row();
       if ($query1->fld_btnoreff == '') {
         $noreff = '9x9x9x9x';
       } else {
         $noreff = $query1->fld_btnoreff;
       }
       $sql=$this->db->query("SELECT t0.fld_btno,t0.fld_btp05,t0.fld_btp03,t0.fld_btp08
                              from tbl_bth t0
                              where t0.fld_btno like '%$noreff%' and t0.fld_bttyid=1 limit 1");

        /*if ($act =='edit' && $flag !=1 ){
         $update_jo2 =$this->db->query("update tbl_bth set fld_btp38 = 1 where fld_btno in ('$noreff') and fld_bttyid in (1,6,10)");
        }else
        {*/
         if($act !='edit'){
          if ($sql->num_rows() > 0) {
               $query2=$sql->row();
              $update_jo = $this->db->query("update tbl_bth t0
                                             set t0.fld_btnoreff='$query2->fld_btno',
                                             t0.fld_btp06='$query2->fld_btp05',
                                             t0.fld_btp24='$query2->fld_btp03',
                                             t0.fld_btnoalt='$query2->fld_btp08'
                                             where t0.fld_btid=$fld_btid limit 1");

           }
          }
  }


 function cekETDVia ($fld_btid,$fld_btp21,$fld_btp22) {
 $datajob = $this->db->query("select * from tbl_bth t0 where t0.fld_btid = $fld_btid");
 $datajob = $datajob->row();
 $url = "form/78000EXT_JOB_ORDER/edit/$fld_btid?act=edit";
   if ($fld_btp21 != null) {
	 if ($fld_btp22 == null) {
	  $this->db->query("update tbl_bth set fld_btp21 = null where fld_btid = $fld_btid limit 1");
	  echo "<div align='center'>Via Column is filled and ETD Via Column Cannot Be Empty, Click <a href='$url'>Here</a> To  Re-Check & Go Back </div>";
	  exit();
	 }

   }
 }


  function AddCOfromSPV($fld_btid){

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=46");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Cash Out! Cash Out Transaction was made before from this transaction.");
         exit();
       }


    $ctid = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='46' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/COJ/" . $date_trans . "/" . $seq_number;

    $qryspv = $this->db->query("select t0.fld_btno ,t1.fld_empnm, t0.fld_btp11
                                from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid = t0.fld_btp11
                                where t0.fld_btid=$fld_btid
                               ");
    $qryspv = $qryspv->row();


    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btiid,fld_btno,fld_btdesc,fld_btloc)
                      value (2,1,now(),now(),$ctid,46,5,'$fld_btno',concat('$qryspv->fld_btno','-',substr('$qryspv->fld_empnm',1,15)),$location)");
    $last_insert_id = $this->db->insert_id();

    ## Get Settlement Detail
    /*
    $settle_dtl = $this->db->query("insert into tbl_btd_finance
                               (fld_btreffid,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp04,fld_coaid,fld_btp11,fld_locid,fld_btp12)
                                    select
                                    t0.fld_btreffid,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t4.fld_btno,t2.fld_btamt01,t2.fld_btamt02,t2.fld_btp04,'705',t2.fld_bt05,$location,t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );
     */
     if($qryspv->fld_btp11 == 1499 || $qryspv->fld_btp11 == 99902 || $qryspv->fld_btp11 == 484 || $qryspv->fld_btp11 == 99908 || $qryspv->fld_btp11 == 943 ||
	   $qryspv->fld_btp11 == 99901 || $qryspv->fld_btp11 == 2254 || $qryspv->fld_btp11 == 2625 || $qryspv->fld_btp11 == 479 || $qryspv->fld_btp11 == 1300 ||
	   $qryspv->fld_btp11 == 620 || $qryspv->fld_btp11 == 638 || $qryspv->fld_btp11 == 1051 || $qryspv->fld_btp11 == 485 || $qryspv->fld_btp11 == 1273 ||
	   $qryspv->fld_btp11 == 1464 || $qryspv->fld_btp11 == 2649 || $qryspv->fld_btp11 == 493 || $qryspv->fld_btp11 == 959 || $qryspv->fld_btp11 == 477 ||
	   $qryspv->fld_btp11 == 785 || $qryspv->fld_btp11 == 496 || $qryspv->fld_btp11 == 99909) {
    $settle_dtl = $this->db->query("insert into tbl_btd_finance
                                  (fld_btreffid,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp04,fld_coaid,fld_btp11,fld_locid,fld_btp12)
                                    select
                                    t0.fld_btreffid,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t4.fld_btno,t2.fld_btamt01,t2.fld_btamt02,t2.fld_btp04,if(t2.fld_bt05=1,'705','698'),t2.fld_bt05,$location,t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );
     }
	 else{
	 $settle_dtl = $this->db->query("insert into tbl_btd_finance
                               (fld_btreffid,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp04,fld_coaid,fld_btp11,fld_locid,fld_btp12)
                                    select
                                    t0.fld_btreffid,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t4.fld_btno,t2.fld_btamt01,t2.fld_btamt02,t2.fld_btp04,'705',t2.fld_bt05,$location,t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );

	 }

     $update_emp = $this->db->query("update tbl_bth set fld_btp01 = (select fld_empid from tbl_btd_finance where fld_btidp =$last_insert_id limit 1)
                                     where fld_btid=$last_insert_id ");

     $get_advance = $this->db->query("select if(tx0.fld_btp12>0,tx0.fld_btp12,tx0.fld_btp08)AS A,tx0.fld_btp05 AS B,tx0.fld_btp06 AS C
                                      from tbl_bth tx0 where tx0.fld_btid =$fld_btid");


     $query1=$get_advance->row();
     $update_advance = $this->db->query("update tbl_bth t0
                                         set t0.fld_btuamt='$query1->A',
                                         t0.fld_btp05='$query1->B',
                                         t0.fld_btp06='$query1->C'
                                         where t0.fld_btid='$last_insert_id' limit 1 ");

     $get_remain = $this->db->query("select if(tx1.fld_btp13>0 or tx1.fld_btp13<0 ,tx1.fld_btp13,tx1.fld_btp09) AS B from tbl_bth tx1 where tx1.fld_btid =$fld_btid");
     $query2=$get_remain->row();
     $update_remain = $this->db->query("update tbl_bth t1
                                        set t1.fld_btp04=$query2->B
                                        where t1.fld_btid=$last_insert_id ");

     #Check company
     $check_comp =  $this->db->query("select t2.fld_usercomp 'company'
                                      from tbl_btd_advaprv t0
                                      left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid = 4
				      left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
                                      where t0.fld_btidp = $fld_btid limit 1 ");
     $query3=$check_comp->row();
     $update_comp = $this->db->query("update tbl_bth t0
                                      set t0.fld_btp23 = '$query3->company',
                                      t0.fld_btiid = if('$query3->company' = 1,'1313', '5')
                                      where t0.fld_btid = $last_insert_id limit 1");


     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,46)");


     $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$last_insert_id?act=edit";
     redirect($url);
 }

 function AddCRTfromSPV($fld_btid){

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=54");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Cresit term! Credit term Transaction was made before from this transaction.");
         exit();
       }
    #3 cek status
     $cek2 =$this->db->query("select t0.fld_btstat 'status' from tbl_bth t0 where t0.fld_btid ='$fld_btid' and t0.fld_btstat = 3");
     if ($cek2->num_rows = 0){
     $this->ffis->message("ACCESS DENIED! SPV Transaction must be Approved status.");
         exit();
    }
    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='54' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/CRT/" . $date_trans . "/" . $seq_number;
$qryspv = $this->db->query("select t0.fld_btno ,t1.fld_empnm
                                from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid = t0.fld_btp11
                                where t0.fld_btid=$fld_btid
                               ");
    $qryspv = $qryspv->row();


    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno,fld_btdesc)
                      value (2,1,now(),now(),$ctid,54,'$fld_btno',concat('$qryspv->fld_btno','-',substr('$qryspv->fld_empnm',1,15)))");
    $last_insert_id = $this->db->insert_id();
$settle_dtl = $this->db->query("insert into tbl_btd_finance
                               (fld_btreffid,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp04,fld_coaid,fld_btp12)
                                    select
                                    t0.fld_btreffid,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t4.fld_btno,t2.fld_btamt01,t2.fld_btamt02,t2.fld_btp04,'705',t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );

       $update_header = $this->db->query("update tbl_bth set fld_btamt =(select sum(fld_btamt01)from tbl_btd_finance tx0 where tx0.fld_btidp ='$last_insert_id')where fld_btid ='$last_insert_id'");
 $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,54)");


     #Check company
     $check_comp =  $this->db->query("select t2.fld_usercomp 'company'
                                      from tbl_btd_advaprv t0
                                      left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid = 4
                                      left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
                                      where t0.fld_btidp = $fld_btid limit 1 ");
     $query3=$check_comp->row();
     $update_comp = $this->db->query("update tbl_bth t0
                                      set t0.fld_btp23 = '$query3->company'
                                      where t0.fld_btid = $last_insert_id limit 1");


     $url = base_url() . "index.php/page/form/78000CREDIT_TERM/edit/$last_insert_id?act=edit";
     redirect($url);
 }

  function AddCIJfromCOJ($fld_btid){

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=42");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create CIJ! CIJ Transaction was made before from this transaction.");
         exit();
       }
    ## cek status COJ
    // $cek2 =$this->db->query("select t0.fld_btstat 'status' from tbl_bth t0 where t0.fld_btid =$fld_btid and t0.fld_btstat = 3");
    // if ($cek2->num_rows == 0){
    // $this->ffis->message("ACCESS DENIED! COJ Transaction must be Approved!");
    //     exit();
    //}
    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='42' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/CIJ/" . $date_trans . "/" . $seq_number;
    $qrycoj = $this->db->query("select t0.fld_btno
                                from tbl_bth t0
                                where t0.fld_btid=$fld_btid
                               ");
    $qrycoj = $qrycoj->row();


    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno,fld_btiid,fld_btdesc,fld_btloc)
                      value (2,1,now(),now(),'$ctid',42,'$fld_btno',5,concat('DEPOSIT','-','$qrycoj->fld_btno'),1)");
    $last_insert_id = $this->db->insert_id();
	$settle_dtl = $this->db->query("insert into tbl_btd_finance
                                  (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
                                   fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btdocreff, fld_btnodoc)
                                   select
                                   $last_insert_id,'0',t0.fld_bedivid,t0.fld_btid,t0.fld_btnoreff, t0.fld_empid, '698',t0.fld_btdesc,t0.fld_btamt01,
                                   t0.fld_locid, '8', t0.fld_btdocreff, t0.fld_btnodoc
                                   from tbl_btd_finance t0
                                   where t0.fld_btidp=$fld_btid"
                                   );

    $update_header = $this->db->query("update tbl_bth set fld_btamt =(select sum(fld_btamt01)from tbl_btd_finance tx0
                                       where tx0.fld_btidp ='$last_insert_id')where fld_btid ='$last_insert_id'");

    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,42)");

     $url = base_url() . "index.php/page/form/78000CASH_IN/edit/$last_insert_id?act=edit";
     redirect($url);
 }

  function AddGJLfromSPV($fld_btid){

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=51");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create General Journal! General Journal Transaction was made before from this transaction.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='51' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/GJL/" . $date_trans . "/" . $seq_number;

    $qryspv = $this->db->query("select t0.fld_btno ,t1.fld_empnm
                                from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid = t0.fld_btp11
                                where t0.fld_btid=$fld_btid
                               ");
    $qryspv = $qryspv->row();
    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno,fld_btdesc,fld_btloc)
                      value (2,1,now(),now(),$ctid,51,'$fld_btno',concat('$qryspv->fld_btno','-',substr('$qryspv->fld_empnm',1,15)),'$location')");
    $last_insert_id = $this->db->insert_id();

    ## Get Settlement Detail
    $settle_dtl = $this->db->query("insert into tbl_btd_finance
                                    (fld_btnoreff,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btamt01,fld_coaid,fld_btp12)
                                    select
                                    t4.fld_btno,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t2.fld_btamt01,'705',t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );

     #Check company
     $check_comp =  $this->db->query("select t2.fld_usercomp 'company'
                                      from tbl_btd_advaprv t0
                                      left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid = 4
                                      left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
                                      where t0.fld_btidp = $fld_btid limit 1 ");
     $query3=$check_comp->row();
     $update_comp = $this->db->query("update tbl_bth t0
                                      set t0.fld_btp23 = '$query3->company'
                                      where t0.fld_btid = $last_insert_id limit 1");



     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,51)");


     $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$last_insert_id?act=edit";
     redirect($url);
 }

function AddBOfromSPV ($fld_btid){
	## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid in (46,95)");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Bank Out! Bank Out Transaction was made before from this transaction.");
         exit();
       }


    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='95' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/BOJ/" . $date_trans . "/" . $seq_number;

    $qryspv = $this->db->query("select t0.fld_btno ,t1.fld_empnm
                                from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid = t0.fld_btp11
                                where t0.fld_btid=$fld_btid
                               ");
    $qryspv = $qryspv->row();


    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btiid,fld_btno,fld_btdesc)
                      value (2,1,now(),now(),$ctid,95,0,'$fld_btno',concat('$qryspv->fld_btno','-',substr('$qryspv->fld_empnm',1,15)))");
    $last_insert_id = $this->db->insert_id();

    ## Get Settlement Detail
    $settle_dtl = $this->db->query("insert into tbl_btd_finance
                               (fld_btreffid,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp04,fld_coaid,fld_btp12)
                                    select
                                    t0.fld_btreffid,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t4.fld_btno,t2.fld_btamt01,t2.fld_btamt02,t2.fld_btp04,'705',t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );


     $update_emp = $this->db->query("update tbl_bth set fld_btp01 = (select fld_empid from tbl_btd_finance where fld_btidp =$last_insert_id limit 1)
                                     where fld_btid=$last_insert_id ");

     $get_advance = $this->db->query("select if(tx0.fld_btp12>0,tx0.fld_btp12,tx0.fld_btp08)AS A,tx0.fld_btp05 AS B,tx0.fld_btp06 AS C
                                      from tbl_bth tx0 where tx0.fld_btid =$fld_btid");


     $query1=$get_advance->row();
     $update_advance = $this->db->query("update tbl_bth t0
                                         set t0.fld_btuamt=$query1->A,
                                         t0.fld_btp05=$query1->B,
                                         t0.fld_btp06=$query1->C
                                         where t0.fld_btid=$last_insert_id ");

     $get_remain = $this->db->query("select if(tx1.fld_btp13>0 or tx1.fld_btp13<0 ,tx1.fld_btp13,tx1.fld_btp09) AS B from tbl_bth tx1 where tx1.fld_btid =$fld_btid");
     $query2=$get_remain->row();
     $update_remain = $this->db->query("update tbl_bth t1
                                        set t1.fld_btp01=$query2->B
                                        where t1.fld_btid=$last_insert_id ");

     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,95)");


     $url = base_url() . "index.php/page/form/78000BANK_OUT/edit/$last_insert_id?act=edit";
     redirect($url);
 }

function AddBOfromAPV ($fld_btid){

    $location = $this->session->userdata('location');
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid in (95)");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Bank Out! Bank Out Transaction was made before from this transaction.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='95' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/BOJ/" . $date_trans . "/" . $seq_number;

    $qryspv = $this->db->query("select t0.fld_btno ,t1.fld_empnm,t0.fld_btp02
                                from tbl_bth t0
                                left join hris.tbl_emp t1 on t1.fld_empid = t0.fld_btp01
                                where t0.fld_btid=$fld_btid
                               ");
    $qryspv = $qryspv->row();


    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btiid,fld_btno,fld_btamt,fld_btuamt,fld_btdesc)
                      value (2,1,now(),now(),$ctid,95,0,'$fld_btno','$qryspv->fld_btp02','$qryspv->fld_btp02',concat('$qryspv->fld_btno','-',substr('$qryspv->fld_empnm',1,15)))");
    $last_insert_id = $this->db->insert_id();

    ## Get Settlement Detail
   /*settle_dtl = $this->db->query("insert into tbl_btd_finance
                               (fld_btreffid,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp04,fld_coaid)
                                    select
                                    t0.fld_btreffid,$last_insert_id,concat(substr(t5.fld_benm,1,15),'-',t3.fld_btinm),t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t4.fld_btno,t2.fld_btamt01,t2.fld_btamt02,t2.fld_btp04,'705'
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =4 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    left join dnxapps.tbl_be t5 on t5.fld_beid = t4.fld_baidc
                                    where t0.fld_btidp=$fld_btid"
                                   );


     $update_emp = $this->db->query("update tbl_bth set fld_btp01 = (select fld_empid from tbl_btd_finance where fld_btidp =$last_insert_id limit 1)
                                     where fld_btid=$last_insert_id ");

     $get_advance = $this->db->query("select if(tx0.fld_btp12>0,tx0.fld_btp12,tx0.fld_btp08)AS A,tx0.fld_btp05 AS B,tx0.fld_btp06 AS C
                                      from tbl_bth tx0 where tx0.fld_btid =$fld_btid");


     $query1=$get_advance->row();
     $update_advance = $this->db->query("update tbl_bth t0
                                         set t0.fld_btuamt=$query1->A,
                                         t0.fld_btp05=$query1->B,
                                         t0.fld_btp06=$query1->C
                                         where t0.fld_btid=$last_insert_id ");

     $get_remain = $this->db->query("select if(tx1.fld_btp13>0 or tx1.fld_btp13<0 ,tx1.fld_btp13,tx1.fld_btp09) AS B from tbl_bth tx1 where tx1.fld_btid =$fld_btid");
     $query2=$get_remain->row();
     $update_remain = $this->db->query("update tbl_bth t1
                                        set t1.fld_btp01=$query2->B
                                        where t1.fld_btid=$last_insert_id ");
*/

     #Check company
     $check_comp =  $this->db->query("select t2.fld_usercomp 'company'
                                      from tbl_btd_advaprv t0
                                      left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid = 2
                                      left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
                                      where t0.fld_btidp = $fld_btid limit 1 ");
     $query3=$check_comp->row();
     $update_comp = $this->db->query("update tbl_bth t0
                                      set t0.fld_btp23 = '$query3->company'
                                      where t0.fld_btid = $last_insert_id limit 1");



     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,95)");


     $url = base_url() . "index.php/page/form/78000BANK_OUT/edit/$last_insert_id?act=edit";
     redirect($url);
 }

function AddGJLfromAPV($fld_btid){
    $location = $this->session->userdata('location');

    ## check payment type
    $pay = $this->db->query("select t0.fld_btp18 'payment' from tbl_bth t0 where t0.fld_btid = $fld_btid ");
    $pay = $pay->row();
     if ($pay->payment != 3) {
         $this->ffis->message("Can't create General Journal! Payment method must be transfer!");
         exit();
       }

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=51");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create General Journal! General Journal Transaction was made before from this transaction.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='51' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/GJL/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno) value (2,1,now(),now(),$ctid,51,'$fld_btno')");
    $last_insert_id = $this->db->insert_id();

    ## Get Advance Detail
    $adv_dtl = $this->db->query("insert into tbl_btd_finance
                                    (fld_btnoreff,fld_btidp,fld_btdesc,fld_bedivid,fld_empid,fld_btdocreff,fld_btamt01,fld_btp12)
                                    select
                                    t4.fld_btno,$last_insert_id,t3.fld_btinm,t1.fld_baidv,t1.fld_btp11,
                                    if(t4.fld_bttyid in(1,65),if(length(t4.fld_btp08)>0,t4.fld_btp08,t4.fld_btp07),t4.fld_btp23),
                                    t2.fld_btamt01,t2.fld_costtype
                                    from tbl_btd_advaprv t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid =2 and t1.fld_btstat = 3
                                    left join tbl_btd_cost t2 on t2.fld_btidp = t1.fld_btid
                                    left join tbl_bti t3 on t3.fld_btiid = t2.fld_costtype and t3.fld_bticid = 1
                                    left join tbl_bth t4 on t4.fld_btid = t2.fld_bt01 and t4.fld_bttyid in (1,6,10,65)
                                    where t0.fld_btidp=$fld_btid"
                                   );

     #Check company
     $check_comp =  $this->db->query("select t2.fld_usercomp 'company'
                                      from tbl_btd_advaprv t0
                                      left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid = 2
                                      left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
                                      where t0.fld_btidp = $fld_btid limit 1 ");
     $query3=$check_comp->row();
     $update_comp = $this->db->query("update tbl_bth t0
                                      set t0.fld_btp23 = '$query3->company'
                                      where t0.fld_btid = $last_insert_id limit 1");



     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,51)");
     $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$last_insert_id?act=edit";
     redirect($url);
 }


  function AddCAfromJOExp($fld_btid){
    ## check btr
    $division =$this->session->userdata('division');
    $location = $this->session->userdata('location');
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=2");
    if ($cek->num_rows() > 0) {
         $unlockadv = $this->db->query("select fld_btp37 from tbl_bth where fld_btid = '$fld_btid' and fld_bttyid = 6")->row()->fld_btp37;
        if($unlockadv != 1){
	      $this->ffis->message("Can't create Advance! Advance Transaction was made before from this transaction.");
        }
    }

    ## check user group
    $user_group=$this->session->userdata('group');
    if ($user_group == 49 || $user_group == 55) {

    ## check freight type
    $cek2 = $this->db->query("select fld_btp04 AS A from tbl_bth where fld_btid = $fld_btid");
    $query1 = $cek2->row();

    if ($query1->A != 2) {
      $this->ffis->message("Can't create Advance! Freight must be Collect.");
      exit();
    }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='2' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/JOC/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidv,fld_baidp,fld_bttyid,fld_btno,fld_btloc) value
                    (2,1,now(),$division,$ctid,2,'$fld_btno','$location')");
    $last_insert_id = $this->db->insert_id();

    ## Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,2)");
     $closeadv =$this->db->query("update tbl_bth set fld_btp37 = 0 where fld_btid = '$fld_btid' and fld_btp37 = 1 limit 1");
    $url = base_url() . "index.php/page/form/78000JOCASH_ADVANCE_EXP/edit/$last_insert_id?act=edit";
    redirect($url);
    } else {
      echo "<p>You don't have permission to create Advance!</p>";
      exit();
    }

  }


  function AddGJLfromINV($fld_btid){
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=51");
    if ($cek->num_rows() > 0) {
      $this->ffis->message("Can't create General Journal! General Journal Transaction was made before from this transaction.");
      exit();
    }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='51' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/GJL/" . $date_trans . "/" . $seq_number;

    $get_inv_no = $this->db->query("select tx0.fld_btno AS A, tx0.fld_baidc AS B
                                    from tbl_bth tx0 where tx0.fld_btid =$fld_btid");
    $query1=$get_inv_no->row();

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno,fld_btdesc,fld_baidc) value
                    (2,1,now(),now(),$ctid,51,'$fld_btno','$query1->A',$query1->B)");
    $last_insert_id = $this->db->insert_id();

    ## Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,51)");
    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$last_insert_id?act=edit";
    redirect($url);
  }

  function AddGJLfromINV1($fld_btid){
    $countx = $_POST["count"];
    $idx = $_POST["count"];
        for ($i=1; $i<=$countx; $i++){

          for ($x=1; $x<=$countx; $x++){
            if($_POST["rowdata$x"] == $i) {
                  $number = 1;
                  $parentID = $_POST["parentID$x"];

          $job = $_POST["reffno$x"];

                }
          }
        }

    ## check TPK
    $cek = $this->db->query("select * from tbl_btd_tpk t0 where t0.fld_btnoreff = '$job' and t0.fld_status = 0");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't process to General Journal! Check TPK transaction before.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='51' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/GJL/" . $date_trans . "/" . $seq_number;

    $get_inv_no = $this->db->query("select tx0.fld_btno AS A, tx0.fld_baidc AS B
                                    from tbl_bth tx0 where tx0.fld_btid =$fld_btid");
    $query1=$get_inv_no->row();

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno,fld_btdesc,fld_baidc) value
                    (2,1,now(),now(),$ctid,51,'$fld_btno','$query1->A',$query1->B)");
    $last_insert_id = $this->db->insert_id();

    $count = $_POST["count"];
		$id = $_POST["count"];
		$fld_baidp  = $this->session->userdata('ctid');
        $total_amount = 0;
        for ($i=1; $i<=$count; $i++){
			$amount = 0;
			$number = 0;
			$Sid='';
			for ($x=1; $x<=$count; $x++){
				if($_POST["rowdata$x"] == $i) {
					$number = 1;
					$parentID = $last_insert_id ;
					$code = $_POST["code$x"];
					$reffno = $_POST["reffno$x"];
                                        $reffno2 = $_POST["reffno2$x"]; #upd t1215
                    $fld_btdocreff = $_POST["fld_btdocreff$x"];
					$amount = $amount+$_POST["amount$x"];
					$fld_journaldesc = $_POST["fld_journaldesc$x"];
                    $fld_journalid = $_POST["fld_journalid$x"];
                    $exim_type = $_POST["exim_type$x"];
                    $division = $_POST["division$x"];
                    $coa108 = $_POST["coa108$x"];
                    $coa401 = $_POST["coa401$x"];
					//print $_POST["rowdata$x"];
					if ($fld_journalid > 1)
					{
						$Sid .=$fld_journalid;
						$Sid .=",";
					}
				}
			}
			if ($number > 0){
			  $sid=substr_replace($Sid, "", -1);
			  $this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btdocreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$fld_journaldesc',($amount * -1),($amount * -1),'$reffno','$fld_btdocreff','$code','$location')");

	  $last_insert_id2 = $this->db->insert_id();
              $total_amount = $total_amount + $amount;
              $this->db->query("update tbl_journal set fld_btreffid1 = $last_insert_id2 where fld_journalid in ($sid)");
              if($exim_type == 1) {
                 $code = 795;
                 } else {
                 if($division == 13) {
                   $code = 948;
                 } else {
                   $code = 943;
                 }
               }
              echo "$fld_journaldesc###$amount###$exim_type### $code###$division<br>";
              $this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btdocreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$fld_journaldesc',$amount,$amount,'$reffno','$fld_btdocreff','$code','$location')");

                        }
		}



    if($coa108 > 0) {
      $this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btdocreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$cust UANG MUKA TRUCKING',$coa108,$coa108,'$reffno2','$fld_btdocreff','701','$location')");

      $this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btdocreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$cust HUTANG DET',($coa108 * -1),($coa108 * -1),'$reffno2','$fld_btdocreff','737','$location')");
      $this->db->query("update tbl_btd_finance set fld_btp11 = 1 where fld_btidp = $fld_btid and fld_coaid = 701 limit 20");
    }

    if($coa401 > 0) {
      $this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btdocreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$cust ',$coa401,$coa401,'$reffno','$fld_btdocreff','797','$location')");

      $this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btdocreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$cust HUTANG DET',($coa401 * -1),($coa401 * -1),'$reffno','$fld_btdocreff','737','$location')");
    }

    ## Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,51)");
    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$last_insert_id?act=edit";
    redirect($url);
  }
  function get_quo_ist ($fld_btid){
		$data = $this->db->query("select
		t0.fld_btid 'idH',
		t1.fld_btid 'idD',
		t0.fld_baidc 'cust',
		#date_format(t1.fld_btpdt02,'%Y-%m-%d') 'date',
                 date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
		t0.fld_btp02 'origin',
                t0.fld_btp05 'dest',
		t1.fld_contsize 'size',
		t0.fld_bttax 'type',
                t0.fld_btloc
		from
		tbl_bth t0
		left join tbl_btd_container t1 on t1.fld_btidp = t0.fld_btid
		where
		t0.fld_bttyid in (10)
		and t0.fld_btid = $fld_btid ");
                $rdata = $data->row();
			$m_data = $this->db->query("select
                        t0.*,
			t1.fld_btid,
			t1.fld_btno

			from
			dnxapps.tbl_btd_quo_iland t0
			left join dnxapps.tbl_bth t1 on t1.fld_btid = t0.fld_btidp
			where
			t1.fld_bttyid in (33,86)
			and t1.fld_baidc = '$rdata->cust'
			and '$rdata->date' between date_format(t1.fld_btdtsa,'%Y-%m-%d') and date_format(t1.fld_btdtso,'%Y-%m-%d')
                        and t0.fld_btp01 = '$rdata->origin'
			and t0.fld_btp02 = '$rdata->dest'
			#and t0.fld_btp04 = '$rdata->type'
			and t1.fld_btstat = 3
			and t1.fld_baidv = 1
			and t1.fld_btp37 = 1
			limit 1
			");
                        $quo = $m_data->row();
			$quo_id = $m_data->fld_btid;
			if($m_data->num_rows() > 0){
                          $this->db->query("update exim.tbl_bth t0 set t0.fld_btnoreff = '$quo->fld_btno' where t0.fld_btid = $rdata->idH limit 1");
                          $this->db->query("replace into tbl_btd_iland_cost (fld_btidp,fld_btamt02,fld_btamt03,fld_btamt04,fld_btamt05,fld_btamt06,fld_btamt07,fld_btamt08,fld_btamt09,fld_btamt10,
                                                                             fld_btamt11,fld_btamt12,fld_btamt13,fld_btamt14,fld_btamt15,fld_btamt16,fld_btamt17,fld_btamt18,fld_btamt19,fld_btamt20)
                                                                      values($rdata->idH,$quo->fld_btamt02,$quo->fld_btamt03,$quo->fld_btamt04,$quo->fld_btamt05,$quo->fld_btamt06,$quo->fld_btamt07,$quo->fld_btamt08,
                                                                             $quo->fld_btamt09,$quo->fld_btamt10,$quo->fld_btamt11,$quo->fld_btamt12,$quo->fld_btamt13,$quo->fld_btamt14,$quo->fld_btamt15,
                                                                             $quo->fld_btamt16,$quo->fld_btamt17,$quo->fld_btamt18,$quo->fld_btamt19,$quo->fld_btamt20)
                          ");
                        } else {
                           $this->db->query("update exim.tbl_bth t0 set t0.fld_baidc = '',
                                             t0.fld_btnoreff='',
                                             t0.fld_btp15 ='',
                                             t0.fld_btp02 ='',
                                             t0.fld_btp05 =''
                                             where t0.fld_btid = $rdata->idH and t0.fld_btloc = 1
                                             limit 1");
                          $this->ffis->message("Transaction Failed : Invalid Quotation Number, please contact Marketing Division .");
                        }
  }

  function AddGJLfromCOP($fld_btid){
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=51");
    if ($cek->num_rows() > 0) {
      $this->ffis->message("Can't create General Journal! General Journal Transaction was made before from this transaction.");
      exit();
    }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='51' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/GJL/" . $date_trans . "/" . $seq_number;

    //$get_inv_no = $this->db->query("select tx0.fld_btno AS A, tx0.fld_baidc AS B
    //                                 from tbl_bth tx0 where tx0.fld_btid =$fld_btid");
    //$query1=$get_inv_no->row();

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno) value
                    (2,1,now(),now(),$ctid,51,'$fld_btno')");
    $last_insert_id = $this->db->insert_id();

    ## Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,51)");
    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$last_insert_id?act=edit";
    redirect($url);
  }


  function AddGJLfromBPS($fld_btid){

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=51");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create General Journal! General Journal Transaction was made before from this transaction.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='51' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/GJL/" . $date_trans . "/" . $seq_number;

    $qrybps = $this->db->query("select t0.fld_btno ,t0.fld_baidc, t0.fld_btnoalt, t0.fld_btamt,t0.fld_bttax
                                from tbl_bth t0
                                where t0.fld_btid=$fld_btid
                               ");
    $qrybps = $qrybps->row();
    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno,fld_btdesc,fld_baidc)
                      value (2,1,now(),now(),$ctid,51,'$fld_btno',concat('$qrybps->fld_btno','-','$qrybps->fld_btnoalt'),$qrybps->fld_baidc)");
    $last_insert_id = $this->db->insert_id();



     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,51)");


     $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$last_insert_id?act=edit";
     redirect($url);
 }


  function AddCOfromCA($fld_btid){
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Cash Out! Cash Out Transaction was made before from this transaction.");
         exit();
       }


    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='46' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/COJ/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btiid,fld_btno) value (2,1,now(),now(),$ctid,46,5,'$fld_btno')");
    $last_insert_id = $this->db->insert_id();


    $get_advance = $this->db->query("select tx0.fld_btamt AS A, tx0.fld_btdesc AS B, tx0.fld_btiid AS C,  tx0.fld_baidv AS D
                                     from tbl_bth tx0 where tx0.fld_btid =$fld_btid");


     $query1=$get_advance->row();
     $update_coj = $this->db->query("update tbl_bth t0
                                     set t0.fld_btuamt=$query1->A,
                                     t0.fld_btdesc='$query1->B',
                                     t0.fld_btp01=$query1->C,
                                     t0.fld_baidv=$query1->D
                                     where t0.fld_btid=$last_insert_id ");

     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,46)");


     $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$last_insert_id?act=edit";
     redirect($url);
  }

  function AddCOfromCOP($fld_btid){
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Cash Out! Cash Out Transaction was made before from this transaction.");
         exit();
       }


    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='46' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/COJ/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_btdtp,fld_baidp,fld_bttyid,fld_btno) value (2,1,now(),now(),$ctid,46,'$fld_btno')");
    $last_insert_id = $this->db->insert_id();


    $get_amount = $this->db->query("select tx0.fld_btamt01 AS A, tx0.fld_btamt02 AS B,tx0.fld_btp01 AS C,tx0.fld_baidv AS D,
                                    concat('CLOSING','-',tx0.fld_btp04) AS E
                                     from tbl_bth tx0 where tx0.fld_btid =$fld_btid");


     $query1=$get_amount->row();
     $update_coj = $this->db->query("update tbl_bth t0
                                     set t0.fld_btuamt= if($query1->A=0,$query1->B,$query1->A),
                                     t0.fld_btdesc='$query1->E',
                                     t0.fld_btp01=$query1->C,
                                     t0.fld_baidv=$query1->D
                                     where t0.fld_btid=$last_insert_id ");

     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,46)");

     $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$last_insert_id?act=edit";
     redirect($url);
 }

/*
  function CreateSPVfromAPV_old($fld_btid){
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Settlement Approval! SPV Transaction was made before from this transaction.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='9' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/SPV/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno) value (2,1,now(),$ctid,9,'$fld_btno')");
    $last_insert_id = $this->db->insert_id();

    $get_apv = $this->db->query("select tx0.fld_baidv AS A, tx0.fld_btp01 AS B
                                     from tbl_bth tx0 where tx0.fld_btid =$fld_btid");

     $query1=$get_apv->row();
     $update_spv = $this->db->query("update tbl_bth t0
                                     set t0.fld_baidv='$query1->A',
                                     t0.fld_btp11='$query1->B'
                                     where t0.fld_btid=$last_insert_id ");

     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,9)");


     $url = base_url() . "index.php/page/form/78000SETTLEMENT_APPROVAL/edit/$last_insert_id?act=edit";
     redirect($url);
  }
*/

   function CreateTPKfromPDS($fld_btid,$status){
    $user_group=$this->session->userdata('group');
    $location = $this->session->userdata('location');

    ## check user group
    if ($user_group == 12 || $user_group == 13) {
          $this->ffis->message("You don't have permission to Create TPK!");
    }

    ## check btr
    if ($status != 3){
     $this->ffis->message("Can't create TPK! PDS Transaction must be approved.");
    }
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
      if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create TPK! TPK Transaction was made before from this transaction.");
         }
    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='110' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/TPK/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno,fld_btloc) value (2,1,now(),$ctid,110,'$fld_btno','$location')");
    $last_insert_id = $this->db->insert_id();

     # Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,110)");

     # Insert detail PDS to TPK
    $query = $this->db->query ("insert into tbl_btd_tpk (fld_btidp,fld_coaid,fld_btno,fld_btnoreff,fld_bl,fld_cust,fld_btdesc,fld_btreffid,fld_status)
                                select distinct
                                $last_insert_id,
                                '705',
                                t3.fld_btno 'DO Number',
                                t3.fld_btnoreff,
                                t0.fld_btno 'POD Number',
                                t3.fld_baidc,
                                concat(t0.fld_customer,'-',t4.fld_btno),
                                0,0
                                from
                                tbl_trk_settlement t0
                                left join tbl_bth t1 on t1.fld_btid=t0.fld_btreffid
                                left join tbl_btr t2 on t2.fld_btrdst = t1.fld_btid
                                left join tbl_bth t3 on t3.fld_btid = t2.fld_btrsrc and t3.fld_bttyid in (77,112)
                                left join tbl_btd t4 on t4.fld_btidp = t1.fld_btid
                                where
                                t0.fld_btidp = '$fld_btid'
                               ");

    $url = base_url() . "index.php/page/form/78000TPK/edit/$last_insert_id?act=edit";
    redirect($url);


   }

   function CreateJSTfromJOC($fld_btid,$status){
    $user_group=$this->session->userdata('group');
    $location = $this->session->userdata('location');
    $ctid = $this->session->userdata('ctid');

   #check approval
    if ($status != 6){
     $this->ffis->message("Can't create Settlement! JOC Transaction must be verified.");
    }
   #check double transaction
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
      if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Settlement! JST Transaction was made before from this transaction.");
      }
   #check user
    $cek2 = $this->db->query("select t0.fld_baidp 'krani' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $cek2 = $cek2->row();

      if ($cek2->krani != $ctid) {
         $this->ffis->message("Can't create Settlement! You can only create settlement with same user in JOC.");
      }


   #check detail lolo for depot advance cash
   $cek5 = $this->db->query("select t0.fld_btp37 'depotflag' from tbl_bth t0 where t0.fld_btid = $fld_btid");
   $cek5 = $cek5->row();

   $cek6  = $this->db->query("select t1.fld_costtype 'lifton' from tbl_bth t0
                              left join tbl_btd_cost t1 on t1.fld_btidp = t0.fld_btid
                              where t0.fld_btid = $fld_btid limit 1");
   $cek6 = $cek6->row();

   $payment = $this->db->query("select t0.fld_btp18 'paytype' from tbl_bth t0 where t0.fld_btid = $fld_btid");
   $payment = $payment->row();


    if ($cek5->depotflag != 1 && $payment->paytype == 2 && ($cek6->lifton == 3490 || $cek6->lifton ==5453)) {

       $this->ffis->message("Can't create Settlement! Depot DET must be checked and Cost type must be Lift On or Lift Off!!");

    }


   #check payment type
   $cek3 = $this->db->query("select t0.fld_btp18 'paytype' from tbl_bth t0 where t0.fld_btid = $fld_btid");
   $cek3 = $cek3->row();

      if ($cek3->paytype < 2 && $cek3->paytype >= 5) {
         $this->ffis->message("Can't create Settlement! JOC payment type must be transfer or EDC.");
      }

      if ($cek3->paytype != 2 && $cek5->depotflag == 1){
         $this->ffis->message("Can't create Settlement! JOC payment type must be Cash for Depot Advance.");
      }


   #check company
   $cek4 = $this->db->query("select t1.fld_usercomp 'company'
                             from tbl_bth t0
                             left join tbl_user t1 on t1.fld_userid = t0.fld_btp23
                             where t0.fld_btid = $fld_btid");
   $cek4 = $cek4->row();

      if ($cek4->company != 1) {
         $this->ffis->message("Can't create Settlement! JOC Transaction must be REMA Transaction.");
      }


    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='4' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/JST/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno,fld_btloc) value (2,1,now(),$ctid,4,'$fld_btno','$location')");
    $last_insert_id = $this->db->insert_id();

    $insert_detail = $this->db->query("insert into tbl_btd_cost
                                (fld_btidp,fld_bt01,fld_costtype,fld_btp01,fld_currency,fld_btqty01,fld_btuamt01,fld_btamt01)
                                select '$last_insert_id',t0.fld_bt01,t0.fld_costtype,t0.fld_btp01,t0.fld_currency,t0.fld_btqty01,
                                t0.fld_btuamt01,t0.fld_btamt01
                                from tbl_btd_cost t0
                                where t0.fld_btidp = $fld_btid ");

    $get_joc = $this->db->query("select tx0.fld_baidv 'division', tx0.fld_btamt 'req_amount', tx0.fld_btamt01 'payment',
                                 tx0.fld_btp11 'ops_staff',tx0.fld_btp23 'rema_flag',tx0.fld_btp18 'paytype', tx0.fld_btp45 'terminal'
                                 from tbl_bth tx0 where tx0.fld_btid =$fld_btid");

     $query1=$get_joc->row();
     $update_jst = $this->db->query("update tbl_bth t0
                                     set t0.fld_baidv='$query1->division',t0.fld_btp12='$query1->req_amount',
                                     t0.fld_btamt01='$query1->payment',t0.fld_btp11 ='$query1->ops_staff',
                                     t0.fld_btp23='$query1->rema_flag',t0.fld_btp18 = '$query1->paytype',t0.fld_btp45 = '$query1->terminal'
                                     where t0.fld_btid=$last_insert_id limit 1");

    // update joc to approve
     $update_joc = $this->db->query("update tbl_bth t0 set t0.fld_btstat = 3,t0.fld_btdtsa = now() where t0.fld_btid = $fld_btid limit 1");

     # Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,4)");

    $url = base_url() . "index.php/page/form/78000JO_SETTLEMENT/edit/$last_insert_id?act=edit";
     redirect($url);

}

   function apvReprocess($fld_btid,$status){
   $user_group=$this->session->userdata('group');
   if ($user_group == 9){
       if ($status != 3){
     	$this->ffis->message("Access denied , Status Advance Transaction must be Approved and don't have APV Transaction.");
    }

    $cekApv = $this->db->query("select t0.fld_btid,t1.fld_btno from tbl_btd_advaprv t0
				left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp and t1.fld_bttyid in (8)
				 where
				t1.fld_btdt > (DATE_SUB(now(), INTERVAL 5 DAY)) and
				t0.fld_btreffid = '$fld_btid' limit 1");
     $rcekApv= $cekApv->row();
     #echo "id=$rcekApv->fld_btno";
	if ($cekApv->num_rows() > 0) {
         $this->ffis->message("Failed! This Advance have a APV Transaction number :$rcekApv->fld_btno .");
         } else {
         $this->db->query("update tbl_bth set fld_btstat = 6 where fld_btid = $fld_btid and fld_bttyid = 2 limit 1");

	}
    $url = base_url() . "index.php/page/form/78000JOCASH_ADVANCE/edit/$fld_btid?act=edit";
     redirect($url);
   } else {
   $this->ffis->message("Access denied ,user is not allowed to process this fitur.");
   }

    }
   function CreateSPVfromAPV($fld_btid,$status){
    $user_group=$this->session->userdata('group');
    $location = $this->session->userdata('location');

    ## check btr
    if ($status != 3){
     $this->ffis->message("Can't create Settlement Approval! APV Transaction must be approved.");
    }
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
      if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Settlement Approval! SPV Transaction was made before from this transaction.");
         }
    $cek_company = $this->db->query("select t3.fld_usercomp 'company'
                                   from tbl_bth t0
                                   left join tbl_btd_advaprv t1 on t1.fld_btidp = t0.fld_btid
				   left join tbl_bth t2 on t2.fld_btid = t1.fld_btreffid and t2.fld_bttyid = 2
				   left join tbl_user t3 on t3.fld_userid = t2.fld_btp23
                                   where t0.fld_btid = $fld_btid");
    $comp = $cek_company->row();
    $comp1 = $comp->company;

    //Cek Settle in ASM (Khusus DE)
    if($location == 1 && $comp1 !=1){
    $cek_settle = $this->db->query("select t2.fld_btid 'settle' from tbl_btd_advaprv t0
                                    left join tbl_btr t1 on t1.fld_btrsrc = t0.fld_btreffid and t1.fld_btrdsttyid = 4
                                    left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst and t2.fld_bttyid = 4
                                    where t0.fld_btidp = $fld_btid");
    $settle = $cek_settle->result();
     foreach ($settle as $rsettle) {
         $query = $this->db->query("select t0.fld_btstat 'status',t0.fld_btno 'asmno'
                                    from tbl_bth t0
                                    left join tbl_btd_doc t1 on t1.fld_btidp = t0.fld_btid
                                    where t1.fld_btiid = $rsettle->settle limit 1 ");
          $query1 = $query->row();

          if($query->num_rows() > 0) {

                if($query1->status != 3){
                        $this->ffis->message("Can't create SPV! Please approve ASM transaction ($query1->asmno) first!");
                        exit();
                }
          }
          else{
                $this->ffis->message("Can't create SPV! Please make ASM Transaction before!");
                exit();
          }

     }
    }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='9' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/SPV/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno,fld_btloc) value (2,1,now(),$ctid,9,'$fld_btno','$location')");
    $last_insert_id = $this->db->insert_id();

    $get_apv = $this->db->query("select tx0.fld_baidv AS A, tx0.fld_btp01 AS B
                                     from tbl_bth tx0 where tx0.fld_btid =$fld_btid");

     $query1=$get_apv->row();
     $update_spv = $this->db->query("update tbl_bth t0
                                     set t0.fld_baidv='$query1->A',
                                     t0.fld_btp11='$query1->B',t0.fld_btp01 ='$query1->B'
                                     where t0.fld_btid=$last_insert_id ");

     # Insert tbl_btr
   $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,9)");

      $query = $this->db->query ("insert into tbl_btd_advaprv (fld_btidp,fld_btreffid,fld_btamt01,fld_btamt03,fld_btamt02,fld_btamt04,fld_btamt05,fld_btamt07,fld_btamt06,fld_btamt08,fld_btp01) select $last_insert_id,t2.fld_btid,t2.fld_btp12,t2.fld_btp05,t2.fld_btamt,t2.fld_btp13,t2.fld_btp08,t2.fld_btp06,t2.fld_btp07,t2.fld_btp09,t3.fld_empnm from tbl_btd_advaprv t0
                                left join tbl_btr t1 on t1.fld_btrsrc = t0.fld_btreffid and t1.fld_btrdsttyid = 4
                                left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst and t2.fld_bttyid = 4
                                left join hris.tbl_emp t3 on t3.fld_empid = t2.fld_btp11
                                 where t0.fld_btidp = '$fld_btid' ");

$get_settle_user = $this->db->query("select tx1.fld_btp23 'userid'
           			    from tbl_btd_advaprv tx0
		   		    left join tbl_bth tx1 on tx1.fld_btid = tx0.fld_btreffid and tx1.fld_bttyid = 4
		   		    where tx0.fld_btidp = '$last_insert_id' limit 1");
$query2 = $get_settle_user->row();

     $query = $this->db->query ("update tbl_bth set fld_btp12= (select sum(fld_btamt01) from tbl_btd_advaprv where fld_btidp = '$last_insert_id'),fld_btamt= (select sum(fld_btamt02) from tbl_btd_advaprv where fld_btidp = '$last_insert_id'),fld_btp13= (select sum(fld_btamt04) from tbl_btd_advaprv where fld_btidp = '$last_insert_id'),fld_btp07= (select sum(fld_btamt06) from tbl_btd_advaprv where fld_btidp = '$last_insert_id'),fld_btp13= (select sum(fld_btamt04) from tbl_btd_advaprv where fld_btidp = '$last_insert_id'),fld_btp09= (select sum(fld_btamt08) from tbl_btd_advaprv where fld_btidp = '$last_insert_id'),
fld_btp23='$query2->userid' where fld_btid = '$last_insert_id' limit 1");
     $url = base_url() . "index.php/page/form/78000SETTLEMENT_APPROVAL/edit/$last_insert_id?act=edit";
     redirect($url);
  }

   function CreateCTDfromAPV($fld_btid){

    ## check payment type
    $pay = $this->db->query("select t0.fld_btp18 'payment' from tbl_bth t0 where t0.fld_btid = $fld_btid ");
    $pay = $pay->row();
     if ($pay->payment != 1) {
         $this->ffis->message("Can't create Container Deposit! Payment method must be giro/chq!");
         exit();
       }

    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=11");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't create Container Deposit! CTD Transaction was made before from this transaction.");
         exit();
       }

    ## get advance number
    $advance =  $this->db->query("select t1.fld_btreffid 'adv',fld_btamt05 'deposit'
                                  from tbl_bth t0
                                  left join tbl_btd_advaprv t1 on t1.fld_btidp = t0.fld_btid
                                  where t0.fld_btid = $fld_btid limit 1");
    $advance = $advance->row();

    ## get Job Order
    $jo =  $this->db->query("select t1.fld_bt01 'jo'
                             from tbl_bth t0
                             left join tbl_btd_cost t1 on t1.fld_btidp = t0.fld_btid
                             where t0.fld_btid = $advance->adv limit 1");

    $jo = $jo->row();

    ## detail jo
    $jo_info = $this->db->query("select t0.fld_btno 'jo_no',t0.fld_baidc 'cust',t0.fld_btp07 'house',t0.fld_btp08 'master',t0.fld_btp15 'ship'
                                 from tbl_bth t0
                                 where
                                 t0.fld_btid = $jo->jo
                                 and
                                 t0.fld_bttyid in (1,65)");
    $jo_info = $jo_info->row();


    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='11' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/CTD/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth
                     (fld_baido,fld_baidv,fld_btstat,fld_btdt,fld_baidc,fld_baidp,fld_bttyid,fld_btno,fld_btp25,fld_btp07,fld_btp08,fld_btp15,
                      fld_btamt,fld_btp01,fld_btp24)
                      value (2,14,1,now(),$jo_info->cust,$ctid,11,'$fld_btno','$jo_info->jo_no','$jo_info->house','$jo_info->master','$jo_info->ship',
                      '$advance->deposit','$advance->deposit','$advance->deposit')");
    $last_insert_id = $this->db->insert_id();

     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,11)");
     $url = base_url() . "index.php/page/form/78000CONTAINER_DEPOSIT/edit/$last_insert_id?act=edit";
     redirect($url);
 }


   function CreateRSFfromSHI($fld_btid){

    ## check approval SHI
    $cek = $this->db->query("select t0.fld_btstat 'status' from tbl_bth t0 where t0.fld_btid = $fld_btid ");
    $cek = $cek->row();
     if ($cek->status != 3) {
         $this->ffis->message("Can't create Settlement Refund! Submit EIR Transaction not yet approved!");
         exit();
       }

    ## check btr
    $cek1 = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=66");
       if ($cek1->num_rows() > 0) {
         $this->ffis->message("Can't create Settlement Refund! Refund Transaction was made before from this transaction.");
         exit();
       }


    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='66' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/RSF/" . $date_trans . "/" . $seq_number;


    $staff = $this->db->query("select t0.fld_btp01 'ops_staff' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $staff = $staff->row();


    $this->db->query("insert into tbl_bth
                     (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno,fld_btp01)
                      value (2,1,now(),$ctid,66,'$fld_btno',$staff->ops_staff)");
    $last_insert_id = $this->db->insert_id();

    ## get detail shi
    $shi_dtl = $this->db->query("insert into tbl_btd_deposit
                                 (fld_btidp,fld_btdesc,fld_btp02,fld_blno,fld_btp01,fld_btreffid,fld_btflag)
                                 select
                                 $last_insert_id,'REFUND JAMINAN', t1.fld_btp02,t1.fld_blno,t2.fld_btp15,t1.fld_btreffid,'1'
                                 from tbl_bth t0
                                 left join tbl_btd_deposit t1 on t1.fld_btidp = t0.fld_btid
                                 left join tbl_bth t2 on t2.fld_btid = t1.fld_btreffid and t2.fld_bttyid = 11
                                 where
                                 t0.fld_btid = $fld_btid
                                ");

    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,66)");
    $url = base_url() . "index.php/page/form/78000SETRFUND_ENTRY/edit/$last_insert_id?act=edit";
    redirect($url);
 }


   function AddRSCfromCTD($fld_btid){

    ## check total settle vs total deposit
    $cek = $this->db->query("select t0.fld_btamt 'deposit',t0.fld_btp14 'settle' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $cek1 = $cek->row();

       if (($cek1->deposit == $cek1->settle)|| ($cek1->settle > $cek1->deposit))  {
         $this->ffis->message("Can't create Settlement Cost! Total Settlement already equal or greater than total deposit.");
         exit();
       }

    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='87' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/RSC/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno) value (2,1,now(),$ctid,87,'$fld_btno')");
    $last_insert_id2 = $this->db->insert_id();


    $get_cost = $this->db->query("select
                                  tx0.fld_btid AS depid,tx0.fld_btno AS depositno,
                                  tx0.fld_btp08 AS master,tx0.fld_btp15 AS shipping,
                                  tx0.fld_btp07 AS house, tx0.fld_btp06 AS detention,
                                  tx0.fld_btp21 AS adm, tx0.fld_btp16 AS repair
                                  from tbl_bth tx0 where tx0.fld_btid =$fld_btid");
    $query1=$get_cost->row();


    ##insert detention cost
    if($query1->detention > 0){
     $detention = $this->db->query("insert into tbl_btd_deposit (fld_btidp,fld_btreffid,fld_btdesc,fld_btp02,fld_blno,fld_btp01,fld_btamt01,fld_btp03)
                                    value($last_insert_id2,$query1->depid,'DETENTION COST','$query1->master','$query1->house',$query1->shipping,
                                    $query1->detention,5)");
    }

    ##insert adm cost
    if($query1->adm > 0){
     $adm = $this->db->query("insert into tbl_btd_deposit (fld_btidp,fld_btreffid,fld_btdesc,fld_btp02,fld_blno,fld_btp01,fld_btamt01,fld_btp03)
                           value($last_insert_id2,$query1->depid,'ADM/WASH COST','$query1->master','$query1->house',$query1->shipping,$query1->adm,5)");
    }

    ##insert repair cost
    if($query1->repair > 0){
     $adm = $this->db->query("insert into tbl_btd_deposit (fld_btidp,fld_btreffid,fld_btdesc,fld_btp02,fld_blno,fld_btp01,fld_btamt01,fld_btp03)
                           value($last_insert_id2,$query1->depid,'REPAIR COST','$query1->master','$query1->house',$query1->shipping,$query1->repair,5)");
    }

     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id2,87)");


     $url = base_url() . "index.php/page/form/78000REFUND_COST/edit/$last_insert_id2?act=edit";
     redirect($url);
  }


  function CancelCA($fld_btid){
    ## check btr
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and t0.fld_btrdsttyid=69");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("Can't cancel Advance! Cancel transaction was made before from this transaction.");
         exit();
       }


    $ctid = $this->session->userdata('ctid');
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='69' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/CAC/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno) value (2,1,now(),$ctid,69,'$fld_btno')");
    $last_insert_id = $this->db->insert_id();


    $get_advance = $this->db->query("select if(tx0.fld_btp02=0,if(tx0.fld_btamt=0,tx0.fld_btuamt,tx0.fld_btamt),tx0.fld_btp02) AS A, tx0.fld_btflag AS B,
                                     if(tx0.fld_btp11='',if(tx0.fld_btiid='',tx0.fld_btp01,tx0.fld_btiid),tx0.fld_btp11) AS C,
                                     tx0.fld_baidv AS D,tx0.fld_btloc AS E
                                     from tbl_bth tx0 where tx0.fld_btid =$fld_btid");


     $query1=$get_advance->row();
     $update_coj = $this->db->query("update tbl_bth t0
                                     set t0.fld_btamt=$query1->A,
                                     t0.fld_btflag='$query1->B',
                                     t0.fld_btiid=$query1->C,
                                     t0.fld_baidv=$query1->D,
                                     t0.fld_btloc=$query1->E
                                     where t0.fld_btid=$last_insert_id ");

     ## Insert tbl_btr
     $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,69)");

    $listjoc = $this->db->query("SELECT
        t0.fld_btreffid 'jocid',
        t1.fld_btid 'apvid'

        FROM tbl_btd_advaprv t0
        LEFT JOIN tbl_bth t1 on t1.fld_btid=t0.fld_btidp

        WHERE
        t1.fld_bttyid=8
        and t1.fld_btid='$fld_btid'
    ")->result();

    foreach ($listjoc as $key => $item) {
        $this->db->query("update tbl_bth set fld_btstat = 5,fld_btp44 = '$ctid' where fld_btid = '$item->jocid' limit 1 ");
    }


      $url = base_url() . "index.php/page/form/78000CA_CANCEL/edit/$last_insert_id?act=edit";
     redirect($url);
 }

 function UpdateOverPayment($id,$idr,$usd){

     $update_op = $this->db->query("update tbl_bth t0
                                    set t0.fld_btp05=$idr,t0.fld_btp06=$usd
                                    where t0.fld_btid = (select tx0.fld_btrsrc from tbl_btr tx0 where tx0.fld_btrdst = $id) and t0.fld_bttyid = 4
                                  ");
 }


 function CancelJOExp($id){
 #Check User Group
   $ctid = $this->session->userdata('ctid');
   $user_group=$this->session->userdata('group');
   if ($user_group == 35 || $user_group == 36 || $user_group == 38 || $user_group == 49) {
   $sql="update tbl_bth set fld_btstat = 4,fld_btdesc = concat(fld_btdesc,'cancel transaction by $ctid') where fld_btid='$id' limit 1";
   $this->db->query($sql);

   $sql2="update tbl_btd_stuffing t0
          set t0.fld_btp07=1
          where t0.fld_btidp='$id'";
   $this->db->query($sql2);

   }
   else {
   echo "<p>You don't have permission to Cancel JO!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/view/78000EXT_JOB_ORDER";
    redirect($url);

 }


 function CancelJOInter($id){
 #Check User Group

   $user_group=$this->session->userdata('group');
   if ($user_group == 36 || $user_group == 46) {
   	$sql="update tbl_bth set fld_btstat = 4 where fld_btid='$id' limit 1";
   	$this->db->query($sql);
   }
   else {
   echo "<p>You don't have permission to Cancel JO!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/form/78000INTER_ISLAND/edit/$id?act=edit";
    redirect($url);

 }

  function CompleteJOImp($id){
  #Check User Group
    $user_group=$this->session->userdata('group');
    if ($user_group == 6 || $user_group == 7 || $user_group == 4 || $user_group == 1) {
      $cek = $this->db->query("select * from tbl_bth t0 where t0.fld_btid = $id limit 1")->row();

      if ($cek->fld_btp32 == 0) {
        	$html2 .= "- PIB Response<br>";
        	$html1 = "Transaction failed : Please check the following field(s) :<br>";
        	$this->ffis->message("$html1 $html2");
      }

      if ($cek->fld_btnoalt == '') {
                $html2 .= "- AJU Number<br>";
                $html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
      }

      if ($cek->fld_btp05 == '') {
                $html2 .= "- Destination<br>";
                $html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
      }

      if ($cek->fld_bttax == 3 && $cek->fld_btp33 == 0) {
                $html2 .= "- Break Bulk UoM<br>";
                $html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
      }

      if ($cek->fld_bttax == 1 && ($cek->fld_btqty == '' && $cek->fld_btp06 == '')) {
                $html2 .= "- Container Party<br>";
                $html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
      }

      $sql="update tbl_bth set fld_btstat = 3 where fld_btid='$id' limit 1";
      $this->db->query($sql);
    }
    else {
      $this->ffis->message("Transaction failed : Permission deny  ");
    }
    $url = base_url() ."index.php/page/form/78000JOB_ORDER_IMP/edit/$id?act=edit";
    redirect($url);
  }

 function ApproveJOExp($id){
  ## check approval
    $cek1 = $this->db->query("select t0.fld_btstat,t0.fld_btp30 from tbl_bth t0 where t0.fld_btid = $id")->row();
       if ($cek1->fld_btstat == 3) {
         $this->ffis->message("This Job already completed!");
         exit();
       }

       #if ($cek1->fld_btp30 == 1) {
       #  $this->ffis->message("This Job has not been completed by CS! Please confirm to CS!");
       #  exit();
       #}

  #Check User Group
    $user_group=$this->session->userdata('group');
    if ($user_group == 49 || $user_group == 36) { //document and manager
      $cek = $this->db->query("select t0.fld_btp27 'peb_arrange',
                               t1.fld_doctype 'doktype',t1.fld_docnum 'docnum', t1.fld_btp03 'coo_type', t1.fld_btp04 'total_form',
                               t1.fld_dt05 'final_si',t1.fld_dt04 'kirim_dok',t1.fld_docdt 'draft_coo',
                               t1.fld_dt01 'terima_coo',t1.fld_dt06 'proses_coo',t1.fld_dt02 'kirim_coo',t1.fld_dt03 'terima_bl',t1.fld_btp02 'krani_bl'
                               from tbl_bth t0
                               left join tbl_btd_document t1 on t1.fld_btidp = t0.fld_btid
                               where t0.fld_btid = $id");

      foreach ($cek->result() as $row){

      if ($row->doktype == '' && $row->peb_arrange == 1) {
        $html2 .= "- Doc Type<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->docnum == '' && $row->peb_arrange == 1) {
        $html2 .= "- Doc Number<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->coo_type == '' && $row->peb_arrange == 1 && $row->doktype == 861) {
         $html2 .= "- COO Form Type<br>";
         $html1 = "Transaction failed : Please check the following field(s) :<br>";
         $this->ffis->message("$html1 $html2");
      }

	   if ($row->total_form == '' && $row->peb_arrange == 1 && $row->doktype == 861) {
         $html2 .= "- Total Form<br>";
         $html1 = "Transaction failed : Please check the following field(s) :<br>";
         $this->ffis->message("$html1 $html2");
       }

     // if ($row->final_si == '0000-00-00'  && $row->peb_arrange == 1) {
     //   $html2 .= "- Final SI<br>";
     //   $html1 = "Transaction failed : Please check the following field(s) :<br>";
     //   $this->ffis->message("$html1 $html2");
     // }

      if ($row->kirim_dok == '0000-00-00'  && $row->peb_arrange == 1) {
        $html2 .= "- Tgl Kirim Dok<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->draft_coo == '0000-00-00'  && $row->peb_arrange == 1 && $row->doktype == 861) {
        $html2 .= "- Draft COO<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->terima_coo == '0000-00-00'  && $row->peb_arrange == 1 && $row->doktype == 861) {
        $html2 .= "- Terima COO<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->proses_coo == '0000-00-00'  && $row->peb_arrange == 1 && $row->doktype == 861) {
        $html2 .= "- Proses COO<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->kirim_coo == '0000-00-00'  && $row->peb_arrange == 1 && $row->doktype == 861) {
        $html2 .= "- Kirim COO<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

	   if ($row->terima_bl == '0000-00-00'  && $row->peb_arrange == 1 && $row->doktype == 705) {
        $html2 .= "- Terima BL<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->krani_bl == 0  && $row->peb_arrange == 1) {
        $html2 .= "- Krani BL<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }


      }
      $sql="update tbl_bth set fld_btstat = 3 where fld_btid='$id' limit 1";
      $this->db->query($sql);
    }
    else {
      $this->ffis->message("Transaction failed : Permission deny  ");
    }
    $url = base_url() ."index.php/page/form/78000EXT_JOB_ORDER/edit/$id?act=edit";
    redirect($url);
  }


 function CompleteJOCSExp($id){
  #Check User Group
    $user_group=$this->session->userdata('group');
    $userid = $this->session->userdata('ctid');
    if ($user_group == 35 || $userid == 487 || $userid == 2100) { //export CS
      $cek = $this->db->query("select t0.fld_btp01 'si',t0.fld_btp08 'bl', t0.fld_bttax 'party',t0.fld_btp09 'lcl',
                               t0.fld_btqty 'twenty',t0.fld_btp06 'forty',t0.fld_btuamt 'hc',
                               t1.fld_contnum 'contnum',t1.fld_conttype 'conttype', t1.fld_contsize 'contsize', t1.fld_btpdt02 'stuff_date',
                               t1.fld_btp09 'stuff_loc',t1.fld_btp07 'truck',t1.fld_btp08 'driver',
                               t1.fld_btp02 'fact_in',t1.fld_btp03 'closing',t1.fld_btp04 'fact_out',t1.fld_btp05 'utc'
                               from tbl_bth t0
                               left join tbl_btd_container t1 on t1.fld_btidp = t0.fld_btid
                               where t0.fld_bttax = 1 and t0.fld_btid = $id");

      foreach ($cek->result() as $row){

      if ($row->si == '') {
        $html2 .= "- SI / Invoice<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      #if ($row->bl == '') {
      #  $html2 .= "- B/L Number<br>";
      #  $html1 = "Transaction failed : Please check the following field(s) :<br>";
      #  $this->ffis->message("$html1 $html2");
      #}

      if ($row->party == 3 && ($row->twenty > 0  && $row->forty > 0 && $row->hc > 0)) {
         $html2 .= "- Container Party Qty<br>";
         $html1 = "Transaction failed : Please check the following field(s) :<br>";
         $this->ffis->message("$html1 $html2");
      }

      if ($row->party == 2 && ($row->lcl == '')) {
         $html2 .= "- LCL Qty<br>";
         $html1 = "Transaction failed : Please check the following field(s) :<br>";
         $this->ffis->message("$html1 $html2");
      }

      if ($row->contnum == '') {
        $html2 .= "- Container Number<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->conttype == '') {
        $html2 .= "- Seal Number<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->contsize == 0) {
        $html2 .= "- Container Size<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->stuff_date == '0000-00-00 00:00:00') {
        $html2 .= "- Stuffing Date<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->stuff_loc == '') {
        $html2 .= "- Stuffing Loc<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->truck == '') {
        $html2 .= "- Truck Number<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->driver == '') {
        $html2 .= "- Driver<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->fact_in == '0000-00-00 00:00:00') {
        $html2 .= "- Factory In<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->closing == '0000-00-00 00:00:00') {
        $html2 .= "- Closing<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->fact_out == '0000-00-00 00:00:00') {
        $html2 .= "- Factory Out<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }

      if ($row->utc == '0000-00-00 00:00:00') {
        $html2 .= "- UTC In<br>";
        $html1 = "Transaction failed : Please check the following field(s) :<br>";
        $this->ffis->message("$html1 $html2");
      }
     }
      $sql="update tbl_bth set fld_btp30 = 3 where fld_btid='$id' limit 1";
      $this->db->query($sql);
    }
    else {
      $this->ffis->message("Transaction failed : Permission deny  ");
    }
    $url = base_url() ."index.php/page/form/78000EXT_JOB_ORDER/edit/$id?act=edit";
    redirect($url);
 }

 function ApproveJOInter($id){
 #Check User Group
   $flag =  $this->uri->segment(4);
   $user_group=$this->session->userdata('group');
   if ($user_group == 36 || $user_group == 46 || $user_group == 1) {
        if($flag == 2){
        $sql="update tbl_bth set fld_btstat = 1 where fld_btid='$id' limit 1";
        $this->db->query($sql);
        }else {
        $sql="update tbl_bth set fld_btstat = 3 where fld_btid='$id' limit 1";
        $this->db->query($sql);
        }
   }
   else {
   echo "<p>You don't have permission to Change Status!</p>";
       exit();
   }

    $url = base_url() ."index.php/page/form/78000INTER_ISLAND/edit/$id?act=edit";
    redirect($url);

 }

 function CloseJO($id){
 #Check User Group
   $user_group=$this->session->userdata('group');
   if ($user_group == 6) {
   $get_jo= $this->db->query("select t0.fld_bt01 'joid'
                              from tbl_btd_cost t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                              where t1.fld_btid='$id' group by t0.fld_bt01");


   foreach ($get_jo->result() as $row){
             $this->db->query("update tbl_bth t0
                               set t0.fld_btp25=1
                               where t0.fld_btid=$row->joid limit 1");
            }
   }

   else {
   echo "<p>You don't have permission to Close JO!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/form/78000JO_SETTLEMENT/edit/$id?act=edit";
    redirect($url);

 }

  function CekJOExp($id){
   #Cek JO
   $get_jo= $this->db->query("select t0.fld_bt01 'joid',fld_costtype 'cost_desc'
                              from tbl_btd_cost t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                              where t1.fld_btid='$id' group by t0.fld_bt01");


   foreach ($get_jo->result() as $row){
             $this->db->query("update tbl_bth t0
                               set t0.fld_btp25=1
                               where t0.fld_btid=$row->joid limit 1");
            }



   echo "<p>You don't have permission to Close JO!</p>";
       exit();



    $url = base_url() ."index.php/page/form/78000JO_SETTLEMENT/edit/$id?act=edit";
    redirect($url);

 }


  function OpenJO($id){
   #Check User Group

   $user_group=$this->session->userdata('group');
   if ($user_group == 6) {

   $update_jo = $this->db->query("update tbl_bth t0
                                  set t0.fld_btp25=0
                                  where t0.fld_btid=$id limit 1");
   }
   else {
   echo "<p>You don't have permission to Re-Open JO!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/form/78000JOB_ORDER_IMP/edit/$id?act=edit";
    redirect($url);

 }
/*
 function UnlockAdvanceSIJ($id){
   #Check User Group

   $user_group=$this->session->userdata('group');
   if($user_group == 1 || $user_group == 5 || $user_group == 6 || $user_group == 7) {

   $update_jo = $this->db->query("update tbl_bth t0
                                  set t0.fld_btp44=1
                                  where t0.fld_btid=$id limit 1");
   }
   else {
   echo "<p>You don't have permission to Unlock JO for advance!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/form/78000JOB_ORDER_IMP/edit/$id?act=edit";
    redirect($url);

 }
*/


 function CancelSRC($id){
   #Check User Group

   $user_group=$this->session->userdata('group');
   if($user_group == 1 || $user_group == 9 || $user_group == 10 || $user_group == 52 || $user_group == 80 || $user_group == 81) {

   $cancel_src = $this->db->query("update tbl_bth t0
                                  set t0.fld_btstat=5
                                  where t0.fld_btid=$id limit 1");

   //backup reffid settle cancel
   $update_detail_reff2 = $this->db->query("update tbl_btd_receipt t0
                                          set t0.fld_btreffid2 = t0.fld_btreffid
                                          where t0.fld_btidp=$id");


   //reset reffid
   $update_detail_reff = $this->db->query("update tbl_btd_receipt t0
    	                                  set t0.fld_btreffid = 0
    	                                  where t0.fld_btidp=$id");
   }
   else {
   echo "<p>You don't have permission to Cancel Settlement Receipt!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/form/78000SETTLE_RECEIPT/edit/$id?act=edit";
    redirect($url);

 }



 function cekInvjst($fld_btid){
 $userid = $this->session->userdata('ctid');
 $location = $this->session->userdata('location');
 $cek = $this->db->query("SELECT tz0.fld_btid,tz0.fld_btp05 FROM `tbl_btd_cost` tz0 WHERE tz0.fld_btidp = $fld_btid and tz0.fld_btp05 in ('') and tz0.fld_bt05 = 1 ");
 $cnt = $cek->num_rows();
 if($cnt > 0){
	$this->ffis->message("Detail Cost type Invoice ,must be fill Number Invoice ..... ");
 }
 if ($location == 1){
 $data = $this->db->query("select t0.fld_btidp,t0.fld_btp05,t1.fld_btno ,t2.fld_empnm 'by'
		from tbl_btd_cost t0
		left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
		left join hris.tbl_emp t2 on t2.fld_empid = t1.fld_baidp
		where t0.fld_btp05
		in (
		SELECT tz0.fld_btp05 FROM `tbl_btd_cost` tz0 WHERE tz0.fld_btidp = $fld_btid and tz0.fld_btp05 not in (0) and tz0.fld_bt05 = 1
		)
		AND
		t1.fld_bttyid = 4
		AND
		date_format(t1.fld_btdt,'%Y-%m-%d') >'2020-10-01'
		AND
		t0.fld_bt05 = 1
		and
		t0.fld_btp05 not in (0)
		and
		t0.fld_btidp <> '$fld_btid'
		");
                 $count = $data->num_rows();
      if ($count > 0){
      echo "<center>Please check the list below,Duplicated invoice Number:<br>";
       $no =0;
      foreach ($data->result() as $rdata){
       $no = $no +1;

          echo "<center style='color:red'>$no . $rdata->fld_btno  [ $rdata->fld_btp05 ] <br>";
           }
           echo exit();
        }


 #echo "id=$userid,$fld_btid";
 #exit();
  }
 }


 function addAdv($id){
 $user_group = $this->session->userdata('group');
 $group_add = $this->session->userdata('group_add');
#echo "aa:$user_group";
#echo exit();
 if ($user_group ==63 ){
    $update_jo = $this->db->query("update tbl_bth t0
                                  set t0.fld_btp37=1
                                   where t0.fld_btid=$id and fld_btp37 = 0 limit 1");
  $this->ffis->message("Unlock  add Advance Succesfully  ..... ");
}else
{
  echo "<p>You don't have permission to Unlock Additional Advance</p>";
       exit();
}
 }

 function aprvBosb($id){
   #Check User Group

   $user_group=$this->session->userdata('group');
   if ($user_group == 66) {

   $aprv_Bosb = $this->db->query("update tbl_bth t0
                                  set t0.fld_btstat=3
                                  where t0.fld_btid=$id limit 1");
   }
   else {
   echo "<p>Access denied, You don't have permission to Approve Cash Advance Transaction!</p>";
       exit();

   }

    $url = base_url() ."index.php/page/form/78000JOCASH_ADVANCE/edit/$id?act=edit";
    redirect($url);

 }

 function exportTaxUploadIn($fld_btid) {
  $filename = 'TaxInUpload-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t0.*,
                              SUBSTR(t0.fld_tax_no,-13) 'tax_number',
                              t0.fld_tax_month 'tax_month',
                              t0.fld_tax_year 'tax_year',
                              date_format(t0.fld_date,'%d/%m/%Y') 'date',
                              t1.fld_npwp 'npwp',
                              t1.fld_supplier 'supplier',
                              t1.fld_address 'address',
                              t0.fld_dpp,
                              t0.fld_ppn
                              from tbl_b1_tax t0
                              left join tbl_supp_de t1 on t1.fld_code = t0.fld_supp_code
                              where
                              t0.fld_taxnumberpostid=$fld_btid
                              ");
   echo 'FM;KD_JENIS_TRANSAKSI;FG_PENGGANTI;NOMOR_FAKTUR;MASA_PAJAK;TAHUN_PAJAK;TANGGAL_FAKTUR;NPWP;NAMA;ALAMAT_LENGKAP;JUMLAH_DPP;JUMLAH_PPN;JUMLAH_PPNBM;IS_CREDITABLE' . "\n";

   foreach($data->result() as $rdata) {
   echo "FM;01;0;$rdata->tax_number;$rdata->tax_month;$rdata->tax_year;$rdata->date;$rdata->npwp;$rdata->supplier;$rdata->address;$rdata->fld_dpp;$rdata->fld_ppn;0;1\n";
   }
 }

  function exportTaxUpload($fld_btid) {
    $filename = 'TaxOutUpload-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t0.*,
                              concat((select tx0.fld_tconfhdrno from tbl_taxnumber_conf tx0),t0.fld_tax_no) 'tax_number',
                              t0.fld_invoice_no 'trans_code',
			      date_format(t0.fld_date,'%m') 'month',
                              date_format(t0.fld_date,'%Y') 'year',
                              date_format(t0.fld_date,'%d/%m/%Y') 'date',
                              t1.fld_npwp 'npwp',
                              t1.fld_customer 'company',
                              t1.fld_address 'address',
                              CASE
                              WHEN t0.fld_flag_ocf =1 and t0.fld_type =4 THEN
                            (select sum(floor(tx0.fld_dpp)*0.1)from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type =4)
                              WHEN (t0.fld_flag_ocf =0 and t0.fld_type =4) and (t0.fld_dpp = t0.fld_ppn) THEN
                             (select sum(floor(tx0.fld_dpp))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type =4)
                              WHEN t0.fld_type =1 THEN
                             (select sum(floor(tx0.fld_dpp))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=1)
                              WHEN t0.fld_type =2 THEN
                             (select sum(floor(tx0.fld_dpp))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=2)
                              WHEN t0.fld_type =3 THEN
                             (select sum(floor(tx0.fld_dpp))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=3)
                              ELSE
                              floor(t0.fld_dpp)
                              END as 'dpp',
                              CASE
                              WHEN t0.fld_flag_ocf =1 and t0.fld_type =4 THEN
                             (select sum(floor(tx0.fld_ppn)*0.1)from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=4)
                              WHEN t0.fld_flag_ocf =0 and t0.fld_type =4 THEN
                             (select sum(floor(tx0.fld_ppn))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=4)
                              WHEN t0.fld_type =3 THEN
                             (select sum(floor(tx0.fld_ppn))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=3)
                              WHEN t0.fld_type =2 THEN
                             (select sum(floor(tx0.fld_ppn))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=2)
                              WHEN t0.fld_type =1 THEN
                             (select sum(floor(tx0.fld_ppn))from tbl_invoice_d tx0 where tx0.fld_code_h = t0.fld_code and tx0.fld_flag_ppn ='Y' and tx0.fld_type=1)
                              ELSE
                              floor(t0.fld_ppn)
                              END as 'ppn',
                              if((t0.fld_flag_ocf =1 and t0.fld_type =4),'04','01')'kode'
                              from tbl_invoice_h t0
                              left join tbl_cust_de t1 on t1.fld_code = t0.fld_cust_code
                              where
                              t0.fld_taxnumberpostid=$fld_btid
                              ");
    echo 'FK;KD_JENIS_TRANSAKSI;FG_PENGGANTI;NOMOR_FAKTUR;MASA_PAJAK;TAHUN_PAJAK;TANGGAL_FAKTUR;NPWP;NAMA;ALAMAT_LENGKAP;JUMLAH_DPP;JUMLAH_PPN;JUMLAH_PPNBM;ID_KETERANGAN_TAMBAHAN;FG_UANG_MUKA;UANG_MUKA_DPP;UANG_MUKA_PPN;UANG_MUKA_PPNBM;REFERENSI' . "\n";
    echo 'LT;NPWP;NAMA;JALAN;BLOK;NOMOR;RT;RW;KECAMATAN;KELURAHAN;KABUPATEN;PROPINSI;KODE_POS;NOMOR_TELEPON;;;;;;' . "\n";
    echo 'OF;KODE_OBJEK;NAMA;HARGA_SATUAN;JUMLAH_BARANG;HARGA_TOTAL;DISKON;DPP;PPN;TARIF_PPNBM;PPNBM;;;;;;;;;' . "\n";


    foreach($data->result() as $rdata) {
    echo "FK;$rdata->kode;0;$rdata->tax_number;$rdata->month;$rdata->year;$rdata->date;$rdata->npwp;$rdata->company;$rdata->address;" . number_format($rdata->dpp,0,'','') . ";" . number_format($rdata->ppn,0,'','') . ";0;0;0;0;0;0;$rdata->trans_code\n";
    echo "FAPR;PT. DUNIA EXPRESS;JL. AGUNG KARYA VII NO.1 JAKARTA UTARA;;;;;;;;;;;;;;;;;;\n";

#  echo "\"LT\";\"$rdata->npwp\";\"$rdata->company\";\"$rdata->address\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\"\n";
      ###Print Detail

      $item = array();
      $item = $this->db->query("select
     				t0.fld_name 'desc',
                                if(t0.fld_unit_price = 0,t0.fld_total_price,t0.fld_unit_price) unit_price,
                                if(t0.fld_qty = 0,1,t0.fld_qty) 'qty',
                                floor(if(t0.fld_flag_ocf =1,t0.fld_ppn,t0.fld_dpp)) 'dpp',
                                t0.fld_total_price,
    				floor(if(t0.fld_flag_ocf=1,t0.fld_ppn*0.1,t0.fld_ppn)) 'ppn'
    				from tbl_invoice_d t0
    				where
    				t0.fld_code_h='$rdata->fld_code'
                                and
                                t0.fld_type='$rdata->fld_type'
    				and
                                t0.fld_flag_ppn = 'Y'
    			        ");
      $item = $item->result();
      foreach($item as $ritem){
        echo "OF;;$ritem->desc;$ritem->unit_price;$ritem->qty;$ritem->fld_total_price;0;$ritem->dpp;$ritem->ppn;0;0;0;0;0;0;0;0;0;0;0;0\n";
      }

    }
  }

  function exportTaxUpload2($fld_btid) {
    $filename = 'TaxUpload-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t0.*,
                              substr(replace(replace(t0.fld_taxnumberno,'-',''),'.',''),4) 'tax_number',
                              substr(t0.fld_btno,5,3) 'trans_code',
			      date_format(t1.fld_btdt,'%m') 'month',
                              if(t1.fld_btp13=5,concat(t1.fld_btno,' ','PPN DIBEBASKAN ATAS JASA'),t1.fld_btno) 'btno',
                              t1.fld_btp54 'supportdocno',
                              date_format(t1.fld_btdt,'%Y') 'year',
                              date_format(t1.fld_btdt,'%d/%m/%Y') 'date',
                              substr(ifnull(t2.fld_becredno,'000000000000000'),1,15) 'npwp',
                              concat(if(t7.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t7.fld_benm) 'company',
                              ifnull(concat(t3.fld_beaddrplc,' ',t3.fld_beaddrstr),'DKI JAKARTA RAYA ') 'address',
                              if(t1.fld_btflag=1,if(t1.fld_btp13=1,floor(1*(select sum(tz0.fld_btamt01) from tbl_btd_finance tz0
                              where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)),floor((select sum(tz0.fld_btamt01) from tbl_btd_finance tz0
                              where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1))),
                              if(t1.fld_btp13=1,floor(1*(select sum(tz0.fld_btamt01)* t1.fld_btp03 from tbl_btd_finance tz0
                              where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)),floor((select sum(tz0.fld_btamt01)* t1.fld_btp03
                              from tbl_btd_finance tz0
                              where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)))) 'dpp',
                              if(t1.fld_btflag=1,floor(t1.fld_btuamt),floor(t1.fld_btuamt* t1.fld_btp03)) 'ppn',
                              #if(t1.fld_btflag=1,floor(0.1*(select sum(tz0.fld_btamt01) from tbl_btd_finance tz0
                              #where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)),floor(0.1*(select sum(tz0.fld_btamt01)* t1.fld_btp03
                              #from tbl_btd_finance tz0
                              #where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1))) 'ppn',
                              t1.fld_btp13,
                              if(t1.fld_btp13 = 1,'05',if(t1.fld_btp13 = 5,'08','01')) 'code'
                              from tbl_taxnumber t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btid
                              left join dnxapps.tbl_becred t2 on t2.fld_becredid = t1.fld_btp08
                              left join dnxapps.tbl_beaddr t3 on t3.fld_beaddrid = t1.fld_btp09
                              left join dnxapps.tbl_be t7 on t7.fld_beid = t1.fld_baidc
                              left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t7.fld_beprefix and t8.fld_tyid = 173
                              where
                              t0.fld_taxnumberpostid=$fld_btid
                                ");
    echo 'FK;KD_JENIS_TRANSAKSI;FG_PENGGANTI;NOMOR_FAKTUR;MASA_PAJAK;TAHUN_PAJAK;TANGGAL_FAKTUR;NPWP;NAMA;ALAMAT_LENGKAP;JUMLAH_DPP;JUMLAH_PPN;JUMLAH_PPNBM;ID_KETERANGAN_TAMBAHAN;FG_UANG_MUKA;UANG_MUKA_DPP;UANG_MUKA_PPN;UANG_MUKA_PPNBM;REFERENSI;NOMOR_DOKUMEN_PENDUKUNG' . "\n";
    echo 'LT;NPWP;NAMA;JALAN;BLOK;NOMOR;RT;RW;KECAMATAN;KELURAHAN;KABUPATEN;PROPINSI;KODE_POS;NOMOR_TELEPON;;;;;;' . "\n";
    echo 'OF;KODE_OBJEK;NAMA;HARGA_SATUAN;JUMLAH_BARANG;HARGA_TOTAL;DISKON;DPP;PPN;TARIF_PPNBM;PPNBM;;;;;;;;;' . "\n";
    foreach($data->result() as $rdata) {
      $code = $rdata->code;
      if($rdata->btp13 == 1) {
        $rdata->dpp = $rdata->dpp * 0.1;
      }

      if(strlen($rdata->address) > 3) {
        $rdata->address =  $rdata->address;
      } else {
        $rdata->address = "DKI JAKARTA RAYA";
      }


      echo "FK;$code;0;$rdata->tax_number;$rdata->month;$rdata->year;$rdata->date;$rdata->npwp;$rdata->company;$rdata->address;$rdata->dpp;$rdata->ppn;0;0;0;0;0;0;$rdata->btno;$rdata->supportdocno\n";
      echo "FAPR;PT. DUNIA EXPRESS;JL. AGUNG KARYA VII NO. 1 JAKARTA UTARA;;;;;;;;;;;;;;;;;;\n";
    #  echo "\"LT\";\"$rdata->npwp\";\"$rdata->company\";\"$rdata->address\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\"\n";
      ###Print Detail
      ###From dnxapps
      $item = array();

      $item = $this->db->query("select
    				t0.fld_btid ,
     				t0.fld_btdesc 'desc',
    				if(t1.fld_btflag=1,if(date_format(t1.fld_btdt, '%Y-%m-%d') >= '2022-04-01',if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.011),floor(t0.fld_btamt01 * 0.11)),if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.01),floor(t0.fld_btamt01 * 0.1))),
                                 if(date_format(t1.fld_btdt, '%Y-%m-%d') >= '2022-04-01',if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.011* t1.fld_btp03),floor(t0.fld_btamt01 * 0.11 * t1.fld_btp03)),if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.01* t1.fld_btp03),floor(t0.fld_btamt01 * 0.1 * t1.fld_btp03)))) 'ppn',
    				if('$currency' = 'IDR','Rp.','$') 'curr_code',
                                if(t1.fld_btflag=1,floor(t0.fld_btamt01),
                                floor(t0.fld_btamt01*t1.fld_btp03)) 'total_price',
    				if(t1.fld_btflag=1,if(t1.fld_btp13=1,floor(t0.fld_btamt01*1),floor(t0.fld_btamt01)),
                                if(t1.fld_btp13=1,floor(t0.fld_btamt01*t1.fld_btp03*0.1),floor(t0.fld_btamt01*t1.fld_btp03))) 'fld_btamt01',
                                if(t1.fld_btflag=1,floor(t0.fld_btuamt01),floor(t0.fld_btuamt01*t1.fld_btp03)) 'fld_btuamt01',
                                t0.fld_btqty01
    				from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
    				where
    				t0.fld_btidp='$rdata->fld_btid'
    				and t0.fld_btflag=1
    			       ");
      $item = $item->result();
      $tot_ppn = 0;
      foreach($item as $ritem){
        $tot_ppn =  $tot_ppn + $ritem->ppn;
      }

      foreach($item as $ritem){
        if( $tot_ppn != $rdata->ppn) {
          $ritem->ppn = floor($ritem->ppn);
        } else {
           $ritem->ppn =  $ritem->ppn;
        }
        echo "OF;;$ritem->desc;$ritem->fld_btuamt01;$ritem->fld_btqty01;$ritem->total_price;0;$ritem->fld_btamt01;$ritem->ppn;0;0;0;0;0;0;0;0;0;0;0;0\n";
      }


   }
  }



  function printSettlement($fld_btid) {
    $settlement_query =$this->db->query("
    select
    t1.fld_btno,
    t3.fld_benm,
    t2.fld_btno 'job',
    t5.fld_btno 'cash',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date'
    from tbl_btd_cost t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
    left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
    left join dnxapps.tbl_be t3 on t3.fld_beid=t2.fld_baidc
    left join tbl_btr t4 on t4.fld_btrdst = t1.fld_btid and t4.fld_btrdsttyid = 4
    left join tbl_bth t5 on t5.fld_btid = t4.fld_btrsrc
    where
    t0.fld_btidp='$fld_btid'
    ");
    $settle_data = $settlement_query->row();
    $settlement = $settlement_query->result();
    foreach ($settlement as $rsettlement) {
      $job_group[] = $rsettlement->job;
    }
    $pagenum =0;
    $job = array_unique($job_group);
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,14),$orientation='Portrait');
    $this->cezpdf->ezSetMargins(10,5,10,5);
      foreach ($job as $rjob) {
        $datadtl = array();
        $datadtl = $this->db->query("
        select
    t1.fld_btno,
    t3.fld_benm,
    t2.fld_btno 'job',
    t2.fld_btp23 'do',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    t4.fld_btinm 'cost',
    t5.fld_tyvalnm 'uom',
    t6.fld_tyvalnm 'curr',
    t0.fld_btqty01,
    format(t0.fld_btuamt01,2) 'amount',
    format(t0.fld_btamt01,2) 'subtotal',
    t0.fld_btuamt01,
    t0.fld_btamt01,
    t0.fld_currency
    from tbl_btd_cost t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
    left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
    left join dnxapps.tbl_be t3 on t3.fld_beid=t2.fld_baidc
    left join tbl_bti t4 on t4.fld_btiid=t0.fld_costtype
    left join tbl_tyval t5 on t5.fld_tyvalcd=t0.fld_btp01 and t5.fld_tyid=67
     left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_currency and t6.fld_tyid=39
    where
    t0.fld_btidp='$fld_btid'
    and
    t2.fld_btno = '$rjob'
    ");
    $datadtl = $datadtl->result_array();
    $count = count($datadtl);
    $idr = 0;
    $usd = 0;
    for ($i=0; $i<$count; ++$i) {
      ${$rjob . "count"} = ${$rjob . "count"} + 1;
        if($datadtl[$i]['fld_currency'] == 1) {
          $idr = $idr + $datadtl[$i]['fld_btamt01'];
        }
        if($datadtl[$i]['fld_currency'] == 2) {
          $usd = $usd + $datadtl[$i]['fld_btamt01'];
        }
      $datadtl[$i]['count'] = ${$rjob . "count"};
    }

        $this->cezpdf->ezText("Settlement Report", 13, array('justification' => 'center'));
        $this->cezpdf->ezSetDy(-25);
        $data_hdr = array(
                          array('row1'=>'Settlement No','row2'=>':','row3'=>$settle_data->fld_btno),
                          array('row1'=>'Date','row2'=>':','row3'=>$settle_data->date),
                          array('row1'=>'Customer ','row2'=>':','row3'=>$datadtl[0]['fld_benm']),
                          array('row1'=>'Job No ','row2'=>':','row3'=>$datadtl[0]['job']),
                          array('row1'=>'DO No','row2'=>':','row3'=>$datadtl[0]['do']),
                          array('row1'=>'Advance No','row2'=>':','row3'=>$settle_data->cash)
                          );
        $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>500,'fontSize'=>'11','cols'=>array('row1'=>array('width'=>100),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
        $this->cezpdf->ezSetDy(-15);


        $this->cezpdf->ezTable($datadtl,array('count'=>'No','cost'=>'Cost Description','uom'=>'UoM','curr'=>'Currency ','fld_btqty01'=>'Qty','amount'=>'Amount','subtotal'=>'Subtotal'),'',
   array('rowGap'=>'2','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'11',
   'cols'=>array('counteor'=>array('width'=>10),
   'cost'=>array('width'=>200),
   'uom'=>array('width'=>70,'justification'=>'center'),
   'curr'=>array('width'=>70,'justification'=>'center'),
   'qty'=>array('width'=>50,'justification'=>'center'),
   'amount'=>array('width'=>70,'justification'=>'right'),
   'subtotal'=>array('width'=>70,'justification'=>'right'),
    )));
    $this->cezpdf->ezSetDy(-15);
    $data_sum = array(
                          array('row1'=>'Total IDR','row2'=>number_format($idr,0,',','.')),
                          array('row1'=>'Total USD','row2'=>number_format($usd,0,',','.'))
                          );
        $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'2','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>280,'xOrientation'=>'right','width'=>500,'fontSize'=>'11','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>100,'justification'=>'right'))));

#        $this->cezpdf->ezSetDy(-15);
        $this->cezpdf->ezNewPage();
        $this->cezpdf->ezSetY(365);
//       $this->cezpdf->ezSetY(795);


     }
     header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=settlement.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

   function exportJO_home($fld_btid,$jonb) {
    $filename = 'ListJO-'.date('Ymd') . '.xls';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    $userid = $this->session->userdata('ctid');
    #$jo = ($_GET['jo'] == '') ? '' : $_GET['jo'];
    #$jonb =  $this->uri->segment(3);
    $data = $this->db->query("select
date_format(t0.fld_btdt,'%Y-%m-%d') 'JODate',
date_format(t0.fld_btdt,'%Y-%m') 'dtjo',
t0.fld_btno 'JONumber',
t6.fld_tyvalnm 'ImportType',
if(t0.fld_btidp=1,'YES','NO') 'PartOff',
t1.fld_benm 'Customer',
t0.fld_baidc'custid',
t5.fld_tyvalnm 'DocType',
t0.fld_btp07 'House',
t0.fld_btp08 'Master',
t0.fld_btnoalt 'AJUNumber',
t0.fld_btid,
t0.fld_baidv
from tbl_bth t0
left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
left join hris.tbl_emp t2 on t2.fld_empid=t0.fld_baidp
left join tbl_tyval t3 on t3.fld_tyvalcd=t0.fld_btstat and t3.fld_tyid=16
left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btflag and t4.fld_tyid=53
left join tbl_tyval t5 on t5.fld_tyvalcd=t0.fld_btiid and t5.fld_tyid=15
left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_bttax and t6.fld_tyid=109
left join tbl_bth t7 on t7.fld_btnoreff = t0.fld_btno and t7.fld_bttyid = 41
where
t0.fld_bttyid in (1,6,10)
and t0.fld_btstat !=5
and ifnull(t7.fld_btid,0) = 0
and t0.fld_btdt > '2018-01-01 00:00:00'
and t0.fld_btdt >= '2018-09-17'
and (t1.fld_bep12 = '$userid' or t1.fld_bep13 = '$userid')
and if('$jonb' = '',1,t0.fld_btno like '$jonb')
                              ");
    echo "<table border = 1>
           <tr>
           <td> JO Date</td>
           <td>JO Number</td>
           <td>Import Type</td>
           <td>Part off?</td>
           <td>Customer</td>
           <td>Doc type</td>
           <td>House B/L</td>
           <td>Master B/L</td>
           <td>AJU Number</td>
           </tr>";
   foreach($data->result() as $rdata) {
   echo "<tr>
		<td>" . $rdata->JODate . "</td>
             <td>" . $rdata->JONumber . "</td>
		<td>" . $rdata->ImportType . "</td>
                <td>" . $rdata->PartOff . "</td>
                <td>" . $rdata->Customer . "</td>
                <td>" . $rdata->DocType . "</td>
                <td>" . $rdata->House . "</td>
                <td>" . $rdata->Master . "</td>
                <td>" . $rdata->AJUNumber . "</td>
         </tr>";
   }

   echo "</table>";
  }

   function exportInv_penalty($fld_btid,$jonb) {
    $filename = 'Invoice-Penalty-'.date('Ymd') . '.xls';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    $userid = $this->session->userdata('ctid');
    #$jo = ($_GET['jo'] == '') ? '' : $_GET['jo'];
    #$jonb =  $this->uri->segment(3);
    $data = $this->db->query("select
    t1.fld_btno'INV_NUMBER',
    #t2.fld_benm 'CUSTOMER',
    concat(if(t2.fld_beprefix > 0,concat(t7.fld_tyvalnm, '. '),''), t2.fld_benm) 'CUSTOMER',
    date_format(t1.fld_btdt,'%Y-%m-%d')'INV_DATE',
    t1.fld_btp01 'REC_BY',
    date_format(t4.fld_btdtp,'%Y-%m-%d')'REC_DATE',
    if(t1.fld_btp31 in (0,''),t2.fld_bep01,t5.fld_tyvalnm) 'TOP',
    t6.fld_tyvalnm 'CUR',
    if(t1.fld_btflag = 1,t1.fld_btamt,0) 'IDR',
    if(t1.fld_btflag = 2,t1.fld_btamt,0)'USD',
    if(t1.fld_btflag = 3,t1.fld_btamt,0)'BP',
   t1.fld_btdesc 'REMARKS',
    if(t1.fld_btp31 in (0,''),datediff(now(),date_format(date_add(t1.fld_btdtso, interval t2.fld_bep01 day) + 1,'%Y-%m-%d')),datediff(now(),date_format(date_add(t1.fld_btdtso, interval t5.fld_tyvalnm day) + 1,'%Y-%m-%d'))) 'aging2',
    t0.fld_btnoreff 'noreff',
    t0.fld_btp08 'aging',
if(t1.fld_btp31 in (0,''),date_format(date_add(t1.fld_btdtso, interval t2.fld_bep01 day) + 1,'%Y-%m-%d'),date_format(date_add(t1.fld_btdtso, interval t5.fld_tyvalnm day) + 1,'%Y-%m-%d')) 'due_date',
ifnull( (0.03 * if(t1.fld_btp31 in (0,''),datediff(now(),date_format(date_add(t1.fld_btdtso, interval t2.fld_bep01 day) + 1,'%Y-%m-%d')),datediff(now(),date_format(date_add(t1.fld_btdtso, interval t5.fld_tyvalnm day) + 1,'%Y-%m-%d'))) * ifnull(t1.fld_btbalance - t1.fld_btp28,0))/30,0)'Penalty1',
    (select date_format(tz1.fld_btdt,'%Y-%m-%d') from tbl_btd_finance tz0 left join tbl_bth tz1 on tz1.fld_btid = tz0.fld_btidp where tz1.fld_bttyid = 45 and tz0.fld_btreffid = t0.fld_btreffid and tz1.fld_btdt > 0)'payDate',
   # (select tz1.fld_btno from tbl_btd_finance tz0 left join tbl_bth tz1 on tz1.fld_btid = tz0.fld_btidp where tz1.fld_bttyid = 45 and tz0.fld_btreffid = t0.fld_btreffid and tz1.fld_btdt > 0)'payNo',
    t0.fld_btamt01 'Penalty',
    t0.fld_coaid 'coaid'

    from tbl_btd_finance t0
    left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid and t1.fld_bttyid = 41
    left join dnxapps.tbl_be t2 on t2.fld_beid = t1.fld_baidc and t2.fld_betyid=5 and t2.fld_bestat = 1
    left join tbl_btd_invdel t3 on t3.fld_btiid = t1.fld_btid
    left join tbl_bth t4 on t4.fld_btid = t3.fld_btidp
    left join tbl_tyval t5 on t5.fld_tyvalcd = t1.fld_btp31 and t5.fld_tyid = 93
    left join tbl_tyval t6 on t6.fld_tyvalcd = t1.fld_btflag and t6.fld_tyid = 39
    left join dnxapps.tbl_tyval t7 on t7.fld_tyvalcd = t2.fld_beprefix and t7.fld_tyid=173
    where
    t0.fld_btidp = $fld_btid and t4.fld_bttyid = 58
    ");
    echo "<table border = 1>
           <tr>
           <td rowspan = 2>No</td>
           <td rowspan = 2> Invoice Number</td>
           <td rowspan = 2>Customer</td>
           <td rowspan = 2>Invoice Date</td>
           <td rowspan = 2>Receive Date</td>
           <td rowspan = 2>Receive by</td>
           <td rowspan = 2>TOP</td>
           <td rowspan = 2>Due date</td>
           <td rowspan = 2>Currancy</td>
           <td colspan = 2 >Invoice Amount</td>
           <td rowspan = 2>Payment Date</td>
           <td rowspan = 2>Remarks</td>
	   <td rowspan = 2>Aging</td>
           <td rowspan = 2>Penalty</td>
           </tr>
          <td>IDR</td>
           <td>USD</td>
           </tr>";
   $no =0;
   foreach($data->result() as $rdata) {
   $no = $no +1;
   echo "<tr>   <td >" . $no ." </td>
                <td>" . $rdata->INV_NUMBER . "</td>
                <td>" . $rdata->CUSTOMER . "</td>
                <td>" . $rdata->INV_DATE . "</td>
                <td>" . $rdata->REC_DATE . "</td>
                <td>" . $rdata->REC_BY . "</td>
                <td>" . $rdata->TOP . "</td>
                <td>" . $rdata->due_date . "</td>
		<td>" . $rdata->CUR . "</td>
                <td>" . $rdata->IDR . "</td>
                <td>" . $rdata->USD . "</td>
                <td>" . $rdata->payDate . "</td>
                <td>" . $rdata->REMARKS . "</td>
                <td>" . $rdata->aging . "</td>
		<td>" . $rdata->Penalty . "</td>
              </tr>";
      $total_idr = $total_idr + $rdata->IDR;
      $total_usd = $total_usd + $rdata->USD;
      $totalp_idr =  $totalp_idr + $rdata->Penalty;

   }
   echo "<tr>";
   echo "<td colspan=9 align='center'>Total</td>";
   echo "<td align ='right'>" . $total_idr . "</td>";
   echo"<td colspan =4></td>";
   echo "<td>" . $totalp_idr . "</td>";
   echo "</tr>";
   echo "</table>";

  }
   function insertContainerDeposit($btid) {
     $ctid  = $this->session->userdata('ctid');
     $divid  = $this->session->userdata('divid');
     $query=$this->db->query("select t0.*, t1.fld_btno,t1.fld_baidc,if(t0.fld_btamt01>0 ,t0.fld_btamt01,fld_btuamt01) 'deposit_amt',
                              t2.fld_btdt 'receipt_date'
                              from tbl_btd_deposit t0
	                      left join tbl_bth t1 on t1.fld_btid=t0.fld_btp06
                              left join tbl_bth t2 on t2.fld_btid=t0.fld_btidp
	                      where t0.fld_btidp=$btid");
     foreach ($query->result() as $row){
       $trans_no = $this->mkautono2(2,11);
       $a=$this->db->query("SELECT distinct t0.*
			    FROM tbl_bth t0
			    left join tbl_btd_finance t1 on t1.fld_btidp=t0.fld_btid
			    WHERE t0.fld_bttyid=11 and t0.fld_btp25='$row->fld_btno'");
       $a1 = $a->row();

       if ($a->num_rows() < 1) {
                        #echo "$a1->fld_baidc###$row->fld_btamt01";
                        # exit();
			$sql="insert into tbl_bth (fld_baidc,fld_baido,fld_baidp,fld_baidv,fld_btamt,fld_btamt01,fld_btamt02,fld_btbalance,fld_btdesc
			,fld_btdt,fld_btdtsa,fld_btid,fld_btiid,fld_btno,fld_btnoalt,fld_btnoreff,fld_btp01,fld_btp02,fld_btp03,fld_btp04,fld_btp05
			,fld_btp06,fld_btp07,fld_btp08,fld_btp09,fld_btp10,fld_btp11,fld_btp12,fld_btp13,fld_btp15,fld_btp16,fld_btp18,fld_btp19,fld_btp20
			,fld_btp21,fld_btp22,fld_btp23,fld_btp24,fld_btp25,fld_btp26,fld_btp27,fld_btp28,fld_btp29,fld_btqty,fld_btstat,fld_bttax,fld_bttyid)
			select fld_baidc,2,'$ctid','$divid','$row->fld_btamt01',null,null,null,null,now(),null,null,fld_btiid,'$trans_no',fld_btnoalt,fld_btnoreff,'$row->deposit_amt',0,null,null,null
			,null,fld_btp07,fld_btp08,fld_btp06,'$row->receipt_date',null,null,fld_btp13,fld_btp15,null,fld_btp18,null,fld_btp20,null,null,null,
                        '$row->fld_btamt01',fld_btno,fld_btid,null
			,null,null,fld_btqty,1,fld_bttax,11
			from tbl_bth A where A.fld_btid=$row->fld_btp06";
			$this->db->query($sql);
			$fup_lid = $this->db->insert_id();
			$this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($btid,$fup_lid,11)");
			$sql="insert into tbl_btd_container(fld_btidp, fld_btp01,fld_btp02,fld_btp03,fld_btp04,fld_btp05,fld_btp06,fld_btp07,fld_btpdt01,fld_btpdt02,fld_btpdt03,fld_btpdt04,
			fld_btpdt05,fld_btpdt06,fld_btpdt07,fld_contnum,fld_contsize,fld_conttype)
			select $fup_lid, fld_btp01,fld_btp02,fld_btp03,fld_btp04,fld_btp05,fld_btp06,fld_btp07,fld_btpdt01,fld_btpdt02,fld_btpdt03,fld_btpdt04,
			fld_btpdt05,fld_btpdt06,fld_btpdt07,fld_contnum,fld_contsize,fld_conttype
			from tbl_btd_container where fld_btidp=$row->fld_btp06";
			$this->db->query($sql);

			$sql="insert into tbl_btd_finance
			(fld_btidp,fld_btcmt,fld_btdesc,fld_btp03, fld_btp04, fld_btp05,fld_btqty01, fld_btreffid,fld_btamt01,fld_btuamt01)
			 select distinct
                         ".$fup_lid.",
                         t1.fld_btno 'JONo',
                         t2.fld_btno 'COJNo',
                         t0.fld_btdesc 'Desc',
                         t5.fld_tyvalnm 'Currency',
                         concat(''),
                         t1.fld_btid,
                         t0.fld_btqty01 'Qty',
                         t0.fld_btamt01 idr,
                         t0.fld_btuamt01 usd
                         from tbl_btd_finance t0
                         left join tbl_bth t1 on t1.fld_btno=t0.fld_btnoreff and t1.fld_bttyid in (1,65)
                         left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                         left join dnxapps.tbl_be t3 on t3.fld_beid = t1.fld_baidc
                         left join tbl_be t4 on t4.fld_beid=t1.fld_btp15 and t4.fld_betyid=8
                         left join tbl_tyval t5 on t5.fld_tyvalcd=t2.fld_btflag and t5.fld_tyid=39
                         WHERE
                         t2.fld_bttyid in (46,51,95,54)
                         and
                         t0.fld_coaid = 703
                         and
                         t2.fld_btstat = 3
                         and
                         (t0.fld_btdesc like '%jaminan%' or t0.fld_btdesc like '%repair%')
                         and
                         date_format(t1.fld_btdt,'%Y%m') >= 201712
                         and
                         t0.fld_btid=".$row->fld_btreffid."
                         union
                         select distinct
                         ".$fup_lid.",
                         t1.fld_btnoreff 'JONo',
                         t2.fld_btno 'COJNo',
                         t0.fld_btp01 'Desc',
                         t5.fld_tyvalnm 'Currency',
                         concat(''),
                         t1.fld_btid,
                         0 'Qty',
                         t0.fld_btamt01 idr,
                         0 usd
                         from tbl_btd_invdel t0
                         left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid and t1.fld_bttyid in (41,82)
                         left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                         left join dnxapps.tbl_be t3 on t3.fld_beid = t1.fld_baidc
                         #left join tbl_be t4 on t4.fld_beid=t1.fld_btp15 and t4.fld_betyid=8
                         left join tbl_tyval t5 on t5.fld_tyvalcd=t0.fld_currency and t5.fld_tyid=39
                         WHERE
                         t2.fld_bttyid =59
                         and
                         t2.fld_btstat = 3
                         and
                         (t0.fld_btp01 like '%jaminan%' or t0.fld_btp01 like '%repair%')
                         and
                         date_format(t1.fld_btdt,'%Y%m') >= 201712
                         and
                         t0.fld_btid=".$row->fld_btreffid."";
			$this->db->query($sql);
        } else { // update demurage/detention/repair
	foreach ($a->result() as $b)
	  $sql="insert into tbl_btd_finance
			(fld_btidp,fld_btcmt,fld_btdesc,fld_btp03, fld_btp04, fld_btp05,fld_btqty01, fld_btreffid,fld_btamt01,fld_btuamt01)
			 select distinct
                         ".$b->fld_btid.",
                         t1.fld_btno 'JONo',
                         t2.fld_btno 'COJNo',
                         t0.fld_btdesc 'Desc',
                         t5.fld_tyvalnm 'Currency',
                         concat(''),
                         t1.fld_btid,
                         t0.fld_btqty01 'Qty',
                         t0.fld_btamt01 idr,
                         t0.fld_btuamt01 usd
                         from tbl_btd_finance t0
                         left join tbl_bth t1 on t1.fld_btno=t0.fld_btnoreff and t1.fld_bttyid in (1,65)
                         left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                         left join dnxapps.tbl_be t3 on t3.fld_beid = t1.fld_baidc
                         left join tbl_be t4 on t4.fld_beid=t1.fld_btp15 and t4.fld_betyid=8
                         left join tbl_tyval t5 on t5.fld_tyvalcd=t2.fld_btflag and t5.fld_tyid=39
                         WHERE
                         t2.fld_bttyid in (46,51,95,54)
                         and
                         t0.fld_coaid = 703
                         and
                         t2.fld_btstat = 3
                         and
                         (t0.fld_btdesc like '%jaminan%' or t0.fld_btdesc like '%repair%')
                         and
                         date_format(t1.fld_btdt,'%Y%m') >= 201712
                         and
                         t0.fld_btid=".$row->fld_btreffid."
                          union
                         select distinct
                         ".$b->fld_btid.",
                         t1.fld_btnoreff 'JONo',
                         t2.fld_btno 'COJNo',
                         t0.fld_btp01 'Desc',
                         t5.fld_tyvalnm 'Currency',
                         concat(''),
                         t1.fld_btid,
                         0 'Qty',
                         t0.fld_btamt01 idr,
                         0 usd
                         from tbl_btd_invdel t0
                         left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid and t1.fld_bttyid in (41,82)
                         left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                         left join dnxapps.tbl_be t3 on t3.fld_beid = t1.fld_baidc
                         #left join tbl_be t4 on t4.fld_beid=t1.fld_btp15 and t4.fld_betyid=8
                         left join tbl_tyval t5 on t5.fld_tyvalcd=t0.fld_currency and t5.fld_tyid=39
                         WHERE
                         t2.fld_bttyid =59
                         and
                         t2.fld_btstat = 3
                         and
                         (t0.fld_btp01 like '%jaminan%' or t0.fld_btp01 like '%repair%')
                         and
                         date_format(t1.fld_btdt,'%Y%m') >= 201712
                         and
                         t0.fld_btid=".$row->fld_btreffid."
                         ";
			$this->db->query($sql);

           $this->db->query("update tbl_bth set
                       fld_btp01=(select sum(fld_btamt01) from tbl_btd_finance
                       where fld_btidp = $b->fld_btid),
                       fld_btamt = ifnull(fld_btp01,0)+ifnull(fld_btp02,0)
                       where fld_btid = $b->fld_btid limit 1");

           $this->db->query("update tbl_bth set fld_btp24 = if(fld_btp23>0,ifnull(fld_btamt,0)-ifnull(fld_btp23,0),fld_btamt)
                             where fld_btid = $b->fld_btid limit 1");

           $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($btid,$b->fld_btid,11)");
	}

      }

      // update ctd
        $cekcde = $this->db->query("SELECT fld_btp23 FROM tbl_bth WHERE fld_btid = '$btid' AND fld_bttyid=60 LIMIT 1")->row();

        $btrlist = $this->db->query("SELECT fld_btrdst FROM tbl_btr WHERE fld_btrsrc = '$btid' AND fld_btrdsttyid=11 GROUP by fld_btrdst")->result();
        if ($cekcde->fld_btp23 == 1) {
          foreach ($btrlist as $key => $item) {
            $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '1' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
          }
        } else {
          foreach ($btrlist as $key => $item) {
            $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '0' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
          }
        }

  }

  function updateInvFromBIT($btid){
    echo "Waiting this process ...";
    $ctid  = $this->session->userdata('ctid');
    $divid  = $this->session->userdata('divid');

    $data = $this->db->query("SELECT t0.fld_btno 'btno',t0.fld_btidp 'idInv' ,t0.fld_baidc 'customer',t0.fld_btiid 'vGroup',t0.fld_btamt 'amount',t0.fld_btp01 'joNo',date_format(t0.fld_btdt,'%Y-%m-%d')'billDate',t0.fld_baidp 'postBy' FROM  tbl_bth t0 WHERE t0.fld_btid = $btid and t0.fld_bttyid = 26 limit 1");

    $rdata = $data->row();
    $idInv = $rdata->idInv;
    if($idInv > 0){
      $inv = $this->db->query("SELECT t0.fld_btid 'id', t0.fld_btno 'btno', t0.fld_btstat 'stat', t0.fld_baidc 'customer' FROM tbl_bth t0 WHERE t0.fld_btid = $idInv and t0.fld_bttyid = 41  limit 1");
      $rinv = $inv->row();
    }

    $stat = $rinv->stat;

    if ($stat == 1 || $stat == 2) {
      $trkBill = $this->db->query("
        SELECT
        t2.fld_btamt01 'amount',
        COUNT(t2.fld_btid) 'qty',
        SUM(t2.fld_btamt01) 'sum',
        t6.*
        from
        tbl_btd t0
        left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
        left join tbl_trk_billing t2 on t2.fld_btreffid = t0.fld_btid
        left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd=t1.fld_btp15 and t6.fld_tyid=28
        where
        t1.fld_bttyid = 80
        and
        t2.fld_btidp = $btid
        AND
        t6.fld_tyid
        GROUP BY t6.fld_tyvalcd, t2.fld_btamt01
      ");

      foreach ($trkBill->result() as $key => $value) {
      $inBtdFinance = $this->db->query("INSERT INTO `tbl_btd_finance`(`fld_coaid`, `fld_bedivid`, `fld_btidp`, `fld_btdesc`, `fld_locid`, `fld_btuamt01`, `fld_btqty01`, `fld_btamt01`, `fld_btreffid`, `fld_btnoreff`) VALUES (786, $rdata->vGroup, '$rinv->id', 'Trucking Charge', 1, $value->amount, $value->qty, $value->sum, '$btid', '$rdata->joNo')");
      }

      $inBtr = $this->db->query("INSERT INTO `tbl_btr` (`fld_btrsrc`, `fld_btrdst`, `fld_btrdsttyid`) VALUES ('$rinv->id', '$btid', '67')");
    }

  }

  function updateInvFromBITRev($btid){
    echo "Waiting this process ...";
    $dataBIT = $this->db->query("SELECT fld_btidp 'idInvoice' FROM `tbl_bth` WHERE `fld_btid` = $btid AND `fld_bttyid` = 26 limit 1")->row();
    $dataDSV = $this->db->query("SELECT fld_btstat 'statDSV' FROM `tbl_bth` WHERE `fld_btid` = $dataBIT->idInvoice AND `fld_bttyid` = 41 limit 1")->row();

    if ($dataDSV->statDSV == 1 || $dataDSV->statDSV == 2) {
      $delBtdFin = $this->db->query("DELETE FROM tbl_btd_finance WHERE fld_btidp=$dataBIT->idInvoice AND fld_btreffid=$btid LIMIT 100");
      $delBrt = $this->db->query("DELETE FROM tbl_btr WHERE fld_btrsrc=$dataBIT->idInvoice AND fld_btrdst=$btid LIMIT 1");
    }
  }

  function appvPDS($btid){
    echo "Waiting this process ...";
    $dataPDS = $this->db->query("SELECT fld_btid 'idPDS', fld_btstat 'statPDS' FROM `tbl_bth` WHERE `fld_btid` = $btid AND `fld_bttyid` = 91 limit 1")->row();

    $updtPDS = $this->db->query("UPDATE tbl_bth SET fld_btp02=CURRENT_TIMESTAMP() WHERE fld_btid=$btid LIMIT 1");
  }

  function UpdateContainerDeposit($btid,$bttyid,$mode) {
    $ctid  = $this->session->userdata('ctid');
    $divid  = $this->session->userdata('divid');
    $query=$this->db->query("select t0.*,t0.fld_btp05,t0.fld_btamt01,t1.fld_btp01,t1.fld_btdt,t1.fld_btdtsa,t0.fld_btp04,t0.fld_btp03
                             from tbl_btd_deposit t0
                             left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                             where t0.fld_btidp='$btid'");

     if($mode == 'aprv') {
      foreach ($query->result() as $row){
        if($bttyid == 63) {
          $this->db->query("update tbl_bth set fld_btp30= '$row->fld_btdt' where fld_btid='$row->fld_btreffid' limit 1");
          $this->db->query("update tbl_btd_container set fld_btpdt07= '$row->fld_btdt' where fld_btidp='$row->fld_btreffid' AND fld_contnum='$row->fld_btp03' limit 1");
          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }
         if($bttyid == 61) {
          $this->db->query("update tbl_bth set fld_btp29= '$row->fld_btdtsa',fld_btp18= '$row->fld_btdt',fld_btp12 = $row->fld_btp01,
                            fld_btp11=$row->fld_btp04
                            where fld_btid='$row->fld_btreffid' limit 1");
          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }

        if($bttyid == 64) {
          $this->db->query("update tbl_bth set fld_btp31= '$row->fld_btdt' where fld_btid='$row->fld_btreffid' limit 1");
          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }

        // settlement refund
        if($bttyid == 66) {
          $this->db->query("update tbl_bth set fld_btp27 = $row->fld_btp03,
                            fld_btp28 = '$row->fld_btp04',
                            fld_btp14= ifnull(fld_btp14,0) + $row->fld_btamt01
                            where fld_btid='$row->fld_btreffid' limit 1");
          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }
        // settlement cost
        if($bttyid == 87) {
          $this->db->query("update tbl_bth set fld_btp14= ifnull(fld_btp14,0) + $row->fld_btamt01
                            where fld_btid='$row->fld_btreffid' limit 1");
          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }

        if($bttyid == 84) {
          $this->db->query("update tbl_bth set fld_btp22= '$row->fld_btdt' where fld_btid='$row->fld_btreffid' limit 1");//EIR Return
          $this->db->query("update tbl_bth set fld_btp17= '$row->fld_btp05' where fld_btid='$row->fld_btreffid' limit 1");//EIR Depo

          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }

      }


    } else if ($mode == 'rev') {
      foreach ($query->result() as $row) {
        if($bttyid == 63) {
          $this->db->query("update tbl_btd_container set fld_btpdt07= '' where fld_btidp='$row->fld_btreffid' AND fld_contnum='$row->fld_btp03' limit 1");
          $this->db->query("delete  from tbl_btr where fld_btrsrc = $row->fld_btreffid and fld_btrdst= $btid limit 1");
        }

        if($bttyid == 64) {
         $this->db->query("update tbl_bth set fld_btp31= '' where fld_btid='$row->fld_btreffid' limit 1");
         $this->db->query("delete from tbl_btr where fld_btrsrc = $row->fld_btreffid and fld_btrdst= $btid limit 1");
        }


       if($bttyid == 66) {
          $this->db->query("update tbl_bth set fld_btp14= ifnull(fld_btp14,0) - $row->fld_btamt01
                            where fld_btid='$row->fld_btreffid' limit 1");
          $this->db->query("delete from tbl_btr where fld_btrsrc = $row->fld_btreffid and fld_btrdst= $btid limit 1");

       }

       if($bttyid == 87) {
          $this->db->query("update tbl_bth set fld_btp14= ifnull(fld_btp14,0) - $row->fld_btamt01
                            where fld_btid='$row->fld_btreffid' limit 1");
          $this->db->query("delete from tbl_btr where fld_btrsrc = $row->fld_btreffid and fld_btrdst= $btid limit 1");

       }


        if($bttyid == 84) {
         $this->db->query("update tbl_bth set fld_btp22= '' where fld_btid='$row->fld_btreffid' limit 1");//EIR Return
         $this->db->query("update tbl_bth set fld_btp17= '' where fld_btid='$row->fld_btreffid' limit 1");//EIR Depo
         $this->db->query("delete from tbl_btr where fld_btrsrc = $row->fld_btreffid and fld_btrdst= $btid limit 1");
        }

      }


    }
      else if ($mode == 'req') {
            foreach ($query->result() as $row) {
        // settlement cost
        if($bttyid == 87) {
          $this->db->query("update tbl_bth set fld_btp14= ifnull(fld_btp14,0) + $row->fld_btamt01
                            where fld_btid='$row->fld_btreffid' limit 1");
          $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst) values($row->fld_btreffid,$btid)");
        }

       }

    }


  }

  function UpdateDepositDate($btid){
      $query=$this->db->query("select t0.*, t1.fld_btdtp, t1.fld_btdtsa from tbl_btd_deposit t0 left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                               where t0.fld_btidp='$btid'");
       foreach ($query->result() as $row){
         $this->db->query("update tbl_bth set fld_btp22='$row->fld_btdtp',fld_btp29='$row->fld_btdtsa' where fld_btid='$row->fld_btreffid' limit 1");

       }
     $url = base_url() . "index.php/page/form/78000DEPOSIT_SHIP/edit/$btid?act=edit";
     redirect($url);

  }

  #function mail
  function sendmail($subject,$message)
	{
	$curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://172.17.1.17/index.php/email/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n\t\"from\": \"application@dunextr.com\",\n\t\"from_name\": \"Exim Application\",\n\t\"to\": \"trisnanto@dunextr.com,truck_planner@dunextr.com\",\n\t\"subject\": \"$subject\",\n\t\"message\": \"$message\"\n}",
        CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
        ),
));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

	}
  function sendmailexp($subject,$message)
        {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://172.17.1.17/index.php/email/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n\t\"from\": \"application@dunextr.com\",\n\t\"from_name\": \"Exim Application\",\n\t\"to\": \"depo@dunextr.com,dika@dunextr.com,truck_planner@dunextr.com,monitoring_exp@dunextr.com,melly.friska@dunextr.com,expcs.04@dunextr.com,yuhendra@dunextr.com,teguh@dunextr.com,expdoc.06@dunextr.com,expopt.01@dunextr.com,export@dunextr.com,rachmi@dunextr.com\",\n\t\"subject\": \"$subject\",\n\t\"message\": \"$message\"\n}",
        // CURLOPT_POSTFIELDS =>"{\n\t\"from\": \"application@dunextr.com\",\n\t\"from_name\": \"Exim Application\",\n\t\"to\": \"depo@dunextr.com,dika@dunextr.com,truck_planner@dunextr.com,monitoring_exp@dunextr.com,melly.friska@dunextr.com,expcs.04@dunextr.com,yuhendra@dunextr.com,teguh@dunextr.com,asep@dunextr.com,expdoc.06@dunextr.com,expopt.01@dunextr.com,rachmi@dunextr.com\",\n\t\"subject\": \"$subject\",\n\t\"message\": \"$message\"\n}",
        CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
        ),
));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

        }

 function sendmailimp($subject,$message)
        {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://172.17.1.17/index.php/email/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n\t\"from\": \"application@dunextr.com\",\n\t\"from_name\": \"Exim Application\",\n\t\"to\": \"truck_planner@dunextr.com,ridho@dunextr.com,juned@dunextr.com,eko@dunextr.com,impcs.15@dunextr.com,impcs.12@dunextr.com\",\n\t\"subject\": \"$subject\",\n\t\"message\": \"$message\"\n}",
        CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
        ),
));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        }
 function mailtris($subject,$message)
        {
                $this->load->library('email');
                $result = $this->email
                ->from('application@dunextr.com')
                //->reply_to($this->input->post('email'), $this->input->post('name'))   // Optional, an account where a human being reads.
                ->to('trisnanto@dunextr.com')
                ->subject(''.$subject.'')
                ->message($message)
                ->send();

                        if($result) {
                          $this->email->print_debugger();
                        } else {
                          $this->email->print_debugger();
                        }
        }

  function sendmailexim($to,$subject,$message)
	{
         $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://172.17.1.17/index.php/email/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n\t\"from\": \"application@dunextr.com\",\n\t\"from_name\": \"Exim Application\",\n\t\"to\": \"$to\",\n\t\"subject\": \"$subject\",\n\t\"message\": \"$message\"\n}",
        CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
        ),
));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
	}

  function sendmailtrk($to,$subject,$message)
        {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://172.17.1.17/index.php/email/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n\t\"from\": \"application@dunextr.com\",\n\t\"from_name\": \"Exim Application\",\n\t\"to\": \"$to\",\n\t\"subject\": \"$subject\",\n\t\"message\": \"$message\"\n}",
        CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
        ),
));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        }

  function cekClosingDate($btid) {
     ###Cek Monthly Closing
    $closing  = $this->db->query("select concat(fld_btp01,'-31') 'periode', fld_btp01  from tbl_bth where fld_bttyid = 67  and fld_btstat = 3 order by fld_btp01 desc limit 1");
    $closing = $closing->row();

    $cek = $this->db->query("select fld_btdtp 'posting' from tbl_bth where fld_btid = $btid ");
    $cek = $cek->row();

    if ($cek->posting <= $closing->periode) {
      $this->ffis->message("Journal periode $closing->fld_btp01  have been closed ..... ");
    }
  }

  function getTaxNumberBackup($fld_btid,$fld_btidp) {
    $cek = $this->db->query("select count(1) 'ctn' from tbl_btd_finance where fld_btidp='$fld_btid' and fld_btflag=1");
    $cek = $cek->row();
    $cekheader = $this->db->query("select fld_btp13 'taxbatam' from tbl_bth where fld_btid='$fld_btid'");
    $cekheader = $cekheader->row();
    if($cek->ctn > 0 || $cekheader->taxbatam == 3) {
      $data = $this->db->query("select t0.*,date_format(t0.fld_btdt,'%Y-%m-%d') 'dtsa' from tbl_bth t0 where fld_btid='$fld_btid'");
      $data = $data->row();
      $nowdt = date('Y-m-d');
      $firstdt = date('Y-m-01');
      #$firstdt = date('2015-01-01');
      if($data->fld_bttaxno <> " ") { ### Jika Memakai Nomor Backup
        $conf = $this->db->query("select if($data->fld_btp13 = 1,replace( fld_tconfhdrno, '010.', '050.' ) ,if($data->fld_btp13 = 3,replace( fld_tconfhdrno, '010.', '070.' ), if($data->fld_btp13 = 4,replace( fld_tconfhdrno, '010.', '090.' ),if($data->fld_btp13 = 5,replace( fld_tconfhdrno, '010.', '080.' ),fld_tconfhdrno)))) 'fld_tconfhdrno' ,fld_tconfnsa,fld_tconfnso,fld_tconfdtsa,fld_tconfdtso,fld_tconfid
                                  from tbl_taxnumber_conf
                                  where
                                  fld_tconfstat=4 order by fld_tconfid  limit 1");
        $conf = $conf->row();
        #$data = $data->row();
        $last_no = $this->db->query("select (fld_taxnumberinc + 1) 'number' from tbl_taxnumber t0
                                   where t0.fld_tconfid = $conf->fld_tconfid order by fld_taxnumberid desc limit 1");
        $last_no = $last_no->row();
        if($last_no->number < $conf->fld_tconfnsa) {
          $inc_number = str_pad($conf->fld_tconfnsa, 8, "0", STR_PAD_LEFT);
          $number = $conf->fld_tconfhdrno . "." . $inc_number;
           $this->db->query("insert into tbl_taxnumber (fld_taxnumberinc,fld_taxnumberno,fld_taxnumberdt,fld_btno,fld_tconfid,fld_btid,fld_taxnumberdb) values
                        ('$inc_number','$number','$data->fld_btdt','$data->fld_btno',$conf->fld_tconfid,'$fld_btid','exim')");
          $this->db->query("update tbl_bth set fld_bttaxno = '$number' where fld_btid = '$fld_btid' limit 1");

        } else if ($last_no->number > $conf->fld_tconfnso) {
          $this->message("Tax Number Slot is Empty");
        } else {
          $inc_number = str_pad($last_no->number, 8, "0", STR_PAD_LEFT);
          $number = $conf->fld_tconfhdrno . "." . $inc_number;
           $this->db->query("insert into tbl_taxnumber (fld_taxnumberinc,fld_taxnumberno,fld_taxnumberdt,fld_btno,fld_tconfid,fld_btid,fld_taxnumberdb) values
                        ('$inc_number','$number','$data->fld_btdt','$data->fld_btno',$conf->fld_tconfid,'$fld_btid','exim')");

          $this->db->query("update tbl_bth set fld_bttaxno = '$number' where fld_btid = '$fld_btid' limit 1");

        }
       }
      }
     }

  function getTaxNumber($fld_btid,$fld_btidp) {
    $cek = $this->db->query("select count(1) 'ctn' from tbl_btd_finance where fld_btidp='$fld_btid' and fld_btflag=1");
    $cek = $cek->row();
    $cekheader = $this->db->query("select fld_btp13 'taxbatam' from tbl_bth where fld_btid='$fld_btid'");
    $cekheader = $cekheader->row();
    if($cek->ctn > 0 || $cekheader->taxbatam == 3) {
      $data = $this->db->query("select t0.*,date_format(t0.fld_btdt,'%Y-%m-%d') 'dtsa' from tbl_bth t0 where fld_btid='$fld_btid'");
      $data = $data->row();
      $nowdt = date('Y-m-d');
      $firstdt = date('Y-m-01');
      #$firstdt = date('2015-01-01');
      if($data->dtsa < $firstdt) { ### Jika Memakai Nomor Backup
        $conf = $this->db->query("select if($data->fld_btp13 = 1,replace( fld_tconfhdrno, '010.', '050.' ) ,if($data->fld_btp13 = 3,replace( fld_tconfhdrno, '010.', '070.' ), if($data->fld_btp13 = 4,replace( fld_tconfhdrno, '010.', '090.' ),if($data->fld_btp13 = 5,replace( fld_tconfhdrno, '010.', '080.' ), fld_tconfhdrno)))) 'fld_tconfhdrno' ,fld_tconfnsa,fld_tconfnso,fld_tconfdtsa,fld_tconfdtso,fld_tconfid
                                  from tbl_taxnumber_conf
                                  where date_format(now(),'%Y-%m-%d') between date_format(fld_tconfdtsa,'%Y-%m-%d')
                                  and date_format(fld_tconfdtso,'%Y-%m-%d') and fld_tconfstat=4 order by fld_tconfid  limit 1");
        $conf = $conf->row();
        #$data = $data->row();
        $last_no = $this->db->query("select (fld_taxnumberinc + 1) 'number' from tbl_taxnumber t0
                                   where t0.fld_tconfid = $conf->fld_tconfid order by fld_taxnumberid desc limit 1");
        $last_no = $last_no->row();
        if($last_no->number < $conf->fld_tconfnsa) {
          $inc_number = str_pad($conf->fld_tconfnsa, 8, "0", STR_PAD_LEFT);
          $number = $conf->fld_tconfhdrno . "." . $inc_number;
           $this->db->query("insert into tbl_taxnumber (fld_taxnumberinc,fld_taxnumberno,fld_taxnumberdt,fld_btno,fld_tconfid,fld_btid,fld_taxnumberdb) values
                        ('$inc_number','$number','$data->fld_btdt','$data->fld_btno',$conf->fld_tconfid,'$fld_btid','exim')");
          $this->db->query("update tbl_bth set fld_bttaxno = '$number' where fld_btid = '$fld_btid' limit 1");

        } else if ($last_no->number > $conf->fld_tconfnso) {
          $this->message("Tax Number Slot is Empty");
        } else {
          $inc_number = str_pad($last_no->number, 8, "0", STR_PAD_LEFT);
          $number = $conf->fld_tconfhdrno . "." . $inc_number;
           $this->db->query("insert into tbl_taxnumber (fld_taxnumberinc,fld_taxnumberno,fld_taxnumberdt,fld_btno,fld_tconfid,fld_btid,fld_taxnumberdb) values
                        ('$inc_number','$number','$data->fld_btdt','$data->fld_btno',$conf->fld_tconfid,'$fld_btid','exim')");

          $this->db->query("update tbl_bth set fld_bttaxno = '$number' where fld_btid = '$fld_btid' limit 1");

        }

      } else { ### Jika Tidak Memakai Nomor Backup
        $conf = $this->db->query("select if($data->fld_btp13 = 1,replace( fld_tconfhdrno, '010.', '050.' ),if($data->fld_btp13 = 3,replace( fld_tconfhdrno, '010.', '070.' ) ,if($data->fld_btp13 = 4,replace( fld_tconfhdrno, '010.', '090.' ), if($data->fld_btp13 = 5,replace( fld_tconfhdrno, '010.', '080.' ), fld_tconfhdrno)))) 'fld_tconfhdrno' ,fld_tconfnsa,fld_tconfnso,fld_tconfdtsa,fld_tconfdtso,fld_tconfid
                                  from tbl_taxnumber_conf where date_format(now(),'%Y-%m-%d') between date_format(fld_tconfdtsa,'%Y-%m-%d')
                                  and date_format(fld_tconfdtso,'%Y-%m-%d') and fld_tconfstat=2 order by fld_tconfid  limit 1");
        #$data = $data->row();
        $conf = $conf->row();

        ### Cek Konfigurasi Tax Number
        if($conf) {
          echo "GOOD";
        } else {
          $this->message("Valid Tax Configuration is Unavaliable ... ");
        }
        ###

        $last_no = $this->db->query("select (fld_taxnumberinc + 1) 'number' from tbl_taxnumber t0
                                   where t0.fld_tconfid = $conf->fld_tconfid order by fld_taxnumberid desc limit 1");
        $last_no = $last_no->row();
        $last_number  = $last_no->number;
        if( $last_number) {
          echo "Good";
        } else {
          $last_number = $conf->fld_tconfnsa;
        }
        if($last_number < $conf->fld_tconfnsa) {
          $this->message("Invalid Tax Number");
        } else if ($last_number > $conf->fld_tconfnso) { ### Jika Nomor Habis Lanjutkan ambil konfigurasi sebelumnya
          ### Cari Konfigurasi Baru
          $conf_upd = $this->db->query("select * from tbl_taxnumber_conf where date_format(now(),'%Y-%m-%d') between date_format(fld_tconfdtsa,'%Y-%m-%d')
                                and date_format(fld_tconfdtso,'%Y-%m-%d') and fld_tconfstat=1 order by fld_tconfid limit 1");
          $conf_upd = $conf_upd->row();
          if($conf_upd){
            $inc_number = str_pad($conf_upd->fld_tconfnsa, 8, "0", STR_PAD_LEFT);
            $number = $conf_upd->fld_tconfhdrno . "." . $inc_number;
            $this->db->query("insert into tbl_taxnumber (fld_taxnumberinc,fld_taxnumberno,fld_taxnumberdt,fld_btno,fld_tconfid,fld_btid,fld_taxnumberdb) values
                              ('$inc_number','$number','$data->fld_btdt','$data->fld_btno',$conf_upd->fld_tconfid,'$fld_btid','exim')");
            $this->db->query("update tbl_taxnumber_conf set fld_tconfstat=3 where fld_tconfid=$conf->fld_tconfid");
            $this->db->query("update tbl_taxnumber_conf set fld_tconfstat=2 where fld_tconfid=$conf_upd->fld_tconfid");
            $this->db->query("update tbl_bth set fld_bttaxno = '$number' where fld_btid = '$fld_btid' limit 1");

          } else {
            $this->message("Valid Tax Configuration is Unavaliable ... ");
          }
        } else {
          $inc_number = str_pad($last_number, 8, "0", STR_PAD_LEFT);
          $number = $conf->fld_tconfhdrno . "." . $inc_number;
          $this->db->query("insert into tbl_taxnumber (fld_taxnumberinc,fld_taxnumberno,fld_taxnumberdt,fld_btno,fld_tconfid,fld_btid,fld_taxnumberdb) values
                        ('$inc_number','$number','$data->fld_btdt','$data->fld_btno',$conf->fld_tconfid,'$fld_btid','exim')");
          $this->db->query("update tbl_bth set fld_bttaxno = '$number' where fld_btid = '$fld_btid' limit 1");
        }
      }
    }
  }

  function printBatchInvoice($batch_id) {
    $batch = $this->db->query("select * from tbl_btd_invdel tz0 where tz0.fld_btidp = $batch_id");
    $batch = $batch->result();

    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,28),$orientation='portrait');
    $this->cezpdf->ezSetMargins(100,5,10,15);

    foreach ($batch as $rbatch) {
//    $this->cezpdf->ezNewPage();
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
    echo $rbatch->fld_btiid;
    $fld_btid = $rbatch->fld_btiid;

    $getData = $this->db->query("
    select
    t0.fld_btno 'btno',
    #t1.fld_benm 'cust',
    concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm,'. '),''),t1.fld_benm)'cust',
    t0.fld_baido 'comp',

    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp19 'ex_inv',
    ifnull(t9.fld_tyvalnm,t0.fld_btdesc) 'remark',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t3.fld_beaddrplc != '',concat(t3.fld_beaddrplc,'\n'),'') , t3.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    if(t0.fld_btp13=5,concat(t0.fld_btcmt,' ','PPN DIBEBASKAN ATAS JASA'),t0.fld_btcmt) 'note',
    t0.fld_btdesc 'for',
    t6.fld_tyvalnm'freight',
    t0.fld_bttaxno 'tax_no',
    t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
	if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '','',format(t0.fld_btuamt,2))'vat_cstm',
    t0.fld_btp04 'amt',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    t0.fld_baidp 'posted',
    t7.fld_empnm 'postedby',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btp13=1,'VAT 1 %','VAT 10 %')'vat1',
    if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '' ,'',if(t0.fld_btdt < '2022-04-01 00:00:00',if(t0.fld_btp13 = 1, 'VAT 1%', 'VAT 10%'),if(t0.fld_btp13 = 1, 'VAT 1.1%', 'VAT 11%'))) 'vat1_cstm',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_btp04 'peb',

    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
	if(t0.fld_baidc = 8876 and t0.fld_bttaxno = '','',if(t2.fld_tyvalnm ='IDR','Rp.','$'))'curr_code_cstm',
    if(t0.fld_btloc in(1,0) ,'ELLY DWIYANTI',if(t0.fld_btloc = 6,'EKO PRASETYO','DIMAS W J')) 'ttd',
    if(t0.fld_btloc in (1,0),'FINANCE SUPERVISOR',if(t0.fld_btloc = 6,'FINANCE','FINANCE SUPERVISOR')) 'jabatan'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join dnxapps.tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1 and t3.fld_beaddrid=t0.fld_btp09
    left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
    left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
    left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid=72
    left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
    left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid=173
    left join tbl_tyval t9 on t9.fld_tyvalcd=t0.fld_btp40 and t9.fld_tyid=117
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $currency = $data->currency;
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,' *)','')) 'desc',
    if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    #t0.fld_btuamt01 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'

    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
   order by 1
    ");

    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl_count = $getDataDtl->num_rows();
   # $this->load->library('cezpdf');
   # $this->cezpdf->Cezpdf(array(21.5,30.5),$orientation='portrait');
   # $this->cezpdf->ezSetMargins(100,5,10,15);
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
      }
    }
  /*
    $this->cezpdf->addText(380,710,10,"INV Type.");
    $this->cezpdf->addText(443,710,10,':'.$data->inv_type);
    $this->cezpdf->addText(380,700,10,"Date");
    $this->cezpdf->addText(443,700,10,':'.$data->date);
    $this->cezpdf->addText(380,690,10,"INV Type.");
    $this->cezpdf->addText(443,690,10,':'.$data->inv_type);*/

//    $this->cezpdf->line(450, 415, 550, 415);
  //  $this->cezpdf->line(450, 380, 550, 380);
    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>'No ','row3'=>': '.$data->btno),
                  array('row1'=>$data->cust,'row2'=>'Date','row3'=>': '.$data->date),
		  array('row1'=>$data->address,'row2'=>'INV Type','row3'=>': '.$data->inv_type),
                  array('row1'=>'','row2'=>'','row3'=>''),
			  );

    $this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>'','row3'=>''),'',
	  array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'9',
	  'cols'=>array('row1'=>array('width'=>290),'row2'=>array('width'=>70),'row3'=>array('width'=>160))));
    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
                  array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
                  array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
                  array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
                  array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>':'.$data->do_no),
                  array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>':'.$data->si_no),
                  array('row1'=>'Freight','row2'=>':'.$data->freight,'row3'=>'TAX Number','row4'=>':'.$data->tax_no),                    );
      $this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));


    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDy(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl1,array('count'=>'No','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9',
      'cols'=>array('count'=>array('width'=>30), 'desc'=>array('width'=>275),'currency'=>array('width'=>1),
      'unit'=>array('width'=>94, 'justification'=>'right'),
      'curr_code'=>array('width'=>45, 'justification'=>'right'),'subtotal'=>array('width'=>100, 'justification'=>'right'))));
      $this->cezpdf->ezSetDy(-5);

      $data_sum = array(
		  array('row3'=>'.....................'),
		  array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
		  array('row1'=>$data->vat1_cstm ,'row2'=>$data->curr_code_cstm ,'row3'=>$data->vat_cstm),
		  array('row1'=> '','row3'=>'.....................'),
		  array('row1'=>'TOTAL AMOUNT' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
		  array('row1'=>'','0'),
                  array('row1'=>'',''),
		  array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
	  'cols'=>array('row1'=>array('width'=>443,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>70,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,175,200,9,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,155,200,9,$data->jabatan,'center');
//      $this->cezpdf->addText(380,710,10,"INV Type.");
 //     $this->cezpdf->addText(443,710,10,':'.$data->inv_type);
      $this->cezpdf->addText(50,115,9,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,9,'IDR   ');
      $this->cezpdf->addText(50,85,9,'USD   ');
      $this->cezpdf->addText(85,105,9,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
     # $this->cezpdf->addText(85,95,9,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,9,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,9,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,135,9,'Terbilang #'.$terbilang.' Rupiah #');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);


    }
    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));
    $this->db->query("insert into tbl_trans_log (fld_baidp,fld_btidp,fld_log_tyid,fld_btdesc,fld_btdt) value('$data->posted','$fld_btid',4,'[msg-system] BATCH PRINTED',now())");
    $last_insert_id = $this->db->insert_id();
    $this->db->query("update tbl_bth set fld_btp42 ='$last_insert_id' where fld_btid = '$fld_btid'  limit 1");
    }


    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;

  }


  function printBatchInvoiceMerge($batch_id) {
    $batch = $this->db->query("select * from tbl_btd_invdel tz0 where tz0.fld_btidp = $batch_id");
    $batch = $batch->result();

    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,28),$orientation='portrait');
    $this->cezpdf->ezSetMargins(100,5,10,15);

    foreach ($batch as $rbatch) {
//    $this->cezpdf->ezNewPage();
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
    echo $rbatch->fld_btiid;
    $fld_btid = $rbatch->fld_btiid;

    $getData = $this->db->query("
    select
    t0.fld_btno 'btno',
    #t1.fld_benm 'cust',
    concat(if(t1.fld_beprefix > 0,concat(t8.fld_tyvalnm,'. '),''),t1.fld_benm)'cust',
    t0.fld_baido 'comp',

    t0.fld_btp16 'qty',
    t0.fld_btp24 'vessel',
    t0.fld_btnoalt 'bl',
    t0.fld_btp11 'measure',
    t0.fld_btp17 'cont_no',
    t0.fld_btp19 'ex_inv',
    ifnull(t9.fld_tyvalnm,t0.fld_btdesc) 'remark',
    t0.fld_btp23 'inv_no',
    t0.fld_bttaxno 'tax',t0.fld_btnoreff'inv_reff',
    t0.fld_btp14 'reff_no',
    concat(if(t3.fld_beaddrplc != '',concat(t3.fld_beaddrplc,'\n'),'') , t3.fld_beaddrstr) 'address',
    t0.fld_btp22 'comm',
    t0.fld_btp18 'do_no',
    t0.fld_btp20 'si_no',
    t0.fld_btp06 'pol',
    t0.fld_btcmt 'note',
    t0.fld_btdesc 'for',
    t6.fld_tyvalnm'freight',
    t0.fld_bttaxno 'tax_no',
    t0.fld_btbalance'subtotal',t4.fld_tyvalnm'inv_type',
    format(t0.fld_btamt,2)'subamt',
    format(t0.fld_btuamt,2)'vat',
    t0.fld_btp04 'amt',
    date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
    t0.fld_baidp 'posted',
    t7.fld_empnm 'postedby',
    t2.fld_tyvalnm 'currency',
    if(t0.fld_btdt < '2022-04-01 00:00:00',if(t0.fld_btp13=1,'VAT 1 %','VAT 10 %'),if(t0.fld_btp13=1,'VAT 1.1 %','VAT 11 %'))'vat1',
    if(t0.fld_btp15=2,'PIB / PIUD No','PEB / PIUD No')'doc_type',
    if(t0.fld_btp15=2,'PIB Date','PEB Date')'doc_type1',
    if(t0.fld_btp15=2,'Port of Loading','Destination')'doc_type2',
    date_format(t0.fld_btp02,'%Y-%m-%d') 'bl_date',
    date_format(t0.fld_btp05,'%Y-%m-%d') 'pib_date',
    t0.fld_btp04 'peb',

    if(t2.fld_tyvalnm ='IDR','Rp.','$') 'curr_code',
    if(t0.fld_btloc in(1,0) ,'ELLY DWIYANTI',if(t0.fld_btloc = 6,'EKO PRASETYO','DIMAS W J')) 'ttd',
    if(t0.fld_btloc in (1,0),'FINANCE SUPERVISOR',if(t0.fld_btloc = 6,'FINANCE','FINANCE SUPERVISOR')) 'jabatan'
    from tbl_bth t0
    left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
    left join dnxapps.tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid = 39
    left join dnxapps.tbl_beaddr t3 on t3.fld_beid=t1.fld_beid and t3.fld_beaddrstat=1 and t3.fld_beaddrid=t0.fld_btp09
    left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp15 and t4.fld_tyid = 45
    left join tbl_prove t5 on t5.fld_proveid=t3.fld_beaddrprove
    left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btp30 and t6.fld_tyid=72
    left join hris.tbl_emp t7 on t7.fld_empid = t0.fld_baidp
    left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t1.fld_beprefix and t8.fld_tyid=173
    left join tbl_tyval t9 on t9.fld_tyvalcd=t0.fld_btp40 and t9.fld_tyid=117
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $currency = $data->currency;
    $fld_btp06 = $this->db->query("SELECT `fld_btp06` FROM `tbl_btd_finance` WHERE `fld_btidp` = $rbatch->fld_btiid limit 1")->row();
// var_dump($fld_btp06->fld_btp06);
// exit();
    if ($fld_btp06->fld_btp06 == "") {
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid ,
    concat(t0.fld_btdesc,if(t0.fld_btflag = 1,' *)','')) 'desc',
    if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(t0.fld_btuamt01,2),' x (',t0.fld_btqty01,')'),'') 'unit',
    #t0.fld_btuamt01 'unit',
    format(t0.fld_btamt01,2) 'subtotal',
    #t0.fld_btqty01 'qty',
    t0.fld_btnoreff 'noreff',
    t0.fld_coaid 'coaid',
    #ifnull('$currency','Unknown') 'currency',
    if('$currency' = 'IDR','Rp.','$') 'curr_code'

    from tbl_btd_finance t0
    where
    t0.fld_btidp='$fld_btid'
   order by 1
    ");
    } else {
      $getDataDtl = $this->db->query("
      select
      t0.fld_btid ,
      GROUP_CONCAT(concat(t0.fld_btdesc,if(t0.fld_btflag = 1,'*)','')) SEPARATOR ' + ') 'desc',
      if(t0.fld_btflag = 1 or t0.fld_btp05 = 1,concat(format(SUM(t0.fld_btuamt01),2),' x (',t0.fld_btqty01,')'),'') 'unit',
      format(SUM(t0.fld_btamt01),2) 'subtotal',
      t0.fld_btnoreff 'noreff',
      t0.fld_coaid 'coaid',
      if('$currency' = 'IDR','Rp.','$') 'curr_code'

      from tbl_btd_finance t0
      where
      t0.fld_btidp='$fld_btid'
      GROUP BY t0.fld_btp06
      ");
    }



    $total_cost = 0;
    $counteor = 0;
    $no =0;
    $dataDtl_count = $getDataDtl->num_rows();
    if ($getDataDtl->num_rows() > 0) {
      $datadtl1 = $getDataDtl->result_array();
      $count = count($datadtl1);
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $no=$no+1;
        ###Prepare Data
        $datadtl1[$i]['count'] = $counteor;
      }
    }

    $this->cezpdf->ezSetDy(-20);
    $data_prn = array(array('row1'=>'Messrs :', 'row2'=>'No ','row3'=>': '.$data->btno),
                  array('row1'=>$data->cust,'row2'=>'Date','row3'=>': '.$data->date),
      array('row1'=>$data->address,'row2'=>'INV Type','row3'=>': '.$data->inv_type),
                  array('row1'=>'','row2'=>'','row3'=>''),
        );

    $this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>'','row3'=>''),'',
    array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'9',
    'cols'=>array('row1'=>array('width'=>290),'row2'=>array('width'=>70),'row3'=>array('width'=>160))));
    $this->cezpdf->ezSetDy(-25);
    $data_cst = array(array('row1'=>'Job Number', 'row2'=>': '.$data->inv_reff,'row3'=>'Invoice Number','row4'=>': '.$data->inv_no),
                  array('row1'=>'Names of Goods ','row2'=>': '.$data->comm,'row3'=>'Measurement','row4'=>': '.$data->measure),
                  array('row1'=>'Quantity','row2'=>': '.$data->qty,'row3'=>'Remarks','row4'=>': '.$data->remark),
                  array('row1'=>'Ex Vessel','row2'=>': '.$data->vessel,'row3'=>$data->doc_type2,'row4'=>': '.$data->pol),
                  array('row1'=>'B/L No','row2'=>': '.$data->bl,'row3'=>'B/L Date','row4'=>': '.$data->bl_date),
                  array('row1'=>$data->doc_type,'row2'=>': '.$data->peb,'row3'=>$data->doc_type1,'row4'=>': '.$data->pib_date),
                  array('row1'=>'Cont. No','row2'=>': '.$data->cont_no,'row3'=>'D/O Number','row4'=>':'.$data->do_no),
                  array('row1'=>'EX.Invoice','row2'=>': '.$data->ex_inv,'row3'=>'S/I Number','row4'=>':'.$data->si_no),
                  array('row1'=>'Freight','row2'=>':'.$data->freight,'row3'=>'TAX Number','row4'=>':'.$data->tax_no),                    );
      $this->cezpdf->ezTable($data_cst,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>180),'row3'=>array('width'=>90),'row4'=>array('width'=>180))));


    if ($dataDtl_count < 250) {
      $this->cezpdf->ezSetDy(-20);
      #Print Detail
      $this->cezpdf->setStrokeColor(0,0,0);
      $this->cezpdf->setLineStyle(1);
      $this->cezpdf->ezTable($datadtl1,array('count'=>'No','desc'=>'Description of Cost','currency'=>'','unit'=>'','curr_code'=>'','subtotal'=>''),'',
      array('rowGap'=>'0.3','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9',
      'cols'=>array('count'=>array('width'=>30), 'desc'=>array('width'=>275),'currency'=>array('width'=>1),
      'unit'=>array('width'=>94, 'justification'=>'right'),
      'curr_code'=>array('width'=>45, 'justification'=>'right'),'subtotal'=>array('width'=>100, 'justification'=>'right'))));
      $this->cezpdf->ezSetDy(-5);

      $data_sum = array(
      array('row3'=>'.....................'),
      array('row1'=>'SUBTOTAL' ,'row2'=>$data->curr_code ,'row3'=>$data->subamt),
      array('row1'=>$data->vat1 ,'row2'=>$data->curr_code ,'row3'=>$data->vat),
      array('row1'=> '','row3'=>'.....................'),
      array('row1'=>'TOTAL AMOUNT' ,'row2'=>$data->curr_code ,'row3'=>number_format($data->subtotal,2,',','.')),
      array('row1'=>'','0'),
                  array('row1'=>'',''),
      array('row1'=>''));

      $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
    'cols'=>array('row1'=>array('width'=>443,'justification'=>'right'),'row2'=>array('width'=>30,'justification'=>'right'),
           'row3'=>array('width'=>70,'justification'=>'right') )));

      $data_notes = array(
                  array('row1'=>' Note : ' ,'row2'=>$data->note)
                  );

      $this->cezpdf->ezTable($data_notes,array('row1'=>'','row2'=>''),'',
      array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
          'cols'=>array('row1'=>array('width'=>50,'justification'=>'left'),'row2'=>array('width'=>300,'justification'=>'left') )));

      $this->cezpdf->ezSetY(35);
      $this->cezpdf->addTextWrap(400,175,200,9,$data->ttd,'center');
      $this->cezpdf->addTextWrap(400,155,200,9,$data->jabatan,'center');
//      $this->cezpdf->addText(380,710,10,"INV Type.");
 //     $this->cezpdf->addText(443,710,10,':'.$data->inv_type);
      $this->cezpdf->addText(50,115,9,'PLEASE TRANSFER TO OUR ACCOUNT : ');
      $this->cezpdf->addText(50,105,9,'IDR   ');
      $this->cezpdf->addText(50,85,9,'USD   ');
      $this->cezpdf->addText(85,105,9,'4281360889 (BCA BANK CAB. SUNTER MALL)  ');
     # $this->cezpdf->addText(85,95,9,'1025527547 (Bank Commonwealth Cikarang Branch)');
      $this->cezpdf->addText(85,85,9,'4281929369 ( BCA BANK CAB. SUNTER MALL )');
      $this->cezpdf->addText(50,125,9,'Revised invoice (1) week after received');
      $terbilang = ucwords($this->number_to_words($data->subtotal));
      $this->cezpdf->addText(50,135,9,'Terbilang #'.$terbilang.' Rupiah #');

      $this->cezpdf->addText(410,20,8,'Created By : ' . $data->postedby);


    }
    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',
    array('rowGap'=>'0','xPos'=>150,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,
    'cols'=>array('row1'=>array('width'=>140),'row2'=>array('width'=>100,'justification' => 'right'))));

    }


    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=salesInvoice-" . date('Ymd') .  ".pdf");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = $this->cezpdf->ezOutput();
    echo $output;

  }


function printdeliveryReceipt() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'del_date',
    if(t0.fld_btp04='0000-00-00',date_format(now(),'%d-%m-%Y'),date_format(t0.fld_btp04,'%d-%m-%Y')) 'date_now',
    format(t0.fld_btamt,0) 'total',
    t1.fld_benm 'owner',
    t3.fld_empnm 'posted',
    t2.fld_bedivcd 'code',
    concat(ifnull(t6.fld_tyvalnm,''), '. ', t4.fld_benm) 'customer',
    t0.fld_btp02 'attn',
    t0.fld_btcmt 'note',
    if(date_format(t0.fld_btp04, '%d-%m-%Y') = '00-00-0000','',date_format(t0.fld_btp04,'%d-%m-%Y')) 'deliverdate',
    if(date_format(t0.fld_btdt4, '%d-%m-%Y')= '00-00-0000','',date_format(t0.fld_btdt4, '%d-%m-%Y')) 'deliverdate2',
    concat(if(t5.fld_beaddrplc != '',concat(t5.fld_beaddrplc,'\n'),'') , t5.fld_beaddrstr) 'address'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baido
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_baidp
    left join dnxapps.tbl_be t4 on t4.fld_beid=t0.fld_baidc and t4.fld_betyid=5
    left join dnxapps.tbl_beaddr t5 on t5.fld_beaddrid=t0.fld_btp01
    left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = t4.fld_beprefix and t6.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t1.fld_btno 'item',
    concat(ifnull(t4.fld_tyvalnm,''), '. ', t3.fld_benm) 'customer',
    format(t1.fld_btbalance,0)'amount',
    t0.fld_btp01 'remark'
    from tbl_btd_invdel t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=39
    left join dnxapps.tbl_be t3 on t3.fld_beid=t1.fld_baidc and t3.fld_betyid=5
    left join dnxapps.tbl_tyval t4 on t4.fld_tyvalcd = t3.fld_beprefix and t4.fld_tyid = 173
    where
    t0.fld_btidp='$fld_btid'
    ORDER BY t0.fld_btiid ASC
    ");
 $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
      		    $count = count($datadtl);
		    for ($i=0; $i<$count; ++$i)
		    {
		      $counteor = $counteor + 1;
			 ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
		    }

    }

    # ---  deliverdate > get date from two condition
    if(empty($data->deliverdate2)){
      (empty($data->deliverdate))? $_deliverdate = '' : $_deliverdate = $data->deliverdate;
    }else{
      (empty($data->deliverdate2))? $_deliverdate = '' : $_deliverdate = $data->deliverdate2;
    }
    # --- end

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
       // $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,788,30);
        //$this->cezpdf->addText(90,810,10,'PT.Dunia Express Transindo     ');
        //$this->cezpdf->addText(90,800,10,'Jl.Agung Karya VII No.1 Sunter     ');
       // $this->cezpdf->addText(90,790,10,'Jakarta Utara');
        $this->cezpdf->ezSetMargins(50,35,10,15);

    $this->cezpdf->ezText("Delivery Receipt" . "   ", 16, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(12);
         $header = array(array('row1'=>'Delivery Number','row2'=>': '.$data->fld_btno,'row3'=>''),
                         #array('row1'=>'Date','row2'=>': '.$_deliverdate,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date','row2'=>': '.$data->deliverdate,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date 2','row2'=>': '.$data->deliverdate2,'row3'=>''),
                         array('row1'=>'Customer','row2'=>': '.$data->customer,'row3'=>''),
                         array('row1'=>'Address','row2'=>': '.$data->address,'row3'=>''),
                       	 array('row1'=>'Attn','row2'=>': '.$data->attn,'row3'=>''),
                         array('row1'=>'Note','row2'=>': '.$data->note,'row3'=>'')
                );
     $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>90,'justification' => 'left'),
         'row2'=>array('width'=>230,'justification' => 'left'),
         'row3'=>array('width'=>70,'justification' => 'left'),
         'row4'=>array('width'=>180,'justification' => 'left'),
         )));

//detail list
    if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-10);
  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
   $this->cezpdf->ezTable($datadtl,array('count'=>'No','item'=>'Invoice No','customer'=>'Customer','amount'=>'Amount','remark'=>'Remark'),'',
   array('rowGap'=>'0.3','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10','cols'=>array        (
   'count'=>array('width'=>30,'justification'=>'center'),
   'item'=>array('width'=>120,'justification'=>'center'),
   'customer'=>array('width'=>180,'justification'=>'center'),
   'amount'=>array('width'=>80,'justification'=>'right'),
   'remark'=>array('width'=>130,'justification'=>'center'),
    )));
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(12);
	 $acc = array(array('row1'=>'Jakarta, '.$_deliverdate),
                      array('row1'=>'Nama Jelas','row2'=> ':  ..............................'),
                      array('row1'=>'Tanggal Terima' ,'row2'=> ':  ..............................'),
                      array('row1'=>'' ,'row2'=> ''),
                     // array('row1'=>'' ,'row2'=> ''),
                    //  array('row1'=>'' ,'row2'=> ''),
                      array('row1'=>'Tanda Tangan','row2'=> ':  ..............................'),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>''),'',array
	 ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>180,'justification' => 'left'),
	 )));
	  $this->cezpdf->ezSetDy(12);
          $check = array(array('row1'=>''),
                      array('row1'=>''),
                      array('row1'=>'' ),
                      array('row1'=>''),
                );
     $this->cezpdf->ezTable($check,array('row1'=>'Final Check'),'',array
         ('rowGap'=>'2','xPos'=>390,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>1,'cols'=>array        (
         'row1'=>array('width'=>100,'justification' => 'center'),
         )));

    $this->cezpdf->ezSetY(385);

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=delivery_receipt.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

function printdeliveryReceipt2() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'del_date',
    date_format(now(),'%d-%m-%Y') 'date_now',
    format(t0.fld_btamt,0) 'total',
    t1.fld_benm 'owner',
    t3.fld_empnm 'posted',
    t2.fld_bedivcd 'code',
    concat(t6.fld_tyvalnm, '. ', t4.fld_benm) 'customer',
    t0.fld_btp02 'attn',
    t0.fld_btcmt 'note',
    concat(if(t5.fld_beaddrplc != '',concat(t5.fld_beaddrplc,'\n'),'') ,t5.fld_beaddrstr) 'address'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baido
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_baidp
    left join dnxapps.tbl_be t4 on t4.fld_beid=t0.fld_baidc and t4.fld_betyid=5 and t4.fld_bestat = 1
    left join dnxapps.tbl_beaddr t5 on t5.fld_beaddrid=t0.fld_btp01
    left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = t4.fld_beprefix and t6.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t1.fld_btno 'item',
    t0.fld_btp01
    from tbl_btd_invdel t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=39
    where
    t0.fld_btidp='$fld_btid'
    ");
    $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
                    $count = count($datadtl);
                    for ($i=0; $i<$count; ++$i)
                    {
                      $counteor = $counteor + 1;
                         ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
                    }

    }

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
        $this->cezpdf->ezSetMargins(50,35,10,15);
    $this->cezpdf->addTextWrap(50,550,600,20,'TO   :') ;
    $customer = array(array('row1'=>$data->customer),
                      array('row1'=>$data->address),
                      array('row1'=>$data->attn),
                      array('row1'=>$data->note),
                     );
     $this->cezpdf->ezSetY(520);
    $this->cezpdf->ezTable($customer,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'20','cols'=>array('row1'=>array('width'=>600),'row2'=>array('width'=>250))));


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=delivery_receipt_envelope.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

function printdeliveryReceipt3() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'del_date',
    date_format(now(),'%d-%m-%Y') 'date_now',
    format(t0.fld_btamt,0) 'total',
    t1.fld_benm 'owner',
    t3.fld_empnm 'posted',
    t2.fld_bedivcd 'code',
    concat(t6.fld_tyvalnm, '. ', t4.fld_benm) 'customer',
    t0.fld_btp02 'attn',
    t0.fld_btcmt 'note',
    if(date_format(t0.fld_btp04, '%d-%m-%Y') = '00-00-0000','',date_format(t0.fld_btp04,'%d-%m-%Y')) 'deliverdate',
    if(date_format(t0.fld_btdt4, '%d-%m-%Y')= '00-00-0000','',date_format(t0.fld_btdt4, '%d-%m-%Y')) 'deliverdate2',
    concat(if(t5.fld_beaddrplc != '',concat(t5.fld_beaddrplc,'\n'),'') , t5.fld_beaddrstr) 'address'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baido
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_baidp
    left join dnxapps.tbl_be t4 on t4.fld_beid=t0.fld_baidc and t4.fld_betyid=5
    left join dnxapps.tbl_beaddr t5 on t5.fld_beaddrid=t0.fld_btp01
    left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = t4.fld_beprefix and t6.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t1.fld_btno 'item',
    if(t1.fld_btp15=2,t0.fld_btp01,'')'bl',
    if(t1.fld_btp15=1,t0.fld_btp01,'')'invoice',
    format(t1.fld_btbalance,0)'amount',t1.fld_btnoalt,date_format(t1.fld_btdt,'%Y-%m-%d') 'date'
    from tbl_btd_invdel t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=39
    where
    t0.fld_btidp='$fld_btid'
    ");
    $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
                    $count = count($datadtl);
                    for ($i=0; $i<$count; ++$i)
      {
                      $counteor = $counteor + 1;
                         ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
                    }

    }

               # ---  deliverdate > get date from two condition
    if(empty($data->deliverdate2)){
      (empty($data->deliverdate))? $_deliverdate = '' : $_deliverdate = $data->deliverdate;
    }else{
      (empty($data->deliverdate2))? $_deliverdate = '' : $_deliverdate = $data->deliverdate2;
    }
    # --- end

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
       // $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,788,30);
        //$this->cezpdf->addText(90,810,10,'PT.Dunia Express Transindo     ');
        //$this->cezpdf->addText(90,800,10,'Jl.Agung Karya VII No.1 Sunter     ');
       // $this->cezpdf->addText(90,790,10,'Jakarta Utara');
        $this->cezpdf->ezSetMargins(120,35,10,15);

         $this->cezpdf->ezText("Delivery Receipt" . "  ", 16, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(12);
         $header = array(array('row1'=>'Delivery Number','row2'=>': '.$data->fld_btno,'row3'=>''),
                         #array('row1'=>'Date','row2'=>': '.$data->del_date,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date','row2'=>': '.$data->deliverdate,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date 2','row2'=>': '.$data->deliverdate2,'row3'=>''),
                         array('row1'=>'Customer','row2'=>': '.$data->customer,'row3'=>''),
                         array('row1'=>'Address','row2'=>': '.$data->address,'row3'=>''),
                         array('row1'=>'Attn','row2'=>': '.$data->attn,'row3'=>''),
                         array('row1'=>'Note','row2'=>': '.$data->note,'row3'=>'')

   /* $this->cezpdf->ezText("Delivery Receipt" . "   ", 16, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(12);
         $header = array(array('row1'=>'Delivery Number','row2'=>':','row3'=>$data->fld_btno),
                         array('row1'=>'Date','row2'=>':','row3'=>$data->del_date,'row4'=>'Sender    :'.$data->posted),
                         array('row1'=>'Customer','row2'=>':','row3'=>$data->customer),
                         array('row1'=>'Address','row2'=>':','row3'=>$data->address),
                         array('row1'=>'Attn','row2'=>':','row3'=>$data->attn),
                         array('row1'=>'Note','row2'=>':','row3'=>$data->note)*/
                );

              $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>90,'justification' => 'left'),
         'row2'=>array('width'=>240,'justification' => 'left'),
         'row3'=>array('width'=>70,'justification' => 'left'),
         'row4'=>array('width'=>180,'justification' => 'left'),
         )));

/*     $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>90,'justification' => 'left'),
         'row2'=>array('width'=>20,'justification' => 'left'),
         'row3'=>array('width'=>200,'justification' => 'left'),
          'row4'=>array('width'=>200,'justification' => 'left'),
         )));*/

     //detail list
    if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-10);
  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
   $this->cezpdf->ezTable($datadtl,array('count'=>'No','item'=>'Transaction No','invoice'=>'Invoice Number','bl'=>'B/L','date'=>'Date','amount'=>'Amount'),'',
   array('rowGap'=>'0.3','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'9','cols'=>array        (
   'count'=>array('width'=>30,'justification'=>'center'),
   'item'=>array('width'=>130,'justification'=>'center'),
   'invoice'=>array('width'=>110,'justification'=>'center'),
   'bl'=>array('width'=>120,'justification'=>'center'),
   'date'=>array('width'=>70,'justification'=>'center'),
   'amount'=>array('width'=>80,'justification'=>'right'),
    )));
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(12);
         $acc = array(array('row1'=>'Jakarta, '.$_deliverdate),
                      array('row1'=>'Nama Jelas','row2'=> ':  ..............................'),
                      array('row1'=>'Tanggal Terima' ,'row2'=> ':  ..............................'),
                      array('row1'=>'' ,'row2'=> ''),
                     // array('row1'=>'' ,'row2'=> ''),
                    //  array('row1'=>'' ,'row2'=> ''),
                      array('row1'=>'Tanda Tangan','row2'=> ':  ..............................'),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>''),'',array
         ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>180,'justification' => 'left'),
         )));
         $this->cezpdf->ezSetDy(12);
          $check = array(array('row1'=>''),
                      array('row1'=>''),
                      array('row1'=>'' ),
                      array('row1'=>''),
                );
     $this->cezpdf->ezTable($check,array('row1'=>'Final Check'),'',array
         ('rowGap'=>'2','xPos'=>390,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>1,'cols'=>array        (
         'row1'=>array('width'=>100,'justification' => 'center'),
         )));

    $this->cezpdf->ezSetY(385);

        }


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=delivery_receipt.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

    function printdeliveryReceipt4() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'del_date',
    if(t0.fld_btp04='0000-00-00',date_format(now(),'%d-%m-%Y'),date_format(t0.fld_btp04,'%d-%m-%Y')) 'date_now',
    format(t0.fld_btamt,0) 'total',
    t1.fld_benm 'owner',
    t3.fld_empnm 'posted',
    t2.fld_bedivcd 'code',
    concat(t6.fld_tyvalnm, '. ', t4.fld_benm) 'customer',
    t0.fld_btp02 'attn',
    t0.fld_btcmt 'note',
    if(date_format(t0.fld_btp04, '%d-%m-%Y') = '00-00-0000','',date_format(t0.fld_btp04,'%d-%m-%Y')) 'deliverdate',
    if(date_format(t0.fld_btdt4, '%d-%m-%Y')= '00-00-0000','',date_format(t0.fld_btdt4, '%d-%m-%Y')) 'deliverdate2',
    concat(if(t5.fld_beaddrplc != '',concat(t5.fld_beaddrplc,'\n'),'') , t5.fld_beaddrstr) 'address'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baido
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_baidp
    left join dnxapps.tbl_be t4 on t4.fld_beid=t0.fld_baidc and t4.fld_betyid=5
    left join dnxapps.tbl_beaddr t5 on t5.fld_beaddrid=t0.fld_btp01
    left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = t4.fld_beprefix and t6.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t1.fld_btno 'item',
    date_format(t1.fld_btdt,'%Y-%m-%d')'date',
    t1.fld_btnoalt 'bl',
    format(t1.fld_btamt,0) 'total',
    format(t1.fld_btuamt,0) 'ppn',
    format(t1.fld_btbalance,0) 'subtotal',
    t0.fld_btp01
    from tbl_btd_invdel t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=39
    where
    t0.fld_btidp='$fld_btid'
    ");
 $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
      		    $count = count($datadtl);
		    for ($i=0; $i<$count; ++$i)
		    {
		      $counteor = $counteor + 1;
			 ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
		    }

    }

         # ---  deliverdate > get date from two condition
    if(empty($data->deliverdate2)){
      (empty($data->deliverdate))? $_deliverdate = '' : $_deliverdate = $data->deliverdate;
    }else{
      (empty($data->deliverdate2))? $_deliverdate = '' : $_deliverdate = $data->deliverdate2;
    }
    # --- end

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
       // $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,788,30);
        //$this->cezpdf->addText(90,810,10,'PT.Dunia Express Transindo     ');
        //$this->cezpdf->addText(90,800,10,'Jl.Agung Karya VII No.1 Sunter     ');
       // $this->cezpdf->addText(90,790,10,'Jakarta Utara');
        $this->cezpdf->ezSetMargins(50,35,10,15);

    $this->cezpdf->ezText("Delivery Receipt" . "   ", 16, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(12);
         $header = array(array('row1'=>'Delivery Number','row2'=>': '.$data->fld_btno,'row3'=>''),
                         #array('row1'=>'Date','row2'=>': '.$data->del_date,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date','row2'=>': '.$data->deliverdate,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date 2','row2'=>': '.$data->deliverdate2,'row3'=>''),
                         array('row1'=>'Customer','row2'=>': '.$data->customer,'row3'=>''),
                         array('row1'=>'Address','row2'=>': '.$data->address,'row3'=>''),
                       	 array('row1'=>'Attn','row2'=>': '.$data->attn,'row3'=>''),
                         array('row1'=>'Note','row2'=>': '.$data->note,'row3'=>'')
                );
     $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>90,'justification' => 'left'),
         'row2'=>array('width'=>240,'justification' => 'left'),
         'row3'=>array('width'=>70,'justification' => 'left'),
         'row4'=>array('width'=>180,'justification' => 'left'),
         )));

//detail list
    if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-10);
  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
   $this->cezpdf->ezTable($datadtl,array('count'=>'No','item'=>'Invoice No','date'=>'Date','bl'=>'BL','total'=>'DPP','ppn'=>'Ppn','subtotal'=>'Total'),'',
   array('rowGap'=>'0.3','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10','cols'=>array        (
   'count'=>array('width'=>30,'justification'=>'center'),
   'item'=>array('width'=>120,'justification'=>'center'),
   'date'=>array('width'=>70,'justification'=>'center'),
   'bl'=>array('width'=>80,'justification'=>'center'),
   'total'=>array('width'=>70,'justification'=>'center'),
   'ppn'=>array('width'=>70,'justification'=>'center'),
   'subtotal'=>array('width'=>100,'justification'=>'center'),
    )));
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(12);
	 $acc = array(array('row1'=>'Jakarta, '.$_deliverdate),
                      array('row1'=>'Nama Jelas','row2'=> ':  ..............................'),
                      array('row1'=>'Tanggal Terima' ,'row2'=> ':  ..............................'),
                      array('row1'=>'' ,'row2'=> ''),
                     // array('row1'=>'' ,'row2'=> ''),
                    //  array('row1'=>'' ,'row2'=> ''),
                      array('row1'=>'Tanda Tangan','row2'=> ':  ..............................'),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>''),'',array
	 ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>180,'justification' => 'left'),
	 )));
	$this->cezpdf->ezSetDy(12);
          $check = array(array('row1'=>''),
                      array('row1'=>''),
                      array('row1'=>'' ),
                      array('row1'=>''),
                );
     $this->cezpdf->ezTable($check,array('row1'=>'Final Check'),'',array
         ('rowGap'=>'2','xPos'=>390,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>1,'cols'=>array        (
         'row1'=>array('width'=>100,'justification' => 'center'),
         )));

         $this->cezpdf->ezSetY(385);

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=delivery_receipt.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }


    function printdeliveryReceipt5() {
    $fld_btid =  $this->uri->segment(3);
    $inv = $this->db->query ("select fld_baidc from tbl_bth where fld_btid='$fld_btid' limit 1");
    $inv = $inv->row();
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'del_date',
    if(t0.fld_btp04='0000-00-00',date_format(now(),'%d-%m-%Y'),date_format(t0.fld_btp04,'%d-%m-%Y')) 'date_now',
    format(t0.fld_btamt,0) 'total',
    t1.fld_benm 'owner',
    t3.fld_empnm 'posted',
    t2.fld_bedivcd 'code',
    concat(t6.fld_tyvalnm, '. ', t4.fld_benm) 'customer',
    t0.fld_btp02 'attn',
    t0.fld_btcmt 'note',
    if(date_format(t0.fld_btp04, '%d-%m-%Y') = '00-00-0000','',date_format(t0.fld_btp04,'%d-%m-%Y')) 'deliverdate',
    if(date_format(t0.fld_btdt4, '%d-%m-%Y')= '00-00-0000','',date_format(t0.fld_btdt4, '%d-%m-%Y')) 'deliverdate2',
    concat(if(t5.fld_beaddrplc != '',concat(t5.fld_beaddrplc,'\n'),'') , t5.fld_beaddrstr) 'address'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baido
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_baidp
    left join dnxapps.tbl_be t4 on t4.fld_beid=t0.fld_baidc and t4.fld_betyid=5
    left join dnxapps.tbl_beaddr t5 on t5.fld_beaddrid=t0.fld_btp01
    left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = t4.fld_beprefix and t6.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t1.fld_btno 'item',
    date_format(t1.fld_btdt,'%Y-%m-%d')'date',
    ifnull(t1.fld_btnoalt,t1.fld_btp23) 'bl',
    ifnull(t1.fld_btp20,0) 'si',
    #if(t1.fld_btp23='',format(t1.fld_btbalance,2),'') 'dtl_kwitansi',
    if(t1.fld_btp23!='',format(t1.fld_btbalance,2),format(t1.fld_btbalance,2)) 'dtl_invoice',
    if(t1.fld_btp23='',t1.fld_btbalance,'') 'kwitansi',
    if(t1.fld_btp23!='',t1.fld_btbalance,t1.fld_btbalance) 'invoice',
    format(t1.fld_btbalance,0) 'subtotal',
    t0.fld_btp01
    from tbl_btd_invdel t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=39
    where
    t0.fld_btidp='$fld_btid'
    order by  t1.fld_btno asc
    ");
//   $dataDtl = $getDataDtl->row();
/*   $sum_commission =0;
   $sum_kwitansi =0;
   $sum_commission = $sum_commission + $getdatadtl->invoice ;
   $sum_kwitansi = $sum_kwitansi + $getdatadtl->kwitansi ;
*/
 $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
      		    $count = count($datadtl);
		    for ($i=0; $i<$count; ++$i)
		    {
		      $counteor = $counteor + 1;
			 ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
		    }

    }

           # ---  deliverdate > get date from two condition
    if(empty($data->deliverdate2)){
      (empty($data->deliverdate))? $_deliverdate = '' : $_deliverdate = $data->deliverdate;
    }else{
      (empty($data->deliverdate2))? $_deliverdate = '' : $_deliverdate = $data->deliverdate2;
    }
    # --- end

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
       // $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,788,30);
        //$this->cezpdf->addText(90,810,10,'PT.Dunia Express Transindo     ');
        //$this->cezpdf->addText(90,800,10,'Jl.Agung Karya VII No.1 Sunter     ');
       // $this->cezpdf->addText(90,790,10,'Jakarta Utara');
        $this->cezpdf->ezSetMargins(50,35,10,15);

    $this->cezpdf->ezText("Delivery Receipt" . "  ", 16, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(12);
         $header = array(array('row1'=>'Delivery Number','row2'=>': '.$data->fld_btno,'row3'=>''),
                         #array('row1'=>'Date','row2'=>': '.$data->del_date,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date','row2'=>': '.$data->deliverdate,'row3'=>'Sender','row4'=>': '.$data->posted),
                         array('row1'=>'Date 2','row2'=>': '.$data->deliverdate2,'row3'=>''),
                         array('row1'=>'Customer','row2'=>': '.$data->customer,'row3'=>''),
                         array('row1'=>'Address','row2'=>': '.$data->address,'row3'=>''),
                       	 array('row1'=>'Attn','row2'=>': '.$data->attn,'row3'=>''),
                         array('row1'=>'Note','row2'=>': '.$data->note,'row3'=>'')
                );
     $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>90,'justification' => 'left'),
         'row2'=>array('width'=>240,'justification' => 'left'),
         'row3'=>array('width'=>70,'justification' => 'left'),
         'row4'=>array('width'=>180,'justification' => 'left'),
         )));

//detail list
    if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-10);
  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
   if ($inv->fld_baidc == 5125){
   $this->cezpdf->ezTable($datadtl,array('count'=>'No','date'=>'Date','item'=>'Invoice No','bl'=>'B/L No','dtl_invoice'=>'Total Invoice','dtl_kwitansi'=>'Total Kwitansi'),'',
   array('rowGap'=>'0.3','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10','cols'=>array        (
   'count'=>array('width'=>25,'justification'=>'center'),
   'date'=>array('width'=>70,'justification'=>'center'),
   'item'=>array('width'=>110,'justification'=>'center'),
#   'si'=>array('width'=>90,'justification'=>'center'),
   'bl'=>array('width'=>180,'justification'=>'center'),
   'dtl_invoice'=>array('width'=>80,'justification'=>'right'),
   'dtl_kwitansi'=>array('width'=>80,'justification'=>'right'),
    )));
   }else
   {
   $this->cezpdf->ezTable($datadtl,array('count'=>'No','date'=>'Date','item'=>'Invoice No','si'=>'SI No','bl'=>'B/L No','dtl_invoice'=>'Total Invoice','dtl_kwitansi'=>'Total Kwitansi'),'',
   array('rowGap'=>'0.3','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>500,'shaded'=>0,'fontSize'=>'10','cols'=>array        (
   'count'=>array('width'=>25,'justification'=>'center'),
   'date'=>array('width'=>70,'justification'=>'center'),
   'item'=>array('width'=>110,'justification'=>'center'),
   'si'=>array('width'=>90,'justification'=>'center'),
   'bl'=>array('width'=>90,'justification'=>'center'),
   'dtl_invoice'=>array('width'=>80,'justification'=>'right'),
   'dtl_kwitansi'=>array('width'=>80,'justification'=>'right'),
    )));}
   $sum_commission =0;
   $sum_kwitansi =0;
   $getDataDtl = $getDataDtl->result();
   foreach ($getDataDtl as $rgetDataDtl) {
   $sum_commission =$sum_commission+ $rgetDataDtl->invoice;
   $sum_kwitansi = $sum_kwitansi + $rgetDataDtl->kwitansi ;
  }
   $data_sum = array(
                         array('row1'=>'Total',
                               'row2'=>number_format($sum_commission,2,',','.'),
                               'row3'=>''
                               )
                          );
       if($ivn->fld_baidc == 5125){
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
        'cols'=>array(
        'row1'=>array('width'=>255,'justification'=>'center'),
        'row2'=>array('width'=>80,'justification'=>'right'),
        'row3'=>array('width'=>80,'justification'=>'right')
        )));
       }else
      {
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>50,'xOrientation'=>'right','width'=>500,'fontSize'=>'9',
        'cols'=>array(
        'row1'=>array('width'=>385,'justification'=>'center'),
        'row2'=>array('width'=>80,'justification'=>'right'),
        'row3'=>array('width'=>80,'justification'=>'right')
        )));}
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(12);
	 $acc = array(array('row1'=>'Jakarta, '.$_deliverdate),
                      array('row1'=>'Nama Jelas','row2'=> ':  ..............................'),
                      array('row1'=>'Tanggal Terima' ,'row2'=> ':  ..............................'),
                      array('row1'=>'' ,'row2'=> ''),
                     // array('row1'=>'' ,'row2'=> ''),
                    //  array('row1'=>'' ,'row2'=> ''),
                      array('row1'=>'Tanda Tangan','row2'=> ':  ..............................'),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>''),'',array
	 ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>180,'justification' => 'left'),
	 )));
	 $this->cezpdf->ezSetDy(12);
          $check = array(array('row1'=>''),
                      array('row1'=>''),
                      array('row1'=>'' ),
                      array('row1'=>''),
                );
        $this->cezpdf->ezTable($check,array('row1'=>'Final Check'),'',array
         ('rowGap'=>'2','xPos'=>390,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>1,'cols'=>array        (
         'row1'=>array('width'=>100,'justification' => 'center'),
         )));

         $this->cezpdf->ezSetY(385);

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=delivery_receipt.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

    function print_Invoice_detail($fld_btid) {
    $filename = 'Invoice_Detail-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                                t1.fld_btno  'number',
                                t1.fld_btp04 'amount1',
				t1.fld_btbalance 'amount',
                                t0.fld_btp01 'remarks'
                              from tbl_btd_invdel t0
                              left join tbl_bth t1 on t1.fld_btid=t0.fld_btiid and t1.fld_bttyid=41
                        where
                        t0.fld_btidp=$fld_btid
                                ");
   # $this->load->helper('csv');
   # query_to_csv($data, TRUE, 'ST'.date('Ymd') . '.csv');
   echo "No,Invoice Number,Amount,Remarks \n";
   $cash = 0;
   $save = 0;
   $no =0;
   foreach($data->result() as $rdata) {
     $no =$no + 1;
     echo "\"$no\",\"$rdata->number\",\"$rdata->amount\",\"$rdata->remarks\"\n";
      $total = $total + $rdata->amount;
 #    $converted = $save + $rdata->Converted ;
  #   $down = $save + $rdata->Down;
   #  $pph = $save + $rdata->pph;
    # $pphfinal = $save + $rdata->pphfinal;
   }
   echo "\"\",\"Total\",\"$total\",\"\",\"\",\"\",\"\",\"\"\n";
  }
  function cekCashApproval ($fld_btid) {
    $ctid = $this->session->userdata('ctid');
    $data = $this->db->query("select if(t1.fld_bttyid = 77,t1.fld_btstat,3) 'status' from tbl_bth t0
                              left join tbl_bth t1 on t1.fld_btno = t0.fld_btnoalt
                              where t0.fld_btid = $fld_btid and t0.fld_bttyid in (80) limit 1");
    $data = $data->row();
    if ($data->status != 3) {
      $this->message("You should completing Cash Approval Process ...");
    }
    $this->db->query("update tbl_bth set fld_btp25 = $ctid where fld_btid = $fld_btid limit 1");
  }


  function printBatchJournal($batch_id) {
    $batch = $this->db->query("select * from tbl_btd_invdel tz0 where tz0.fld_btidp = $batch_id");
    $batch = $batch->result();
    $this->load->library('cezpdf');
    $this->cezpdf->ezSetMargins(10,15,10,25);

    foreach ($batch as $rbatch) {
    $this->cezpdf->ezNewPage();
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
    $fld_btid = $rbatch->fld_btiid;

    $getData =$this->db->query("
    select
    t0.fld_journalid,
    t0.fld_journaldt,
    t0.fld_journaldesc,
    t0.fld_journalamt,
    format(t0.fld_journalamt,2) 'amount',
    t2.fld_bttynm,
    t1.fld_btno,
    t3.fld_coacd,
    t3.fld_coanm,
    t0.fld_journalno
    from  tbl_journal t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_btid
    left join tbl_btty t2 on t2.fld_bttyid=t1.fld_bttyid
    left join tbl_coa t3 on t3.fld_coaid=t0.fld_coaid
    where
    t0.fld_btid='$fld_btid'
    order by t0.fld_journalamt desc
    ");
    $data = $getData->row();
    $detail = $getData->result_array();
    $tot_amount = 0;
    $count = count($detail);
      for ($i=0; $i<$count; ++$i) {
        $tot_amount = $tot_amount + $detail[$i]['fld_journalamt'];
      }
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',40,775,35);
        $this->cezpdf->addText(80,800,9,'PT.Dunia Express Transindo     ');
        $this->cezpdf->addText(80,790,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(80,780,9,'Jakarta Utara');

    $this->cezpdf->ezText('JOURNAL MEMO',14, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
    array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'shaded','xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));

        $this->cezpdf->addText(30,750,10,'Journal Number');
        $this->cezpdf->addText(110,750,10,':');
        $this->cezpdf->addText(120,750,10,$data->fld_journalno);
        $this->cezpdf->addText(30,740,10,'Date');
        $this->cezpdf->addText(110,740,10,':');
        $this->cezpdf->addText(120,740,10,$data->fld_journaldt);
        $this->cezpdf->addText(30,730,10,'Reff. Number');
        $this->cezpdf->addText(110,730,10,':');
        $this->cezpdf->addText(120,730,10,$data->fld_btno);
        $this->cezpdf->addText(30,720,10,'Transaction Type');
        $this->cezpdf->addText(110,720,10,':');
        $this->cezpdf->addText(120,720,10,$data->fld_bttynm);

        $this->cezpdf->addText(15,702,10,'Code');
        $this->cezpdf->addText(150,702,10,'Name');
        $this->cezpdf->addText(360,702,10,'Description');
        $this->cezpdf->addText(535,702,10,'Amount');


        $this->cezpdf->ezSetDy(-95);
        $this->cezpdf->ezTable($detail,array('fld_coacd'=>'','fld_coanm'=>'','fld_journaldesc'=>'','amount'=>''),'',
   array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>680,'shaded'=>0,'fontSize'=>'8',
    'cols'=>array('fld_coacd'=>array('width'=>40), 'fld_coanm'=>array('width'=>200),'fld_journaldesc'=>array('width'=>260),'amount'=>array('width'=>70, 'justification'=>'right'))));
        $this->cezpdf->ezText('Total Balance ' . ' :                                   ' . $tot_amount,14, array('justification' => 'right'));
    }
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=journal_memo.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }



  function submitPOD($fld_btid,$dtsa,$dtso,$fld_btiid,$fld_btp01) {
    $fld_baidp =  $location = $this->session->userdata('ctid');
    $data = $this->db->query("select t0.fld_btid,
                             t0.fld_btno,
                             t1.fld_bticd,
                             t2.fld_benm,
                             concat(t4.fld_areanm, ' > ', t5.fld_areanm) 'route'
                             from tbl_bth t0
                             left join dnxapps.tbl_bti t1 on t1.fld_btiid=t0.fld_btp12
                             left join dnxapps.tbl_be t2 on t2.fld_beid=t0.fld_baidc
                             left join dnxapps.tbl_route t3 on t3.fld_routeid =t0.fld_btp09
                             left join dnxapps.tbl_area t4 on t4.fld_areaid=t3.fld_routefrom
                             left join dnxapps.tbl_area t5 on t5.fld_areaid=t3.fld_routeto
                             left join tbl_bth t6 on t6.fld_btno = t0.fld_btnoalt
                             left join tbl_trk_settlement t7 on t7.fld_btreffid = t0.fld_btid
                             where
                             t0.fld_bttyid in (80)
                             and
                             t6.fld_btstat = 3
                             and
                             t0.fld_btstat = 3
                             /*and
                             if('$fld_btiid' = 1,t6.fld_bttyid = 80,t0.fld_btp26 != 1)
                             */ and
                             if('$fld_btp01' = 1,t6.fld_bttyid = 49,1)
                             and
                             if('$fld_btiid' = 1,t6.fld_bttyid = 112,1)
                             #and
                             #if('$veh_group' = 1,t6.fld_bttyid = 112,t0.fld_bttyid = 49)
                            # and
                            # t0.fld_btp25 = $fld_baidp
                             and
                             ifnull(t7.fld_btreffid,0) = 0
                             and
                             date_format(t0.fld_btdt,'%Y-%m-%d') between  date_format('$dtsa','%Y-%m-%d') and date_format('$dtso','%Y-%m-%d')
                             ");
    $data = $data->result();
   # echo "test=$fld_btid";
    #exit;
    foreach ($data as $rdata) {

          $this->db->query("insert ignore into tbl_trk_settlement (fld_btidp,fld_btreffid,fld_btno,fld_vehicle,fld_trk_settlementdesc,fld_customer) values ('$fld_btid','$rdata->fld_btid','$rdata->fld_btno','$rdata->fld_bticd'
                            ,'$rdata->route','$rdata->fld_benm')");
    }

   /* $detail = $this->db->query("select t0.fld_btid,
                             t0.fld_btno,
                                                         t1.fld_bticd,
                             t2.fld_benm,
                             concat(t4.fld_areanm, ' > ', t5.fld_areanm) 'route',
                             t8.fld_btdesc, t8.fld_btid id
                             from tbl_bth t0
                             left join dnxapps.tbl_bti t1 on t1.fld_btiid=t0.fld_btp12
                             left join dnxapps.tbl_be t2 on t2.fld_beid=t0.fld_baidc
                             left join dnxapps.tbl_route t3 on t3.fld_routeid =t0.fld_btp09
                             left join dnxapps.tbl_area t4 on t4.fld_areaid=t3.fld_routefrom
                             left join dnxapps.tbl_area t5 on t5.fld_areaid=t3.fld_routeto
                             left join tbl_bth t6 on t6.fld_btno = t0.fld_btnoalt
                             LEFT JOIN dnxapps.tbl_btd_route t8 ON t8.fld_btidp=t0.fld_btid
                                                         LEFT JOIN tbl_trk_settlement t7 ON t7.fld_btreffid = t0.fld_btid and t7.fld_btflag=3
                             where
                             t0.fld_bttyid in (80)
and
                             t6.fld_btstat = 3
                             and
                             t0.fld_btstat = 3
                             and
                             if('$fld_btiid' = 1,t6.fld_bttyid = 80,t0.fld_btp26 != 1)
                             and
                             if('$fld_btp01' = 1,t6.fld_bttyid = 49,t0.fld_btp26 != 1)
                             and
                             t0.fld_btp25 = $fld_baidp
                             and
                             ifnull(t7.fld_btreffid,0) = 0
                             and date_format(t0.fld_btdt,'%Y-%m-%d') between  date_format('$dtsa','%Y-%m-%d') and date_format('$dtso','%Y-%m-%d')
                             and t8.fld_btid IS NOT NULL");

                        if ($detail->num_rows() > 0)
                        {
                                $i=1;
                                $a=0;
                                foreach ($detail->result() as $det)
                                {
                                        if ($i<=1){
                                                $no=$det->fld_btno;
                                                $fldbtno=$det->fld_btno;
                                                $a=1;
                                                $bno=$fldbtno."-".$a;
                                        } else {
                                                $no=$det->fld_btno;
                                                if ($fldbtno==$no)
                                                {
                                                        $a=$a+1;
                                                        $bno=$fldbtno."-".$a;
                                                } else {
                                                        $a=1;
                                                        $fldbtno=$no;
                                                        $bno=$fldbtno."-".$a;
						}
                                        }
                                        $this->db->query("insert ignore into tbl_trk_settlement (fld_btidp,fld_btflag,fld_btreffid,fld_btno,fld_vehicle,fld_trk_settlementdesc,fld_customer)
                                         values ('$fld_btid','3','$det->fld_btid','$bno','$det->fld_bticd','$det->route','$det->fld_benm')");
                                }
                        }*/

  }


  function setPaymentTax($fld_btid) {
    $detail = $this->db->query("select  t0.fld_btp01,t0.fld_btp02,t0.fld_btp03,t0.fld_btamt01
				from tbl_btd_finance t0
				left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp where t0.fld_btidp=$fld_btid");
    $detail2 = $this->db->query("select * from tbl_btd_addpayment where fld_btidp=$fld_btid");
    $pph23 = 0;
    $pphfinal = 0;
    $total_amount = 0;
    $add_payment = 0;
    $dp = 0;
    $bank_deduction = 0;
    foreach($detail->result() as $rdetail) {
      $pph23 = $pph23 + $rdetail->fld_btp01;
      $pphfinal = $pphfinal + $rdetail->fld_btp02;
      $total_amount = $total_amount + $rdetail->fld_btamt01;
      $dp = $dp + $rdetail->fld_btuamt01;
    }

    foreach($detail2->result() as $rdetail2) {
      $bank_deduction = $bank_deduction + ($rdetail2->fld_btamt01 * -1);
      $add_payment = $add_payment + $rdetail2->fld_btamt01;
    }

    $total_payment = $total_amount - ($pph23 + $pphfinal) + $bank_deduction + $dp;
    ### Update Total Amount
    $this->db->query("update tbl_bth set fld_btp01='$pph23' where fld_btid='$fld_btid' limit 1");
    $this->db->query("update tbl_bth set fld_btp02='$pphfinal' where fld_btid='$fld_btid' limit 1");
    $this->db->query("update tbl_bth set fld_btamt='$total_amount' where fld_btid='$fld_btid' limit 1");
    $this->db->query("update tbl_bth set fld_btuamt='$total_payment' where fld_btid='$fld_btid' limit 1");
    $this->db->query("update tbl_bth set fld_btp03='$add_payment' where fld_btid='$fld_btid' limit 1");
    $this->db->query("update tbl_bth set fld_btp04='$dp' where fld_btid='$fld_btid' limit 1");

    ### Update Converted Amount
    $this->db->query("update tbl_btd_finance set fld_btp03=(fld_btamt01 * fld_btqty02) where fld_btidp=$fld_btid");
  }
  function exportPODSubmit($fld_btid) {
    $filename = 'PODSubmit-'.date('Ymd') . '.xls';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $posting = $this->db->query("SELECT fld_btno 'no'  FROM `tbl_bth` WHERE `fld_btid` = $fld_btid")->row();

    $data = $this->db->query("select
                              @s:=@s+1 number,
                              t2.fld_btno 'do',t3.fld_empnm 'driver',t4.fld_btno'cont',t4.fld_btdesc'desc',t0.*
                              from tbl_trk_settlement t0
                              left join tbl_bth t1 on t1.fld_btid=t0.fld_btreffid
			      left join tbl_bth t2 on t2.fld_btno= t1.fld_btnoalt and t2.fld_bttyid=77
				left join hris.tbl_truck_driver t3 on t3.fld_empid=t2.fld_btp11
				left join tbl_btd t4 on t4.fld_btidp =t1.fld_btid, (SELECT @s:= 0) AS s
                              where
                              t0.fld_btidp=$fld_btid
                              order by t1.fld_lup ASC
                              ");
    echo "<h3>Posting Number : $posting->no </h3>";
    echo "<table border = 1>
           <tr>
       <td>No</td>
	     <td>DO Number</td>
	     <td>POD Number</td>
	     <td>Driver</td>
             <td>Customer</td>
             <td>Vehicle Number</td>
             <td>Route</td>
             <td>Container Number</td>
             <td>Document Number</td>
           </tr>";
   foreach($data->result() as $rdata) {
   echo "<tr>
       <td>" . $rdata->number . "</td>
	     <td>" . $rdata->do . "</td>
             <td>" . $rdata->fld_btno . "</td>
	     <td>" . $rdata->driver . "</td>
             <td>" . $rdata->fld_customer . "</td>
	     <td>" . $rdata->fld_vehicle . "</td>
             <td>" . $rdata->fld_trk_settlementdesc . "</td>
	     <td>" . $rdata->cont . "</td>
	     <td>" . $rdata->desc . "</td>
         </tr>";
   }

   echo "</table>";
  }

  function print_Payment_detail($fld_btid) {
    $filename = 'Paymant_Detail-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t1.fld_btno 'invoice',
                              t0.fld_btamt01 'Amount',
                              t0.fld_btqty02 'Rate',
                              t0.fld_btp03 'Converted',
                              t0.fld_btuamt01 'Down',
                              t0.fld_btp01 'pph',
			      t0.fld_btp02 'pphfinal',
			      t0.fld_btdesc'Notes'
                              from tbl_btd_finance t0
                              left join tbl_bth t1 on t1.fld_btid=t0.fld_btreffid and t1.fld_bttyid in (41,82)
                        where
                        t0.fld_btidp=$fld_btid
                                ");
   # $this->load->helper('csv');
   # query_to_csv($data, TRUE, 'ST'.date('Ymd') . '.csv');
   echo "Invoice Number,Amount,Rate,Converted Payment,Down Payment, PPH 23,PPH Final, Notes \n";
   $cash = 0;
   $save = 0;
   foreach($data->result() as $rdata) {
     echo "\"$rdata->invoice\",\"$rdata->Amount\",\"$rdata->Rate\",\"$rdata->Converted\",\"$rdata->Down\",\"$rdata->pph\",\"$rdata->pphfinal\",\"$rdata->Notes\"\n";
     $cash = $cash + $rdata->Amount;
     $converted = $save + $rdata->Converted ;
     $down = $save + $rdata->Down;
     $pph = $save + $rdata->pph;
     $pphfinal = $save + $rdata->pphfinal;
   }
   echo "\"Total\",\"$cash\",\"\",\"$converted\",\"$down\",\"$pph\",\"$pphfinal\"\n";
  }

  function printDOTTruck($fld_btid) {
   $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
    select
    t0.fld_btstat,
    t0.fld_btid,
    t0.fld_btno,
    t0.fld_btp17 'chasis',
    t15.fld_tyvalnm 'activity',
    date_format(t0.fld_btdt,'%d-%m-%Y %H:%i') 'date',
    date_format(t0.fld_btdtsa,'%d-%m-%Y') 'plan_date',
    date_format(t0.fld_btdt,'%H:%i') 'time',
    format(t0.fld_btp01,2) 'fld_btp01',
    t8.fld_empnm 'posted_by',
    concat(t17.fld_tyvalnm, '. ', t2.fld_benm) 'customer',
    t0.fld_btp02 'destination1',
    t12.fld_areanm 'destination',
    t0.fld_btp13'consignor',
    t0.fld_btp10'consignee',
    format(t0.fld_btp18,2)'lolo',
    format(t0.fld_btuamt,2) 'addt_cash',
    if(t0.fld_bttyid = 77,t3.fld_bticd,t0.fld_btp12) 'v_number',
    t4.fld_tyvalnm 'v_type',
    if(t0.fld_bttyid = 77,t10.fld_empnm,t0.fld_btp11) 'driver',
    t0.fld_btdesc 'desc',
    t1.fld_empnm 'chasier',
    format(t0.fld_btamt,2) 'delivery_cash',
    t0.fld_btloc 'location',
    t13.fld_empnm 'asst_driver',
    t0.fld_btp21 'depo',
    t0.fld_btp15 'cont_s',
    t16.fld_tyvalnm 'ch_type',
    t14.fld_bedivnm,
    t0.fld_btnoreff 'do_number'

    from tbl_bth t0
    left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_baidv
    left join dnxapps.tbl_be t2 on t2.fld_beid = t0.fld_baidc
    left join dnxapps.tbl_bti t3 on t3.fld_btiid = t0.fld_btp12
    left join tbl_tyval t4 on t4.fld_tyvalcd = t0.fld_btflag and t4.fld_tyid=19
    left join hris.tbl_emp t8 on t8.fld_empid=t0.fld_baidp
    left join hris.tbl_truck_driver t10 on t10.fld_empid=t0.fld_btp11
    left join hris.tbl_truck_driver t13 on t13.fld_empid=t0.fld_btp03
    left join dnxapps.tbl_route t11 on t11.fld_routeid=t0.fld_btp09
    left join dnxapps.tbl_area t12 on t12.fld_areaid=t11.fld_routeto
    left join hris.tbl_bediv t14 on t14.fld_bedivid=t0.fld_btiid
    left join tbl_tyval t15 on t15.fld_tyvalcd = t0.fld_btp05 and t15.fld_tyid=96
    left join tbl_tyval t16 on t16.fld_tyvalcd=t0.fld_btp17 and t16.fld_tyid=16
    left join dnxapps.tbl_tyval t17 on t17.fld_tyvalcd = t2.fld_beprefix and t17.fld_tyid = 173
    where
    t0.fld_btid='$fld_btid'
    ");
        $data = $getData->row();

    if($data->fld_btstat == 1) {
       $this->message("Cannot continue the process, transaction status still New ...");
    }

	$getdetail =$this->db->query("
    select t0.fld_btid,t0.fld_btidp,t0.fld_btno'cont',t0.fld_btreffid,t1.fld_tyvalnm 'size',t0.fld_btp01 'prod',t0.fld_btp02 'term',
        date_format(t0.fld_btdtsa,'%d-%M-%Y')'stuf',
        date_format(t0.fld_btdtso,'%d-%M-%Y')'demu',

        t0.fld_btdesc 'note',
        if(t0.fld_btiid=1,1,'')'contA',
        if(t0.fld_btiid=1,2,'')'contB',
        if(t0.fld_btiid=1,3,'')'contC',
        if(t0.fld_btiid=1,4,'')'contD',
    t0.fld_btiid,t0.fld_btp01,t0.fld_btp02,t0.fld_btdtso,t0.fld_btdtsa,t0.fld_btdesc
    from tbl_btd t0
	left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_btp03 and t1.fld_tyid=28
    where
    t0.fld_btidp='$fld_btid'
    limit 0,1
    ");
        $detail = $getdetail->row();
	$getdetail1 =$this->db->query("
    select t0.fld_btid,t0.fld_btidp,t0.fld_btno'cont',t0.fld_btreffid,t1.fld_tyvalnm 'size',t0.fld_btp01 'prod',t0.fld_btp02 'term',
        date_format(t0.fld_btdtsa,'%d-%M-%Y')'stuf',
        date_format(t0.fld_btdtso,'%d-%M-%Y')'demu',
        t0.fld_btdesc 'note',
        if(t0.fld_btiid=1,1,'')'contA',
        if(t0.fld_btiid=1,2,'')'contB',
        if(t0.fld_btiid=1,3,'')'contC',
        if(t0.fld_btiid=1,4,'')'contD',
    t0.fld_btiid,t0.fld_btp01,t0.fld_btp02,t0.fld_btdtso,t0.fld_btdtsa,t0.fld_btdesc
    from tbl_btd t0
	left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_btp03 and t1.fld_tyid=28
    where
    t0.fld_btidp='$fld_btid'
    limit 1,1
    ");
        $detail1 = $getdetail1->row();

        $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
    $this->cezpdf->ezSetMargins(0,5,10,5);
#jika combo
	if($data->chasis == 3){
	if($data->location == 1)  {
    $this->cezpdf->ezText('PT. DUNIA EXPRESS',10, array('justification' => 'left'));
$this->cezpdf->ezText('WAREHOUSING, CONTAINER DEPOT, TRUCKING, EXPORT, IMPORT, LOGISTICS', 10, array('justification' => 'left'));
#   if($data->location == 1)  {
    $this->cezpdf->ezText('Jl. AGUNG KARYA VII NO 1. SUNTER JAKARTA 14340', 10, array('justification' => 'left'));
    $this->cezpdf->ezText('PHONE : (021)650 5603 - (021)651 1137   FAX : (021)650 5590 - (021)651 0454', 10, array('justification' => 'left'));
 $this->cezpdf->ezSetDy(-2);
$this->cezpdf->addText(20,35,10,' White: Trucking          Red : Security');
        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
                );
 $this->cezpdf->addText(85,80,11,$data->posted_by);
    } else {
 $this->cezpdf->ezSetDy(-46);

        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
                );
 $this->cezpdf->addText(95,80,11,$data->posted_by);
 $this->cezpdf->addText(20,35,10,'White : Driver        Red : Trucking Staff      Yellow : Chasier          Green : Security');
    }

        $this->cezpdf->addText(150,285,15,'TRAILER ORDER');
        $this->cezpdf->setStrokeColor(0,0,0);
        $this->cezpdf->line(10, 310, 595, 310);
        $this->cezpdf->line(10, 270, 595, 270);
        $this->cezpdf->line(10, 50, 595, 50);
        $this->cezpdf->line(10, 310, 10, 50);
        $this->cezpdf->line(400, 310, 400, 270);
        $this->cezpdf->line(595, 310, 595, 50);
        $this->cezpdf->line(80, 75, 170, 75);
        $this->cezpdf->line(430, 75, 535, 75);
 $this->cezpdf->ezSetDy(-10);

        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
 );
    $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>405,'xOrientation'=>'right','width'=>200,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>80),'row2'=>array('width'=>120))));
##Print Detail
   $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));
        #main left
        $this->cezpdf->addText(30,250,10,'Pelanggan');
        $this->cezpdf->addText(30,235,10,'Tujuan');
        $this->cezpdf->addText(30,220,10,'No. Pol');
        $this->cezpdf->addText(30,190,10,'Supir');
        $this->cezpdf->addText(30,175,10,'Uang Jalan');
         $this->cezpdf->addText(30,205,10,'Lokasi Muat');
         $this->cezpdf->addText(30,130,10,'Biaya LoLo');
         $this->cezpdf->addText(30,115,10,'Depot Container');
        $this->cezpdf->addText(30,160,10,'Biaya Tambahan');
	$this->cezpdf->addText(30,145,10,'Total');
         $this->cezpdf->addText(120,250,10,": $data->customer");
       $this->cezpdf->addText(120,235,10,": $data->destination");
       $this->cezpdf->addText(120,220,10,": $data->v_number");
       $this->cezpdf->addText(120,190,10,": $data->driver");
       $this->cezpdf->addText(120,175,10,": $data->delivery_cash");
        $this->cezpdf->addText(120,205,10,": $data->consignor");
        $this->cezpdf->addText(120,130,10,": $data->lolo");
        $this->cezpdf->addText(120,115,10,": $data->depo");
        $this->cezpdf->addText(120,160,10,": $data->addt_cash");
	$this->cezpdf->addText(120,145,10,": $data->fld_btp01");
#main right
        $this->cezpdf->addText(355,250,10,'Tanggal Muat     :');
        $this->cezpdf->addText(355,235,10,'Tanggal Bongkar:');
        $this->cezpdf->addText(355,220,10,'Jam Bongkar      :');
        $this->cezpdf->addText(475,220,10,'s/d   :');
        $this->cezpdf->addText(355,190,10,'Kenek');
        $this->cezpdf->addText(355,175,10,'Cont Number');
        $this->cezpdf->addText(355,205,10,'Lokasi Bongkar');
        $this->cezpdf->addText(355,160,10,'Cont Size');
	$this->cezpdf->addText(355,145,10,'Chasis Type');
        $this->cezpdf->addText(355,130,10,'Catatan');
        $this->cezpdf->addText(335,115,10,'Depot Container');
        $this->cezpdf->addText(430,175,10,": $detail->cont");
        $this->cezpdf->addText(430,160,10,": $detail->size");
        $this->cezpdf->addText(105,60,11,'Planner');
        $this->cezpdf->addText(455,60,11,'Driver');
        $this->cezpdf->addText(439,80,10,$data->driver);
        $this->cezpdf->addText(430,190,10,": $data->asst_driver");
        $this->cezpdf->addText(430,205,10,": $data->consignee");
        $this->cezpdf->addText(430,145,10,": $data->ch_type");
        $this->cezpdf->addText(430,130,10,": $data->desc");
        $this->cezpdf->addText(430,115,10,": $data->depo");
#page 2
	$this->cezpdf->ezNewPage();
	 $this->cezpdf->setStrokeColor(0,0,0);
	$this->cezpdf->ezSetY(367);
	if($data->location == 1)  {
    $this->cezpdf->ezText('PT. DUNIA EXPRESS',10, array('justification' => 'left'));
$this->cezpdf->ezText('WAREHOUSING, CONTAINER DEPOT, TRUCKING, EXPORT, IMPORT, LOGISTICS', 10, array('justification' => 'left'));
#   if($data->location == 1)  {
    $this->cezpdf->ezText('Jl. AGUNG KARYA VII NO 1. SUNTER JAKARTA 14340', 10, array('justification' => 'left'));
    $this->cezpdf->ezText('PHONE : (021)650 5603 - (021)651 1137   FAX : (021)650 5590 - (021)651 0454', 10, array('justification' => 'left'));
 $this->cezpdf->ezSetDy(-2);
$this->cezpdf->addText(20,35,10,' White: Trucking          Red : Security');
        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
                );
 $this->cezpdf->addText(85,80,11,$data->posted_by);
    } else {
 $this->cezpdf->ezSetDy(-46);

        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
                );
 $this->cezpdf->addText(95,80,11,$data->posted_by);
 $this->cezpdf->addText(20,35,10,'White : Driver        Red : Trucking Staff      Yellow : Chasier          Green : Security');
    }

        $this->cezpdf->addText(150,285,15,'TRAILER ORDER');
        $this->cezpdf->line(10, 310, 595, 310);
        $this->cezpdf->line(10, 270, 595, 270);
        $this->cezpdf->line(10, 50, 595, 50);
        $this->cezpdf->line(10, 310, 10, 50);
        $this->cezpdf->line(400, 310, 400, 270);
        $this->cezpdf->line(595, 310, 595, 50);
        $this->cezpdf->line(80, 75, 170, 75);
        $this->cezpdf->line(430, 75, 535, 75);
 $this->cezpdf->ezSetDy(-10);

        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
 );
    $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>405,'xOrientation'=>'right','width'=>200,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>80),'row2'=>array('width'=>120))));
##Print Detail
   $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));
        #main left
        $this->cezpdf->addText(30,250,10,'Pelanggan');
        $this->cezpdf->addText(30,235,10,'Tujuan');
        $this->cezpdf->addText(30,220,10,'No. Pol');
        $this->cezpdf->addText(30,190,10,'Supir');
        $this->cezpdf->addText(30,175,10,'Uang Jalan');
         $this->cezpdf->addText(30,205,10,'Lokasi Muat');
        $this->cezpdf->addText(30,160,10,'Biaya Tambahan');
	$this->cezpdf->addText(30,145,10,'Total');
	$this->cezpdf->addText(30,130,10,'Biaya Lolo');
         $this->cezpdf->addText(120,250,10,": $data->customer");
       $this->cezpdf->addText(120,235,10,": $data->destination");
       $this->cezpdf->addText(120,220,10,": $data->v_number");
       $this->cezpdf->addText(120,190,10,": $data->driver");
       $this->cezpdf->addText(120,175,10,": $data->delivery_cash");
        $this->cezpdf->addText(120,205,10,": $data->consignor");
        $this->cezpdf->addText(120,130,10,": $data->desc");
        $this->cezpdf->addText(120,160,10,": $data->addt_cash");
	$this->cezpdf->addText(120,145,10,": $data->fld_btp01");
	$this->cezpdf->addText(120,130,10,": $data->lolo");
#main right
        $this->cezpdf->addText(355,250,10,'Tanggal Muat     :');
        $this->cezpdf->addText(355,235,10,'Tanggal Bongkar:');
        $this->cezpdf->addText(355,220,10,'Jam Bongkar      :');
        $this->cezpdf->addText(475,220,10,'s/d   :');
        $this->cezpdf->addText(355,190,10,'Kenek');
        $this->cezpdf->addText(355,175,10,'Cont Number');
        $this->cezpdf->addText(355,205,10,'Lokasi Bongkar');
        $this->cezpdf->addText(355,160,10,'Cont Size');
	$this->cezpdf->addText(355,145,10,'Catatan');
        $this->cezpdf->addText(430,175,10,": $detail1->cont");
        $this->cezpdf->addText(430,160,10,": $detail1->size");
        $this->cezpdf->addText(105,60,11,'Planner');
        $this->cezpdf->addText(455,60,11,'Driver');
        $this->cezpdf->addText(439,80,10,$data->driver);
        $this->cezpdf->addText(430,190,10,": $data->asst_driver");
        $this->cezpdf->addText(430,205,10,": $data->consignee");
	$this->cezpdf->addText(430,145,10,": $data->desc");
	}
	else{
if($data->location == 1)  {
    $this->cezpdf->ezText('PT. DUNIA EXPRESS',10, array('justification' => 'left'));
$this->cezpdf->ezText('WAREHOUSING, CONTAINER DEPOT, TRUCKING, EXPORT, IMPORT, LOGISTICS', 10, array('justification' => 'left'));
#   if($data->location == 1)  {
    $this->cezpdf->ezText('Jl. AGUNG KARYA VII NO 1. SUNTER JAKARTA 14340', 10, array('justification' => 'left'));
    $this->cezpdf->ezText('PHONE : (021)650 5603 - (021)651 1137   FAX : (021)650 5590 - (021)651 0454', 10, array('justification' => 'left'));
 $this->cezpdf->ezSetDy(-2);
$this->cezpdf->addText(20,35,10,' White: Trucking          Red : Security');
        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
                );
 $this->cezpdf->addText(85,80,11,$data->posted_by);
    } else {
 $this->cezpdf->ezSetDy(-46);

        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
                );
 $this->cezpdf->addText(95,80,11,$data->posted_by);
 $this->cezpdf->addText(20,35,10,'White : Driver        Red : Trucking Staff      Yellow : Chasier          Green : Security');
    }

        $this->cezpdf->addText(150,285,15,'TRAILER ORDER');
        $this->cezpdf->line(10, 310, 555, 310);
        $this->cezpdf->line(10, 270, 555, 270);
        $this->cezpdf->line(10, 50, 555, 50);
        $this->cezpdf->line(10, 310, 10, 50);
        $this->cezpdf->line(400, 310, 400, 270);
        $this->cezpdf->line(555, 310, 555, 50);
        $this->cezpdf->line(80, 75, 170, 75);
        $this->cezpdf->line(430, 75, 535, 75);
 $this->cezpdf->ezSetDy(-10);

        $header = array(array('row1'=>'No. DO','row2'=>$data->fld_btno),
                  array('row1'=>'Tanggal','row2'=>$data->date),
                  array('row1'=>'Aktivitas','row2'=>$data->activity)
 );
    $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>405,'xOrientation'=>'right','width'=>180,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>50),'row2'=>array('width'=>130))));

          ##Print Detail
   $this->cezpdf->ezTable($datadtl,array('item'=>'','fld_btqty01'=>'','fld_unitnm'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250), 'justification'=>'right')));
        #main left
        $this->cezpdf->addText(30,250,10,'Pelanggan');
        $this->cezpdf->addText(30,235,10,'Tujuan');
        $this->cezpdf->addText(30,220,10,'No. Pol');
        $this->cezpdf->addText(30,190,10,'Lokasi Muat');
        $this->cezpdf->addText(30,175,10,'Supir');
	 $this->cezpdf->addText(30,205,10,'DO');
	 $this->cezpdf->addText(30,130,10,'Total');
         $this->cezpdf->addText(355,115,10,'Depot Cont.');
        $this->cezpdf->addText(30,115,10,'Biaya LoLo');
        $this->cezpdf->addText(30,160,10,'Uang Jalan');
	$this->cezpdf->addText(30,145,10,'Biaya Tambahan');
         $this->cezpdf->addText(120,250,10,": $data->customer");
       $this->cezpdf->addText(120,235,10,": $data->destination");
       $this->cezpdf->addText(120,220,10,": $data->v_number");
       $this->cezpdf->addText(120,190,10,": $data->consignor");
       $this->cezpdf->addText(120,175,10,": $data->driver");
	$this->cezpdf->addText(120,205,10,": $data->do_number");
	$this->cezpdf->addText(120,130,10,": $data->fld_btp01");
        $this->cezpdf->addText(430,115,10,": $data->lolo");
        $this->cezpdf->addText(120,115,10,": $data->depo");
        $this->cezpdf->addText(120,160,10,": $data->delivery_cash");
	$this->cezpdf->addText(120,145,10,": $data->addt_cash");
#main right
        $this->cezpdf->addText(355,250,10,'Planning');
        $this->cezpdf->addText(355,235,10,'Tanggal Muat     :');
        $this->cezpdf->addText(355,220,10,'Tanggal Bongkar:');
        $this->cezpdf->addText(355,205,10,'Jam Bongkar      :');
        $this->cezpdf->addText(475,205,10,'s/d   :');
        $this->cezpdf->addText(355,175,10,'Kenek');
        $this->cezpdf->addText(355,160,10,'Cont Number');
	$this->cezpdf->addText(355,190,10,'Lokasi Bongkar');
	$this->cezpdf->addText(355,145,10,'Cont Size');
	$this->cezpdf->addText(355,130,10,'Catatan');
        $this->cezpdf->addText(430,250,10,": $data->plan_date");
        $this->cezpdf->addText(430,160,10,": $detail->cont");
	$this->cezpdf->addText(430,145,10,": $detail->size");
        $this->cezpdf->addText(105,60,11,'Planner');
        $this->cezpdf->addText(455,60,11,'Driver');
        $this->cezpdf->addText(439,80,10,$data->driver);
        $this->cezpdf->addText(430,175,10,": $data->asst_driver");
	$this->cezpdf->addText(430,190,10,": $data->consignee");
	$this->cezpdf->addText(430,130,10,": $data->desc");
	}

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=do_trucking.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }
  function setTotalAdditionalCost ($fld_btid) {
    $btid = $this->db->query("select t0.fld_btrsrc from
                              tbl_btr t0
                              where t0.fld_btrdst = $fld_btid limit 1
                              ");
    $btid = $btid->row();

    $add_cost = $this->db->query("select sum(tx1.fld_btamt)'amount'  from
                                               tbl_btr tx0
                                               left join tbl_bth tx1 on tx1.fld_btid = tx0.fld_btrdst
                                               where
                                               tx0.fld_btrsrc = $btid->fld_btrsrc
                                               and tx1.fld_bttyid = 78
                                               and tx1.fld_btstat = 3");
    $add_cost = $add_cost->row();
    $this->db->query("update tbl_bth t0
                              set
                              t0.fld_btuamt = $add_cost->amount,
                              t0.fld_btp01 = t0.fld_btuamt + t0.fld_btamt
                              where t0.fld_btid =  $btid->fld_btrsrc
                              and t0.fld_bttyid in (77)
                              limit 1");
  }


  function TruckCashSettlementTrailer($fld_btid,$veh_group,$dtsa,$dtso,$fld_btflag,$fld_btiid,$fld_btp37) {
  $location = $this->session->userdata('location');
  $ctid = $this->session->userdata('ctid');
  $data = $this->db->query("select
                        tx0.fld_btid 'reffid',
                        if(tx0.fld_bttyid = 77,tx0.fld_btno,tx0.fld_btnoalt) 'do_number',
                        tx7.fld_empnm,
                        tx6.fld_benm,
                        tx2.fld_bticd,
                        tx0.fld_btamt 'amount_cost',
                        tx0.fld_btuamt 'amount_add',
                        tx0.fld_btp18 'amount_lolo',
                        tx0.fld_btamt  'amount',
                        tx10.fld_driverid 'driverid',
			tx10.fld_empid 'empid',
                        if(tx0.fld_bttyid=77,concat('Cash Advance'),if(tx0.fld_bttyid = 81,'LoLo',tx8.fld_tyvalnm))'cost_type',
                        concat('459.010') 'account',
                        1 'type',
                        if(tx0.fld_bttyid=77,tx0.fld_btp02,0) 'saving'

			from
			tbl_bth tx0
                        left join tbl_tyval tx1 on tx1.fld_tyvalcd=tx0.fld_btflag and tx1.fld_tyid=19
                        left join dnxapps.tbl_bti tx2 on tx2.fld_btiid=tx0.fld_btp12
                        left join dnxapps.tbl_route tx3 on tx3.fld_routeid =tx0.fld_btp09
                        left join dnxapps.tbl_area tx4 on tx4.fld_areaid=tx3.fld_routefrom
                        left join dnxapps.tbl_area tx5 on tx5.fld_areaid=tx3.fld_routeto
                        left join dnxapps.tbl_be tx6 on tx6.fld_beid=tx0.fld_baidc
                        left join hris.tbl_truck_driver tx7 on tx7.fld_empid=tx0.fld_btp11
                        left join tbl_tyval tx8 on tx8.fld_tyvalcd=tx0.fld_btdesc and tx8.fld_tyid=40
                        left join tbl_bti tx9 on tx9.fld_btiid=4559
                        left join tbl_driver tx10 on tx10.fld_empid = tx0.fld_btp11
                        where
                        tx0.fld_bttyid in (77,78,81)
			and
			tx0.fld_btstat=3
                        and
			if(tx0.fld_bttyid = 81,tx0.fld_btp39 = '$fld_btp37',1)
			and
                        date_format(tx0.fld_btdt,'%Y-%m-%d') between date_format('$dtsa','%Y-%m-%d') and date_format('$dtso','%Y-%m-%d')
                        and
                        tx1.fld_tyvalcfg = '$veh_group'
                        and tx0.fld_btloc = $location
                        and if('$fld_btflag' = 2,tx0.fld_bttyid in (81),tx0.fld_bttyid in (77,78))
                        ");

        foreach ($data->result() as $rdata) {
          $this->db->query("insert ignore into tbl_trk_settlement (fld_btidp,fld_btreffid,fld_btno,fld_driver,fld_customer,fld_vehicle,fld_trk_settlementamt01,fld_trk_settlementamt02,fld_trk_settlementamt03,fld_trk_settlementamt,
          fld_trk_settlementtype,fld_account,fld_btflag,fld_saving,fld_driverid,fld_empid) values ('$fld_btid','$rdata->reffid','$rdata->do_number'," .  $this->db->escape($rdata->fld_empnm) . ",'$rdata->fld_benm',
          '$rdata->fld_bticd','$rdata->amount_cost','$rdata->amount_add','$rdata->amount_lolo','$rdata->amount','$rdata->cost_type','$rdata->account','$rdata->type','$rdata->saving','$rdata->driverid','$rdata->empid')");
        }

    $this->db->query("update tbl_bth t0  set t0.fld_btamt = (select sum(tx.fld_trk_settlementamt) from tbl_trk_settlement tx  where tx.fld_btidp = $fld_btid),t0.fld_btuamt = (select sum(tx1.fld_saving) from tbl_trk_settlement tx1  where tx1.fld_btidp = $fld_btid) where t0.fld_btid = $fld_btid limit 1");

  }


  function exportJBP($fld_btid) {
    $filename = 'dataJBP-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              @s:=@s+1 number,
                              t2.fld_btnoalt 'DONumber',
                              t0.fld_customer 'Customer',
                              t6.fld_tyvalnm 'VehicleType',
                              t7.fld_tyvalnm 'VehicleTypeSubtitute',
                              if(t2.fld_bttyid = 70,
                                 (select tr1.fld_tyvalnm from tbl_btd tr0
                                  left join tbl_tyval tr1 on tr1.fld_tyvalcd = tr0.fld_btp03 and tr1.fld_tyid =28
                                  where
                                  tr0.fld_btidp = t2.fld_btid limit 1
                                 )
                                 ,t6.fld_tyvalnm) 'Size',
                              t1.fld_empnm 'PICInput',
                              t8.fld_empnm 'PICSetting',
                              t3.fld_btno 'PDSNumber'

                              from
                              tbl_trk_settlement t0
                              left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_bill_pic
                              left join tbl_bth t2 on t2.fld_btid = t0.fld_btreffid
                              left join tbl_bth t3 on t3.fld_btid =t0.fld_btidp and t3.fld_bttyid=91
                              left join tbl_tyval t6 on t6.fld_tyvalcd=t2.fld_btflag and t6.fld_tyid=19
                              left join tbl_tyval t7 on t7.fld_tyvalcd=t2.fld_btp35 and t7.fld_tyid=19
                              left join hris.tbl_emp t8 on t8.fld_empid=t0.fld_bill_pic2, (SELECT @s:= 0) AS s
                              where
                              fld_btreffid2=$fld_btid
                                ");
    echo "Number,DO Number,Customer,Vehicle Type,Vehicle Type Subtitute,PIC Input,PIC Setting,PDS Number \n";
    foreach ($data->result() as $rdata) {
      echo "$rdata->number,$rdata->DONumber,$rdata->Customer,$rdata->VehicleType,$rdata->VehicleTypeSubtitute,$rdata->PICInput,$rdata->PICSetting,$rdata->PDSNumber" . "\n";
    }
  }


  function exportSettlement($fld_btid) {
    $filename = 'Trucking-Settlement-Summarry-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t2.fld_tyvalnm 'FleetType',
			      t0.fld_vehicle 'VehicleNumber',
                              t0.fld_btno 'DONumber',
			      date_format(t1.fld_btdt,'%Y-%m-%d')'date',
			      t0.fld_driver 'Driver',
			      t1.fld_btnoreff 'jo_reff',
			      t6.fld_tyvalnm 'trip',
  			      t0.fld_trk_settlementtype 'st_type',
			      t0.fld_trk_settlementamt 'Cash',
                              t0.fld_customer,
                              t0.fld_saving 'Saving',t5.fld_lup,
                              t7.fld_btflag 'flag',
			      t8.fld_btno 'contNo'
			      from tbl_trk_settlement t0
			      left join tbl_bth t1 on t1.fld_btno=t0.fld_btno
                              left join dnxapps.tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=19
                              left join hris.tbl_truck_driver t3 on t3.fld_empid=t1.fld_btp11
			      left join tbl_bti t4 on t4.fld_btiid=t1.fld_btp12
			      left join tbl_aprvtkt t5 on t5.fld_btid=t1.fld_btid
			      left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd=t1.fld_btqty and t6.fld_tyid=41
                              left join tbl_bth  t7 on t7.fld_btid = t0.fld_btidp
                              left join tbl_btd t8 on t8.fld_btidp = t1.fld_btid
                        where
                        t0.fld_btidp=$fld_btid
                        group by t0.fld_btno,t0.fld_btflag,t0.fld_trk_settlementtype,t0.fld_btreffid
			ORDER BY t5.fld_lup ASC
                                ");
   echo "\"\", TRUCKING SETTLEMENT\n\n";
   echo "No,Vehicle Number,DO Number,Job Number,Customer,Container Number,DO Date,Driver,Type,Cash, Saving \n";
   $cash = 0;
   $save = 0;
   $no =0;
   foreach($data->result() as $rdata) {
   $no =$no + 1;
     echo "\"$no\",\"$rdata->VehicleNumber\",\"$rdata->DONumber\",\"$rdata->jo_reff\",\"$rdata->fld_customer\",\"$rdata->contNo\",\"$rdata->date\",\"$rdata->Driver\",\"$rdata->st_type\",\"" . $rdata->Cash . "\",\"" . $rdata->Saving . "\"\n";
     $cash = $cash + $rdata->Cash;
     $save = $save + $rdata->Saving;
   }
   $date = date('d-m-Y');
   $balance = 15000000 - $cash;
   echo "\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"Total\",\"" . $cash . "\",\"" .  $save . "\"\n\n";
   if ($rdata->flag == 1) {
      echo "\"\",\"Total Cash\",\"" . 15000000 . "\"\n";
      echo "\"\",\"Total Operational Cost\",\"" . $cash . "\"\n";
      echo "\"\",\"Total Balance\",\"" . $balance . "\"\n\n\n";
   } else {
      #echo "\"\",\"Total Cash\",\"" . 15000000 . "\"\n";
       echo "\"\",\"Total Operational Cost\",\"" . $cash . "\"\n";
      # echo "\"\",\"Total Balance\",\"" . $balance . "\"\n\n\n";
   }


   echo "\"Posting Date :\",\"" . $date . "\"\n";
   echo "\"Created By\",\"\",\"Checked By\",\"\",\"Acknowledge By\",\"\",\"Approved By\",\"\",\"Received By\"\n\n\n\n\n";
   echo "\"Shofa \",\"\",\"Mona\",\"\",\"Tonny Wijaya\",\"\",\"Elly Dwiyanti\",\"\",\"Fitrotun Chasanah\"\n\n";
   echo "\"Staff Trucking \",\"\",\"Chief Trucking\",\"\",\"Manager Trucking\",\"\",\"Finance SPV\",\"\",\"Cashier\"\n\n";


  }

  function exportInvoiceFaktur($fld_btid) {
    $filename = 'Container-Depot-Invoice-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t0.fld_btp01 'Customer',
                              t0.fld_inv01 'Depo',
                              t0.fld_inv02 'Invoice_Number',
                              t0.fld_dt01 'Invoice_Date',
			      date_format(t1.fld_btdt,'%Y-%m-%d')'Received_Date',
			      date_format(t3.fld_btdt,'%Y-%m-%d')'Delivere_Date',
			      t4.fld_tyvalnm'efaktur'
			      from tbl_btd_faktur t0
		 	      left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp and t1.fld_bttyid=90
			      left join tbl_btd_faktur t2 on t2.fld_btid=t0.fld_btreffid
			      left join tbl_bth t3 on t3.fld_btid=t2.fld_btreffid and t3.fld_bttyid=89
			      left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_val01 and t4.fld_tyid=91

                        where
                        t0.fld_btidp=$fld_btid
                                                       ");
   echo "\"\", Container Depot Invoice\n\n";
   echo "No,Customer,Depo,Invoice Number,Invoice Date,Delivere Date,Received Date,E Faktur Get by,Description \n";
   $no =0;
   foreach($data->result() as $rdata) {
   $no =$no + 1;
     echo "\"$no\",\"$rdata->Customer\",\"$rdata->Depo\",\"$rdata->Invoice_Number\",\"$rdata->Invoice_Date\",\"$rdata->Delivere_Date\",\"$rdata->Received_Date\",\"$rdata->efaktur\",\"\"\n";
   }

  }

 function exportInvoiceFaktur2($fld_btid) {
    $filename = 'Container-Depot-Invoice-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t1.fld_benm 'Customer',
                              t2.fld_benm 'Depo',t0.fld_desc,
                              t0.fld_inv02 'Invoice_Number',
                              t0.fld_dt01 'Invoice_Date'

                              from tbl_btd_faktur t0
			      left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_btp01
			      left join tbl_be t2 on t2.fld_beid=t0.fld_inv01
                        where
                        t0.fld_btreffid=$fld_btid
                                                       ");
   echo "\"\",\"\", CONTAINER DEPOT INVOICE\n\n";
   $date=date('d-m-Y');
   echo "\"\",Delivere Date :,$date\n\n";
   echo "No,Customer,Container Depot,Invoice Number,Invoice Date,Description \n";
   $no =0;
   foreach($data->result() as $rdata) {
   $no =$no + 1;
     echo "\"$no\",\"$rdata->Customer\",\"$rdata->Depo\",\"$rdata->Invoice_Number\",\"$rdata->Invoice_Date\",\"$rdata->fld_desc\"\n";
   }

  }

function printDOTrailers() {
   $fld_btid =  $this->uri->segment(3);

   $getData =$this->db->query("
   select
   t0.fld_btid,
   t0.fld_btstat 'status',
   t0.fld_btno,
   t0.fld_bttyid,
   date_format(t0.fld_btdt,'%d-%m-%Y %H:%i') 'date',
   date_format(t0.fld_btdt,'%H:%i') 'time',
   t0.fld_btp01 'purchase_by',
   if(t0.fld_baidp = 662,'ADI HARIYO SUYONO',t8.fld_empnm) 'posted_by',
   #t2.fld_benm 'customer',
   if(t0.fld_btp36 > 0,0,t0.fld_btp01)'est_delcash',
   concat(if(t2.fld_beprefix > 0,concat(t14.fld_tyvalnm,'. '),''),SUBSTRING(t2.fld_benm,1,27))'customer',
   t0.fld_btp02 'destination1',
   t12.fld_areanm 'destination',
   concat(t12a.fld_areanm, ' -> ',t12.fld_areanm)'route',
   t3.fld_bticd 'v_number',
   t4.fld_tyvalnm 'v_type',
   t10.fld_empnm 'driver',
   t0.fld_btdesc 'desc',
   t0.fld_btp21 'depot',
   t0.fld_btp23 'otd',
   ifnull(t1.fld_empnm,'') 'chasier',
   format(if(t0.fld_btp36 > 0,t0.fld_btp36,t0.fld_btp01),0) 'delivery_cash',
   t0.fld_btloc 'location',
   ifnull(t13.fld_btinm,'') 'project',
   ifnull(t13.fld_empnm,'') 'asst_driver',
   t0.fld_btnoreff 'do_number',
   t0.fld_btp18 'lolo'
   from tbl_bth t0
   left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_baidv
   left join dnxapps.tbl_be t2 on t2.fld_beid = t0.fld_baidc
   left join dnxapps.tbl_bti t3 on t3.fld_btiid = t0.fld_btp12
   left join tbl_tyval t4 on t4.fld_tyvalcd = t0.fld_btflag and t4.fld_tyid=19
   left join hris.tbl_emp t8 on t8.fld_empid=t0.fld_baidp
   left join hris.tbl_truck_driver t10 on t10.fld_empid=t0.fld_btp11
   left join hris.tbl_truck_driver t13 on t13.fld_empid=t0.fld_btp03
   left join dnxapps.tbl_route t11 on t11.fld_routeid=t0.fld_btp09
   left join dnxapps.tbl_area t12 on t12.fld_areaid=t11.fld_routeto
   left join dnxapps.tbl_area t12a on t12a.fld_areaid=t11.fld_routefrom
   left join tbl_bti t13 on t13.fld_btiid = t0.fld_btidp and t13.fld_bticid =14
   left join dnxapps.tbl_tyval t14 on t14.fld_tyvalcd = t2.fld_beprefix and t14.fld_tyid=173
   WHERE
   t0.fld_btid='$fld_btid'
   ");

   $data = $getData->row();
   if ($data->status == 1){
    $this->message("Printing error.... The DO status must be approved ");
   }
   $this->load->library("tcpdf/tcpdf.php");
   $pdf = new TCPDF('P','cm',array(8,15));
   if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
     require_once(dirname(__FILE__).'/lang/eng.php');
     $pdf->setLanguageArray($l);
   }
   $pdf->SetFont('helvetica', '', 11);

   $pdf->AddPage();
   $style = array(
     'border' => false,
     'padding' => 0,
     'fgcolor' => array(0,0,0),
     'bgcolor' => false
   );
   $pdf->SetFont('times', 'B', 10, '', true);
   $pdf->Cell(0, 0, "TRUCK ORDER", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 7,'',true);
   $pdf->Cell(0, 0, "PT. Dunia Express", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->Cell(0, 0, "$data->fld_btno", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->Cell(0, 0, "$data->do_number", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->Cell(0, 0, "$data->date", 0, 1, 'L', 0, '', 0);
   $pdf->Cell(0, 0, " ", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->write2DBarcode("$data->fld_btid,$data->fld_btno,$data->fld_bttyid", 'QRCODE,H', 5, 1.1, 2, 2, $style, 'N');
   $pdf->SetFont('helvetica', '', 6);
   $pdf->ln();
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Customer :", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->customer", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Depot Container :", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->depot", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Truck:", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->v_type - $data->v_number", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Route :", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->route", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Driver :", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->driver", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Ast. Driver :", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->asst_driver", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Cash :", 0, 1, 'L', 0, '',	 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->delivery_cash", 0, 1, 'L', 0, '',   0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Lolo Amount :", 0, 1, 'L', 0, '',  0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->lolo", 0, 1, 'L', 0, '',   0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Cashier :", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->posted_by", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Remark :", 0, 1, 'L', 0, '',	 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->MultiCell(0, 0, $data->desc, 0, 'L', 0, 2, '' ,'', true);
   $pdf->ln();
   $pdf->Cell(10.5, 0, "Diterima", 0, 1, 'C', 0, '',  0);
   $pdf->ln();
   $pdf->ln();
   $pdf->Cell(6.5, 0, "$data->driver", 0, 1, 'R', 0, '',    0);
   $pdf -> SetXY(4,1);
   ### Halaman kedua
   $pdf->AddPage();
   $pdf->SetFont('times', 'B', 10, '', true);
   $pdf->Cell(0, 0, "TRUCK ORDER", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 7,'',true);
   $pdf->Cell(0, 0, "PT. Dunia Express", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->Cell(0, 0, "$data->fld_btno", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->Cell(0, 0, "$data->do_number", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 6,'',true);
   $pdf->Cell(0, 0, "$data->date", 0, 1, 'L', 0, '', 0);
   $pdf->write2DBarcode("$data->fld_btid,$data->fld_btno,$data->fld_bttyid", 'QRCODE,H', 5, 1.1, 2, 2, $style, 'N');
   $pdf->SetFont('helvetica', '', 6);
   $pdf->ln();
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Customer :", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->customer", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Depot Container :", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->depot", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Truck:", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->v_type - $data->v_number", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Route :", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->route", 0, 1, 'L', 0, '', 0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Driver :", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->driver", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Ast.Driver :", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->asst_driver", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Cash :", 0, 1, 'L', 0, '',	 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->delivery_cash", 0, 1, 'L', 0, '',   0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Lolo Amount :", 0, 1, 'L', 0, '',  0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->lolo", 0, 1, 'L', 0, '',   0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Cashier :", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->Cell(0, 0, "$data->posted_by", 0, 1, 'L', 0, '',    0);
   $pdf->SetFont('helvetica', 'B', 8,'',true);
   $pdf->Cell(0, 0, "Remark :", 0, 1, 'L', 0, '',	 0);
   $pdf->SetFont('helvetica', '', 8,'',true);
   $pdf->MultiCell(0, 0, $data->desc, 0, 'L', 0, 2, '' ,'', true);
   $pdf->ln();

   $pdf->Cell(10.5, 0, "Diterima", 0, 1, 'C', 0, '',  0);
   $pdf->ln();
   $pdf->ln();

   $pdf->Cell(6.5, 0, "$data->driver", 0, 1, 'R', 0, '',    0);

   #$pdf->Text(0, 3, 'Pelanggan');
   #$pdf->Text(40, 2, ": " . $data->customer);
   #$pdf->Text(25, 2, 'Cc');
   #$pdf->Text(40, 2, ": ". $data->cc);

   $pdf -> SetXY(4,1);

   ob_end_clean();
   $pdf->Output('truck_order.pdf', 'I');

   }
  function exportSettlement4($fld_btid) {
    $filename = 'Trucking-Settlement-Summarry-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t2.fld_tyvalnm 'FleetType',
                              if(t15.fld_bttyid = 69,t0.fld_vehicle,t15.fld_btp11) 'VehicleNumber',
			      t15.fld_btno 'DO',
                              t0.fld_btno 'DOReturn',
                              if(t15.fld_bttyid = 69,t3.fld_empnm,t15.fld_btp12) 'Driver',
				date_format(t1.fld_btdt,'%Y-%m-%d')'Date',
				t6.fld_btno 'Cont',
				t7.fld_benm 'Customer',
  				concat(t11.fld_areanm,'--->',t10.fld_areanm) 'route',
				t12.fld_tyvalnm 'size',
				t13.fld_tyvalnm'activity',
                              t0.fld_trk_settlementamt 'Cash',
                              t0.fld_saving 'Saving',t5.fld_lup
                              from tbl_trk_settlement t0
                              left join tbl_bth t1 on t1.fld_btno=t0.fld_btno
                              left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btflag and t2.fld_tyid=19
                              left join hris.tbl_truck_driver t3 on t3.fld_empid=t1.fld_btp11
                              left join tbl_bti t4 on t4.fld_btiid=t1.fld_btp12
                              left join tbl_aprvtkt t5 on t5.fld_btid=t1.fld_btid
			      left join tbl_btd t6 on t6.fld_btidp=t1.fld_btid
			      left join tbl_be t7 on t7.fld_beid=t1.fld_baidc
			      left join tbl_bth t8 on t8.fld_btnoalt=t1.fld_btno
 			      left join tbl_route t9 on t9.fld_routeid=ifnull(t8.fld_btp09,t1.fld_btp09)
                              left join tbl_area t10 on t10.fld_areaid=t9.fld_routeto
                              left join tbl_area t11 on t11.fld_areaid=t9.fld_routefrom
			      left join tbl_tyval t12 on t12.fld_tyvalcd=t6.fld_btp03 and t12.fld_tyid=28
			      left join tbl_tyval t13 on t13.fld_tyvalcd=t1.fld_btp05 and t13.fld_tyid=96
			      left join tbl_btr t14 on t14.fld_btrdst = t1.fld_btid
                              left join tbl_bth t15 on t15.fld_btid = t14.fld_btrsrc and t15.fld_bttyid in (69,80)
                        where
                        t0.fld_btidp=$fld_btid
                        ORDER BY t1.fld_lup ASC
                                ");
   echo "POD DOCUMENT SUBMIT\n\n";
   echo "No,DO Number,DO Return,Date,Customer,Vehicle Number,Driver,Route, Cont Type,Container Number,Activity \n";
   $cash = 0;
   $save = 0;
   $no = 0;
    foreach($data->result() as $rdata) {
   $no = $no + 1;
     echo "\"$no\",\"$rdata->DO\",\"$rdata->DOReturn\",\"$rdata->Date\",\"$rdata->Customer\",\"$rdata->VehicleNumber\",\"$rdata->Driver\",\"" . $rdata->route . "\",\"" . $rdata->size . "\",\"$rdata->Cont\",\"$rdata->activity\"\n";
     $cash = $cash + $rdata->Cash;
     $save = $save + $rdata->Saving;
   }

  }

function printCommissionA($fld_btid) {
   $commission_query = $this->db->query("
    select
    t0.*,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    where
    t0.fld_postingid=$fld_btid
    and t0.fld_empjob in (67,68,59)
    order by t0.fld_empnm
    ");

    $comm_data = $commission_query->row();
    $commission = $commission_query->result();
    foreach ($commission as $rcommission) {
      $driver_group[] = $rcommission->fld_empid;
    }
    $pagenum =0;
    $driver = array_unique($driver_group);
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
    $this->cezpdf->ezSetMargins(10,5,10,5);
    foreach ($driver as $rdriver) {
        #echo "$rdriver<br>";
       $additional = $this->db->query("
        select t0.fld_btflag,t0.fld_btamt01 from tbl_btd_driver_additional t0
        where t0.fld_empid=$rdriver
        and t0.fld_btidp = $fld_btid
        limit 1
        ");
        $additional = $additional->row()->fld_btamt01;
        $pagenum = $pagenum + 1;
        $datadtl = array();
        if($comm_data->fld_empjob == 59) { ### Untuk Trailer
        $datadtl = $this->db->query("
        select * from
        (
        select
        t0.fld_commission,t0.fld_meal,t0.fld_btno,t0.fld_commissiondt,fld_empnm,t0.fld_route 'customer',format(t0.fld_commission,0) 'komisi',format(t0.fld_meal,0) 'standby',
        date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
        if(t0.fld_empjob in (68) ,'Sopir',
          if(t0.fld_empjob in (88),'Kenek', '')
        ) 'Role',
        t2.fld_btp10 'consignee',
        concat(t6.fld_areanm,' > ', t7.fld_areanm) 'route',
        concat(if(t2.fld_btp17 = 3,2,1), ' x ',t8.fld_tyvalnm) 'cont_size',
  	format(t0.fld_point,0) 'point',
  	t0.fld_point
        from tbl_commission t0
        left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
        left join tbl_bth t2 on t2.fld_btid=t0.fld_btreffid
        left join dnxapps.tbl_be t3 on t3.fld_beid=t2.fld_baidc
        left join tbl_bth t4 on t4.fld_btno=t0.fld_btno
        left join dnxapps.tbl_route t5 on t5.fld_routeid = t2.fld_btp09
        left join dnxapps.tbl_area t6 on t6.fld_areaid = t5.fld_routefrom
        left join dnxapps.tbl_area t7 on t7.fld_areaid = t5.fld_routeto
        left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t2.fld_btp15 and t8.fld_tyid = 28
        where
        t0.fld_postingid=$fld_btid
        and
        t0.fld_empid = $rdriver
        ) res
        order by res.fld_commissiondt,res.fld_btno
    ");


     $potongan = $this->db->query("
                                   select
t1.fld_empnm 'DriverName',
(select tx0.fld_btamt01
from tbl_btd_driver_insurance tx0
where tx0.fld_btreffid = t0.fld_btid
and tx0.fld_btflag = 1
) 'Insurance' ,
(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag = 2
) 'Jaminan',

(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag in (3)
) 'Hutang',
(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag = 8
) 'Bpjs',

(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag in (4)
) 'HutangCsr',

(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag in (5)
) 'cicilanHP'

from
tbl_btd_driver_insurance t0
left join hris.tbl_truck_driver t1 on t1.fld_empid = t0.fld_empid
where
t0.fld_btidp = $fld_btid
and
t0.fld_empid = $rdriver
and
t0.fld_btreffid = 0

                                   ");
$pasuransi = 0;
$pjaminan = 0;
$phutang = 0;
$phutangCsr = 0;
$pcicilanHP = 0;
$sum_bpjs = 0;
foreach($potongan->result() as $rpotongan) {
if($rpotongan->Insurance > 0) {
$pasuransi = $rpotongan->Insurance * -1;
} elseif ($rpotongan->Jaminan > 0) {
$pjaminan =  $rpotongan->Jaminan * -1;
} elseif($rpotongan->Hutang > 0) {
$phutang = $rpotongan->Hutang * -1;
} elseif($rpotongan->HutangCsr > 0) {
$phutangCsr = $rpotongan->HutangCsr * -1;
} elseif($rpotongan->cicilanHP > 0) {
$pcicilanHP = $rpotongan->cicilanHP * -1;
} elseif($rpotongan->Bpjs > 0) {
$sum_bpjs = $rpotongan->Bpjs * -1;
}

}
$padd = 0;

    } else {
     $datadtl = $this->db->query("
        select
        t0.*,t0.fld_route 'customer',format(t0.fld_commission,0) 'komisi',format(t0.fld_meal,0) 'standby',
        if(length(t0.fld_btno)=0,' ',if(length(t0.fld_btno)=18,if(t6.fld_btqty=1,'P','S'),'S')) 'rit', date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
        if(t0.fld_empjob in (68) ,'Sopir',
          if(t0.fld_empjob in (88),'Kenek', '')
        ) 'Role',
        t2.fld_btp10 'consignee',
        format(t0.fld_point,0) 'point',
        t0.fld_point
        from tbl_commission t0
        left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
        left join tbl_bth t2 on t2.fld_btid=t0.fld_btreffid
        left join tbl_be t3 on t3.fld_beid=t2.fld_baidc
        left join tbl_bth t4 on t4.fld_btno=t0.fld_btno
        left join tbl_btr t5 on t5.fld_btrsrc = t4.fld_btid and t5.fld_btrdsttyid = 20
        left join tbl_bth t6 on t6.fld_btid = t5.fld_btrdst
        where
        t0.fld_postingid=$fld_btid
        and
        t0.fld_empid = $rdriver
        order by t0.fld_commissiondt
    ");
      $asuransi = 0;
      $jaminan = 0;
      $hutang = 0;
      $hutangCsr = 0;
      $cicilanHP = 0;
      $bpjs = 0;
      $insurance = $this->db->query("
      select t0.fld_btflag,sum(t0.fld_btamt01) 'amount'
      from tbl_btd_driver_insurance t0
      where t0.fld_empid=$rdriver
      and t0.fld_btidp = $fld_btid
      group by t0.fld_btflag
        ");
        foreach($insurance->result() as $rinsurance) {
          if($rinsurance->fld_btflag == 1) {
            $asuransi = $rinsurance->amount * -1;
          }
          if($rinsurance->fld_btflag == 2) {
            $jaminan = $rinsurance->amount * -1;
          }
          if($rinsurance->fld_btflag == 3) {
            $hutang = $rinsurance->amount * -1;
          }
          if($rinsurance->fld_btflag == 4) {
            $hutangCsr = $rinsurance->amount * -1;
          }
          if($rinsurance->fld_btflag == 5) {
            $cicilanHP = $rinsurance->amount * -1;
          }
          if($rinsurance->fld_btflag == 8) {
            $bpjs = $rinsurance->amount * -1;
          }

        }

    }
    $datadtl = $datadtl->result_array();
    $count = count($datadtl);
    $sum_commission = 0;
    $sum_standby = 0;
    $sum_point =0;

    for ($i=0; $i<$count; ++$i) {
      ${$rdriver . "count"} = ${$rdriver . "count"} + 1;
      $sum_commission = $sum_commission + $datadtl[$i]['fld_commission'];
      $sum_standby = $sum_standby + $datadtl[$i]['fld_meal'];
      $sum_point = $sum_point + $datadtl[$i]['fld_point'];
      $datadtl[$i]['count'] = ${$rdriver . "count"};
    }

        $this->cezpdf->ezText("Page $pagenum", 12, array('justification' => 'right'));
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
        $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
        $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
          array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
        $this->cezpdf->ezSetDy(-25);
        $this->cezpdf->ezText("Pembayaran Komisi " .  $datadtl[0]['Role'] . "   ", 12, array('justification' => 'center'));
        $this->cezpdf->ezSetDy(-25);
        $data_hdr = array(
                          array('row1'=>'Nomor Posting','row2'=>':','row3'=>$comm_data->fld_btno),
                          array('row1'=>'Tanggal Posting','row2'=>':','row3'=>$comm_data->date),
                          array('row1'=>'Nama ' . $datadtl[0]['Role'],'row2'=>':','row3'=>$datadtl[0]['fld_empnm'])
                          );
        $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>500,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
        $this->cezpdf->ezSetDy(-15);

        if($comm_data->fld_empjob == 59) { ### Untuk Trailer
          $this->cezpdf->ezTable($datadtl,array('count'=>'No','fld_commissiondt'=>'Tanggal','fld_btno'=>'No. DO','customer'=>'Customer ','route'=>'Rute' ,'cont_size'=>'Size','komisi'=>'Komisi','standby'=>'Tambahan','point'=>'Point'),'',
   array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),
   'fld_commissiondt'=>array('width'=>55),
   'fld_btno'=>array('width'=>90,'justification'=>'center'),
   'customer'=>array('width'=>135,'justification'=>'center'),
   'route'=>array('width'=>120,'justification'=>'center'),
   'cont_size'=>array('width'=>40,'justification'=>'center'),
   'komisi'=>array('width'=>45,'justification'=>'right'),
   'standby'=>array('width'=>40,'justification'=>'right'),
   'point'=>array('width'=>40,'justification'=>'right'),
    )));
    $totalTRM = $sum_commission + $sum_standby + $additional + $sum_point + $phutang + $pasuransi + $pjaminan + $pcicilanHP + $phutangCsr + $sum_bpjs;
    $data_sum = array(
                          array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_standby,0,',','.')),
                          array('row1'=>'Total Komisi','row2'=>number_format($sum_commission + $sum_standby,0,',','.'),'row3'=>''),
			  array('row1'=>'Tambahan Lain Lain','row2'=>number_format($additional,0,',','.'),'row3'=>''),
                          array('row1'=>'Point TMS Driver','row2'=>number_format($sum_point,0,',','.'),'row3'=>'','row4'=>''),
			  array('row1'=>'Total Hutang','row2'=>number_format($phutang,0,',','.'),'row3'=>''),
                          array('row1'=>'Hutang Cashier','row2'=>number_format($phutangCsr,0,',','.'),'row3'=>''),
                          array('row1'=>'Cicilan','row2'=>number_format($pcicilanHP,0,',','.'),'row3'=>''),
                          array('row1'=>'Asuransi','row2'=>number_format($pasuransi,0,',','.'),'row3'=>''),
                          array('row1'=>'Jaminan','row2'=>number_format($pjaminan,0,',','.'),'row3'=>''),
                          array('row1'=>'BPJS','row2'=>number_format($sum_bpjs,0,',','.'),'row3'=>''),
                          array('row1'=>'Total Terima','row2'=>number_format($totalTRM),'row3'=>'')
                          );
        $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
        array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>560,'fontSize'=>'8','cols'=>array('row1'=>array('width'=>460,'justification'=>'center'),'row2'=>array('width'=>45,'justification'=>'right'),'row3'=>array('width'=>40,'justification'=>'right'),'row4'=>array('width'=>40,'justification'=>'right'))));


        } else { ### Untuk CAr Carrier da  Box
        $this->cezpdf->ezTable($datadtl,array('count'=>'No','fld_commissiondt'=>'Tanggal','fld_btno'=>'No. DO','customer'=>'Customer ','consignee'=>'Consignee' ,'rit'=>'RITASE','komisi'=>'UPAH','standby'=>'UANG MAKAN'),'',
   array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),
   'fld_commissiondt'=>array('width'=>60),
   'fld_btno'=>array('width'=>100,'justification'=>'center'),
   'customer'=>array('width'=>140,'justification'=>'center'),
   'consignee'=>array('width'=>100,'justification'=>'center'),
   'rit'=>array('width'=>50,'justification'=>'center'),
   'komisi'=>array('width'=>50,'justification'=>'right'),
   'standby'=>array('width'=>50,'justification'=>'right'),
    )));

    $data_sum = array(
                          array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_standby,0,',','.')),
                          array('row1'=>'Potongan Asuransi','row2'=>number_format($asuransi,0,',','.'),'row3'=>''),
                          array('row1'=>'Hutang','row2'=>number_format($hutang,0,',','.'),'row3'=>''),
                          array('row1'=>'Jaminan','row2'=>number_format($jaminan,0,',','.'),'row3'=>''),
                          array('row1'=>'BPJS','row2'=>number_format($bpjs,0,',','.'),'row3'=>''),
			  array('row1'=>'Total Terima','row2'=>number_format($sum_commission + ($asuransi+$hutang+$jaminan+$bpjs),0,',','.'),'row3'=>'')
                          );
        $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>560,'fontSize'=>'8','cols'=>array('row1'=>array('width'=>470,'justification'=>'center'),'row2'=>array('width'=>50,'justification'=>'right'),'row3'=>array('width'=>50,'justification'=>'right'))));
     }
        $this->cezpdf->ezSetDy(-15);
        $this->cezpdf->ezNewPage();
        $this->cezpdf->ezSetY(795);

     }

     header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=commission_sopir.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }
  // new
  function printCommissionB($fld_btid) {
    $commission_query = $this->db->query("
     select
     t0.*,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date'
     from tbl_commission t0
     left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
     where
     t0.fld_postingid=$fld_btid
     and t0.fld_empjob in (88,89,62)
     order by t0.fld_empnm
     ");

     $comm_data = $commission_query->row();
     $commission = $commission_query->result();
     foreach ($commission as $rcommission) {
       $driver_group[] = $rcommission->fld_empid;
     }
     $pagenum =0;
     $driver = array_unique($driver_group);
     $this->load->library('cezpdf');
     $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
     $this->cezpdf->ezSetMargins(10,5,10,5);
       foreach ($driver as $rdriver) {
         $additional = $this->db->query("
         select t0.fld_btflag,sum(t0.fld_btamt01)'fld_btamt01' from tbl_btd_driver_additional t0
         where t0.fld_empid = $rdriver
         and t0.fld_btidp = $fld_btid
         ");
         $additional = $additional->row()->fld_btamt01;
         $pagenum = $pagenum + 1;
         $datadtl = array();
         if($comm_data->fld_empjob == 62) { ### Untuk Trailer
         $datadtl = $this->db->query("
         select * from
         (
         select
         t0.fld_commission,t0.fld_empnm'empnm',t0.fld_meal,t0.fld_btno,t0.fld_commissiondt,t0.fld_empnm,t0.fld_route 'customer',format(t0.fld_commission,0) 'komisi',format(t0.fld_meal,0) 'standby',
         date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
         if(t0.fld_empjob in (68) ,'Sopir',
           if(t0.fld_empjob in (88),'Kenek', '')
         ) 'Role',
         t2.fld_btp10 'consignee',
         concat(t6.fld_areanm,' > ', t7.fld_areanm) 'route',
         concat(if(t2.fld_btp17 = 3,2,1), ' x ',t8.fld_tyvalnm) 'cont_size'
         from tbl_commission t0
         left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
         left join tbl_bth t2 on t2.fld_btid=t0.fld_btreffid
         left join tbl_be t3 on t3.fld_beid=t2.fld_baidc
         left join tbl_bth t4 on t4.fld_btno=t0.fld_btno
         left join tbl_route t5 on t5.fld_routeid = t2.fld_btp09
         left join tbl_area t6 on t6.fld_areaid = t5.fld_routefrom
         left join tbl_area t7 on t7.fld_areaid = t5.fld_routeto
         left join tbl_tyval t8 on t8.fld_tyvalcd = t2.fld_btp15 and t8.fld_tyid = 28
         left join hris.tbl_truck_driver t9 on t0.fld_empid=t9.fld_empid
         where
         t0.fld_postingid=$fld_btid
         and
         t0.fld_empid = $rdriver
         ) res
         order by res.fld_commissiondt,res.fld_btno
     ");
       $potongan = $this->db->query("
                                    select
 t1.fld_empnm 'DriverName',
 (select tx0.fld_btamt01
 from tbl_btd_driver_insurance tx0
 where tx0.fld_btreffid = t0.fld_btid
 and tx0.fld_btflag = 1
 ) 'Insurance' ,
 (select tz0.fld_btamt01
 from tbl_btd_driver_insurance tz0
 where tz0.fld_btreffid = t0.fld_btid
 and tz0.fld_btflag = 2
 ) 'Jaminan',

 (select tz0.fld_btamt01
 from tbl_btd_driver_insurance tz0
 where tz0.fld_btreffid = t0.fld_btid
 and tz0.fld_btflag = 3
 ) 'Hutang',
 (select tz0.fld_btamt01
 from tbl_btd_driver_insurance tz0
 where tz0.fld_btreffid = t0.fld_btid
 and tz0.fld_btflag in (4)
 ) 'HutangCsr'
 from
 tbl_btd_driver_insurance t0
 left join hris.tbl_truck_driver t1 on t1.fld_empid = t0.fld_empid
 where
 t0.fld_btidp = $fld_btid
 and
 t0.fld_empid = $rdriver
 and
 t0.fld_btreffid = 0

                                    ");
 $potongan = $potongan->row();
 $pasuransi = $potongan->Insurance;
 $jaminan = $potongan->Jaminan;
 $hutang = $potongan->Hutang*-1;
 $hutangCsr = $potongan->HutangCsr*-1;
     } else {
         $datadtl = $this->db->query("
         select
         t0.*,format(t0.fld_commission,0) 'komisi',format(t0.fld_meal,0) 'standby',t0.fld_empnm'empnm',
         if(length(t0.fld_btno)=0,' ',if(length(t0.fld_btno)=18,if(t6.fld_btqty=1,'P','S'),'S')) 'rit', date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
         if(t0.fld_empjob in (68) ,'Sopir',
           if(t0.fld_empjob in (88,89),'Kenek', '')
         ) 'Role',
         t2.fld_btp10 'consignee'
         from tbl_commission t0
         left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
         left join tbl_bth t2 on t2.fld_btid=t0.fld_btreffid
         left join tbl_be t3 on t3.fld_beid=t2.fld_baidc
         left join tbl_bth t4 on t4.fld_btno=t0.fld_btno
         left join tbl_btr t5 on t5.fld_btrsrc = t4.fld_btid and t5.fld_btrdsttyid = 20
         left join tbl_bth t6 on t6.fld_btid = t5.fld_btrdst
         where
         t0.fld_postingid=$fld_btid
         and
         t0.fld_empid = $rdriver
         order by t0.fld_commissiondt
     ");
 }
     $datadtl = $datadtl->result_array();
     $count = count($datadtl);
     $sum_commission = 0;
     $sum_standby = 0;
     for ($i=0; $i<$count; ++$i) {
       ${$rdriver . "count"} = ${$rdriver . "count"} + 1;
       $sum_commission = $sum_commission + $datadtl[$i]['fld_commission'];
       $sum_standby = $sum_standby + $datadtl[$i]['fld_meal'];
       $datadtl[$i]['count'] = ${$rdriver . "count"};
     }

         $this->cezpdf->ezText("Page $pagenum", 12, array('justification' => 'right'));
         $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
         $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS TRANSINDO'),
                   array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                   array('row1'=>"Jakarta Utara 14340")
                 );
         $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
           array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
         $this->cezpdf->ezSetDy(-25);
         $this->cezpdf->ezText("Pembayaran Komisi " .  $datadtl[0]['Role'] . "   ", 12, array('justification' => 'center'));
         $this->cezpdf->ezSetDy(-25);
         $data_hdr = array(
                           array('row1'=>'Nomor Posting','row2'=>':','row3'=>$comm_data->fld_btno),
                           array('row1'=>'Tanggal Posting','row2'=>':','row3'=>$comm_data->date),
                           array('row1'=>'Nama ' . $datadtl[0]['Role'],'row2'=>':','row3'=>$datadtl[0]['empnm'])
                           );
         $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
         array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>500,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
         $this->cezpdf->ezSetDy(-15);
         if($comm_data->fld_empjob == 62) { ### Untuk Trailer
           $this->cezpdf->ezTable($datadtl,array('count'=>'No','fld_commissiondt'=>'Tanggal','fld_btno'=>'No. DO','customer'=>'Customer ','route'=>'Rute' ,'cont_size'=>'Size','komisi'=>'Komisi','standby'=>'Tambahan'),'',
    array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'8',
    'cols'=>array('counteor'=>array('width'=>10),
    'fld_commissiondt'=>array('width'=>55),
    'fld_btno'=>array('width'=>90,'justification'=>'center'),
    'customer'=>array('width'=>135,'justification'=>'center'),
    'route'=>array('width'=>120,'justification'=>'center'),
    'cont_size'=>array('width'=>55,'justification'=>'center'),
    'komisi'=>array('width'=>45,'justification'=>'right'),
    'standby'=>array('width'=>50,'justification'=>'right'),
     )));
     $totalTRM = $sum_commission + $sum_standby + $additional + $hutang + $hutangCsr;
     $data_sum = array(
                         array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>''),
                         // array('row1'=>'Total Komisi','row2'=>number_format($sum_commission + $sum_standby,0,',','.'),'row3'=>''),
                         array('row1'=>'Tambahan Lain Lain','row2'=>number_format($additional,0,',','.'),'row3'=>''),
                         array('row1'=>'Total Hutang','row2'=>number_format($hutang,0,',','.'),'row3'=>''),
                         array('row1'=>'Hutang Cashier','row2'=>number_format($hutangCsr,0,',','.'),'row3'=>''),
                         array('row1'=>'Total Terima','row2'=>number_format($totalTRM),'row3'=>'')
                       );
         $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
         array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>560,'fontSize'=>'8','cols'=>array('row1'=>array('width'=>476,'justification'=>'center'),'row2'=>array('width'=>45,'justification'=>'right'),'row3'=>array('width'=>50,'justification'=>'right'))));


         } else { ### Untuk CAr Carrier da  Box

         $this->cezpdf->ezTable($datadtl,array('count'=>'No','fld_commissiondt'=>'Tanggal','fld_btno'=>'No. DO','fld_route'=>'Rute','consignee'=>'Consignee','rit'=>'RITASE','komisi'=>'UPAH','standby'=>'UANG MAKAN'),'',
    array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'8',
    'cols'=>array('counteor'=>array('width'=>10),
    'fld_commissiondt'=>array('width'=>60),
    'fld_btno'=>array('width'=>100,'justification'=>'center'),
    'fld_route'=>array('width'=>140,'justification'=>'center'),
     'consignee'=>array('width'=>100,'justification'=>'center'),
    'rit'=>array('width'=>50,'justification'=>'center'),
    'komisi'=>array('width'=>50,'justification'=>'right'),
     'standby'=>array('width'=>50,'justification'=>'right'),
     )));

     $data_sum = array(
                           array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_standby,0,',','.'))
                           );
         $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
         array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>560,'fontSize'=>'8','cols'=>array('row1'=>array('width'=>470,'justification'=>'center'),'row2'=>array('width'=>50,'justification'=>'right'),'row3'=>array('width'=>50,'justification'=>'right'))));                         }
         $this->cezpdf->ezSetDy(-15);
         $this->cezpdf->ezNewPage();
         $this->cezpdf->ezSetY(795);
      }
      header("Content-type: application/pdf");
         header("Content-Disposition: attachment; filename=commission_kenek.pdf");
         header("Pragma: no-cache");
         header("Expires: 0");

         $output = $this->cezpdf->ezOutput();
         echo $output;
   }
  /* function printCommissionB($fld_btid) {
   $commission_query = $this->db->query("
    select
    t0.*,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    where
    t0.fld_postingid=$fld_btid
    and t0.fld_empjob in (88,89,62)
    order by t0.fld_empnm
    ");
    $comm_data = $commission_query->row();
    $commission = $commission_query->result();
    foreach ($commission as $rcommission) {
      $driver_group[] = $rcommission->fld_empid;
    }

    $driver = array_unique($driver_group);
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
    $this->cezpdf->ezSetMargins(10,5,10,5);
      foreach ($driver as $rdriver) {
        $datadtl = array();
	if($comm_data->fld_empjob == 62) { ### Untuk Trailer
	$datadtl = $this->db->query("
        select * from
        (
        select
        t0.fld_commission,t0.fld_empnm'empnm',t0.fld_meal,t0.fld_btno,t0.fld_commissiondt,t0.fld_empnm,t0.fld_route 'customer',format(t0.fld_commission,0) 'komisi',format(t0.fld_meal,0) 'standby',
        date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
        if(t0.fld_empjob in (68) ,'Sopir',
          if(t0.fld_empjob in (88),'Kenek', '')
        ) 'Role',
        t2.fld_btp10 'consignee',
        concat(t6.fld_areanm,' > ', t7.fld_areanm) 'route',
        concat(if(t2.fld_btp17 = 3,2,1), ' x ',t8.fld_tyvalnm) 'cont_size'
        from tbl_commission t0
        left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
        left join tbl_bth t2 on t2.fld_btid=t0.fld_btreffid
        left join tbl_be t3 on t3.fld_beid=t2.fld_baidc
        left join tbl_bth t4 on t4.fld_btno=t0.fld_btno
        left join tbl_route t5 on t5.fld_routeid = t2.fld_btp09
        left join tbl_area t6 on t6.fld_areaid = t5.fld_routefrom
        left join tbl_area t7 on t7.fld_areaid = t5.fld_routeto
        left join tbl_tyval t8 on t8.fld_tyvalcd = t2.fld_btp15 and t8.fld_tyid = 28
        left join hris.tbl_truck_driver t9 on t0.fld_empid=t9.fld_empid
        where
        t0.fld_postingid=$fld_btid
        and
        t0.fld_empid = $rdriver
        ) res
        order by res.fld_commissiondt,res.fld_btno
    ");
      $potongan = $this->db->query("
                                   select
t1.fld_empnm 'DriverName',
(select tx0.fld_btamt01
from tbl_btd_driver_insurance tx0
where tx0.fld_btreffid = t0.fld_btid
and tx0.fld_btflag = 1
) 'Insurance' ,
(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag = 2
) 'Jaminan',

(select tz0.fld_btamt01
from tbl_btd_driver_insurance tz0
where tz0.fld_btreffid = t0.fld_btid
and tz0.fld_btflag = 3
) 'Hutang'
from
tbl_btd_driver_insurance t0
left join hris.tbl_truck_driver t1 on t1.fld_empid = t0.fld_empid
where
t0.fld_btidp = $fld_btid
and
t0.fld_empid = $rdriver
and
t0.fld_btreffid = 0

                                   ");
$potongan = $potongan->row();
$pasuransi = $potongan->Insurance;
$jaminan = $potongan->Jaminan;
$hutang = $potongan->Hutang;
    } else {
        $datadtl = $this->db->query("
        select
        t0.*,format(t0.fld_commission,0) 'komisi',format(t0.fld_meal,0) 'standby',t0.fld_empnm'empnm',
        if(length(t0.fld_btno)=0,' ',if(length(t0.fld_btno)=18,if(t6.fld_btqty=1,'P','S'),'S')) 'rit', date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
        if(t0.fld_empjob in (68) ,'Sopir',
          if(t0.fld_empjob in (88,89),'Kenek', '')
        ) 'Role',
        t2.fld_btp10 'consignee'
        from tbl_commission t0
        left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
        left join tbl_bth t2 on t2.fld_btid=t0.fld_btreffid
        left join tbl_be t3 on t3.fld_beid=t2.fld_baidc
        left join tbl_bth t4 on t4.fld_btno=t0.fld_btno
        left join tbl_btr t5 on t5.fld_btrsrc = t4.fld_btid and t5.fld_btrdsttyid = 20
        left join tbl_bth t6 on t6.fld_btid = t5.fld_btrdst
        where
        t0.fld_postingid=$fld_btid
        and
        t0.fld_empid = $rdriver
        order by t0.fld_commissiondt
    ");
}
    $datadtl = $datadtl->result_array();
    $count = count($datadtl);
    $sum_commission = 0;
    $sum_standby = 0;
    for ($i=0; $i<$count; ++$i) {
      ${$rdriver . "count"} = ${$rdriver . "count"} + 1;
      $sum_commission = $sum_commission + $datadtl[$i]['fld_commission'];
      $sum_standby = $sum_standby + $datadtl[$i]['fld_meal'];
      $datadtl[$i]['count'] = ${$rdriver . "count"};
    }


        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
        $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS TRANSINDO'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
        $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
          array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
        $this->cezpdf->ezSetDy(-25);
        $this->cezpdf->ezText("Pembayaran Komisi " .  $datadtl[0]['Role'] . "   ", 12, array('justification' => 'center'));
        $this->cezpdf->ezSetDy(-25);
        $data_hdr = array(
                          array('row1'=>'Nomor Posting','row2'=>':','row3'=>$comm_data->fld_btno),
                          array('row1'=>'Tanggal Posting','row2'=>':','row3'=>$comm_data->date),
                          array('row1'=>'Nama ' . $datadtl[0]['Role'],'row2'=>':','row3'=>$datadtl[0]['empnm'])
                          );
        $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>500,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
        $this->cezpdf->ezSetDy(-15);
        if($comm_data->fld_empjob == 62) { ### Untuk Trailer
          $this->cezpdf->ezTable($datadtl,array('count'=>'No','fld_commissiondt'=>'Tanggal','fld_btno'=>'No. DO','customer'=>'Customer ','route'=>'Rute' ,'cont_size'=>'Size','komisi'=>'Komisi','standby'=>'Tambahan'),'',
   array('rowGap'=>'0','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),
   'fld_commissiondt'=>array('width'=>55),
   'fld_btno'=>array('width'=>90,'justification'=>'center'),
   'customer'=>array('width'=>135,'justification'=>'center'),
   'route'=>array('width'=>120,'justification'=>'center'),
   'cont_size'=>array('width'=>55,'justification'=>'center'),
   'komisi'=>array('width'=>45,'justification'=>'right'),
   'standby'=>array('width'=>50,'justification'=>'right'),
    )));

    $data_sum = array(
        //                  array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>''),
      //                    array('row1'=>'Total Komisi','row2'=>number_format($sum_commission + $sum_standby,0,',','.'),'row3'=>''),
    //                      array('row1'=>'Hutang','row2'=>number_format($phutang,0,',','.'),'row3'=>''),
  //                        array('row1'=>'Asuransi','row2'=>number_format($pasuransi,0,',','.'),'row3'=>''),
//                          array('row1'=>'Jaminan','row2'=>number_format($pjaminan,0,',','.'),'row3'=>''),
                          array('row1'=>'Total Terima','row2'=>number_format($sum_commission),'row3'=>'')
                          );
        $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'0','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>560,'fontSize'=>'8','cols'=>array('row1'=>array('width'=>476,'justification'=>'center'),'row2'=>array('width'=>45,'justification'=>'right'),'row3'=>array('width'=>50,'justification'=>'right'))));


        } else { ### Untuk CAr Carrier da  Box

        $this->cezpdf->ezTable($datadtl,array('count'=>'No','fld_commissiondt'=>'Tanggal','fld_btno'=>'No. DO','fld_route'=>'Rute','consignee'=>'Consignee','rit'=>'RITASE','komisi'=>'UPAH','standby'=>'UANG MAKAN'),'',
   array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>560,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),
   'fld_commissiondt'=>array('width'=>60),
   'fld_btno'=>array('width'=>100,'justification'=>'center'),
   'fld_route'=>array('width'=>140,'justification'=>'center'),
    'consignee'=>array('width'=>100,'justification'=>'center'),
   'rit'=>array('width'=>50,'justification'=>'center'),
   'komisi'=>array('width'=>50,'justification'=>'right'),
    'standby'=>array('width'=>50,'justification'=>'right'),
    )));

    $data_sum = array(
                          array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_standby,0,',','.'))
                          );
        $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>560,'fontSize'=>'8','cols'=>array('row1'=>array('width'=>470,'justification'=>'center'),'row2'=>array('width'=>50,'justification'=>'right'),'row3'=>array('width'=>50,'justification'=>'right'))));                         }
        $this->cezpdf->ezSetDy(-15);

        $this->cezpdf->ezNewPage();
        $this->cezpdf->ezSetY(795);
     }
     header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=commission_kenek.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  } */


 //  function printCommission2($fld_btid) {
 //   $location = $this->session->userdata('location');
 //   $hdr = $this->db->query("select * from tbl_bth where fld_btid  = $fld_btid");
 //   $vehicle = $hdr->row()->fld_btp01;

 //   if($vehicle == 1) { ### Trailer
 //   $commission_query = $this->db->query("
 //    select
 //    concat(t0.fld_empnm,'',if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk))'fld_empnm',t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',sum(t0.fld_meal) 'standby',
 //    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',
 //    t0.fld_empjob,
 //    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
 //     and length(tz0.fld_btno) > 0) 'trip_count',
 //    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
 //     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
 //     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
 //      where tx0.fld_empid = t0.fld_empid
 //      and tx1.fld_btreffid > 0
 //     and tx0.fld_btidp = $fld_btid
 //     and tx1.fld_btflag = 1
 //    ),0) 'insurance',
 //    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
 //     and length(tz0.fld_btno) > 0) 'trip_count',
 //    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
 //     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
 //     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
 //      where tx0.fld_empid = t0.fld_empid
 //      and tx1.fld_btreffid > 0
 //     and tx0.fld_btidp = $fld_btid
 //     and tx1.fld_btflag = 2
 //    ),0) 'jaminan',

 //    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
 //     and length(tz0.fld_btno) > 0) 'trip_count',
 //    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
 //     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
 //     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
 //      where tx0.fld_empid = t0.fld_empid
 //      and tx1.fld_btreffid > 0
 //     and tx0.fld_btidp = $fld_btid
 //     and tx1.fld_btflag in (3) limit 1
 //    ),0) 'hutang',
 //     ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
 //     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
 //     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
 //      where tx0.fld_empid = t0.fld_empid
 //      and tx1.fld_btreffid > 0
 //     and tx0.fld_btidp = $fld_btid
 //     and tx1.fld_btflag in (4)  ),0) 'hutangCsr',

 //   # (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
 //    # where
 //    # tz0.fld_btidp = $fld_btid
 //     #and
 //    # tz0.fld_empid = t0.fld_empid
 //    # ) 'Other',
 //     (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
 //     where
 //     tz0.fld_btidp = $fld_btid
 //     and
 //     tz0.fld_empid = t0.fld_empid
 //     ) 'Other',

 //    if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip'
 //    from tbl_commission t0
 //    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
 //    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
 //    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
 //    left join hris.tbl_truck_driver t4 on t4.fld_empid=t0.fld_empid
 //    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_empjob
 //    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
 //    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
 //    where
 //    t0.fld_postingid=$fld_btid
 //    and
 //    #t4.fld_emploc=$location or t4.fld_empworkloc=$location
 //    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_empworkloc = 1, t4.fld_empworkloc in (2,5))))
 //    and
 //    t0.fld_empjob in (67,68,59)
 //    group by t0.fld_empid
 //    order by t0.fld_empnm
 //        ");
 //    } else {
 //   $commission_query = $this->db->query("
 //    select
 //    t0.fld_empnm,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',
 //    #sum(t0.fld_meal) 'standby',
 //    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',
 //    t0.fld_empjob,
 //    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
 //     and length(tz0.fld_btno) > 0) 'trip_count',
 //    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
 //     where tx0.fld_empid=t0.fld_empid
 //     and tx0.fld_btidp = $fld_btid
 //     and tx0.fld_btflag = 1
 //    ),0) 'insurance',
 //    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
 //     and length(tz0.fld_btno) > 0) 'trip_count',
 //    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
 //     where tx0.fld_empid=t0.fld_empid
 //     and tx0.fld_btidp = $fld_btid
 //     and tx0.fld_btflag = 2
 //    ),0) 'jaminan',

 //    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
 //     and length(tz0.fld_btno) > 0) 'trip_count',
 //    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
 //     where tx0.fld_empid=t0.fld_empid
 //     and tx0.fld_btidp = $fld_btid
 //     and tx0.fld_btflag = 3
 //    ),0) 'hutang',
 //   # (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
 //    # where
 //     #tz0.fld_btidp = $fld_btid
 //     #and
 //     #tz0.fld_empid = t0.fld_empid
 //     #) 'other',
 //    if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip'
 //    from tbl_commission t0
 //    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
 //    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
 //    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
 //    left join hris.tbl_truck_driver t4 on t4.fld_empid=t0.fld_empid
 //    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_empjob
 //    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
 //    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
 //    where
 //    t0.fld_postingid=$fld_btid
 //    and
 //    #t4.fld_emploc=$location or t4.fld_empworkloc=$location
 //    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_empworkloc = 1, t4.fld_empworkloc in (2,5))))
 //    and
 //    t0.fld_empjob in (67,68,59)
 //    group by t0.fld_empid
 //    order by t0.fld_empnm
 //        ");
 //    }


 //    $comm_data = $commission_query->row();
 //    $commission = $commission_query->result_array();
 //   $this->load->library('cezpdf');
 //   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
 //   $this->cezpdf->ezSetMargins(10,5,10,5);
 //      $count = count($commission);
 //      $sum_commission = 0;
 //      $sum_standby = 0;
 //      for ($i=0; $i<$count; ++$i) {
 //        $counteor = $counteor + 1;
 //        $commission[$i]['count'] = $counteor;
 //        $sum_commission = $sum_commission + $commission[$i]['commission'];
 //        $sum_standby = $sum_standby + $commission[$i]['standby'];
 //        $sum_insurance = $sum_insurance + $commission[$i]['insurance'];
 //        $sum_other = $sum_other + $commission[$i]['Other'];
 //        $sum_jaminan = $sum_jaminan + $commission[$i]['jaminan'];
 //        $sum_hutang = $sum_hutang + $commission[$i]['hutang'];
 //        $sum_hutangCsr = $sum_hutangCsr + $commission[$i]['hutangCsr'];
 //        if( $commission[$i]['fld_empjob'] == 59) {
 //           $commission[$i]['commission'] = $commission[$i]['commission'];
 //           $commission[$i]['total'] = $commission[$i]['commission'] + $commission[$i]['standby']+$commission[$i]['Other'] - $commission[$i]['insurance'] - $commission[$i]['jaminan'] - $commission[$i]['hutang']- $commission[$i]['hutangCsr'];
 //        } else {
 //          $commission[$i]['total'] = $commission[$i]['commission'] - $commission[$i]['insurance'] - - $commission[$i]['jaminan'] - $commission[$i]['hutang'];
 //        }
 //        $sum_total = $sum_total + $commission[$i]['total'] ;
 //      }
 //      $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
 //      $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS'),
 //                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
 //                  array('row1'=>"Jakarta Utara 14340")
 //                );
 //      $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
 //      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
 //      $this->cezpdf->ezSetDy(-10);
 //      $this->cezpdf->ezText("DRIVER COMMISSION SUMMARY" . "   ", 12, array('justification' => 'center'));
 //      $this->cezpdf->ezSetDy(-20);

 //      if($comm_data->fld_empjob == 59) { ### Trailer
 //      $data_hdr = array(
 //                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
 //                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
 //                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
 //                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
 //                        );
 //      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
 //      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
 //      $this->cezpdf->ezSetDy(-15);
 //      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Nama','commission'=>'Komisi','standby'=>'Tambahan','Other'=>'Lain-Lain','insurance'=>'Asuransi','jaminan'=>'Jaminan','hutang'=>'Total Hutang ','hutangCsr'=>'Hutang Cashier','total'=>'Total','ttd'=>'ttd'),'',
 //      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>540,'shaded'=>0,'fontSize'=>'8',
 //      'cols'=>array('counteor'=>array('width'=>10),
 //      'fld_empnm'=>array('width'=>120),
 //      #'fld_empnip'=>array('width'=>50),
 //      'commission'=>array('width'=>55,'justification'=>'right'),
 //      'standby'=>array('width'=>55,'justification'=>'right'),
 //      'Other'=>array('width'=>50,'justification'=>'right'),
 //      'insurance'=>array('width'=>50,'justification'=>'right'),
 //       'jaminan'=>array('width'=>50,'justification'=>'right'),
 //       'hutang'=>array('width'=>50,'justification'=>'right'),
 //       'hutangCsr'=>array('width'=>50,'justification'=>'right'),
 //       'total'=>array('width'=>55,'justification'=>'right'),
 //       'ttd'=>array('width'=>25,'justification'=>'right'),
 //       )));

 //       $data_sum = array(
 //                         array('row1'=>'Total',
 //                               'row2'=>number_format($sum_commission,0,',','.'),
 //                               'row3'=>number_format($sum_standby,0,',','.'),
 // 				'row4'=>number_format($sum_other,0,',','.'),
 //                               'row5'=>number_format($sum_insurance,0,',','.'),
	// 		       'row6'=>number_format($sum_jaminan,0,',','.'),
 //                               'row7'=>number_format($sum_hutang,0,',','.'),
 //                               'row7a'=>number_format($sum_hutangCsr,0,',','.'),
 //                               'row8'=>number_format($sum_total,0,',','.'))
 //                          );
 //       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>'','row7'=>'','row7a'=>'','row8'=>'','row9'=>''),'',
 //        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>540,'fontSize'=>'8',
 //        'cols'=>array(
 //        'row1'=>array('width'=>140,'justification'=>'center'),
 //        'row2'=>array('width'=>55,'justification'=>'right'),
 //        'row3'=>array('width'=>55,'justification'=>'right'),
 //        'row4'=>array('width'=>50,'justification'=>'right'),
	// 'row5'=>array('width'=>50,'justification'=>'right'),
 //        'row6'=>array('width'=>50,'justification'=>'right'),
 //        'row7'=>array('width'=>50,'justification'=>'right'),
 //        'row7a'=>array('width'=>50,'justification'=>'right'),
 //        'row8'=>array('width'=>55,'justification'=>'right'),
 //        'row9'=>array('width'=>25,'justification'=>'right')
 //        )));
 //       $this->cezpdf->ezSetDy(-15);
 //        $acc = array(array('row1'=>'Created By','row2'=>'Checked By','row3'=>'Aknowledge By','row4'=>'Approved By','row5'=>'Received By'),
 //                     array('row1'=>''),
	// 	     array('row1'=>''),
 //                     array('row1'=>''),
	// 	     array('row1'=>'Shofa','row2'=>'Mona','row3'=>'Tonny Wijaya','row4'=>'Elly Dwiyanti','row5'=>'Fitrotun Chasanah'),
	// 	     array('row1'=>'Staff Trucking ','row2'=>'Chief Trucking','row3'=>'Manager Trucking','row4'=>'Finance SPV','row5'=>'Chasier'),

 //                );
 //     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>''),'',array
 //         ('rowGap'=>'0','xPos'=>20,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
 //         'row1'=>array('width'=>110,'justification' => 'center'),
 //         'row2'=>array('width'=>110,'justification' => 'center'),
 //         'row3'=>array('width'=>110,'justification' => 'center'),
 //         'row4'=>array('width'=>110,'justification' => 'center'),
 //         'row5'=>array('width'=>110,'justification' => 'center'),
 //         )));
 //       }else {
 //          $data_hdr = array(
 //                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
 //                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
 //                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
 //                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
 //                        );
 //      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
 //      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
 //      $this->cezpdf->ezSetDy(-15);
 //      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','job'=>'Job Role','trip_count'=>'Trip','commission'=>'Komisi','insurance'=>'Asuransi','jaminan'=>'Jaminan','hutang'=>'Hutang','total'=>'Total'),'',
 //      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>570,'shaded'=>0,'fontSize'=>'8',
 //      'cols'=>array('counteor'=>array('width'=>5),
 //      'fld_empnm'=>array('width'=>120),
 //      'job'=>array('width'=>50),
 //      'trip'=>array('width'=>50,'justification'=>'center'),
 //      'commission'=>array('width'=>60,'justification'=>'right'),
 //       'insurance'=>array('width'=>60,'justification'=>'right'),
 //       'jaminan'=>array('width'=>50,'justification'=>'right'),
 //       'hutang'=>array('width'=>50,'justification'=>'right'),
 //       'total'=>array('width'=>60,'justification'=>'right'),
 //       )));

 //       $data_sum = array(
 //                         array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_insurance,0,',','.'),
	// 		       'row4'=>number_format($sum_jaminan,0,',','.'),
 //                               'row5'=>number_format($sum_hutang,0,',','.'),'row6'=>number_format($sum_total,0,',','.'))
 //                          );
 //       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>''),'',
 //        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'8',
 //        'cols'=>array('row1'=>array('width'=>290,'justification'=>'center'),
 //        'row2'=>array('width'=>60,'justification'=>'right'),
 //        'row3'=>array('width'=>60,'justification'=>'right'),
 //        'row4'=>array('width'=>50,'justification'=>'right'),
 //        'row5'=>array('width'=>50,'justification'=>'right'),'row6'=>array('width'=>60,'justification'=>'right'))));
 //       $this->cezpdf->ezSetDy(-15);

 //       }
 //       $this->cezpdf->ezNewPage();
 //       $this->cezpdf->ezSetY(795);
 //       header("Content-type: application/pdf");
 //       header("Content-Disposition: attachment; filename=commission_summary.pdf");
 //       header("Pragma: no-cache");
 //       header("Expires: 0");
 //       $output = $this->cezpdf->ezOutput();
 //       echo $output;
 //   }

function printCommission2($fld_btid) {
   $location = $this->session->userdata('location');
   $hdr = $this->db->query("select * from tbl_bth where fld_btid  = $fld_btid");
   $vehicle = $hdr->row()->fld_btp01;

   if($vehicle == 1) { ### Trailer
   $commission_query = $this->db->query("
    select
    concat(t0.fld_empnm,'',if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk))'fld_empnm',t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',sum(t0.fld_meal) 'standby',
    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',sum(t0.fld_point) 'point',
    t0.fld_empjob,
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 1
    ),0) 'insurance',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 2
    ),0) 'jaminan',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 8
   ),0) 'bpjs',

    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid= if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (3) limit 1
    ),0) 'hutang',
     ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (4)  ),0) 'hutangCsr',

     ifnull((select ifnull(sum(tx1.fld_btamt01),0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     #where tx0.fld_driverid=if(date_format(t1.fld_btdt,'%Y-%m-%d') >= '2020-10-01',t0.fld_driverid,t0.fld_empid)
      where tx0.fld_empid = t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag in (5)  ),0) 'cicilanHP',

   # (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
    # where
    # tz0.fld_btidp = $fld_btid
     #and
    # tz0.fld_empid = t0.fld_empid
    # ) 'Other',
     (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
     where
     tz0.fld_btidp = $fld_btid
     and
     tz0.fld_empid = t0.fld_empid
     ) 'Other',

    if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
    left join hris.tbl_truck_driver t4 on t4.fld_empid=t0.fld_empid
    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_empjob
    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
    where
    t0.fld_postingid=$fld_btid
    and
    #t4.fld_emploc=$location or t4.fld_empworkloc=$location
    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_empworkloc = 1, t4.fld_empworkloc in (2,5))))
    and
    t0.fld_empjob in (67,68,59)
    group by t0.fld_empid
    order by t0.fld_empnm
        ");
    } else {
   $commission_query = $this->db->query("
    select
    t0.fld_empnm,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',
    #sum(t0.fld_meal) 'standby',
    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',sum(t0.fld_point) 'point',
    t0.fld_empjob,
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 1
    ),0) 'insurance',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 8
   ),0) 'bpjs',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 2
    ),0) 'jaminan',

    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 3
    ),0) 'hutang',
   # (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
    # where
     #tz0.fld_btidp = $fld_btid
     #and
     #tz0.fld_empid = t0.fld_empid
     #) 'other',
    if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
    left join hris.tbl_truck_driver t4 on t4.fld_empid=t0.fld_empid
    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_empjob
    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
    where
    t0.fld_postingid=$fld_btid
    and
    #t4.fld_emploc=$location or t4.fld_empworkloc=$location
    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_empworkloc = 1, t4.fld_empworkloc in (2,5))))
    and
    t0.fld_empjob in (67,68,59)
    group by t0.fld_empid
    order by t0.fld_empnm
        ");
    }


    $comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
   $this->load->library('cezpdf');
   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
   $this->cezpdf->ezSetMargins(10,5,10,5);
      $count = count($commission);
      $sum_commission = 0;
      $sum_standby = 0;
      $sum_point = 0;
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $commission[$i]['count'] = $counteor;
        $sum_commission = $sum_commission + $commission[$i]['commission'];
        $sum_bpjs = $sum_bpjs +  $commission[$i]['bpjs'];;
        $sum_standby = $sum_standby + $commission[$i]['standby'];
        $sum_insurance = $sum_insurance + $commission[$i]['insurance'];
        $sum_other = $sum_other + $commission[$i]['Other'];
        $sum_jaminan = $sum_jaminan + $commission[$i]['jaminan'];
        $sum_hutang = $sum_hutang + $commission[$i]['hutang'];
        $sum_hutangCsr = $sum_hutangCsr + $commission[$i]['hutangCsr'];
        $sum_cicilanHP = $sum_cicilanHP + $commission[$i]['cicilanHP'];
        $sum_point = $sum_point + $commission[$i]['point'];
        if( $commission[$i]['fld_empjob'] == 59) {
           $commission[$i]['commission'] = $commission[$i]['commission'];
           $commission[$i]['total'] = $commission[$i]['commission'] + $commission[$i]['point'] + $commission[$i]['standby']+$commission[$i]['Other'] - $commission[$i]['insurance'] - $commission[$i]['jaminan']- $commission[$i]['bpjs'] - $commission[$i]['hutang']- $commission[$i]['hutangCsr'] - $commission[$i]['cicilanHP'];
        } else {
          $commission[$i]['total'] = $commission[$i]['commission'] + $commission[$i]['point'] - $commission[$i]['insurance']- $commission[$i]['bpjs'] -  $commission[$i]['jaminan'] - $commission[$i]['hutang'];
        }
        $sum_total = $sum_total + $commission[$i]['total'] ;
      }
      $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
      $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
      $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
      $this->cezpdf->ezSetDy(-10);
      $this->cezpdf->ezText("DRIVER COMMISSION SUMMARY" . "   ", 12, array('justification' => 'center'));
      $this->cezpdf->ezSetDy(-20);

      if($comm_data->fld_empjob == 59) { ### Trailer
      $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Nama','commission'=>'Komisi','standby'=>'Tambahan','Other'=>'Lain-Lain','insurance'=>'Asuransi','jaminan'=>'Jaminan','hutang'=>'Total Hutang ','hutangCsr'=>'Hutang Cashier','cicilanHP'=>'Cicilan','bpjs'=>'BPJS','point'=>'Point','total'=>'Total'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>540,'shaded'=>0,'fontSize'=>'7',
      'cols'=>array('counteor'=>array('width'=>5),
      'fld_empnm'=>array('width'=>110),
      #'fld_empnip'=>array('width'=>50),
      'commission'=>array('width'=>50,'justification'=>'right'),
      'standby'=>array('width'=>50,'justification'=>'right'),
      'Other'=>array('width'=>40,'justification'=>'right'),
      'insurance'=>array('width'=>40,'justification'=>'right'),
       'jaminan'=>array('width'=>40,'justification'=>'right'),
       'hutang'=>array('width'=>40,'justification'=>'right'),
       'hutangCsr'=>array('width'=>40,'justification'=>'right'),
       'cicilanHP'=>array('width'=>40,'justification'=>'right'),
       'bpjs'=>array('width'=>40,'justification'=>'right'),
       'point'=>array('width'=>34,'justification'=>'right'),
       'total'=>array('width'=>50,'justification'=>'right'),
       #'ttd'=>array('width'=>25,'justification'=>'right'),
       )));

       $data_sum = array(
                         array('row1'=>'Total',
                               'row2'=>number_format($sum_commission,0,',','.'),
                               'row3'=>number_format($sum_standby,0,',','.'),
        'row4'=>number_format($sum_other,0,',','.'),
                               'row5'=>number_format($sum_insurance,0,',','.'),
             'row6'=>number_format($sum_jaminan,0,',','.'),
                               'row7'=>number_format($sum_hutang,0,',','.'),
                               'row7a'=>number_format($sum_hutangCsr,0,',','.'),
                               'row7b'=>number_format($sum_cicilanHP,0,',','.'),
                               'row7ba'=>number_format($sum_bpjs,0,',','.'),
			       'row7bb'=>number_format($sum_point,0,',','.'),
                               'row8'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>'','row7'=>'','row7a'=>'','row7b'=>'','row7ba'=>'','row7bb'=>'','row8'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>540,'fontSize'=>'7',
        'cols'=>array(
        'row1'=>array('width'=>129,'justification'=>'center'),
        'row2'=>array('width'=>50,'justification'=>'right'),
        'row3'=>array('width'=>50,'justification'=>'right'),
        'row4'=>array('width'=>40,'justification'=>'right'),
  'row5'=>array('width'=>40,'justification'=>'right'),
        'row6'=>array('width'=>40,'justification'=>'right'),
        'row7'=>array('width'=>40,'justification'=>'right'),
        'row7a'=>array('width'=>40,'justification'=>'right'),
        'row7b'=>array('width'=>40,'justification'=>'right'),
        'row7ba'=>array('width'=>40,'justification'=>'right'),
        'row7bb'=>array('width'=>35,'justification'=>'right'),
        'row8'=>array('width'=>50,'justification'=>'right')
        #'row9'=>array('width'=>25,'justification'=>'right')
        )));
       $this->cezpdf->ezSetDy(-15);
        $acc = array(array('row1'=>'Created By','row2'=>'Checked By','row3'=>'Aknowledge By','row4'=>'Approved By','row5'=>'Received By'),
                     array('row1'=>''),
         array('row1'=>''),
                     array('row1'=>''),
         array('row1'=>'Shofa','row2'=>'Mona','row3'=>'Tonny Wijaya','row4'=>'Elly Dwiyanti','row5'=>'Fitrotun Chasanah'),
         array('row1'=>'Staff Trucking ','row2'=>'Chief Trucking','row3'=>'Manager Trucking','row4'=>'Finance SPV','row5'=>'Chasier'),

                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>''),'',array
         ('rowGap'=>'0','xPos'=>20,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>110,'justification' => 'center'),
         'row2'=>array('width'=>110,'justification' => 'center'),
         'row3'=>array('width'=>110,'justification' => 'center'),
         'row4'=>array('width'=>110,'justification' => 'center'),
         'row5'=>array('width'=>110,'justification' => 'center'),
         )));
       }else {
          $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','job'=>'Job Role','trip_count'=>'Trip','commission'=>'Komisi','insurance'=>'Asuransi','jaminan'=>'Jaminan','hutang'=>'Hutang','bpjs'=>'BPJS','total'=>'Total'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>570,'shaded'=>0,'fontSize'=>'7',
      'cols'=>array('counteor'=>array('width'=>5),
      'fld_empnm'=>array('width'=>110),
      'job'=>array('width'=>45),
      'trip'=>array('width'=>45,'justification'=>'center'),
      'commission'=>array('width'=>55,'justification'=>'right'),
       'insurance'=>array('width'=>55,'justification'=>'right'),
       'jaminan'=>array('width'=>45,'justification'=>'right'),
       'hutang'=>array('width'=>50,'justification'=>'right'),
       'bpjs'=>array('width'=>50,'justification'=>'right'),
       'total'=>array('width'=>55,'justification'=>'right'),
       )));

       $data_sum = array(
                         array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_insurance,0,',','.'),
             'row4'=>number_format($sum_jaminan,0,',','.'),
                               'row5'=>number_format($sum_hutang,0,',','.'),'row6'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'8',
        'cols'=>array('row1'=>array('width'=>290,'justification'=>'center'),
        'row2'=>array('width'=>60,'justification'=>'right'),
        'row3'=>array('width'=>60,'justification'=>'right'),
        'row4'=>array('width'=>50,'justification'=>'right'),
        'row5'=>array('width'=>50,'justification'=>'right'),'row6'=>array('width'=>60,'justification'=>'right'))));
       $this->cezpdf->ezSetDy(-15);

       }
       $this->cezpdf->ezNewPage();
       $this->cezpdf->ezSetY(795);
       header("Content-type: application/pdf");
       header("Content-Disposition: attachment; filename=commission_summary.pdf");
       header("Pragma: no-cache");
       header("Expires: 0");
       $output = $this->cezpdf->ezOutput();
       echo $output;
   }

function printSubmitDlv($fld_btid) {
  $query = array();
  $query = $this->db->query("Select t1.fld_btno 'btno', t2.fld_empnm 'pic', t1.fld_btdt 'datepost',t0.fld_btnoreff 'invoice',t0.fld_btcmt 'attn',t0.fld_btdesc 'Cust'
  from tbl_btd_finance t0
  left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
  left join hris.tbl_emp t2 on t2.fld_empid = t1.fld_baidp
  WHERE
  t1.fld_btid = $fld_btid
  ");
 $this->load->library('cezpdf');
 $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
 $this->cezpdf->ezSetMargins(10,5,10,5);
 $querys = $query->result();
$querys_dt = $query->row();
$query = $query->result_array();
    $count = count($query);
 for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $query[$i]['count'] = $counteor;
 }
    $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
    $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS'),
                array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                array('row1'=>"Jakarta Utara 14340")
              );
    $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
    array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
    $this->cezpdf->ezSetDy(-10);
    $this->cezpdf->ezText("SUBMIT DELIVERY INVOICE" . "   ", 12, array('justification' => 'center'));
    $this->cezpdf->ezSetDy(-20);

    $data_hdr = array(
                      array('row1'=>'Submit Number','row2'=>':','row3'=>$querys_dt->btno),
                      array('row1'=>'Posting Date','row2'=>':','row3'=>$querys_dt->datepost),
                      array('row1'=>'PIC','row2'=>':','row3'=>$querys_dt->pic),
                      array('row1'=>'Divisi','row2'=>':','row3'=>'Finance DE')

                      );
    $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
    array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
    $this->cezpdf->ezSetDy(-15);
    $this->cezpdf->ezTable($query,array('count'=>'No','invoice'=>'Invoice','attn'=>'Attn','Cust'=>'Customer'),'',
    array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>540,'shaded'=>0,'fontSize'=>'8',
    'cols'=>array('counteor'=>array('width'=>10),
    'invoice'=>array('width'=>170),
    'attn'=>array('width'=>120),
    'Cust'=>array('width'=>200,'justification'=>'left'),

     )));


     $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>'','row7'=>'','row8'=>'','row9'=>''),'',
      array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>540,'fontSize'=>'8',
      'cols'=>array(
      'row1'=>array('width'=>30,'justification'=>'center'),
      'row2'=>array('width'=>55,'justification'=>'right'),
      'row3'=>array('width'=>55,'justification'=>'right'),

      )));
     $this->cezpdf->ezSetDy(-15);
      $acc = array(array('row1'=>'Kordinator Divisi','row2'=>'Messenger','row3'=>'Dilaporkan Oleh','row4'=>'Diperiksa Oleh'),
                   array('row1'=>''),
       array('row1'=>''),
                   array('row1'=>''),
       array('row1'=>'(                        )','row2'=>'(                        )','row3'=>'(                        )','row4'=>'(                        )'),

              );
   $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>''),'',array
       ('rowGap'=>'0','xPos'=>20,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
       'row1'=>array('width'=>110,'justification' => 'center'),
       'row2'=>array('width'=>110,'justification' => 'center'),
       'row3'=>array('width'=>110,'justification' => 'center'),
       'row4'=>array('width'=>110,'justification' => 'center'),
       )));
     $this->cezpdf->ezSetDy(-15);
     $this->cezpdf->ezNewPage();
     $this->cezpdf->ezSetY(795);
     header("Content-type: application/pdf");
     header("Content-Disposition: attachment; filename=commission_summary.pdf");
     header("Pragma: no-cache");
     header("Expires: 0");
     $output = $this->cezpdf->ezOutput();
     echo $output;
 }
   function printCommission3($fld_btid) {
   $location = $this->session->userdata('location');
   $hdr = $this->db->query("select * from tbl_bth where fld_btid  = $fld_btid");
   $vehicle = $hdr->row()->fld_btp01;

   if($vehicle == 1) { ### Trailer
   $commission_query = $this->db->query("
    select
    t0.fld_empnm,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',sum(t0.fld_meal) 'standby',
    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',
    t0.fld_empjob,
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     where tx0.fld_empid=t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 1
    ),0) 'insurance',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     where tx0.fld_empid=t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 2
    ),0) 'jaminan',

    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx1.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     left join tbl_btd_driver_insurance tx1 on tx1.fld_btreffid = tx0.fld_btid
     where tx0.fld_empid=t0.fld_empid
      and tx1.fld_btreffid > 0
     and tx0.fld_btidp = $fld_btid
     and tx1.fld_btflag = 3
    ),0) 'hutang',
   # (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
    # where
    # tz0.fld_btidp = $fld_btid
     #and
    # tz0.fld_empid = t0.fld_empid
    # ) 'Other',
    ( select sum(ta0.fld_btamt01)'fld_btamt01' from tbl_btd_driver_additional ta0
    where ta0.fld_empid = t0.fld_empid
    and ta0.fld_btidp = $fld_btid ) 'additional',
    if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
    left join hris.tbl_truck_driver t4 on t4.fld_empid=t0.fld_empid
    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_empjob
    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
    where
    t0.fld_postingid=$fld_btid
    and
    #t4.fld_emploc=$location or t4.fld_empworkloc=$location
    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_empworkloc = 1, t4.fld_empworkloc in (2,5))))
    and
    t0.fld_empjob in (62)
    group by t0.fld_empid
    order by t0.fld_empnm
        ");
    } else {
   $commission_query = $this->db->query("
    select
    t0.fld_empnm,t1.fld_btno,date_format(t1.fld_btdt,'%Y-%m-%d') 'date',t2.fld_tyvalnm 'fleet_type',sum(t0.fld_commission) 'commission',
    #sum(t0.fld_meal) 'standby',
    t3.fld_tyvalnm 'location',t5.fld_btinm 'job',
    t0.fld_empjob,
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 1
    ),0) 'insurance',
    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 2
    ),0) 'jaminan',

    (select count(1) from tbl_commission tz0 where tz0.fld_postingid=$fld_btid and tz0.fld_empid=t0.fld_empid
     and length(tz0.fld_btno) > 0) 'trip_count',
    ifnull((select ifnull(tx0.fld_btamt01,0) from tbl_btd_driver_insurance tx0
     where tx0.fld_empid=t0.fld_empid
     and tx0.fld_btidp = $fld_btid
     and tx0.fld_btflag = 3
    ),0) 'hutang',
   # (select tz0.fld_btamt01 from tbl_btd_driver_additional tz0
    # where
     #tz0.fld_btidp = $fld_btid
     #and
     #tz0.fld_empid = t0.fld_empid
     #) 'other',
    if(t4.status = 1,t6.fld_empnip,t7.fld_empnrk) 'fld_empnip'
    from tbl_commission t0
    left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
    left join tbl_tyval t2 on t2.fld_tyvalcd=t1.fld_btp01 and t2.fld_tyid=46
    left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btloc and t3.fld_tyid=21
    left join hris.tbl_truck_driver t4 on t4.fld_empid=t0.fld_empid
    left join hris.tbl_bti t5 on t5.fld_btiid=t4.fld_empjob
    left join hris.tbl_emp t6 on t6.fld_empid = t4.fld_empid
    left join hris.tbl_emp_osrc t7 on t7.fld_empid = t4.fld_empid
    where
    t0.fld_postingid=$fld_btid
    and
    #t4.fld_emploc=$location or t4.fld_empworkloc=$location
    if(t0.fld_empjob in (59),1,if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t4.fld_empworkloc = 1, t4.fld_empworkloc in (2,5))))
    and
    t0.fld_empjob in (67,68,59)
    group by t0.fld_empid
    order by t0.fld_empnm
        ");
    }


    $comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
   $this->load->library('cezpdf');
   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
   $this->cezpdf->ezSetMargins(10,5,10,5);
      $count = count($commission);
      $sum_commission = 0;
      $sum_standby = 0;
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $commission[$i]['count'] = $counteor;
        $sum_commission = $sum_commission + $commission[$i]['commission'];
        $sum_standby = $sum_standby + $commission[$i]['standby'];
        $sum_insurance = $sum_insurance + $commission[$i]['insurance'];
        //$sum_other = $sum_other + $commission[$i]['Other'];
        $sum_jaminan = $sum_jaminan + $commission[$i]['jaminan'];
        $sum_hutang = $sum_hutang + $commission[$i]['hutang'];
        $sum_additional = $sum_additional + $commission[$i]['additional'];
        if( $commission[$i]['fld_empjob'] == 62) {
           $commission[$i]['commission'] = $commission[$i]['commission'];
           $commission[$i]['total'] = $commission[$i]['commission'] + $commission[$i]['standby'] + $commission[$i]['additional'] - $commission[$i]['insurance'] - $commission[$i]['jaminan'] - $commission[$i]['hutang'];
        } else {
   $commission[$i]['total'] = $commission[$i]['commission'] - $commission[$i]['insurance'] - - $commission[$i]['jaminan'] - $commission[$i]['hutang'];
        }
        $sum_total = $sum_total + $commission[$i]['total'] ;
      }
      $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
      $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
      $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
      $this->cezpdf->ezSetDy(-10);
      $this->cezpdf->ezText("DRIVER COMMISSION SUMMARY" . "   ", 12, array('justification' => 'center'));
      $this->cezpdf->ezSetDy(-20);

      if($comm_data->fld_empjob == 62) { ### Trailer
      $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Nama','fld_empnip'=>'NIP','commission'=>'Komisi','additional'=>'Tambahan','insurance'=>'Asuransi','jaminan'=>'Jaminan','hutang'=>'Hutang','total'=>'Total','ttd'=>'ttd'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>540,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('counteor'=>array('width'=>10),
      'fld_empnm'=>array('width'=>120),
      'fld_empnip'=>array('width'=>50),
      'commission'=>array('width'=>55,'justification'=>'right'),
      'additional'=>array('width'=>55,'justification'=>'right'),
//      'Other'=>array('width'=>50,'justification'=>'right'),
      'insurance'=>array('width'=>50,'justification'=>'right'),
       'jaminan'=>array('width'=>50,'justification'=>'right'),
       'hutang'=>array('width'=>50,'justification'=>'right'),
       'total'=>array('width'=>55,'justification'=>'right'),
       'ttd'=>array('width'=>25,'justification'=>'right'),
       )));

       $data_sum = array(
                         array('row1'=>'Total',
                               'row2'=>number_format($sum_commission,0,',','.'),
                               'row3'=>number_format($sum_standby+$sum_additional,0,',','.'),
 //                             'row4'=>number_format($sum_other,0,',','.'),
                               'row5'=>number_format($sum_insurance,0,',','.'),
                               'row6'=>number_format($sum_jaminan,0,',','.'),
                               'row7'=>number_format($sum_hutang,0,',','.'),
                               'row8'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row5'=>'','row6'=>'','row7'=>'','row8'=>'','row9'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>540,'fontSize'=>'8',
        'cols'=>array(
        'row1'=>array('width'=>200,'justification'=>'center'),
        'row2'=>array('width'=>55,'justification'=>'right'),
        'row3'=>array('width'=>55,'justification'=>'right'),
  //      'row4'=>array('width'=>50,'justification'=>'right'),
        'row5'=>array('width'=>50,'justification'=>'right'),
        'row6'=>array('width'=>50,'justification'=>'right'),
        'row7'=>array('width'=>50,'justification'=>'right'),
        'row8'=>array('width'=>55,'justification'=>'right'),
        'row9'=>array('width'=>25,'justification'=>'right')
        )));
       $this->cezpdf->ezSetDy(-15);
        $acc = array(array('row1'=>'Created By','row2'=>'Checked By','row3'=>'Aknowledge By','row4'=>'Approved By','row5'=>'Received By'),
                     array('row1'=>''),
                     array('row1'=>''),
                     array('row1'=>''),
                     array('row1'=>'Shofa','row2'=>'Mona','row3'=>'Tonny Wijaya','row4'=>'Elly Dwiyanti','row5'=>'Fitrotun Chasanah'),
                     array('row1'=>'Staff Trucking ','row2'=>'Chief Trucking','row3'=>'Manager Trucking','row4'=>'Finance SPV','row5'=>'Chasier'),

                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>''),'',array
         ('rowGap'=>'0','xPos'=>20,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>110,'justification' => 'center'),
         'row2'=>array('width'=>110,'justification' => 'center'),
         'row3'=>array('width'=>110,'justification' => 'center'),
        'row4'=>array('width'=>110,'justification' => 'center'),
         'row5'=>array('width'=>110,'justification' => 'center'),
         )));
       }else {
          $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','job'=>'Job Role','trip_count'=>'Trip','commission'=>'Komisi','insurance'=>'Asuransi','jaminan'=>'Jaminan','hutang'=>'Hutang','total'=>'Total'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>570,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('counteor'=>array('width'=>5),
      'fld_empnm'=>array('width'=>120),
      'job'=>array('width'=>50),
      'trip'=>array('width'=>50,'justification'=>'center'),
      'commission'=>array('width'=>60,'justification'=>'right'),
       'insurance'=>array('width'=>60,'justification'=>'right'),
       'jaminan'=>array('width'=>50,'justification'=>'right'),
       'hutang'=>array('width'=>50,'justification'=>'right'),
       'total'=>array('width'=>60,'justification'=>'right'),
       )));

       $data_sum = array(
                         array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),'row3'=>number_format($sum_insurance,0,',','.'),
                               'row4'=>number_format($sum_jaminan,0,',','.'),
                               'row5'=>number_format($sum_hutang,0,',','.'),'row6'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'8',
        'cols'=>array('row1'=>array('width'=>290,'justification'=>'center'),
        'row2'=>array('width'=>60,'justification'=>'right'),
        'row3'=>array('width'=>60,'justification'=>'right'),
        'row4'=>array('width'=>50,'justification'=>'right'),
        'row5'=>array('width'=>50,'justification'=>'right'),'row6'=>array('width'=>60,'justification'=>'right'))));
        $this->cezpdf->ezSetDy(-15);

       }
       $this->cezpdf->ezNewPage();
       $this->cezpdf->ezSetY(795);
       header("Content-type: application/pdf");
       header("Content-Disposition: attachment; filename=commission_summary.pdf");
       header("Pragma: no-cache");
       header("Expires: 0");
       $output = $this->cezpdf->ezOutput();
       echo $output;
   }



  function PostingCommission($fld_btid,$location,$fld_btiid) {
    $post_data = $this->db->query("select * from tbl_bth where fld_btid= $fld_btid and fld_bttyid=22");
    #$this->db->query("delete from tbl_commission where fld_postingid=$fld_btid");
    $post_data = $post_data->row();
    $vehicle = $post_data->fld_btp01;
    if($fld_btiid == '') {
      $fld_btiid = 0;
    }
    $driver = $this->db->query("select
				     t0.fld_empid,
                                     t00.fld_driverid 'fld_driverid',
      			             t0.fld_empnm,
                                     t0.fld_empjob,
                                     t01.fld_btinm,
                                     t0.status,
                                     if(t0.status=1,t1.fld_btival,
                                       if(t0.fld_empjob in(67),t3.fld_btival, ## Box
                                         if(t0.fld_empjob in(59),t4.fld_btival, ## Trailer
                                           if(t0.fld_empjob in (68,88) ,t2.fld_btival,0) ## Car Carrier
                                         )
                                       )
                                     ) 'standby_allowance',
                                     if(t0.fld_empjob = 68 ,t6.fld_btival,
                                       if(t0.fld_empjob=88,t7.fld_btival,0)
                                     )  'commission',
                                       t0.fld_empjoindt,
                                     if(t00.fld_driverbank01 = '',2,if(t00.fld_driverbank01 > 0 and t00.fld_driverp12 !=1,1,3))'pay_type'

                                     from
                                     tbl_driver t00
                                     left join hris.tbl_truck_driver t0 on t0.fld_empid = t00.fld_empid and t0.fld_emporg = 2
                                     left join tbl_bti t01 on t01.fld_btiid=t0.fld_empjob
                                     left join dnxapps.tbl_bti t1 on t1.fld_btiid=4328
                                     left join dnxapps.tbl_bti t2 on t2.fld_btiid=4329
                                     left join dnxapps.tbl_bti t3 on t3.fld_btiid=4330
                                     left join dnxapps.tbl_bti t4 on t4.fld_btiid=4331
                                     left join dnxapps.tbl_bti t5 on t5.fld_btiid=4332
                                     left join dnxapps.tbl_bti t6 on t6.fld_btiid=4333
                                     left join dnxapps.tbl_bti t7 on t7.fld_btiid=4337
				     where
                                     if('$fld_btiid' > 0,t0.fld_empid =$fld_btiid,1)
                                     and
				     #t0.fld_emploc = '$location' or t0.fld_empworkloc = '$location'
                                     if(t0.fld_empjob in (59,62),1,
                                        if(t0.fld_empid in(66,208,743,704),1,if('$location'=1,t0.fld_empworkloc = 1, t0.fld_empworkloc in (2,5))))
                                     and
				     if($post_data->fld_btiid > 0,t0.fld_empid=$post_data->fld_btiid,1)
				     and
				     t0.fld_empjob in (59,62) and t0.status = 2
				     and t00.fld_driverorg = 2
                                     and t00.fld_commission = 1
				     ");
    $driver = $driver->result();
    $days = 0;
    $dtsa = strtotime($post_data->fld_btdtsa);
    $dtso = strtotime($post_data->fld_btdtso);
      $order = $this->db->query("select
			       t0.fld_btid,t0.fld_driverid 'driverid',
			       t0.fld_btp07 'qty',
			       t0.fld_btp11 'driver',
                               t0.fld_btp14 'driver2',
			       t0.fld_btp03 'kenek',
			       t0.fld_btnoalt,
			       date_format(t0.fld_btdtsa,'%Y-%m-%d') 'date',
                               if(t0.fld_btp04 = 6 and t0.fld_baidc in (5125) ,40000,
                                     if(t0.fld_btp04 = 6 and t0.fld_baidc in (5169,6386,5210,5022) ,20000,if(t0.fld_btp04 in(5,7),0,t10.fld_trfamt))
                                      ## Jika Transit Komisi = 40000
                                 ) 'driver_tariff_trailer',

                               #if(t0.fld_btp04 = 6 and t0.fld_baidc = 5125,20000,t10.fld_trfamt) 'driver_tariff_trailer',
                               t12.fld_trfamt01 'kenek_tariff_trailer',
			       t9.fld_benm 'route',
			       if(ifnull(t10.fld_trfamt,0) < 50000, 0 , t13.fld_btival) 'meal_allowance1',
                               if(ifnull(t10.fld_trfamt,0) < 50000, 0 , if(t0.fld_btp04 in(5,7),0,t14.fld_btival)) 'meal_allowance2',
			       t0.fld_btuamt,
			       if(t1a.fld_empjob in (59),if(t17.fld_btid > 0 ,ifnull(t17.fld_amt,0),0),0) 'point'
		               from tbl_bth t0
			       left join dnxapps.tbl_route t1 on t0.fld_btp09 = t1.fld_routeid
                               left join hris.tbl_truck_driver t1a on t1a.fld_empid=t0.fld_btp11
                               left join hris.tbl_truck_driver t1b on t1b.fld_empid=t0.fld_btp03
			       left join dnxapps.tbl_area t4 on t4.fld_areaid=t1.fld_routeto
			       left join dnxapps.tbl_area t5 on t5.fld_areaid=t1.fld_routefrom
                               left join tbl_btr t6 on t6.fld_btrdst=t0.fld_btid
			       left join tbl_bth t7 on t7.fld_btid=t6.fld_btrsrc and t7.fld_bttyid = 77
			       left join dnxapps.tbl_be t9 on t9.fld_beid=t0.fld_baidc
                               left join dnxapps.tbl_trf t10 on t10.fld_btid=t1.fld_routeid and t10.fld_btiid = t0.fld_btflag and t10.fld_trfp02=if(t0.fld_btp17 = 2 and t0.fld_btp15 = 1,1,t0.fld_btp17)
                               and t10.fld_baid=t1a.status and t10.fld_beid=t0.fld_btloc
                               left join dnxapps.tbl_trf t12 on t12.fld_btid=t1.fld_routeid and t12.fld_btiid = t0.fld_btflag and t12.fld_trfp02=if(t0.fld_btp17 = 2 and t0.fld_btp15 = 1,1,t0.fld_btp17)
                               and t12.fld_baid=t1a.status and t12.fld_beid=t0.fld_btloc
                               left join dnxapps.tbl_tyval t11 on t11.fld_tyvalcd = t0.fld_btflag and t11.fld_tyid = 19
                               left join dnxapps.tbl_bti t13 on t13.fld_btiid = 6815 ### Additional Commission  Business Day
                               left join dnxapps.tbl_bti t14 on t14.fld_btiid = 6816 ### Additional Commission  Holiday
			       left join tbl_toh t17 on t17.fld_btidp = t7.fld_btid and t17.fld_btflag = 50
			       where
			       t0.fld_bttyid = 80
				#and
				#t0.fld_btp04 in (1,2)
			       and
			       date_format(t0.fld_btdtsa,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d')
			       and date_format('$post_data->fld_btdtso','%Y-%m-%d')
			       and t11.fld_tyvalcfg = $vehicle
                               and if($vehicle = 2, t0.fld_btp07 > 0 ,1)
                               and t7.fld_btstat !=5
                               and
                               t0.fld_btstat = 3
			       ");

    $order = $order->result();
    $multi_drop = $this->db->query("select t1.fld_btid,t5.fld_trfamt 'driver',t1.fld_driverid 'driverid',
			       t6.fld_trfamt01 'kenek',t0.fld_btcmt,t0.fld_btid 'id'
                               from
			       tbl_btd_route t0
			       left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
			       left join tbl_btr t2 on t2.fld_btrdst=t1.fld_btid
			       left join tbl_bth t3 on t3.fld_btid=t2.fld_btrsrc and t3.fld_bttyid=13
			       left join tbl_route t4 on t4.fld_routeid=t0.fld_btflag
                               left join hris.tbl_truck_driver t4a on t4a.fld_empid=t1.fld_btp11
                               left join hris.tbl_truck_driver t4b on t4b.fld_empid=t1.fld_btp03
			       left join tbl_trf  t5 on t5.fld_btid=t4.fld_routeid and t5.fld_btiid=t1.fld_btflag and t5.fld_beid=t1.fld_btloc and t5.fld_baid=t4a.status
			       left join tbl_trf  t6 on t6.fld_btid=t4.fld_routeid and t6.fld_btiid=t1.fld_btflag and t6.fld_beid=t1.fld_btloc and t6.fld_baid=t4b.status
			       where
                               t1.fld_bttyid=20
                               and
                               date_format(t3.fld_btdt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d')
                               and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                               and t1.fld_btp04 != 1
                               and if($vehicle = 2, t1.fld_btp07 > 0, 1)
                               ");
    $multi_drop = $multi_drop->result();
    $days = abs(($dtso -  $dtsa) / 86400) + 1;
    $gholiday = $this->db->query("select fld_holidaydt from hris.tbl_holiday where date_format(fld_holidaydt,'%Y-%m-%d') > date_add(date_format(now(),'%Y-%m-%d'), interval -1 MONTH)");
    $gholiday = $gholiday->result();
    $holidayList = array();
    foreach ($gholiday as $rholiday) {
      $holidayList [] = $rholiday->fld_holidaydt;
    }
    ### Absence Driver
    $gabsence = $this->db->query("select t0.fld_driverabsencedt,t0.fld_empid,t0.fld_btflag,t0.fld_driverabsencedesc,t1.fld_tyvalnm from tbl_driverabsence t0
    left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_driverabsencedesc and t1.fld_tyid=539
    where date_format(t0.fld_driverabsencedt,'%Y-%m-%d')
    between date_format('$post_data->fld_btdtsa','%Y-%m-%d')  and date_format('$post_data->fld_btdtso','%Y-%m-%d')");
    $gabsence = $gabsence->result();

    ### Absence Driver From HRD
    $gabsenceHRD = $this->db->query("select date_format(t0.fld_btdt,'%Y-%m-%d') 'date',t1.fld_tyvalnm 'desc' ,t2.fld_btiid 'fld_empid'
                                  from
                                  hris.tbl_btd_leave t0
                                  left join hris.tbl_tyval t1 on t1.fld_tyvalcd = t0.fld_btiid and t1.fld_tyid = 76
                                  left join hris.tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                                  where date_format(t0.fld_btdt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d')  and date_format('$post_data->fld_btdtso','%Y-%m-%d')");
    $gabsenceHRD = $gabsenceHRD->result();

   $gabsenceTrailer = $this->db->query("select t0.fld_driverabsencedt,t0.fld_empid,t0.fld_btflag,t0.fld_driverabsencedesc,t1.fld_tyvalnm from tbl_driverabsence t0
    left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_driverabsencedesc and t1.fld_tyid=98
    where date_format(t0.fld_driverabsencedt,'%Y-%m-%d')
    between date_format('$post_data->fld_btdtsa','%Y-%m-%d')  and date_format('$post_data->fld_btdtso','%Y-%m-%d')
    ");
    $gabsenceTrailer = $gabsenceTrailer->result();



    if($vehicle == 1) {

    $gstay = $this->db->query("select t0.fld_btp11,date_format(t0.fld_btdtsa,'%Y-%m-%d') 'date',t0.fld_btp03,0 'fld_btamt01',t0.fld_btp14,concat(if(t0.fld_btdesc = 7,'REPO','MENGINAP')) 'desc'
                               from tbl_bth t0
                               where
                               t0.fld_bttyid = 73
                               and t0.fld_btdesc in (7,11)
                               and t0.fld_btstat = 3
                               and date_format(t0.fld_btdtsa,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d')  and date_format('$post_data->fld_btdtso','%Y-%m-%d')");
    } else {
       $gstay = $this->db->query("select t1.fld_btp11,t0.fld_btdt 'date',t1.fld_btp03,t0.fld_btamt01,t1.fld_btp14,concat('MENGINAP') 'desc'
                               from tbl_btd_truck_cost t0
                               left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp
                               where t0.fld_btiid in (11,12) and date_format(t0.fld_btdt,'%Y-%m-%d')
                               between date_format('$post_data->fld_btdtsa','%Y-%m-%d')  and date_format('$post_data->fld_btdtso','%Y-%m-%d')");

    }
    $gstay = $gstay->result();


    for ($i=0; $i<$days; ++$i) {
        $tmp_day = strtotime("+$i day", $dtsa);
        $day = strftime("%A",$tmp_day);
        $tmp = strftime("%Y-%m-%d",$tmp_day);
          foreach ($driver as $rdriver) {
            $this->db->query("delete from tbl_commission
                              where
                              fld_postingid=$fld_btid
                              and fld_empid = $rdriver->fld_empid
                              and fld_commissiondt = '$tmp'");
            $commission = 0;
            $mc=0;
            $standby = 0;
	    $tot_commission = 0;
            $absenceList = array();
            $absenceListTrailer = array();
            $absenceListHRD = array();
            $stayList = array();
            foreach ($gabsence as $rabsence) {
              if ($rabsence->fld_empid == $rdriver->fld_empid) {
                $absenceList [] = $rabsence->fld_driverabsencedt;
                if($tmp == $rabsence->fld_driverabsencedt) {
                  $absence_reason = $rabsence->fld_tyvalnm;
                }
              }
            }

	    foreach ($gabsenceHRD as $rabsenceHRD) {
		    if ($rabsenceHRD->fld_empid == $rdriver->fld_empid) {
                $absenceListHRD [] = $rabsenceHRD->date;
                if($tmp == $rabsenceHRD->date) {
                  $absence_reasonHRD = $rabsenceHRD->desc;
                }
              }
            }

            foreach ($gabsenceTrailer as $rabsenceTrailer) {
              if ($rabsenceTrailer->fld_empid == $rdriver->fld_empid) {
                $absenceListTrailer [] = $rabsenceTrailer->fld_driverabsencedt;
                if($tmp == $rabsenceTrailer->fld_driverabsencedt) {
                  $absence_reason = $rabsenceTrailer->fld_tyvalnm;
                }
              }
            }


            $stay_meal = 0;
            foreach ($gstay as $rstay) {
              if ($rstay->fld_btp11 == $rdriver->fld_empid || $rstay->fld_btp03 == $rdriver->fld_empid || $rstay->fld_btp14 == $rdriver->fld_empid) {
                $stayList [] = $rstay->date;
              }
              if($rstay->date == $tmp && ($rstay->fld_btp03 == $rdriver->fld_empid || $rstay->fld_btp11 == $rdriver->fld_empid ||
                 $rstay->fld_btp14 == $rdriver->fld_empid)) {
                $stay_meal = $rstay->fld_btamt01;
                $stay_desc = $rstay->desc;
              }
            }
	    $count_do = 0;
	    foreach ($order as $rorder) { ## Looping DO Return
              if (($rorder->driver == $rdriver->fld_empid || $rorder->kenek == $rdriver->fld_empid || $rorder->driver2 == $rdriver->fld_empid)  && $rorder->date == $tmp) {
        	if($vehicle == 2) {  ## Jika Car Carrier
		  ### Cek Jumlhan chasis
                  $chasis = $this->db->query("select count(1) 'qty' from tbl_btd_car_carrier t0 where t0.fld_btidp = $rorder->fld_btid");
                  $chasis = $chasis->row();
		  if($rdriver->status == 2 && $chasis->qty == $rorder->qty) { ## Jika Mitra
		    $commission = $rdriver->commission * $rorder->qty;
                  } else if($rdriver->status == 1  && $chasis->qty == $rorder->qty) { ## Jika Karyawawan
                    if($rdriver->fld_empid == $rorder->driver){
		      $commission = $rorder->driver_tariff;
                    } else if ($rdriver->fld_empid == $rorder->kenek){
                      $commission = $rorder->kenek_tariff;
                    }
                  } else {
                    $commission = 0;
		  }
                } else if ($vehicle == 3) {  ## Jika Box
                  if($rdriver->fld_empid == $rorder->driver || $rdriver->fld_empid == $rorder->driver2){
                    if($rorder->project_commission == 1) {
                       $commission = ($rorder->driver_tariff_s * ($rorder->fld_btuamt - 1)) + $rorder->driver_tariff_p;
                    } else {
                      $commission = $rorder->driver_tariff;
                    }
                  } else if ($rdriver->fld_empid == $rorder->kenek){
                    $commission = $rorder->kenek_tariff;
                  }

                } else if ($vehicle == 1 && $rdriver->status == 2) {  ## Jika Trailer
                  if($rdriver->fld_empid == $rorder->driver || $rdriver->fld_empid == $rorder->driver2){
                    $commission = $rorder->driver_tariff_trailer;
                    if($day == 'Saturday' || $day == 'Sunday' || in_array($tmp, $holidayList)) {
                      $rorder->meal_allowance = $rorder->meal_allowance2;
                    } else {
                      #$rorder->meal_allowance = $rorder->meal_allowance1;
                       $rorder->meal_allowance = 0;
                    }
                  } else if ($rdriver->fld_empid == $rorder->kenek){
                    $commission = $rorder->kenek_tariff_trailer;
                    $rorder->meal_allowance = 0;
                  }

                }
                $count_do = $count_do + 1;
                 if($rorder->meal_allowance == 0 ) {
                  $rorder->meal_allowance = $stay_meal;
                }

                $this->db->query("insert ignore into tbl_commission
		(fld_postingid,fld_commissiondt,fld_empid,fld_driverid,fld_empnm,fld_driver_status,fld_empjob,fld_meal,fld_commission,fld_tripqty,fld_btreffid,fld_btno,fld_empty,fld_route,fld_commflag,fld_comm01,fld_point)
                values
                ('$fld_btid','$tmp','$rdriver->fld_empid','$rdriver->fld_driverid'," . $this->db->escape($rdriver->fld_empnm) . ",'$rdriver->status','$rdriver->fld_empjob','$rorder->meal_allowance','$commission','$count_do','$rorder->fld_btid','$rorder->fld_btnoalt','TIDAK','$rorder->route',2,'$rdriver->pay_type','$rorder->point')");

                ### Trailer TLO dan MUJ
                if (in_array($tmp, $absenceListTrailer)) {
                  $route = "$absence_reason";
                  $upah = 0;
                  $makan = 0;
                  $this->db->query("insert ignore into tbl_commission
                  (fld_postingid,fld_commissiondt,fld_empid,fld_driverid,fld_empnm,fld_driver_status,fld_empjob,fld_meal,fld_commission,fld_tripqty,fld_btreffid,fld_btno,
                  fld_empty,fld_route,fld_commflag,fld_comm01,fld_point)
                  values
                  ('$fld_btid','$tmp','$rdriver->fld_empid','$rdriver->fld_driverid', " . $this->db->escape($rdriver->fld_empnm) . ",'$rdriver->status','$rdriver->fld_empjob',
                  '$makan','$upah','$count_do','','','TIDAK','$route',3,'$rdriver->pay_type','$rorder->point')");
                }

                ### Multi Drop
                if($vehicle == 3 && $rdriver->status=1) {
                  $commission2 = 0;
		  foreach ($multi_drop as $rmulti_drop) {
                    $number = $rorder->fld_btnoalt . '-' . $mc;
                    if($rmulti_drop->fld_btid == $rorder->fld_btid ) {
                      $mc = $mc + 1;
                      if($rdriver->fld_empjob == 67){
                        $commission2 = $rmulti_drop->driver;
                      } else if ($rdriver->fld_empjob == 89){
                        $commission2 = $rmulti_drop->kenek;
                      }
                      ### Insert To table
                      $this->db->query("insert ignore into tbl_commission
                                        (fld_postingid,fld_commissiondt,fld_empid,fld_driverid,fld_empnm,fld_driver_status,fld_empjob,fld_meal,
                                        fld_commission,fld_tripqty,fld_btreffid,fld_btno,fld_empty,fld_route,fld_commflag,fld_comm01)
                                        values
                                        ('$fld_btid','$tmp','$rdriver->fld_empid','$rdriver->fld_driverid', " . $this->db->escape($rdriver->fld_empnm) . ",'$rdriver->status',
                                        '$rdriver->fld_empjob','0','$commission2','$count_do','$rmulti_drop->fld_btid',
                                        '$number','TIDAK','$rmulti_drop->fld_btcmt',4,'$rdriver->pay_type')");

                    }
                  }
                }

              }
	    }
            if($count_do <= 0 ){ ### Jika supir tidak narik
              if(in_array($tmp, $holidayList)) {
                $route = "LIBUR NASIONAL";
		$upah = 0;
                $makan = 0;
              } elseif ($day == "Sunday") {
                $route = "HARI MINGGU";
                $upah = 0;
                $makan = 0;
              } elseif (in_array($tmp, $absenceList)) {
                $route = "$absence_reason";
		$upah = 0;
                $makan = 0;
              } elseif (in_array($tmp, $absenceListHRD)) {
                $route = "$absence_reasonHRD";
                $upah = 0;
                $makan = 0;
              } elseif (in_array($tmp, $stayList)) {
                $route = "$stay_desc";
                $upah = 0;
                $makan = $stay_meal;
              } else {
                $route = "TIDAK ADA KEGIATAN";
		$upah = 0;
		$upah = $rdriver->standby_allowance;
                $makan = 0;
              }

              $this->db->query("insert ignore into tbl_commission
	      (fld_postingid,fld_commissiondt,fld_empid,fld_driverid,fld_empnm,fld_driver_status,fld_empjob,fld_meal,fld_commission,fld_tripqty,fld_btreffid,fld_btno,
              fld_empty,fld_route,fld_commflag,fld_comm01)
              values
              ('$fld_btid','$tmp','$rdriver->fld_empid','$rdriver->fld_driverid', " . $this->db->escape($rdriver->fld_empnm) . ",'$rdriver->status','$rdriver->fld_empjob',
              '$makan','$upah','$count_do','','','TIDAK','$route',5,'$rdriver->pay_type')");
            }
          }
      }
   }
  function setIdDriver($fld_btid,$fld_formnm){
  #echo "$fld_btid$fld_formnm";
  #exit();
  if($fld_formnm == '78000DRIVER_REFERENCE'){
  $this->db->query("update `tbl_be` t0
                        left join tbl_driver t1 on t1.fld_empid = t0.fld_benm
                        set t0.fld_beidp = t1.fld_driverid
                        WHERE t0.fld_beid = '$fld_btid' and t0.`fld_betyid` = 9");
  $point = 10 ; # driver get driver point
  $this->db->query("update tbl_btd_driver_reference set fld_btp02 = '$point' where fld_btidp = '$fld_btid' and fld_btp01 = 1 ");
  $url = base_url() . "index.php/page/form/78000DRIVER_REFERENCE/edit/$fld_btid";
  redirect($url);
  } else {
  $this->db->query("update tbl_btd_driver_improve t0
                        left join tbl_driver t1 on t1.fld_empid = t0.fld_empid
                        left join dnxapps.tbl_bti t2 on t2.fld_btival01 = t0.fld_btflag and t2.fld_btiid in (28299,28296,28297,28298)

                        set t0.fld_driverid = t1.fld_driverid,t0.fld_btp01 = if(t0.fld_btflag = 1,ifnull(t2.fld_btival * (select tz0.fld_btval03 from dnxapps.tbl_driver_group tz0
                        left join tbl_bti tr0 on tr0.fld_btiid =tz0.fld_btidp and tr0.fld_bticid = 37
                        where tr0.fld_btival04 = 1 and tz0.fld_driverid = t0.fld_driverid limit 1 )/100,0),t2.fld_btival)
                        where t0.fld_btidp ='$fld_btid'
                        and t0.fld_btflag not in (2,4)
                        ");

  $url = base_url() . "index.php/page/form/78000DRIVER_IMPROVE/edit/$fld_btid";
  redirect($url);
  }
 }
  function PostingWeeklyBonus ($fld_btid,$location,$mode) {
	  #echo "$mode" ;
	  #echo exit();
    $post_data = $this->db->query("select * from tbl_bth where fld_btid= $fld_btid");
    $this->db->query("delete from tbl_bonus where fld_postingid=$fld_btid");
    $this->db->query("delete from tbl_bonus_log where fld_postingid=$fld_btid");
    $post_data = $post_data->row();
    $vehicle = $post_data->fld_btp01;
    $type = $post_data->fld_btflag;
    //$vehicle = $post_data->fld_btp01;
    $dtsa = $post_data->fld_btdtsa;
    $dtso = $post_data->fld_btdtso;
    if($type == 1) {
      $this->truckingDailyBonus($fld_btid,$dtsa,$dtso,$post_data->fld_btiid,$vehicle);
    }

    echo "Type = $type<br>";


    if ($type == 3) {
    if ($mode == 'rev' ) {
        $this->db->query("update tbl_bth
                      set fld_btidp = 0
                      where
                      fld_bttyid = 32
                      and fld_btidp = $fld_btid
                      and fld_btflag = 2
                      limit 4 "
                      );
      }
		else {
        $this->db->query("update tbl_bth
                      set fld_btidp = $fld_btid
                      where
                      fld_bttyid = 32
                      and fld_btidp = 0
                      and fld_btflag = 2
                      limit 4 "
                      );
      }
    }

    $driver = $this->db->query("select
				     t0.fld_empid,
      			             t0.fld_empnm,
                                     t0.fld_empjob,
                                     t01.fld_btinm,t00.fld_driverid,
                                     t0.status,
                                     if($type = 2, t1.fld_btival,t2.fld_btival) 'bonus'
                                     from
                                     tbl_driver t00
                                     left join hris.tbl_truck_driver t0 on t0.fld_empid = t00.fld_empid
                                     left join hris.tbl_bti t01 on t01.fld_btiid=t0.fld_empjob
                                     left join dnxapps.tbl_bti t1 on t1.fld_btiid=6519
                                     left join dnxapps.tbl_bti t2 on t2.fld_btiid=6813
                                     #left join tbl_driver t3 on t3.fld_empid = t00.fld_empid and t3.fld_driverstat = 1
				     where
                                     #if('$location'=1,t0.fld_empworkloc = 1, t0.fld_empworkloc in (2,5))
                                     t00.fld_driverloc = 1
                                     and
				     if($post_data->fld_btiid > 0,t0.fld_empid=$post_data->fld_btiid,1)
				     and
				     t00.fld_driverjob = 59
                                     and t00.fld_driverorg = 2
                                     and t00.fld_bonus = 1
                                     and t0.status = 2
				     ");
    $driver = $driver->result();
    $absence = $this->db->query("select
                                 t0.fld_driverabsenceid 'id',
                                 t0.fld_empid  'fld_empid',
                                 0 'dtsa',
                                 0 'dtso',
                                 1 'type',
                                 0 'btid',
                                 0 'limit'
                                 from tbl_driverabsence t0
                                 LEFT JOIN hris.tbl_truck_driver t1 ON t1.fld_empid = t0.fld_empid
			      	 where
                                 t1.fld_empjob = 59
                                 and
                                 t0.fld_driverabsencedesc in (1,2,3,8,9)
                                 and
			         date_format(t0.fld_driverabsencedt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                 UNION
                                 select
                                 tz0.fld_btid 'id',
                                 tz0.fld_btp11 'fld_empid',
                                 date_format(tz2.fld_btdtsa,'%Y-%m-%d') 'dtsa',
                                 date_format(tz2.fld_btdt,'%Y-%m-%d') 'dtso',
                                 3 'type',
                                 tz2.fld_btid 'btid',
                                 tz3.fld_routeltime 'limit'
                                 from
                                 tbl_bth tz0
                                 left join tbl_btr tz1 on tz1.fld_btrsrc=tz0.fld_btid
                                 left join tbl_bth tz2 on tz2.fld_btid = tz1.fld_btrdst and tz2.fld_bttyid = 80
                                 left join dnxapps.tbl_route tz3 on tz3.fld_routeid = tz0.fld_btp09
                                 where
                                 tz0.fld_bttyid = 77
                                 and
                                 tz0.fld_btstat != 5
                                 and
                                 ifnull(tz2.fld_btid,0) > 0
                                 and
                                 date_format(tz0.fld_btdt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                UNION
                                 select
                                 t0.fld_driverincidentid 'id',
                                 t0.fld_empid  'fld_empid',
                                 0 'dtsa',
                                 0 'dtso',
                                 5 'type',
                                 0 'btid',
                                 0 'limit'
                                 from tbl_driverincident t0
                                 LEFT JOIN hris.tbl_truck_driver t1 ON t1.fld_empid = t0.fld_empid
                                 where
                                 t1.fld_empjob = 59 and t0.fld_bt01 = 1
                                 and
                                 date_format(t0.fld_driverincidentdt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                 UNION
                                 select *
                                 from (select
                                 t0.fld_btid 'id' ,
                                 t0.fld_btp11 'fld_empid',
                                 if(t2.fld_btid > 0,date_format(t2.fld_btdtso,'%Y-%m-%d'),date_format(t0.fld_btdt,'%Y-%m-%d')) 'dtsa',
                                 date_format(t0.fld_btdtp,'%Y-%m-%d') 'dtso',
                                 6 'type',
                                 0 'btid',
                                 1 'limit'
                                 from tbl_bth t0
                                 left join tbl_btr t1 on t1.fld_btrsrc = t0.fld_btid and t1.fld_btrdsttyid = 78
                                 left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst and t2.fld_btdesc = 11
                                 where
                                 t0.fld_bttyid = 77
                                 and t0.fld_btstat =3
                                 and t0.fld_btp04 = 1 and t0.fld_btp41 !=1
                                 and date_format(t0.fld_btdt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                 #and t0.fld_btid IN (2877573,2878925,2878217,2871891)
                                 and  date_format(t0.fld_btdtp,'%Y-%m-%d') > if(t2.fld_btid > 0,date_format(t2.fld_btdtso,'%Y-%m-%d'),DATE_ADD(date_format(t0.fld_btdt,'%Y-%m-%d'), INTERVAL 1 DAY))
                                 order by t2.fld_btdesc desc) res
                                 group by res.id
                                 UNION
                                 SELECT
                                 tx0.fld_btid 'id',
                                 tx0.fld_btp11 'fld_empid',
                                 date_format(tx0.fld_btdt,'%Y-%m-%d') 'dtsa',
                                 date_format(tx2.fld_btdt,'%Y-%m-%d') 'dtso',
                                 7 'type',
                                 tx2.fld_btid 'btid',
                                 3 'limit'
                                 FROM tbl_bth tx0
                                 left join tbl_btr tx1 on tx1.fld_btrsrc = tx0.fld_btid and tx1.fld_btrdsttyid = 81
                                 left join tbl_bth tx2 on tx2.fld_btid = tx1.fld_btrdst and tx2.fld_bttyid = 81
                                 where
                                 tx0.fld_bttyid = 77
                                 and tx0.fld_btstat != 5
                                 and tx2.fld_btstat = 3
                                 #and tx2.fld_btp07 !=1
                                 and date_format(tx0.fld_btdt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                 and ifnull(tx2.fld_btid,0) > 0 and tx2.fld_btdt !='0000-00-00 00:00:00'
                                ");
    $absence = $absence->result();
    ### Get total holiday in salary periode
    $gholiday = $this->db->query("select fld_holidaydt from hris.tbl_holiday where date_format(fld_holidaydt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')");
    $gholiday = $gholiday->result();
    $holidayList = array();
    foreach ($gholiday as $rholiday) {
      $holidayList [] = $rholiday->fld_holidaydt;
    }
    ####

    ### Get Latest Bonus
    $wbonush = $this->db->query("select t0.fld_btid  from tbl_bth t0
                                where
                                t0.fld_bttyid = 32
                                and t0.fld_btidp = $fld_btid
                                and t0.fld_btflag = 2
                                limit 4 ");
    $wbonush = $wbonush->result();
    foreach ($driver as $rdriver) {
      echo "##$rdriver->fld_empid##$rdriver->fld_empnm<br>";
      $fail = 0;
      $pod = 0;
      $tat_sum = 0;
      $bonus = 4;
      foreach  ($absence as $rabsence) {
        if($rdriver->fld_empid == $rabsence->fld_empid) {
          if($rabsence->type == 3) {
            $start = strtotime($rabsence->dtsa);
            $stop = strtotime($rabsence->dtso);
            $days = abs(($stop -  $start) / 86400) + 1;
            $tat = 0;
            for ($i=1; $i<$days; ++$i) {
              $tmp_day = strtotime("+$i day", $start);
              $day = strftime("%A",$tmp_day);
              $tmp = strftime("%Y-%m-%d",$tmp_day);
              if(($day != "Sunday") && (!in_array($tmp, $holidayList)) ) {
                $tat = $tat + 1;
              #  echo "did =$rabsence->fld_empid,id=$rabsence->id,tat =$tat<br>";
              }
            }
            $this->db->query("update tbl_bth set fld_btp20 = $tat where fld_btid =  $rabsence->btid and fld_bttyid = 80 limit 1");
            if ($tat > $rabsence->limit) {

              #echo "!!!!!$rdriver->fld_empnm## $tat###$rabsence->limit##$rabsence->btid<br>";
             $fail = $fail + 1;
     }
          } /*else if($rabsence->type == 6){
            $start = strtotime($rabsence->dtsa);
            $stop = strtotime($rabsence->dtso);
			  echo "start=$start<br>";
			  echo "end=$stop";
			  exit();
            $days = abs(($stop -  $start) / 86400) + 1;
            $tat = 0;
            for ($i=1; $i<$days; ++$i) {
              $tmp_day = strtotime("+$i day", $start);
              $day = strftime("%A",$tmp_day);
              $tmp = strftime("%Y-%m-%d",$tmp_day);
              if(($day != "Sunday") && (!in_array($tmp, $holidayList)) ) {
                echo "$day<br>";
                $tat = $tat + 1;
              }
            }
             if ($tat > $rabsence->limit) {
              $fld_empt = $fld_empt  + 1;
              $fail = $fail + 1;
              $empt_list .= "," . $rabsence->btid;
    #          echo "!!!!!$rdriver->fld_empnm## $tat###$rabsence->limit##$rabsence->btid<br>";
              $this->db->query("insert into tbl_bonus_log (fld_postingid,fld_btid,fld_driverid,fld_btflag,fld_logval01) values ($fld_btid,$rabsence->id,$rdriver->fld_driverid,6,'$tat')");
            }

          }*/  else if($rabsence->type == 7){
            $start = strtotime($rabsence->dtsa);
            $stop = strtotime($rabsence->dtso);
            $days = abs(($stop -  $start) / 86400) + 1;
            $tat = 0;
            for ($i=1; $i<$days; ++$i) {
              $tmp_day = strtotime("+$i day", $start);
              $day = strftime("%A",$tmp_day);
              $tmp = strftime("%Y-%m-%d",$tmp_day);
              if(($day != "Sunday") && (!in_array($tmp, $holidayList)) ) {
                echo "$day<br>";
               # echo "did =$rabsence->fld_empid,id=$rabsence->id,tat =$tat<br>";
                $tat = $tat + 1;
              }
            }
             if ($tat > $rabsence->limit) {
              $fld_lolo = $fld_lolo  + 1;
              $fail = $fail + 1;
              $empt_list .= "," . $rabsence->btid;
              $this->db->query("insert into tbl_bonus_log (fld_postingid,fld_btid,fld_driverid,fld_btflag,fld_logval01) values ($fld_btid,$rabsence->id,$rdriver->fld_driverid,7,'$tat')");
            }

          }  else {
            $fail = $fail + 1;
          }
        }
      }
      if($type == 3) {
        $fail = 0;
        $bonus = 0;
        foreach ($wbonush as $rwbonush) {
          $wbonusd = $this->db->query("select 1 from tbl_bonus t0
                                   where
                                   t0.fld_postingid = $rwbonush->fld_btid
                                   and t0.fld_btflag = 2
                                   and t0.fld_bonusamt = 100000
                                   and t0.fld_empid = $rdriver->fld_empid
                                  ");
            if ($wbonusd->num_rows() > 0) {
              $bonus = $bonus + 1;
            }
        }

      }
 # echo "!!!!!$rdriver->fld_empnm## $bonus %% $fail<br>";
      if ($fail == 0 && $bonus == 4) {
        $this->db->query("insert into tbl_bonus (fld_btflag,fld_empid,fld_bonusamt,fld_postingid,fld_driverid) values ($post_data->fld_btflag,$rdriver->fld_empid,$rdriver->bonus,$fld_btid,'$rdriver->fld_driverid')");
      } else {
        $this->db->query("insert into tbl_bonus (fld_btflag,fld_empid,fld_bonusamt,fld_postingid,fld_pod,fld_driverid) values ($post_data->fld_btflag,$rdriver->fld_empid,0,$fld_btid,$pod,'$rdriver->fld_driverid')");
      }
    }
 # exit();
  }


   function truckingDailyBonus($fld_btid,$dtsa,$dtso,$fld_empid,$vehicle) {
   $this->db->query("delete from tbl_bonus where fld_postingid=$fld_btid");
   $driver = $this->db->query("select
                                     t0.fld_empid,
                                     t0.fld_empfpid,
                                     t0.fld_empnm,
                                     t0.fld_empjob,
                                     t01.fld_btinm,

                                     t1.fld_btival 'bonus',
                                     (select tx0.fld_fplogtime from hris.tbl_fplog tx0 where tx0.fld_fplogdt = date_format('$dtsa','%Y-%m-%d') and tx0.fld_empfpid = t0.fld_empfpid order by tx0.fld_fplogtime limit 1) 'in',
                                     (select tx0.fld_fplogtime from hris.tbl_fplog tx0 where tx0.fld_fplogdt = date_format('$dtsa','%Y-%m-%d') and tx0.fld_empfpid = t0.fld_empfpid order by tx0.fld_fplogtime desc limit 1) 'out'
                                     from
                                     tbl_driver t00
                                     left join hris.tbl_truck_driver t0 on t0.fld_empid = t00.fld_empid
                                     left join hris.tbl_bti t01 on t01.fld_btiid=t0.fld_empjob
                                     left join dnxapps.tbl_bti t1 on t1.fld_btiid=12427
                                     where
                                     if($fld_empid > 0,t0.fld_empid=$fld_empid,1)
                                    # and
                                    # t00.fld_driverjob = if($vehicle = 1,59,if($vehicle = 2,68,67))
                                     and t00.fld_driverorg = 2
                                     and t00.fld_bonus = 1
                                     and t0.status = 2
                                     ");
    $driver = $driver->result();
    $order = $this->db->query("select t0.fld_btp11 'fld_empid'
                               from tbl_bth t0
                               where
                               #t0.fld_bttyid = if($vehicle = 1,69,13)
                               #and
                               t0.fld_btstat = 3
                               and
                               date_format(t0.fld_btdt,'%Y-%m-%d')= date_format('$dtsa','%Y-%m-%d')");
    $order = $order->result();
    $itpq = $this->db->query("select  t0.fld_empid  from tbl_driverabsence t0 where
                             t0.fld_driverabsencedesc = 11
                             and date_format(t0.fld_driverabsencedt,'%Y-%m-%d') =  date_format('$dtsa','%Y-%m-%d')");
   $itpq = $itpq ->result();

    foreach($driver as $rdriver) {
      $in = 0;
      $out = 0;
      $do = 0;
      $itp = 0;
      $bonus = $rdriver->bonus;
   echo "$rdriver->fld_empnm ## $rdriver->fld_empid<br>";
      if($rdriver->in > '00:00:00' && $rdriver->in < '08:30:00') {
        $in = 1;
      }
      if($rdriver->out > '00:00:00' && $rdriver->out > '17:00:00') {
        $out = 1;
      }

      ###Order Trucking
      foreach ($order as $rorder) {
        if($rorder->fld_empid == $rdriver->fld_empid) {
           $out = 1;
           $do = $do + 1;
        }
      }

      ### ITP
      foreach ($itpq as $ritpq) {
        if($ritpq->fld_empid == $rdriver->fld_empid) {
           $in = 1;
           $out = 1;
           $itp = $itp +1;
        }
      }

    if($in + $out != 2) {
        $bonus = 0;
      }
       $this->db->query("insert into tbl_bonus (fld_btflag,fld_empid,fld_bonusamt,fld_postingid,fld_in,fld_out,fld_bonusp01,fld_bonusp02) values (1,$rdriver->fld_empid,$bonus,$fld_btid,'$rdriver->in','$rdriver->out',$do,$itp)");

      }

    $url = base_url() ."index.php/page/form/78000WEEKLYBONUS/edit/$fld_btid?act=edit";
    redirect($url);
  }




  function exportBonusA($fld_btid) {
    $filename = 'Trucking-Settlement-Summarry-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    $location = $this->session->userdata('location');
    $commission_query = $this->db->query("select
                              t2.fld_btno,
                              t0.fld_empid,
                              date_format(t2.fld_btdt,'%Y-%m-%d') 'date',
                              t0.fld_empnm,
                              t0.fld_empjob,
                              format(t1.fld_bonusamt,0) 'bonus',
                              t1.fld_bonusamt,
                              if(t0.status = 1,t3.fld_empnip,t4.fld_empnrk) 'fld_empnip',
                              t5.fld_tyvalnm 'fleet_type',
                              t6.fld_tyvalnm 'location',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc in (1,2,3)
                              ) 'alpa',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc = 8

                              ) 'tlo',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc = 9
                              ) 'tao',
                               (select count(1)
                                    from tbl_bth tx0
                                    left join tbl_bth tx1 on tx1.fld_btno = tx0.fld_btnoalt
                                    left join dnxapps.tbl_route tx2 on tx2.fld_routeid = tx0.fld_btp09
                                    where
                                    date_format(tx0.fld_btdtsa,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_btp11 = t0.fld_empid
                                    and tx0.fld_bttyid = 80
                                    and tx1.fld_btstat != 5
                                    and tx0.fld_btp20 > tx2.fld_routeltime
                              ) 'pod'

                              from
                              tbl_driver t00
                              left join hris.tbl_truck_driver t0 on t0.fld_empid = t00.fld_empid
                              left join tbl_bonus t1 on t1.fld_empid=t0.fld_empid
                              left join tbl_bth t2 on t2.fld_btid = t1.fld_postingid
                              left join hris.tbl_emp t3 on t3.fld_empid = t0.fld_empid
                              left join hris.tbl_emp_osrc t4 on t4.fld_empid = t0.fld_empid
                              left join tbl_tyval t5 on t5.fld_tyvalcd=t2.fld_btp01 and t5.fld_tyid=46
                              left join tbl_tyval t6 on t6.fld_tyvalcd=t2.fld_btloc and t6.fld_tyid=21
                              where
                              t1.fld_postingid=$fld_btid
                              order by t0.fld_empnm
                              ");

    $comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
    echo " TRUCKING BONUS\n\n";
      echo "No,Nama,NIP,POD,Absen,Tolak Order,MUJ,Tidak Ambil Order, Bonus\n";
	   foreach($commission_query->result() as $commission) {
	     echo "\"$commission->count\",\"$commission->fld_empnm\",\"$commission->fld_empnip\",\"$commission->pod\",\"$commission->alpa\",\"$commission->tlo\",\"$commission->muj\",\"$commission->tao\",\" $commission->bonus \"\n";
     $total = $total + $commission->jaminan;
     $amount = $amount + $commission->insurance; }
}
   function printBonusA($fld_btid) {
   $location = $this->session->userdata('location');
   $commission_query = $this->db->query("select
                              t2.fld_btno,
                              t0.fld_empid,
                              date_format(t2.fld_btdt,'%Y-%m-%d') 'date',
                              t0.fld_empnm,
                              t0.fld_empjob,
                              format(t1.fld_bonusamt,0) 'bonus',
                              t1.fld_bonusamt,
                              if(t0.status = 1,t3.fld_empnip,t4.fld_empnrk) 'fld_empnip',
                              t5.fld_tyvalnm 'fleet_type',
                              t6.fld_tyvalnm 'location',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc in (1,2,3)
                              ) 'alpa',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc = 8

                              ) 'tlo',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc = 9
                              ) 'tao',
                               (select count(1)
                                    from tbl_bth tx0
                                    left join tbl_bth tx1 on tx1.fld_btno = tx0.fld_btnoalt
                                    left join dnxapps.tbl_route tx2 on tx2.fld_routeid = tx1.fld_btp09
                                    where
                                    date_format(tx0.fld_btdtsa,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                    and tx0.fld_btp11 = t0.fld_empid
                                    and tx0.fld_bttyid = 80
                                    and tx1.fld_btstat != 5
                                    and tx0.fld_btp20 > tx2.fld_routeltime
                              ) 'pod',
                                (select count(1)
				  from tbl_bonus_log h0
				  left join tbl_driver h1 on h1.fld_driverid = h0.fld_driverid
				  where h0.fld_btflag = 6
				  and h0.fld_postingid = '$fld_btid'
                                  and h1.fld_empid = t00.fld_empid)'empty',
                                (select count(1)
                                  from tbl_bonus_log h0
                                  left join tbl_driver h1 on h1.fld_driverid = h0.fld_driverid
                                  where h0.fld_btflag = 7
                                  and h0.fld_postingid = '$fld_btid'
                                  and h1.fld_empid = t00.fld_empid) 'lolo',
                               (select count(1)
                                from tbl_driverincident tx0
                                where
                                date_format(tx0.fld_driverincidentdt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                                and tx0.fld_empid = t0.fld_empid and tx0.fld_bt01 = 1) 'inc'


                              from
                              tbl_driver t00
                              left join hris.tbl_truck_driver t0 on t0.fld_empid = t00.fld_empid
                              left join tbl_bonus t1 on t1.fld_empid=t0.fld_empid
                              left join tbl_bth t2 on t2.fld_btid = t1.fld_postingid
                              left join hris.tbl_emp t3 on t3.fld_empid = t0.fld_empid
                              left join hris.tbl_emp_osrc t4 on t4.fld_empid = t0.fld_empid
                              left join tbl_tyval t5 on t5.fld_tyvalcd=t2.fld_btp01 and t5.fld_tyid=46
                              left join tbl_tyval t6 on t6.fld_tyvalcd=t2.fld_btloc and t6.fld_tyid=21
                              where
                              t1.fld_postingid=$fld_btid
                              order by t0.fld_empnm

                              ");

    $comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
   $this->load->library('cezpdf');
   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
   $this->cezpdf->ezSetMargins(10,5,10,5);
      $count = count($commission);
      $sum_commission = 0;
      $sum_standby = 0;
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $commission[$i]['count'] = $counteor;
        $sum_commission = $sum_commission + $commission[$i]['commission'];
        $sum_standby = $sum_standby + $commission[$i]['standby'];
        $sum_insurance = $sum_insurance + $commission[$i]['insurance'];
        $sum_jaminan = $sum_jaminan + $commission[$i]['jaminan'];
        $sum_hutang = $sum_hutang + $commission[$i]['hutang'];
        $sum_total = $sum_total + $commission[$i]['fld_bonusamt'] ;
      }
      $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
      $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS TRANSINDO'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
      $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
      $this->cezpdf->ezSetDy(-10);
      $this->cezpdf->ezText("TRAILER DRIVER BONUS SUMMARY" . "   ", 12, array('justification' => 'center'));
      $this->cezpdf->ezSetDy(-20);

      if($comm_data->fld_empjob == 59) { ### Trailer
      $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','fld_empnip'=>'NIP','pod'=>'POD','alpa'=>'Absen','tlo'=>'Tolak Order','muj'=>'MUJ','tao'=>'Tidak Ambil Order','inc'=>'Inc','empty'=>'Empty','lolo'=>'Lolo','bonus'=>'Bonus'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>570,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('counteor'=>array('width'=>10),
      'fld_empnm'=>array('width'=>130),
      'fld_empnip'=>array('width'=>50),
      'commission'=>array('width'=>50,'justification'=>'center'),
      'standby'=>array('width'=>50,'justification'=>'center'),
      'insurance'=>array('width'=>50,'justification'=>'center'),
       'jaminan'=>array('width'=>50,'justification'=>'center'),
       'hutang'=>array('width'=>50,'justification'=>'center'),
       'tao'=>array('width'=>40,'justification'=>'center'),
       'inc'=>array('width'=>40,'justification'=>'center'),
       'empty'=>array('width'=>40,'justification'=>'center'),
       'lolo'=>array('width'=>40,'justification'=>'center'),
       'total'=>array('width'=>60),
       )));

       $data_sum = array(
                         array('row1'=>'Total',
                               'row2'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>540,'fontSize'=>'8',
        'cols'=>array(
        'row1'=>array('width'=>520,'justification'=>'center'),
        'row2'=>array('width'=>51)
        )));
        $this->cezpdf->ezSetDy(-15);
        $acc = array(array('row1'=>'Created By','row2'=>'Checked By','row3'=>'Aknowledge By','row4'=>'Approved By','row5'=>'Received By'),
                     array('row1'=>''),
                     array('row1'=>''),
                     array('row1'=>''),
                     array('row1'=>'Shofa','row2'=>'Mona','row3'=>'Tonny Wijaya','row4'=>'Elly Dwiyanti','row5'=>'Fitrotun Chasanah'),
                     array('row1'=>'Staff Trucking','row2'=>'Chief Trucking ','row3'=>'Manager Trucking','row4'=>'Finance SPV','row5'=>'Chasier'),

                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>''),'',array
         ('rowGap'=>'0','xPos'=>20,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>110,'justification' => 'center'),
         'row2'=>array('width'=>110,'justification' => 'center'),
         'row3'=>array('width'=>110,'justification' => 'center'),
         'row4'=>array('width'=>110,'justification' => 'center'),
         'row5'=>array('width'=>110,'justification' => 'center'),
         )));

       }else {
          $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','job'=>'Job Role','trip_count'=>'Trip','commission'=>'Commission','insurance'=>'Insurance','total'=>'Total'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>570,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('counteor'=>array('width'=>5),
      'fld_empnm'=>array('width'=>140),
      'job'=>array('width'=>80),
      'trip'=>array('width'=>70,'justification'=>'center'),
      'commission'=>array('width'=>60,'justification'=>'right'),
       'insurance'=>array('width'=>60,'justification'=>'right'),
       'total'=>array('width'=>60,'justification'=>'right'),
       )));

       $data_sum = array(
                         array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),
                               'row4'=>number_format($sum_insurance,0,',','.'),'row5'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row4'=>'','row5'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'8',
        'cols'=>array('row1'=>array('width'=>390,'justification'=>'center'),
        'row2'=>array('width'=>60,'justification'=>'right'),
        'row4'=>array('width'=>60,'justification'=>'right'),'row5'=>array('width'=>60,'justification'=>'right'))));
       $this->cezpdf->ezSetDy(-15);
          $data_sum = array(
                         array('row1'=>'Total','row2'=>number_format($sum_commission,0,',','.'),
                               'row4'=>number_format($sum_insurance,0,',','.'),'row5'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>'','row4'=>'','row5'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'8',
        'cols'=>array('row1'=>array('width'=>390,'justification'=>'center'),
        'row2'=>array('width'=>60,'justification'=>'right'),
        'row4'=>array('width'=>60,'justification'=>'right'),'row5'=>array('width'=>60,'justification'=>'right'))));
       }
       //$this->cezpdf->ezNewPage();
       $this->cezpdf->ezSetY(795);
       header("Content-type: application/pdf");
       header("Content-Disposition: attachment; filename=bonus_summary.pdf");
       header("Pragma: no-cache");
       header("Expires: 0");
       $output = $this->cezpdf->ezOutput();
       echo $output;
   }

   function printBonusB($fld_btid) {
     $location = $this->session->userdata('location');
     $commission_query = $this->db->query("select
                              t2.fld_btno,
                              t0.fld_empid,
                              date_format(t2.fld_btdt,'%Y-%m-%d') 'date',
                              t0.fld_empnm,
                              t0.fld_empjob,
                              if(t0.status = 1,t3.fld_empnip,t4.fld_empnrk) 'fld_empnip',
                              t5.fld_tyvalnm 'fleet_type',
                              t6.fld_tyvalnm 'location',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') = date_format(t01.fld_commissiondt,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc = 3
                              ) 'alpa',
                              (select count(1)
                                    from tbl_driverabsence tx0
                                    where
                                    date_format(tx0.fld_driverabsencedt,'%Y-%m-%d') = date_format(t01.fld_commissiondt,'%Y-%m-%d')
                                    and tx0.fld_empid = t0.fld_empid
                                    and tx0.fld_driverabsencedesc = 8
                              ) 'tlo',

                               (select count(1)
                                    from tbl_bth tx0
                                    where
                                    date_format(tx0.fld_btdtsa,'%Y-%m-%d') =  date_format(t01.fld_commissiondt,'%Y-%m-%d')
                                    and tx0.fld_btp11 = t0.fld_empid
                                    and tx0.fld_bttyid = 14
                                    and tx0.fld_btstat = 3
                                    and tx0.fld_btidp = 1
                              ) 'muj',
                              t02.fld_btp20 'pod',
                              t02.fld_btnoalt 'wo_number',
                              date_format(t01.fld_commissiondt,'%Y-%m-%d') 'wo_date'
                              from
                              tbl_commission t01
                              left join tbl_bth t02 on t02.fld_btid = t01.fld_btreffid
                              left join hris.tbl_truck_driver t0 on t0.fld_empid = t01.fld_empid
                              left join dnxapps.tbl_bth t2 on t2.fld_btid = $fld_btid
                              left join hris.tbl_emp t3 on t3.fld_empid = t0.fld_empid
                              left join hris.tbl_emp_osrc t4 on t4.fld_empid = t0.fld_empid
                              left join tbl_tyval t5 on t5.fld_tyvalcd=t2.fld_btp01 and t5.fld_tyid=46
                              left join tbl_tyval t6 on t6.fld_tyvalcd=t2.fld_btloc and t6.fld_tyid=21
                              where
                              date_format(t01.fld_commissiondt,'%Y-%m-%d') between date_format(t2.fld_btdtsa,'%Y-%m-%d') and date_format(t2.fld_btdtso,'%Y-%m-%d')
                              and
                              t0.fld_empjob = 59
                              order by t0.fld_empnm,t01.fld_commissiondt
                              ");

    $comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
   $this->load->library('cezpdf');
   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
   $this->cezpdf->ezSetMargins(10,5,10,5);
      $count = count($commission);
      $sum_commission = 0;
      $sum_standby = 0;
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $commission[$i]['count'] = $counteor;
        $sum_commission = $sum_commission + $commission[$i]['commission'];
        $sum_standby = $sum_standby + $commission[$i]['standby'];
        $sum_insurance = $sum_insurance + $commission[$i]['insurance'];
        $sum_jaminan = $sum_jaminan + $commission[$i]['jaminan'];
        $sum_hutang = $sum_hutang + $commission[$i]['hutang'];
        $sum_total = $sum_total + $commission[$i]['bonus'] ;
      }
      $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
      $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS TRANSINDO'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
      $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
      $this->cezpdf->ezSetDy(-10);
      $this->cezpdf->ezText("TRAILER DRIVER BONUS DETAIL" . "   ", 12, array('justification' => 'center'));
      $this->cezpdf->ezSetDy(-20);

      if($comm_data->fld_empjob == 59) { ### Trailer
      $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','fld_empnip'=>'NIP','wo_date'=>'WO Date','wo_number'=>'WO Number','pod'=>'POD','alpa'=>'Absen','tlo'=>'Tolak Order','muj'=>'MUJ'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>540,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('counteor'=>array('width'=>10),
      'fld_empnm'=>array('width'=>140),
      'fld_empnip'=>array('width'=>50),
      'wo_date'=>array('width'=>60),
      'wo_number'=>array('width'=>60),
      'commission'=>array('width'=>60,'justification'=>'center'),
      'standby'=>array('width'=>60,'justification'=>'center'),
      'insurance'=>array('width'=>60,'justification'=>'center'),
       'jaminan'=>array('width'=>60,'justification'=>'center'),
       'hutang'=>array('width'=>60,'justification'=>'center'),
       'total'=>array('width'=>60,'justification'=>'right'),
       )));

       $this->cezpdf->ezSetDy(-15);
       }else {
          $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );
      $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','job'=>'Job Role','trip_count'=>'Trip','commission'=>'Commission','insurance'=>'Insurance','total'=>'Total'),'',
      array('rowGap'=>'4','showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>570,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('counteor'=>array('width'=>5),
      'fld_empnm'=>array('width'=>140),
      'job'=>array('width'=>80),
      'trip'=>array('width'=>70,'justification'=>'center'),
      'commission'=>array('width'=>60,'justification'=>'right'),
       'insurance'=>array('width'=>60,'justification'=>'right'),
       'total'=>array('width'=>60,'justification'=>'right'),
       )));

       $this->cezpdf->ezSetDy(-15);

       }
       $this->cezpdf->ezNewPage();
       $this->cezpdf->ezSetY(795);
       header("Content-type: application/pdf");
       header("Content-Disposition: attachment; filename=bonus_detail.pdf");
       header("Pragma: no-cache");
       header("Expires: 0");
       $output = $this->cezpdf->ezOutput();
       echo $output;
   }
  function printBonusC($fld_btid) {
    $post_data = $this->db->query("select * from tbl_bth where fld_btid= $fld_btid");
    $post_data = $post_data->row();
    $type = $post_data->fld_btflag;
    $vehicle = $post_data->fld_btp01;
    $dtsa = $post_data->fld_btdtsa;
    $dtso = $post_data->fld_btdtso;
    $location = $this->session->userdata('location');

   $commission_query = $this->db->query("select
                              t2.fld_btno,
                              t0.fld_empid,
                              date_format(t2.fld_btdt,'%Y-%m-%d') 'date',
                              t0.fld_empnm,
                              t0.fld_empjob,
                              format(t1.fld_bonusamt,0) 'bonus',
                              t1.fld_bonusamt,
                              if(t0.status = 1,t3.fld_empnip,t4.fld_empnrk) 'fld_empnip',
                              t5.fld_tyvalnm 'fleet_type',
                              t6.fld_tyvalnm 'location',
                              t1.fld_in 'in',
                              t1.fld_out 'out',
                              t1.fld_bonusp01 'order',
                              t1.fld_bonusp02 'itp'
                              from
                              hris.tbl_truck_driver t0
                              left join exim.tbl_bonus t1 on t1.fld_empid=t0.fld_empid
                              left join exim.tbl_bth t2 on t2.fld_btid = t1.fld_postingid
                              left join hris.tbl_emp t3 on t3.fld_empid = t0.fld_empid
                              left join hris.tbl_emp_osrc t4 on t4.fld_empid = t0.fld_empid
                              left join tbl_tyval t5 on t5.fld_tyvalcd=t2.fld_btp01 and t5.fld_tyid=46
                              left join tbl_tyval t6 on t6.fld_tyvalcd=t2.fld_btloc and t6.fld_tyid=21
                              where
                              t1.fld_postingid=$fld_btid
                              order by t0.fld_empnm
                              ");
$comm_data = $commission_query->row();
    $commission = $commission_query->result_array();
   $this->load->library('cezpdf');
   $this->cezpdf->Cezpdf(array(21.5,29),$orientation='Portrait');
   $this->cezpdf->ezSetMargins(10,5,10,5);
      $count = count($commission);
      $sum_commission = 0;
      $sum_standby = 0;
      for ($i=0; $i<$count; ++$i) {
        $counteor = $counteor + 1;
        $commission[$i]['count'] = $counteor;
         $sum_total = $sum_total + $commission[$i]['fld_bonusamt'] ;
      }
      $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,755,35);
      $data_kop = array(array('row1'=>'PT.DUNIA EXPRESS'),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
      $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>100,'xOrientation'=>'right','width'=>460,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>300))));
      $this->cezpdf->ezSetDy(-10);
      $this->cezpdf->ezText("TRAILER DRIVER BONUS SUMMARY" . "   ", 12, array('justification' => 'center'));
      $this->cezpdf->ezSetDy(-20);

      $data_hdr = array(
                        array('row1'=>'Posting Number','row2'=>':','row3'=>$comm_data->fld_btno),
                        array('row1'=>'Posting Date','row2'=>':','row3'=>$comm_data->date),
                        array('row1'=>'Fleet Type','row2'=>':','row3'=>$comm_data->fleet_type),
                        array('row1'=>'Location','row2'=>':','row3'=>$comm_data->location)
                        );

$this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
      array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>650,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>90),'row2'=>array('width'=>15),'row3'=>array('width'=>200))));
      $this->cezpdf->ezSetDy(-70);

      $this->cezpdf->ezTable($commission,array('count'=>'No','fld_empnm'=>'Name','fld_empnip'=>'NIP','in'=>'Absen IN','out'=>'Absen Out','order'=>'Truck Order','itp'=>'ITP','bonus'=>'Bonus'),'',
      array('rowGap'=>'4','showHeadings'=>1,'showLines'=>'1','xPos'=>20,'xOrientation'=>'right','width'=>550,'shaded'=>0,'fontSize'=>'8',
      'cols'=>array('count'=>array('width'=>30),
      'fld_empnm'=>array('width'=>170),
      'fld_empnip'=>array('width'=>50),
      'in'=>array('width'=>60,'justification'=>'center'),
      'out'=>array('width'=>60,'justification'=>'center'),
      'order'=>array('width'=>60,'justification'=>'center'),
      'itp'=>array('width'=>50,'justification'=>'center'),
       'total'=>array('width'=>70),
       )));

       $data_sum = array(
                         array('row1'=>'Total',
                               'row2'=>number_format($sum_total,0,',','.'))
                          );
       $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>''),'',
        array('rowGap'=>'4','showHeadings'=>0,'shaded'=>0,'showLines'=>1,'xPos'=>20,'xOrientation'=>'right','width'=>550,'fontSize'=>'8',
        'cols'=>array(
        'row1'=>array('width'=>480,'justification'=>'center'),
        'row2'=>array('width'=>70)
        )));
       $this->cezpdf->ezSetDy(-15);
      $this->cezpdf->ezNewPage();
       $this->cezpdf->ezSetY(795);
       header("Content-type: application/pdf");
       header("Content-Disposition: attachment; filename=bonus_summary.pdf");
       header("Pragma: no-cache");
       header("Expires: 0");
       $output = $this->cezpdf->ezOutput();
       echo $output;
   }

  function exportFakturPajak($fld_btid) {
  $filename = 'ExportFakturPajak-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("select
                              t0.*,
                              substr(replace(replace(t0.fld_taxnumberno,'-',''),'.',''),4) 'tax_number',
                              substr(t0.fld_btno,5,3) 'trans_code',
                              date_format(t1.fld_btdt,'%m') 'month',
                              if(t1.fld_btp13=5,concat(t1.fld_btno,' ','PPN DIBEBASKAN ATAS JASA'),t1.fld_btno) 'btno',
                              t1.fld_btp54 'supportdocno',
                              date_format(t1.fld_btdt,'%Y') 'year',
                              date_format(t1.fld_btdt,'%d/%m/%Y') 'date',
                              substr(ifnull(t2.fld_becredno,'000000000000000'),1,15) 'npwp',
                              concat(if(t7.fld_beprefix > 0,concat(t8.fld_tyvalnm, '. '),''), t7.fld_benm) 'company',
                              ifnull(concat(t3.fld_beaddrplc,' ',t3.fld_beaddrstr),'DKI JAKARTA RAYA ') 'address',
                              if(t1.fld_btflag=1,
                                 if(t1.fld_btp13=1,floor(1*(select sum(tz0.fld_btamt01) from tbl_btd_finance tz0 where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)),floor((select sum(tz0.fld_btamt01) from tbl_btd_finance tz0 where tz0.fld_btidp = t0.fld_btid and if(t1.fld_btp13=3,tz0.fld_btflag = 0,tz0.fld_btflag = 1)))),
                                 if(t1.fld_btp13=1,floor(1*(select sum(tz0.fld_btamt01)* t1.fld_btp03 from tbl_btd_finance tz0 where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)),floor((select sum(tz0.fld_btamt01)* t1.fld_btp03 from tbl_btd_finance tz0 where tz0.fld_btidp = t0.fld_btid and if(t1.fld_btp13=3,tz0.fld_btflag = 0,tz0.fld_btflag = 1))))) 'dpp',
                              if(t1.fld_btflag=1 and t1.fld_btp13!=3,floor(t1.fld_btuamt),if(t1.fld_btp13=3,floor(t1.fld_btamt02),floor(t1.fld_btuamt* t1.fld_btp03))) 'ppn',
                              #if(t1.fld_btflag=1,floor(0.1*(select sum(tz0.fld_btamt01) from tbl_btd_finance tz0
                              #where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1)),floor(0.1*(select sum(tz0.fld_btamt01)* t1.fld_btp03
                              #from tbl_btd_finance tz0
                              #where tz0.fld_btidp = t0.fld_btid and tz0.fld_btflag = 1))) 'ppn',
                              t1.fld_btp13,
                              if(t1.fld_btp13 = 1,'05',if(t1.fld_btp13 = 3,'07',if(t1.fld_btp13 = 5,'08','01'))) 'code'
                              from tbl_taxnumber t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btid
                              left join dnxapps.tbl_becred t2 on t2.fld_becredid = t1.fld_btp08
                              left join dnxapps.tbl_beaddr t3 on t3.fld_beaddrid = t1.fld_btp09
                              left join dnxapps.tbl_be t7 on t7.fld_beid = t1.fld_baidc
                              left join dnxapps.tbl_tyval t8 on t8.fld_tyvalcd = t7.fld_beprefix and t8.fld_tyid = 173
                              where
                              t0.fld_btid=$fld_btid
                                ");
    echo 'FK;KD_JENIS_TRANSAKSI;FG_PENGGANTI;NOMOR_FAKTUR;MASA_PAJAK;TAHUN_PAJAK;TANGGAL_FAKTUR;NPWP;NAMA;ALAMAT_LENGKAP;JUMLAH_DPP;JUMLAH_PPN;JUMLAH_PPNBM;ID_KETERANGAN_TAMBAHAN;FG_UANG_MUKA;UANG_MUKA_DPP;UANG_MUKA_PPN;UANG_MUKA_PPNBM;REFERENSI;NOMOR_DOKUMEN_PENDUKUNG' . "\n";
    echo 'LT;NPWP;NAMA;JALAN;BLOK;NOMOR;RT;RW;KECAMATAN;KELURAHAN;KABUPATEN;PROPINSI;KODE_POS;NOMOR_TELEPON;;;;;;' . "\n";
    echo 'OF;KODE_OBJEK;NAMA;HARGA_SATUAN;JUMLAH_BARANG;HARGA_TOTAL;DISKON;DPP;PPN;TARIF_PPNBM;PPNBM;;;;;;;;;' . "\n";
    foreach($data->result() as $rdata) {
      $code = $rdata->code;
      if($rdata->btp13 == 1) {
        $rdata->dpp = $rdata->dpp * 0.1;
      }

      if(strlen($rdata->address) > 3) {
        $rdata->address =  $rdata->address;
      } else {
        $rdata->address = "DKI JAKARTA RAYA";
      }


      echo "FK;$code;0;$rdata->tax_number;$rdata->month;$rdata->year;$rdata->date;$rdata->npwp;$rdata->company;$rdata->address;$rdata->dpp;$rdata->ppn;0;0;0;0;0;0;$rdata->btno;$rdata->supportdocno\n";
      echo "FAPR;PT. DUNIA EXPRESS;JL. AGUNG KARYA VII NO. 1 JAKARTA UTARA;;;;;;;;;;;;;;;;;;\n";
    #  echo "\"LT\";\"$rdata->npwp\";\"$rdata->company\";\"$rdata->address\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\";\"\"\n";
      ###Print Detail
      ###From dnxapps
      $item = array();

      $item = $this->db->query("select
                                t0.fld_btid ,
                                t0.fld_btdesc 'desc',
                                if(t1.fld_btflag=1,if(date_format(t1.fld_btdt, '%Y-%m-%d')>='2022-04-01',if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.011),floor(t0.fld_btamt01 * 0.11)),if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.01),floor(t0.fld_btamt01 * 0.1))),
                                  if(date_format(t1.fld_btdt, '%Y-%m-%d')>='2022-04-01',if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.011* t1.fld_btp03),floor(t0.fld_btamt01 * 0.11 * t1.fld_btp03)),if(t1.fld_btp13 = 1,floor(t0.fld_btamt01 * 0.01* t1.fld_btp03),floor(t0.fld_btamt01 * 0.1 * t1.fld_btp03)))) 'ppn',
                                if('$currency' = 'IDR','Rp.','$') 'curr_code',
                                if(t1.fld_btflag=1,floor(t0.fld_btamt01),
                                floor(t0.fld_btamt01*t1.fld_btp03)) 'total_price',
                                if(t1.fld_btflag=1,if(t1.fld_btp13=1,floor(t0.fld_btamt01*1),floor(t0.fld_btamt01)),
                                if(t1.fld_btp13=1,floor(t0.fld_btamt01*t1.fld_btp03*0.1),floor(t0.fld_btamt01*t1.fld_btp03))) 'fld_btamt01',
                                if(t1.fld_btflag=1,floor(t0.fld_btuamt01),floor(t0.fld_btuamt01*t1.fld_btp03)) 'fld_btuamt01',
                                t0.fld_btqty01
                                from tbl_btd_finance t0
                                left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                where
                                t0.fld_btidp='$rdata->fld_btid'
                                and if(t1.fld_btp13=3,t0.fld_btflag=0,t0.fld_btflag=1)
                               ");
      $item = $item->result();
      $tot_ppn = 0;
      foreach($item as $ritem){
        $tot_ppn =  $tot_ppn + $ritem->ppn;
      }
      foreach($item as $ritem){
        if( $tot_ppn != $rdata->ppn) {
          $ritem->ppn = floor($ritem->ppn);
        } else {
           $ritem->ppn =  $ritem->ppn;
        }
        echo "OF;;$ritem->desc;$ritem->fld_btuamt01;$ritem->fld_btqty01;$ritem->total_price;0;$ritem->fld_btamt01;$ritem->ppn;0;0;0;0;0;0;0;0;0;0;0;0\n";
      }


   }
  }




  function printBonusA1 ($fld_btid) {
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=BonusSummary.xls");
      header("Pragma: no-cache");
      header("Expires: 0");

    $post_data = $this->db->query("select * from tbl_bth where fld_btid= $fld_btid");
    $post_data = $post_data->row();
    $absence = $this->db->query("select t0.fld_empid,t0.fld_driverabsencedesc,count(t0.fld_driverabsenceid) 'cnt'
                                    from tbl_driverabsence t0
                                    left join hris.tbl_truck_driver t1 on t1.fld_empid = t0.fld_empid
                                    where
                                    date_format(t0.fld_driverabsencedt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                    and t1.fld_empjob = 59
                                    group by t0.fld_empid,t0.fld_driverabsencedesc

                                   ");
    $absence = $absence->result();


    $data = $this->db->query("select
                              t2.fld_btno,
                              t0.fld_empid,
                              date_format(t2.fld_btdt,'%Y-%m-%d') 'date',
                              t0.fld_empnm 'driver',
                              t1.fld_bonusamt 'total'
                              from
                              hris.tbl_truck_driver t0
                              left join dnxapps.tbl_bonus t1 on t1.fld_empid=t0.fld_empid
                              left join dnxapps.tbl_bth t2 on t2.fld_btid = t1.fld_postingid
                              where
                              t1.fld_postingid=$fld_btid
                              and
                              t0.fld_empjob = 59
                                ");
     echo "<table border=1>
             <tr>
               <td>Nomor Posting</td>
               <td>Tanggal Posting</td>
               <td>Nomor Sopir</td>
               <td>POD</td>
               <td>ABS</td>
               <td>TLO</td>
               <td>MUJ</td>
               <td>Total</td>
             </tr>

          ";

   foreach($data->result() as $rdata) {
     $pod = 0;
     $abs = 0;
     $tlo = 0;
     $muj = 0;

     foreach($absence as $rabsence) {
       if($rabsence->fld_empid == $rdata->fld_empid) {
         if($rabsence->fld_driverabsencedesc == 8) {
           $tlo = $rabsence->cnt;
         }
         if($rabsence->fld_driverabsencedesc == 3) {
           $abs = $rabsence->cnt;
         }
       }


     }
     $no =$no + 1;
     echo "<tr>";
     echo "<td>$rdata->fld_btno</td>";
     echo "<td>$rdata->date</td>";
     echo "<td>$rdata->driver</td>";
     echo "<td>$pod</td>";
     echo "<td>$abs</td>";
     echo "<td>$tlo</td>";
     echo "<td>$muj</td>";
     echo "<td>$rdata->total</td>";
     echo "</tr>";
   }
     echo "</table>";
  }

  function printBonusB1 ($fld_btid) {
    #  header("Content-type: application/octet-stream");
    #  header("Content-Disposition: attachment; filename=BonusDetail.xls");
    #  header("Pragma: no-cache");
    #  header("Expires: 0");

    $post_data = $this->db->query("select * from tbl_bth where fld_btid= $fld_btid");
    $post_data = $post_data->row();
    $absence = $this->db->query("select t0.fld_empid,t0.fld_driverabsencedesc,count(t0.fld_driverabsenceid) 'cnt',t0.fld_driverabsencedt 'date'
                                    from tbl_driverabsence t0
                                    left join hris.tbl_truck_driver t1 on t1.fld_empid = t0.fld_empid
                                    where
                                    date_format(t0.fld_driverabsencedt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                                    and t1.fld_empjob = 59
                                    group by t0.fld_empid,t0.fld_driverabsencedesc,t0.fld_driverabsencedt

                                   ");
    $absence = $absence->result();


    $data = $this->db->query("select
                              concat('$post_data->fld_btno') 'fld_btno',
                              t0.fld_empid,
                              date_format('$post_data->fld_btdt','%Y-%m-%d') 'date',
                              t0.fld_route,
                              t0.fld_empnm,
                              date_format(t0.fld_commissiondt,'%Y-%m-%d') 'wo_date'
                              from tbl_commission t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid
                              where
                              date_format(t0.fld_commissiondt,'%Y-%m-%d') between date_format('$post_data->fld_btdtsa','%Y-%m-%d') and date_format('$post_data->fld_btdtso','%Y-%m-%d')
                              and t0.fld_empjob = 59
                              order by t0.fld_empnm,t0.fld_commissiondt
                                ");
     echo "<table border=1>
             <tr>
               <td>Nomor Posting</td>
               <td>Tanggal Posting</td>
               <td>Nomor Sopir</td>
               <td>Tanggal Kegiatan </td>
               <td>Rute</td>
               <td>POD</td>
               <td>ABS</td>
               <td>TLO</td>
               <td>MUJ</td>
             </tr>

          ";

   foreach($data->result() as $rdata) {
     $pod = 0;
     $abs = 0;
     $tlo = 0;
     $muj = 0;

     foreach($absence as $rabsence) {

       if($rabsence->fld_empid == $rdata->fld_empid && $rabsence->date == $rdata->wo_date) {
         if($rabsence->fld_driverabsencedesc == 8) {
           $tlo = $rabsence->cnt;
         }
         if($rabsence->fld_driverabsencedesc == 3) {
           $abs = $rabsence->cnt;
         }
       }


     }
     $no =$no + 1;
     echo "<tr>";
     echo "<td>$rdata->fld_btno</td>";
     echo "<td>$rdata->date</td>";
     echo "<td>$rdata->fld_empnm</td>";
     echo "<td>$rdata->wo_date</td>";
     echo "<td>$rdata->fld_route</td>";
     echo "<td>$pod</td>";
     echo "<td>$abs</td>";
     echo "<td>$tlo</td>";
     echo "<td>$muj</td>";
     echo "</tr>";
   }
     echo "</table>";
  }

/*
  function setApprovalAction1 ($btid,$bttyid,$mode) {
    if($bttyid == 45) {
      if($mode == 'aprv' || $mode == 'req') {
        $this->db->query("update tbl_bth t0
                              left join tbl_btd_finance t1 on t0.fld_btid=t1.fld_btreffid
                              left join tbl_bth t2 on t2.fld_btid = t1.fld_btidp
                              set t0.fld_btp28 = t0.fld_btp28 + t1.fld_btamt01,
                              t0.fld_btp29 = t2.fld_btno
                              where t1.fld_btidp = $btid and t0.fld_bttyid in (41,82) ");
      }
      if($mode == 'rev') {
        $this->db->query("update tbl_bth t0
                              left join tbl_btd_finance t1 on t0.fld_btid=t1.fld_btreffid
                              set t0.fld_btp28 = t0.fld_btp28 - t1.fld_btamt01,
                              t0.fld_btp29 = ''
                              where t1.fld_btidp = $btid and t0.fld_bttyid in (41,82)");
      }
    }
  }
*/

 function setApprovalAction ($btid,$bttyid,$mode) {
    if($bttyid == 45) {
      if($mode == 'aprv' || $mode == 'req') {

		$data = $this->db->query("select t1.fld_btamt01,t2.fld_btno,t1.fld_btreffid from tbl_bth t0
				          left join tbl_btd_finance t1 on t0.fld_btid=t1.fld_btreffid
                                          left join tbl_bth t2 on t2.fld_btid = t1.fld_btidp
                                          where t1.fld_btidp = $btid and t0.fld_bttyid in (41,82)");
		$data = $data->result();

        foreach ($data as $rdata) {
          $this->db->query("update tbl_bth set fld_btp28 = fld_btp28 + $rdata->fld_btamt01,
                            fld_btp29 = '$rdata->fld_btno' where fld_btid=$rdata->fld_btreffid limit 1 ");
        }

      }
      if($mode == 'rev') {
        $data1 = $this->db->query("select t1.fld_btamt01,t2.fld_btno,t1.fld_btreffid from tbl_bth t0
                                   left join tbl_btd_finance t1 on t0.fld_btid=t1.fld_btreffid
				   left join tbl_bth t2 on t2.fld_btid = t1.fld_btidp
				   where t1.fld_btidp = $btid and t0.fld_bttyid in (41,82)");
		$data1 = $data1->result();

        foreach ($data1 as $rdata1) {
//          $this->db->query("update tbl_bth set fld_btp28 = fld_btp28 - $rdata1->fld_btamt01,
  //                          fld_btp29 = '' where fld_btid=$rdata1->fld_btreffid limit 1 ");

           $this->db->query("update tbl_bth set fld_btp28 = 0,
                            fld_btp29 = '' where fld_btid=$rdata1->fld_btreffid limit 1 ");

        }
      }
    }
  }

  function insertJournalLog ($fld_btid) {
    $fld_time = date('Y-m-d H:i');
    $fld_baidp = $this->session->userdata('ctid');
    $this->db->query("insert into tbl_journal_log (fld_btid,fld_time,fld_baidp) values ('$fld_btid','$fld_time','$fld_baidp')");

  }


  function exportReturnDoc ($fld_btid) {
      $filename = 'return_document_'.date('Ymd') . '.xls';
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=$filename");
      header("Pragma: no-cache");
      header("Expires: 0");

      $data = $this->db->query("select
          t2.fld_btnoalt 'DONumber',
          t3.fld_btno 'PDSNumber',
          t0.fld_customer 'Customer',
          t6.fld_tyvalnm 'VehicleType',
          t7.fld_tyvalnm 'VehicleTypeSubtitute',
          if(t2.fld_bttyid = 70,(select tr1.fld_tyvalnm from tbl_btd tr0
          left join tbl_tyval tr1 on tr1.fld_tyvalcd = tr0.fld_btp03 and tr1.fld_tyid =28
          where
          tr0.fld_btidp = t2.fld_btid limit 1)
          ,t6.fld_tyvalnm) 'Size',
          t1.fld_empnm 'PICBilling',
          t8.fld_empnm 'PICInput',
          t00.fld_btdtsa01 'ReturnDate',
          t00.fld_btdtso01 'ReceiptDate',
          t00.fld_btdesc 'Remark',
          t9.fld_btno 'TransNumber'

          from
          tbl_btd_do t00
          left join tbl_trk_settlement t0 on t0.fld_trk_settlementid=t00.fld_btiid
          left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_bill_pic
          left join tbl_bth t2 on t2.fld_btid = t0.fld_btreffid
          left join tbl_bth t3 on t3.fld_btid =t0.fld_btidp and t3.fld_bttyid=71
          left join tbl_tyval t6 on t6.fld_tyvalcd=t2.fld_btflag and t6.fld_tyid=19
          left join tbl_tyval t7 on t7.fld_tyvalcd=t2.fld_btp35 and t7.fld_tyid=19
          left join hris.tbl_emp t8 on t8.fld_empid=t0.fld_bill_pic2
          left join tbl_bth t9 on t9.fld_btid=t00.fld_btidp and t9.fld_bttyid=160
          where
          t00.fld_btidp=$fld_btid
      ");
      echo "<table width=100%>
              <tr>
                  <td nowrap>No</td>
                  <td nowrap>Transaction Number</td>
                  <td nowrap>DO Number</td>
                  <td nowrap>PDS Number</td>
                  <td nowrap>Customer</td>
                  <td nowrap>Vehicle Type</td>
                  <td nowrap>Vehicle Type Subtitute</td>
                  <td nowrap>Size</td>
                  <td nowrap>PIC Billing</td>
                  <td nowrap>PIC Input</td>
                  <td nowrap>Return Date</td>
                  <td nowrap>Receipt Date</td>
                  <td nowrap>Remark</td>
              </tr>";
      $no = 0;
      $total = 0;
      foreach($data->result() as $rdata) {
          $no = $no + 1;
          echo "<tr>";
          echo "<td nowrap>" . $no . "</td>";
          echo "<td>" .  $rdata->TransNumber . "</td>";
          echo "<td>" .  $rdata->DONumber . "</td>";
          echo "<td>" .  $rdata->PDSNumber . "</td>";
          echo "<td>" .  $rdata->Customer . "</td>";
          echo "<td>" .  $rdata->VehicleType . "</td>";
          echo "<td>" .  $rdata->VehicleTypeSubtitute . "</td>";
          echo "<td>" .  $rdata->Size . "</td>";
          echo "<td>" .  $rdata->PICBilling . "</td>";
          echo "<td>" .  $rdata->PICInput . "</td>";
          echo "<td>" .  $rdata->ReturnDate . "</td>";
          echo "<td>" .  $rdata->ReceiptDate . "</td>";
          echo "<td>" .  $rdata->Remark . "</td>";
          echo "</tr>";
      }
      echo "</table>";
  }

//TambahanJuli
  function exportDeduction($fld_btid) {
    $filename = 'Deduction-'.date('Ymd') . '.xls';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->db->query("SELECT
        t4.fld_btno 'TransactionNumber',
        t1.fld_empnm 'Driver',
        t0.fld_btamt01 'Amount',
        t0.fld_btdesc 'Description',
        date_format(t0.fld_btdt,'%Y-%m-%d') 'Date'
        FROM tbl_btd_driver_insurance t0
        left join hris.tbl_truck_driver t1 ON  t0.fld_empid = t1.fld_empid
        left join tbl_bth t4 on t0.fld_btidp =t4.fld_btid

        WHERE
        t0.fld_btidp = $fld_btid
                order by t0.fld_btid ASC");
    echo "<table border = 1>
           <tr>
             <td>Transaction Number</td>
             <td>Driver</td>
             <td>Amount</td>
             <td>Description</td>
             <td>Date</td>
           </tr>";
   foreach($data->result() as $rdata) {
   echo "<tr>
            <td>" . $rdata->TransactionNumber . "</td>
             <td>" . $rdata->Driver . "</td>
             <td>" . $rdata->Amount . "</td>
             <td>" . $rdata->Description . "</td>
             <td>" . $rdata->Date . "</td>
  </tr>";
   }

   echo "</table>";
  }


  function get_loc_reff($fld_btid,$activity,$divisi){
	 	#$fld_btid = 3867681;
		$data = $this->db->query("select t0.fld_btid,
								t0.fld_btp21 'depo',
								t0.fld_btp51 'loading',
								t0.fld_btp52 'unloading',
								t0.fld_bttyid ,
								t0.fld_btiid 'division',
								t0.fld_btp05 'activity',
								t1.fld_tyvalcfg 'group_veh'
								from tbl_bth t0
								left join tbl_tyval t1 on t1.fld_tyvalcd = t0.fld_btflag and t1.fld_tyid = 19
								where
							    t0.fld_btid = $fld_btid
								limit 1")->row();
	    if($data->fld_bttyid == 77){
		$depo = $data->depo;
		$loading = $data->loading;
		$unloading = $data->unloading;
		$do_type = 0;
		$division = $data->division;
		$activity = $data->activity;
			#if($data->fld_bttyid == 69){
			if($division == 13 and $activity == 1){
			$do_type = 1;
			} else if($division == 14 and $activity == 2){
			$do_type = 2;
			} else if($division == 11 and $activity == 3){
			$do_type = 3;
			} else if($division == 11 and $activity == 1){
			$do_type = 4;
			} else if($division == 11 and $activity == 2){
			$do_type = 5;
			} else {
			$do_type = 0;
			}

		#}

	   $loc_gps = $this->db->query("select * from (
	    # query poi depo
	    select
		t0.fld_poiid 'id',
		t0.fld_poinm 'name',
		t0.fld_gpslat 'lat',
		t0.fld_gpslong 'long',
		if('$division' = 13 and '$activity' = 1 ,1,
		if('$division' = 14 and '$activity' = 2 ,3,
		if('$division' = 11 and '$activity' = 1 ,1,
		if('$division' = 11 and '$activity' = 2 ,3,
		if('$division' = 11 and '$activity' = 3 ,3,
		0
		))))) 'seq'
		from
		dnxapps.tbl_poi t0
		where
		t0.fld_poitype in (1,2,3)
		and
		t0.fld_poiid = '$depo'
        union
		# query poi loading
	    select
		t0.fld_poiid 'id',
		concat(t0.fld_poinm, ' [', t1.fld_tyvalnm, ']') 'name',
		t0.fld_gpslat 'lat',
		t0.fld_gpslong 'long',
		if('$division' = 13 and '$activity' = 1 ,2,
		if('$division' = 14 and '$activity' = 2 ,1,
		if('$division' = 11 and '$activity' = 1 ,2,
		if('$division' = 11 and '$activity' = 2 ,1,
		if('$division' = 11 and '$activity' = 3 ,1,
		0
		))))) 'seq'
		from dnxapps.tbl_poi t0
		left join dnxapps.tbl_tyval t1 on t1.fld_tyvalcd = t0.fld_poitype and t1.fld_tyid = 220
		where
		t0.fld_poitype in (1,3,4)
		and
		t0.fld_poiid = '$loading'
		union
		# query poi unloading
		select
		t0.fld_poiid 'id',
		concat(t0.fld_poinm, ' [', t1.fld_tyvalnm, ']') 'name',
		t0.fld_gpslat 'lat',
		t0.fld_gpslong 'long',
		if('$division' = 13 and '$activity' = 1 ,3,
		if('$division' = 14 and '$activity' = 2 ,2,
		if('$division' = 11 and '$activity' = 1 ,3,
		if('$division' = 11 and '$activity' = 2 ,2,
		if('$division' = 11 and '$activity' = 3 ,2,
		0
		))))) 'seq'
		from dnxapps.tbl_poi t0
		left join dnxapps.tbl_tyval t1 on t1.fld_tyvalcd = t0.fld_poitype and t1.fld_tyid = 220
		where
		t0.fld_poitype in (1,3,4)
		and
		t0.fld_poiid = '$unloading'

	   ) as res limit 10
	   ");
     foreach ($loc_gps->result() as $rloc_gps){
		 if($rloc_gps->seq > 0 && $rloc_gps->lat != '' && $rloc_gps->long != ''){
		 $loc_seq = $rloc_gps->seq;
		 $loc_lat = $rloc_gps->lat;
		 $loc_long = $rloc_gps->long;
                 $poiid = $rloc_gps->id;
                # echo "name=$rloc_gps->name,lat =$rloc_gps->lat,seq =$do_type,seq=$loc_seq<br>";
		 $this->db->query("insert ignore into tbl_loc_reff (fld_idp,fld_flag,fld_locseq,fld_locstat,fld_gpslat,fld_gpslong,fld_poiid) values($fld_btid,$do_type,$loc_seq,0,'$loc_lat','$loc_long','$poiid')");
		 }
	 }
        #exit();
	}


}


  function exportInvToExcel($fld_btid) {
    $filename = 'Export-Invoice-'.$fld_btid.'-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $baseDatas = $this->db->query("SELECT
        t0.fld_btid 'invdelID',
        t1.fld_btid 'invID',
        t1.fld_bttyid 'invTyid'

        FROM tbl_btd_invdel t0
        LEFT JOIN tbl_bth t1 ON t1.fld_btid = t0.fld_btiid

        WHERE
        t0.fld_btflag = 118
        AND t0.fld_btidp = '$fld_btid'

        ORDER BY t0.fld_btid ASC
    ");

    echo "Export Invoice ".$fld_btid."\n\n";
    $no =0;
    foreach($baseDatas->result() as $baseData) {
        $no =$no + 1;
        if($baseData->invTyid == 41 || $baseData->invTyid == 82){
            $invData = $this->db->query("SELECT
                t0.fld_btid 'crud',
                replace(replace(t0.fld_btno,'/DSV',''),'/INV','') 'invNumber',
                date_format(t0.fld_btdt,'%Y-%m-%d') 'btdt',
                t0.fld_btdesc 'desc',
                t0.fld_btbalance 'totInv',
                concat(if(t1.fld_beprefix > 0,concat(t2.fld_tyvalnm,'. '),''),t1.fld_benm)'customer',
                t0.fld_btp04 'pebPibNumber',
                t0.fld_btnoalt 'blNumber',
                t0.fld_btp18 'doNumber',
                t0.fld_btnoreff 'jobNumber'

                from
                tbl_bth t0
                left join dnxapps.tbl_be t1 on t1.fld_beid = t0.fld_baidc and t1.fld_betyid=5 and t1.fld_bestat = 1
                left join dnxapps.tbl_tyval t2 on t2.fld_tyvalcd = t1.fld_beprefix and t2.fld_tyid = 173

                where
                t0.fld_bttyid in (41,82)
                and t0.fld_btid = '$baseData->invID'
                limit 1
            ")->row();

            echo "\"H$no\",\"$invData->crud\",\"$invData->customer\",\"$invData->invNumber\",\"$invData->pebPibNumber\",\"$invData->blNumber\",\"$invData->doNumber\",\"$invData->jobNumber\",\"$invData->btdt\",\"$invData->desc\",\"$invData->totInv\",\n";

            $invDetailDatas = $this->db->query("SELECT
                t0.fld_btidp 'crud',
                t0.fld_btdesc 'desc',
                t0.fld_btqty01 'qty',
                t0.fld_btuamt01 'unitAmount',
                t0.fld_btamt01 'totAmount'

                from
                tbl_btd_finance t0
                left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp

                where
                t1.fld_bttyid in (41,82)
                and t1.fld_btid = '$invData->crud'
            ");

            if ($invDetailDatas->num_rows() > 0) {
                foreach($invDetailDatas->result() as $invDetailData) {
                    echo "\"D$no\",\"$invDetailData->crud\",\"$invDetailData->desc\",\"$invDetailData->qty\",\"$invDetailData->unitAmount\",\"$invDetailData->totAmount\",\n";
                }
            }
        }

        if($baseData->invTyid == 51){
            $invData = $this->db->query("SELECT
                t0.fld_btid 'crud',
                t0.fld_btno 'btno',
                date_format(t0.fld_btdt,'%Y-%m-%d') 'btdt',
                t0.fld_btdesc 'desc',
                t0.fld_btuamt 'cashAdvance'

                from
                tbl_bth t0
                where
                t0.fld_bttyid in (51)
                and t0.fld_btid = '$baseData->invID'
                limit 1
            ")->row();

            echo "\"H$no\",\"$invData->crud\",\"$invData->btno\",\"$invData->btdt\",\"$invData->desc\",\"$invData->cashAdvance\",\n";

            $invDetailDatas = $this->db->query("SELECT
                t0.fld_btidp 'crud',
                t0.fld_btdesc 'desc',
                t0.fld_btamt01 'amount',
                t0.fld_btnoreff 'reffNum',
                t0.fld_btdocreff 'reffDoc'

                from
                tbl_btd_finance t0
                left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp

                where
                t1.fld_bttyid in (51)
                and t1.fld_btid = '$invData->crud'
            ");

            if ($invDetailDatas->num_rows() > 0) {
                foreach($invDetailDatas->result() as $invDetailData) {
                    echo "\"D$no\",\"$invDetailData->crud\",\"$invDetailData->desc\",\"$invDetailData->amount\",\"$invDetailData->reffNum\",\"$invDetailData->reffDoc\",\n";
                }
            }
        }

        if($baseData->invTyid == 46){
            $invData = $this->db->query("SELECT
                t0.fld_btid 'crud',
                t0.fld_btno 'btno',
                date_format(t0.fld_btdt,'%Y-%m-%d') 'btdt',
                t0.fld_btdesc 'desc',
                t0.fld_btamt 'totInv'

                from
                tbl_bth t0
                where
                t0.fld_bttyid in (46)
                and t0.fld_btid = '$baseData->invID'
                limit 1
            ")->row();

            echo "\"H$no\",\"$invData->crud\",\"$invData->btno\",\"$invData->btdt\",\"$invData->desc\",\"$invData->totInv\",\n";

            $invDetailDatas = $this->db->query("SELECT
                t0.fld_btidp 'crud',
                t0.fld_btdesc 'desc',
                t0.fld_btamt01 'amount'

                from
                tbl_btd_finance t0
                left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp

                where
                t1.fld_bttyid in (46)
                and t1.fld_btid = '$invData->crud'
            ");

            if ($invDetailDatas->num_rows() > 0) {
                foreach($invDetailDatas->result() as $invDetailData) {
                    echo "\"D$no\",\"$invDetailData->crud\",\"$invDetailData->desc\",\"$invDetailData->amount\",\n";
                }
            }
        }

    }

  }

  function CreateJSTfromJOCDE($fld_btid,$status){
    $user_group=$this->session->userdata('group');
    $location = $this->session->userdata('location');
    $ctid = $this->session->userdata('ctid');

    #check approval
    if ($status != 6){
      $this->ffis->message("Can't create Settlement! JOC Transaction must be Approved.");
    }
    #check double transaction
    $cek = $this->db->query("select * from tbl_btr t0 where t0.fld_btrsrc = $fld_btid");
    if ($cek->num_rows() > 0) {
        $this->ffis->message("Can't create Settlement! JST Transaction was made before from this transaction.");
    }
    #check user
    $cek2 = $this->db->query("select t0.fld_baidp 'krani' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $cek2 = $cek2->row();

    #if ($cek2->krani != $ctid) {
    #    $this->ffis->message("Can't create Settlement! You can only create settlement with same user in JOC.");
    #}

    #check detail lolo for depot advance cash
    $cek5 = $this->db->query("select t0.fld_btp37 'depotflag' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $cek5 = $cek5->row();

    $cek6  = $this->db->query("select t1.fld_costtype 'lifton', t1.fld_btp02'depo' from tbl_bth t0
                              left join tbl_btd_cost t1 on t1.fld_btidp = t0.fld_btid
                              where t0.fld_btid = $fld_btid limit 1");
    $cek6 = $cek6->row();

    $payment = $this->db->query("select t0.fld_btp18 'paytype' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $payment = $payment->row();
    // echo $cek5->depotflag ." - ". $payment->paytype ." - ". $cek6->lifton ." - ". is_null($cek6->depo);
    if ($payment->paytype != 2 && $cek6->lifton != 5453 ) {
        $this->ffis->message("Can't create Settlement! Payment Type must be Cash and Cost type must be Lift Off!!");
    }

    #if($cek5->depotflag != 1 || $cek6->depo == ''){
    #  $this->ffis->message("Can't create Settlement! Depot DET must be checked and Depot must be filled !!");
    #}

    #check payment type
    $cek3 = $this->db->query("select t0.fld_btp18 'paytype' from tbl_bth t0 where t0.fld_btid = $fld_btid");
    $cek3 = $cek3->row();

    if ($cek3->paytype < 2 && $cek3->paytype >= 5) {
        $this->ffis->message("Can't create Settlement! JOC payment type must be transfer or EDC.");
    }

    if ($cek3->paytype != 2 && $cek5->depotflag == 1){
        $this->ffis->message("Can't create Settlement! JOC payment type must be Cash for Depot Advance.");
    }

    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='4' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    $query=$query->row();
    $get_seq_number = (substr($query->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/JST/" . $date_trans . "/" . $seq_number;

    $this->db->query("insert into tbl_bth (fld_baido,fld_btstat,fld_btdt,fld_baidp,fld_bttyid,fld_btno,fld_btloc) value (2,1,now(),$ctid,4,'$fld_btno','$location')");
    $last_insert_id = $this->db->insert_id();

    $insert_detail = $this->db->query("insert into tbl_btd_cost
                                (fld_btidp,fld_bt01,fld_costtype,fld_btp01,fld_currency,fld_btqty01,fld_btuamt01,fld_btamt01)
                                select '$last_insert_id',t0.fld_bt01,t0.fld_costtype,t0.fld_btp01,t0.fld_currency,t0.fld_btqty01,
                                t0.fld_btuamt01,t0.fld_btamt01
                                from tbl_btd_cost t0
                                where t0.fld_btidp = $fld_btid ");

    $get_joc = $this->db->query("select tx0.fld_baidv 'division', tx0.fld_btamt 'req_amount', tx0.fld_btamt01 'payment',
                                  tx0.fld_btp11 'ops_staff',tx0.fld_btp23 'rema_flag',tx0.fld_btp18 'paytype', tx0.fld_btp45 'terminal'
                                  from tbl_bth tx0 where tx0.fld_btid =$fld_btid");

      $query1=$get_joc->row();
      $update_jst = $this->db->query("update tbl_bth t0
                                      set t0.fld_baidv='$query1->division',t0.fld_btp12='$query1->req_amount',
                                      t0.fld_btamt01='$query1->payment',t0.fld_btp11 ='$query1->ops_staff',
                                      t0.fld_btp23='$query1->rema_flag',t0.fld_btp18 = '$query1->paytype',t0.fld_btp45 = '$query1->terminal'
                                      where t0.fld_btid=$last_insert_id limit 1");

    // update joc to approve
      $update_joc = $this->db->query("update tbl_bth t0 set t0.fld_btstat = 3,t0.fld_btdtsa = now() where t0.fld_btid = $fld_btid limit 1");

      # Insert tbl_btr
    $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($fld_btid,$last_insert_id,4)");

    $url = base_url() . "index.php/page/form/78000JO_SETTLEMENT/edit/$last_insert_id?act=edit";
    redirect($url);
  }


}
