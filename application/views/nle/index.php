<!-- Begin Page Content -->
<div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
                <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?php echo $title ?></h6>
        </div>

                <div class="card-body">
                        <div class="row table-responsive">
                                <table class="datatable table table-striped">
                                        <thead>
                                                <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Address</th>
                                                        <th>City</th>
                                                        <th>&nbsp;</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php foreach ($model as $row): ?>
                                                <tr>
                                                        <td><?php echo $row->fld_benm ?></td>
                                                        <td><?php echo $row->fld_bemail ?></td>
                                                        <td><?php echo $row->fld_beaddr ?></td>
                                                        <td><?php echo $row->fld_becity ?></td>
                            <td>
                                <a href="<?php echo base_url() ?>nle/detail/<?php echo $row->fld_beid ?>" class="btn btn-info btn-xs"><i class="fa fa-key"></i> Detail</a>
                            </td>
                                                </tr>
                                                <?php endforeach ?>
                                        </tbody>
                                </table>
                        </div>
                </div>
                <!-- /.container-fluid -->
    </div>
</div>
