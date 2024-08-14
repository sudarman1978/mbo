<?
$viewrs = $this->db->query("select * from tbl_bth t0
                            where t0.fld_bttyid = 1 order by t0.fld_btid")->result();
?>
<div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">

                    </div>
                    <!-- /BREADCRUMB -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

      <div class="d-grid gap-2 col-6 mx-auto">

        <button class="btn btn-secondary mb-4" type="button" onclick="window.location.href='<?=base_url();?>index.php/page/view/78000GENERATE_BILLING_FORM'">Add Record</button>

      </div>
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Trans Number</th>
                            <th>Date</th>
                            <th>Periode</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                      <? foreach ($viewrs as $rviewrs) {?>
                        <tr>
                            <td><?=$rviewrs->fld_btno;?></td>
                            <td><?=$rviewrs->fld_btdt;?></td>
                            <td><?=$rviewrs->fld_periode;?></td>
                            <td><?=$rviewrs->fld_btdesc;?></td>

                        </tr>
               <? } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>
</div>
