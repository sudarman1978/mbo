<?php
class Payroll_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function salaryPosting($fld_btid,$fld_baido,$fld_btdt,$fld_btdtsap,$fld_btdtsop) {
    ### Get total holiday in salary periode
    $gholiday = $this->db->query("select fld_holidaydt from tbl_holiday where fld_holidaydt >= '$fld_btdtsap' and fld_holidaydt <=  '$fld_btdtsop' and fld_baido = $fld_baido"); 
    $gholiday = $gholiday->result();
    $holidayList = array();
    foreach ($gholiday as $rholiday) {
      $holidayList [] = $rholiday->fld_holidaydt;
    }
    ####

    $days_in_month = date('t');
    $firstday = date('Y-m') . '-' . '1' ;
    $lastday = date('Y-m') . '-' . $days_in_month ;
    $fld_btdtsa = strtotime($fld_btdtsap);
    $fld_btdtso = strtotime($fld_btdtsop);
    $firstday = strtotime($firstday);
    $lastday = strtotime($lastday);
    $days = abs(($lastday -  $firstday) / 86400) + 1;

  echo "Start to counting .....  <br>";
    $gemp = $this->db->query("
    select 
	t0.fld_empid,
	t0.fld_empfpid,
	t0.fld_empnm,
	t0.fld_emplevel,
	t0.fld_empworkhour,
    t0.fld_empjoindt,
    if(date_format(t0.fld_empjoindt,'%Y-%m-%d') < date_format(now(),'%Y-02-01'),12,period_diff(date_format(now(),'%Y12'),date_format(t0.fld_empjoindt,'%Y%m') -1)) 'Month_active',
	ifnull(t0.fld_empbasepay,0) 'BasePay',
	if(t0.fld_empmealpay=1,ifnull(t1.fld_btival,0),0) 'Meal',
	if(t0.fld_emptranspay=1,ifnull(t2.fld_btival,0),0) 'Transport',
	if(t0.fld_empfuncpay=1,ifnull(t3.fld_btival,0),0) 'Functional',
    if(t0.fld_empcoop=1,ifnull(t5.fld_btival,0),0) 'Koperasi',
    if(t0.fld_empastek=1,ifnull(t6.fld_btival,0),0) 'Astek1',
    if(t0.fld_empastek=1,ifnull(t7.fld_btival,0),0) 'Astek2',
    ifnull(t8.fld_btival,0) 'M_bjbtn',
    ifnull(t9.fld_btival,0) 'P_bjbtn',
    ifnull(t10.fld_btival,0) 'PTKP'
	from tbl_emp t0 
	left join tbl_bti t1 on t1.fld_btiid=1
	left join tbl_bti t2 on t2.fld_btiflag = t0.fld_emplevel and t2.fld_bticid=2
	left join tbl_bti t3 on t3.fld_btiflag = t0.fld_emplevel and t3.fld_bticid=3
    left join tbl_bti t5 on t5.fld_btiid=9
    left join tbl_bti t6 on t6.fld_btiid=10
    left join tbl_bti t7 on t7.fld_btiid=20
    left join tbl_bti t8 on t8.fld_btiid=21
    left join tbl_bti t9 on t9.fld_btiid=22
    left join tbl_bti t10 on t10.fld_btiflag = t0.fld_emptaxstat and t10.fld_bticid=4
	where 
	t0.fld_emporg = $fld_baido
    and
    t0.fld_empstat != 4
    ");

    foreach ($gemp->result() as $row) {
    ### Prepare Variables
      $absence_deduction = 0;
      $late_deduction = 0;
      echo "Employee Name : " . $row->fld_empnm . "<br>";
      ### Cek Employee Sayurday Off Scheduled
      $gsaturday = $this->db->query("select fld_saturdayoffdt from tbl_saturdayoff where fld_empid = $row->fld_empid and fld_saturdayoffdt >= '$fld_btdtsap' and fld_saturdayoffdt <=  '$fld_btdtsop' ");
      $gsaturday = $gsaturday->result();
      $saturdayList = array();
      foreach ($gsaturday as $rsaturday) {
        $saturdayList [] = $rsaturday->fld_saturdayoffdt;
      }
      ###

      ### Get Employee Leave Wage Payed
      $gleave1 = $this->db->query("select sum(t0.fld_btqty) 'leave' from tbl_bth  t0 left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_btp03 and t1.fld_tyid=22 where t0.fld_btiid = $row->fld_empid and t0.fld_bttyid = 23 and t1.fld_tyvalcfg=1 and date_format(t0.fld_btdtsa,'%Y-%m-%d')  >= '$fld_btdtsap' and date_format(t0.fld_btdtso,'%Y-%m-%d')  <=  '$fld_btdtsop' ");
      $gleave1 = $gleave1->row();
      $leave1 = $gleave1->leave;
      ###

      ### Get Employee Leave Wage Not Payed
      $gleave2 = $this->db->query("select sum(t0.fld_btqty) 'leave' from tbl_bth  t0 left join tbl_tyval t1 on t1.fld_tyvalcd=t0.fld_btp03 and t1.fld_tyid=22 where t0.fld_btiid = $row->fld_empid and t0.fld_bttyid = 23 and t1.fld_tyvalcfg=2  and date_format(t0.fld_btdtsa,'%Y-%m-%d')  >= '$fld_btdtsap' and date_format(t0.fld_btdtso,'%Y-%m-%d')  <=  '$fld_btdtsop' ");
      $gleave2 = $gleave2->row();
      $leave2 = $gleave2->leave;
      ###

      ### Get Employee Late
      $glate_charge = $this->db->query(" select
      round(time_to_sec(timediff(t0.fld_empattdin,t3.fld_shiftin) )/60 - if(t0.fld_empattdidt > 0,120,if(t0.fld_empattdin > t3.fld_shiftdispens,0,30))) 'late_time'  
      from 
      tbl_empattd t0 
      left join tbl_emp t1 on t1.fld_empfpid=t0.fld_empfpid
      left join tbl_empshift t2 on date_format(t2.fld_empshiftdt,'%Y-%m-%d') = date_format(t0.fld_empattddt,'%Y-%m-%d')  and (select tx.fld_btiid from tbl_bth tx where tx.fld_btid=t2.fld_btidp) = t1.fld_empid 
      left join tbl_shift t3 on t3.fld_shiftid=if(t1.fld_empworkhour=1,t2.fld_shiftid,1)
      where t0.fld_empfpid = $row->fld_empfpid 
      and 
      date_format(t0.fld_empattddt,'%Y-%m-%d') >= date_format('$fld_btdtsap','%Y-%m-%d')  
      and 
      date_format(t0.fld_empattddt,'%Y-%m-%d') <= date_format('$fld_btdtsop','%Y-%m-%d') 
      having late_time > 0
      ");
      $late_charge = 0;
      foreach ($glate_charge->result() as $rlate_charge) {
      $late_charge = $late_charge + $rlate_charge->late_time;
      }
      ###

      $empwork_days = 0;
      for ($i=0; $i<$days; ++$i) {
        $tmp_day = strtotime("+$i day", $firstday);
        $day = strftime("%A",$tmp_day);
        $tmp = strftime("%Y-%m-%d",$tmp_day);
        if(($day != "Sunday") && (!in_array($tmp, $holidayList)) && (!in_array($tmp, $saturdayList))) {
          $empwork_days = $empwork_days + 1;
        }
      }
      echo "Employee Work Days : " . $empwork_days . "<br>"; 
      $total_salary = 0;
      $meal_pay = $row->Meal * $empwork_days ;
      $transport_pay = $row->Transport * $empwork_days;
      $base_pay = $row->BasePay;
      $functional_pay = $row->Functional;
      $emp_coop_charge = $row->Koperasi;
      $astek_tariff1 = $row->Astek1;
      $astek_tariff2 = $row->Astek2;
    
      ### Jamsostekstek
      $astek_charge1 = $astek_tariff1 * ($base_pay +  $meal_pay + $functional_pay); // Porsi Karyawan
      $astek_charge2 = $astek_tariff2 * ($base_pay +  $meal_pay + $functional_pay); // Porsi Perusahaan
      ###

      echo "Base Pay : " . $base_pay . "<br>";
      echo "Meal Pay : " . $meal_pay . "<br>";
      echo "Transport Pay : " . $transport_pay . "<br>";
      echo "Functional Pay : " . $row->Functional . "<br>";
      echo "Jamsostek (Company) = " . $astek_charge2 . "<br>";
     
     
      ### Potongan Absen
      if ($leave1 + $leave2 > 0) {
        $transport_deduction = ($leave1 + $leave2) *  $transport_pay;
        $wage_deduction = $leave2 * $wage_per_day;
        if ($row->fld_emplevel < 4) {
          $absence_deduction = $transport_deduction + $absence_deduction;
        }
        else {
          $absence_deduction = 0;
        }
      }
      ###

      ### Potongan Telat 
      $wage_per_day = ($base_pay / 30 ) + $meal_allowance;
      $wage_per_menit = $wage_per_day / 420;
      if ($late_charge > 0) {
        if ($row->fld_emplevel < 4) {
          $late_deduction = $late_charge * $wage_per_menit;
        }
        else {
          $late_deduction = 0;
        }
      }
      ###

      ### PPH 21
      $bruto = $base_pay + $meal_pay + $transport_pay + $functional_pay + $astek_charge2;
      $T_jbtn = ($bruto * $row->P_bjbtn);
      if ($T_jbtn > $row->M_bjbtn) {
        $T_jbtn = $row->M_bjbtn ;
      } else {
        $T_jbtn = $T_jbtn ;
      }
      $T_jbtn = $T_jbtn * $row->Month_active ;
      $y_bruto = ($bruto * 12) - $T_jbtn;
      $pkp = $y_bruto - $row->PTKP ;
      if ($pkp <= 50000000) {
        $p_pph21 = 0.05 ;
      }
      else if ($pkp > 50000000 && $pkp <= 250000000 ) {
        $p_pph21 = 0.15 ;
      }
      else if ($pkp > 250000000 && $pkp <= 500000000 ) {
        $p_pph21 = 0.25 ;
      }
      else if ($pkp > 50000000) {
        $p_pph21 = 0.30 ;
      }   
      
      $pph21 = (($pkp * $p_pph21) / 12) - $astek_charge2;
      echo "###$bruto - $T_jbtn###$y_bruto- $row->PTKP###";
      
      echo "_______Potongan_____________ <br>" ;
	  echo " Absence Deduction = " . $absence_deduction . "<br>";
      echo " Late Deduction = " . $late_deduction . "<br>";
      echo " PPH21 = " . $pph21 . "<br>";
      echo " Jamsostek (Employee) = " . $astek_charge1 . "<br>";
      
      $total_pay = ($base_pay + $meal_pay + $transport_pay + $functional_pay) - ($absence_deduction + $late_deduction + $emp_coop_charge);
      ###
      
      echo "----------------------------------------------------<br>";

      $this->db->query("insert into tbl_empsalary (fld_btid,fld_company,fld_periode,fld_empid,fld_basepay,fld_mealpay,fld_transpay,fld_funcpay,fld_absencecharge,fld_latecharge,fld_coopcharge,fld_astekcharge,fld_totalpay) values ($fld_btid,$fld_baido,date_format('$fld_btdt','%Y-%m'),$row->fld_empid,$base_pay,$meal_pay,$transport_pay,$functional_pay,$absence_deduction,$late_deduction,$emp_coop_charge,$astek_charge1,$total_pay)");
    }
                
  }
}
