<div class="content-wrapper" style="padding-top: 13px;">

    <link rel="stylesheet" href="../assets/css/table.css">
    <section class="content">

        <div class="container-fluid">
            <div class="row mt-4">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> Transaction </h3>
                            <?php if ($_GET['status'] == 'Open') { ?>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <!-- <input type="text" name="table_search" class="form-control " placeholder="Search"> -->
                                        <button type="button" class="btn btn-primary" id="newSync">
                                            <i class="fas fa-sync"></i> New Sync
                                        </button>
                                        <!-- <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button> -->
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body ">


                        <!-- <form id="searchForm"> -->

                        <div id="toolbar" class="mb-3">
                            <label>From</label>
                            <input value="<?php echo date('m/d/Y'); //$_POST["from"]; 
                                            ?>" type="date" name="from" id="from" size="5" />
                            <label>To</label>
                            <input value="<?php echo date('m/d/Y'); //$_POST["to"]; 
                                            ?>" type="date" name="to" id="to" size="5" />
                            <input type="button" id="btnSearch" value="Search" class="btn btn-sm btn-info" />
                            <input type="button" id="btnDelete" value="Delete" onclick="Delete()" class="btn btn-sm btn-danger" />
                        </div>

                        <!-- </form> -->




                        <div class="responsive">
                            <table class="table table-head-fixed table-sm table-bordered dt-responsive text-nowrap" width="100%" id="tblData">
                                <thead>
                                    <tr>
                                        <!-- <th data-sortable="true"><input id="select-all" type="checkbox" /> Transaction Number</th> -->
                                        <th class="text-center">Transaction Number </th>
                                        <th class="text-center"> Sync Date </th>
                                        <th class="text-center"> SKU </th>
                                        <th class="text-center"> Company </th>
                                        <th class="text-center"> Source </th>
                                        <th class="text-center"> User </th>
                                        <th class="text-center" <?= $_GET['status'] == 'Open' ? 'hidden' : '' ?>> Date Completed </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>






                    </div>




                </div>
            </div>
        </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="exampleModalLabel" style="font-weight: bold;">Modal title</>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container col-md-12">
                    <table class="table table-sm table-hover table-bordered table-responsive text-nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%;">E-commerce</th>
                                <th class="text-center" style="width: 10%;">Product ID</th>
                                <th class="text-center" style="width: 10%;">SKU</th>
                                <th class="text-center" style="width: 10%;">Model ID</th>
                                <th class="text-center" style="width: 50%;">Product Name</th>
                                <th class="text-center" style="width: 10%;">QTY</th>
                                <th class="text-center" style="width: 10%;">Orig. QTY</th>
                            </tr>
                        </thead>
                        <tbody id="Mtbody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class=" modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- New Sync Modal -->
<div class="modal fade" id="newSyncModal" data-backdrop="static" tabindex="-1" aria-labelledby="newSyncModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="frmData" action="" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #007bff; color: white">
                    <h5 class="modal-title" id="newSyncModalLabel">Sync Material Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Company</label>
                            <select name="company" class="form-control form-select-sm">
                                <option>ROBERTS</option>
                                <option>URATEX</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Material Code</label>
                            <input name="materialcode" id="materialcode" type="text" class="form-control form-control-border border-width-2" required />
                        </div>
                    </div>
                    <!-- <div class="card-footer">
                        <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button>
                    </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>


</section>