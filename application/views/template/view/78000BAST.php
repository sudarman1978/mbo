<?
$viewrs = $this->db->query("select
                            t0.fld_btid,
                            t0.fld_btno,
                            date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                            t2.fld_benm,
                            t0.fld_btqty,
                            format(t0.fld_btamt,0) 'amount'
                            from tbl_bth t0
                            left join tbl_ba t1 on t1.fld_baid = t0.fld_baidc
                            left join tbl_be t2 on t2.fld_beid = t1.fld_beid
                            where t0.fld_bttyid = 2 order by t0.fld_btid")->result();
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

        <button class="btn btn-secondary mb-4" type="button" onclick="window.location.href='<?=base_url();?>index.php/page/view/78000BAST_FORM'">Add Record</button>

      </div>
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <table id="html5-extension" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Action`</th>
                            <th>Trans Number</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Unit</th>
                            <th>Total Charge</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                      <? foreach ($viewrs as $rviewrs) {?>
                        <tr>
                          <td class="text-center">
                                                    <a href="<?=base_url();?>index.php/page/view/78000BAST_FORM/<?=$rviewrs->fld_btid;?>" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-original-title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-8 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
                                                    <a href="javascript:void(0);" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-8 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                          </td>
                            <td><?=$rviewrs->fld_btno;?></td>
                            <td><?=$rviewrs->date;?></td>
                            <td><?=$rviewrs->fld_benm;?></td>
                            <td align="right"><?=$rviewrs->fld_btqty?></td>
                            <td align="right"><?=$rviewrs->amount;?></td>
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
