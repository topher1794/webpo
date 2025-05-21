<div class="content-wrapper" style="padding-top: 13px;">

    <link rel="stylesheet" href="../assets/css/table.css">
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Users</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control " placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <form id="frmData" action="" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                    <div class="responsive">
                                        <table class="table table-head-fixed table-sm table-bordered dt-responsive text-nowrap" id="tblData">
                                            <thead>
                                                <tr>
                                                    <th>Name </th>
                                                    <th>Email Address </th>
                                                    <th>Token </th>
                                                    <th>Token Expiration</th>
                                                    <th>Status </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                            </div>
                            <div class="card-footer">
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>

    </section>
</div>