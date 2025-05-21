<div class="content-wrapper" style="padding-top: 13px;">

    <link rel="stylesheet" href="../assets/css/table.css">
    <section class="content">

        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Material Code MasterList</h3>
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
                                <!-- <div class="form-group">
                                    <label for="exampleInputEmail1">Company</label>
                                    <select name="company" class="form-control form-select-sm">
                                        <option>ROBERTS</option>
                                        <option>URATEX</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Account Type</label>
                                    <select name="company" class="form-control form-select-sm">
                                        <option>SHOPEE</option>
                                        <option>LAZADA</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Material Code</label>
                                    <input name="materialcode" id="materialcode" type="text" class="form-control form-control-border border-width-2" />
                                </div> -->



                                <form method="post" action="">

                                    <div id="toolbar" class="mb-4">
                                        <div class="row">
                                            <div class="md-2">
                                                <label>Comapny</label>
                                                <select name="company" id="company" class="form-control form-select-sm">
                                                    <option>ROBERTS</option>
                                                    <option>URATEX</option>
                                                </select>
                                            </div>
                                            <div class="md-2 ml-2">
                                                <label>Source</label>
                                                <select name="source" id="source" class="form-control form-select-sm">
                                                    <option>SHOPEE</option>
                                                    <option>LAZADA</option>
                                                </select>
                                            </div>
                                            <!-- <div class="md-2 ml-2">
                                                <label for=""></label>
                                                <button>SEARCH</button>
                                            </div> -->

                                        </div>

                                    </div>

                                </form>


                                <!-- <div class=""> -->
                                <!-- <div class="card-body"> -->
                                <table class="table table-responsive table-sm table-hover table-bordered text-nowrap" width="100%" id="tblData">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Product ID</th>
                                            <th class="text-center">Parent Sku </th>
                                            <th class="text-center">Sku </th>
                                            <th class="text-center">Product Name </th>
                                            <th class="text-center">Account Type </th>
                                            <th class="text-center">Company </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!-- </div> -->
                                <!-- </div> -->

                            </div>
                            <div class="card-footer">
                                <!-- <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button> -->
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>



        <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <form id="frmUpload" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="uploadtype" value="sku" />
                        <div class="modal-header">
                            <h4 class="modal-title">Upload File</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="lblAttachment">Attachment</label>
                                <input name="file" type="file" class="form-control" id="lblAttachment">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button id="btnUpload" type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>




    </section>
</div>