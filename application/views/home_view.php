<?
$company = $this->session->userdata('company');
$asset = $this->db->query("select t0.fld_bticd,
                           t1.fld_engine, t1.fld_speed
                           from tbl_bti t0
                           left join gpstrack.tbl_gps_update t1 on t1.fld_imei = t0.fld_imei
                           where t0.fld_btiorg =$company")->result();
$driver = $this->db->query("select * from tbl_driver t0 where t0.fld_driverorg = $company")->result();
$op = $this->db->query("select t0.fld_btno
                        from tbl_bth t0 where t0.fld_baido = $company and t0.fld_btstat != 3")->result();
$asset_qty = 0;
$asset_park = 0;
$asset_drive = 0;
$asset_stop = 0;
$driver_qty = 0;
$order_active = 0;
foreach ($asset as $rasset) {
  $asset_qty = $asset_qty + 1;
  if($rasset->fld_engine == 1 && $rasset->fld_speed > 0) {
    $asset_drive = $asset_drive + 1;
  }
}

foreach ($driver as $rdriver) {
  $driver_qty = $driver_qty + 1;

}

foreach ($op as $rop) {
  $order_active = $order_active + 1;
}
?>
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container sidebar-closed" id="container">

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div >
                    <div class="row layout-top-spacing">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                          <div class="row widget-statistic">


                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 layout-spacing">
                                <div class="widget widget-one_hybrid widget-followers">
                                    <div class="widget-heading">
                                        <div class="w-title">
                                            <div class="w-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            </div>
                                            <div class="">
                                                <p class="w-value"><?=$asset_qty;?></p>
                                                <h5 class="">Total Unit</h5>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>



                              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 layout-spacing">
                                  <div class="widget widget-one_hybrid widget-followers">
                                      <div class="widget-heading">
                                          <div class="w-title">
                                              <div class="w-icon">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                              </div>
                                              <div class="">
                                                  <p class="w-value"><?=$driver_qty;?></p>
                                                  <h5 class="">Total Supir</h5>
                                              </div>
                                          </div>
                                      </div>
                                      
                                  </div>
                              </div>
                              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 layout-spacing">
                                  <div class="widget widget-one_hybrid widget-referral">
                                      <div class="widget-heading">
                                          <div class="w-title">
                                              <div class="w-icon">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                              </div>
                                              <div class="">
                                                  <p class="w-value"><?=$order_active;?></p>
                                                  <h5 class="">Total Pelanggan</h5>
                                              </div>
                                          </div>
                                      </div>
                                      
                                  </div>
                              </div>

                              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 layout-spacing">
                                  <div class="widget widget-one_hybrid widget-referral">
                                      <div class="widget-heading">
                                          <div class="w-title">
                                              <div class="w-icon">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                              </div>
                                              <div class="">
                                                  <p class="w-value"><?=$order_active;?></p>
                                                  <h5 class="">Total Order Aktif</h5>
                                              </div>
                                          </div>
                                      </div>
                                      
                                  </div>
                              </div>


                      
                    </div>

                </div>

            </div>

        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->
