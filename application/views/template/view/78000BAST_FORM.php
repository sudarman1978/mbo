<?
$node =  $this->uri->segment(4);
$customer = $this->db->query("select t0.fld_baid,t1.fld_benm from tbl_ba t0
                              left join tbl_be t1 on t1.fld_beid = t0.fld_beid
                              where
                              t0.fld_batyid = 3")->result();
if($node > 0) {
  $data = $this->db->query("select
                              t0.fld_btid,
                              t0.fld_btno,
                              t0.fld_btamt,
                              date_format(t0.fld_btdt,'%Y-%m-%d') 'fld_btdt',
                              date_format(t0.fld_btdtsa,'%Y-%m-%d') 'fld_btdtsa',
                              t0.fld_baidc,
                              t2.fld_benm,
                              t0.fld_btqty,
                              format(t0.fld_btamt,0) 'amount'
                              from tbl_bth t0
                              left join tbl_ba t1 on t1.fld_baid = t0.fld_baidc
                              left join tbl_be t2 on t2.fld_beid = t1.fld_beid
                              where
                              t0.fld_btid = $node")->row();
}
?>
<link href="<?=base_url();?>layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />

<div class="container">
  <div class="container">
    <!-- BREADCRUMB -->
    <div class="page-meta">
      <nav class="breadcrumb-style-one" aria-label="breadcrumb">
      </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div id="navSection" data-bs-spy="affix" class="nav  sidenav">
      <div class="sidenav-content">
        <a href="#flStackForm" class="active nav-link">Print</a>
        <a href="#flStackForm" class="active nav-link">Create Invoice</a>
      </div>
    </div>
    <div class="row">
      <div id="flLoginForm" class="col-lg-12 layout-spacing">
        <div class="statbox widget box box-shadow">
          <div class="widget-content widget-content-area">
            <form class="row g-3" action="<?=base_url()?>index.php/page/saveForm" method="post">
               <input class="form-control form-control-sm" type="hidden" name=fld_viewnm value="<?=$fld_viewnm;?>">

              <div class="col-md-6">
                <label for="fld_btid" class="form-label">ID</label>
                <input  class="form-control form-control-sm" id="fld_btid" name=fld_btid readonly value="<?=$data->fld_btid;?>">
              </div>
              <div class="col-md-6">
                <label for="fld_btno" class="form-label">Transaction Number</label>
                <input class="form-control form-control-sm" type="text" placeholder="[AUTO]" name=fld_btno readonly value="<?=$data->fld_btno;?>">
              </div>
              <div class="col-md-6">
                <label for="fld_btdt" class="form-label">Installation Date</label>
                <input id="fld_btdt" name="fld_btdt" value="<?=$data->fld_btdt;?>" class="form-control form-control-sm flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
              </div>
              <div class="col-md-6">
                <label for="fld_baidc" class="form-label">Customer</label>
                <select class="form-control form-control-sm" id=fld_baidc name=fld_baidc value="<?=$data->fld_baidc;?>">
                  <option>------select------</option>
                  <? foreach ($customer as $rcustomer) {
                     if($rcustomer->fld_baid == $data->fld_baidc) {
                        echo "  <option value=$rcustomer->fld_baid selected >$rcustomer->fld_benm</option>";
                     } else {
                        echo "  <option value=$rcustomer->fld_baid>$rcustomer->fld_benm</option>";
                     }

                  }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="fld_btqty" class="form-label">Total Unit</label>
                <input class="form-control form-control-sm" id="fld_btqty" name="fld_btqty" value="<?=$data->fld_btqty;?>">
              </div>
              <div class="col-md-6">
                <label for="fld_btamt" class="form-label">Total Price</label>
                <input class="form-control form-control-sm" id="fld_btamt" name="fld_btamt" value="<?=$data->fld_btamt;?>">
              </div>
              <div class="col-md-6">
                <label for="fld_btdtsa" class="form-label">Billing Start Date</label>
                <input id="fld_btdt" name="fld_btdtsa" value="<?=$data->fld_btdtsa;?>" class="form-control form-control-sm flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                </div>
              <div class="col-md-12">
                <label for="fld_btdesc" class="form-label">Notes</label>
                <textarea class="form-control form-control-sm" id="fld_btdesc" name="fld_btdesc" rows="3"></textarea>
              </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>




                      <div class="widget-content widget-content-area simple-tab">
                          <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
                              <li class="nav-item">
                                  <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
                              </li>
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Profile <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>
                                  <div class="dropdown-menu">
                                      <a class="dropdown-item" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Action</a>
                                      <a class="dropdown-item"  id="profile-tab2" data-bs-toggle="tab" href="#profile2" role="tab" aria-controls="profile2" aria-selected="false">Another action</a>
                                  </div>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link disabled" href="#" tabindex="-1" >Disabled</a>
                              </li>
                          </ul>
                          <div class="tab-content" id="simpletabContent">
                              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <p class="mb-4">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                  </p>

                                  <p>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                  </p>
                              </div>
                              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                  <div class="media">
                                      <img class="me-3" src="<?=base_url();?>src/assets/img/profile-32.jpeg" alt="Generic placeholder image">
                                      <div class="media-body">
                                          Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                                      </div>
                                  </div>
                              </div>
                              <div class="tab-pane fade" id="profile2" role="tabpanel" aria-labelledby="profile-tab2">
                                  <p class="">
                                      Duis aute irure dolor in reprehenderit in voluptate velit esse
                                      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                  </p>
                              </div>
                              <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                  <p class="">
                                      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                      consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                  </p>
                              </div>
                          </div>



                      </div>





            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
var f1 = flatpickr(document.getElementById('fld_btdtsa'));
</script>
<script src="<?=base_url();?>src/assets/js/scrollspyNav.js"></script>
