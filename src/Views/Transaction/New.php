
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="padding-top: 13px;">
    <!-- Content Header (Page header) -->

    <link rel="stylesheet" href="../assets/css/table.css">
    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Upload File</h3>
                        </div>


                        <form action="actionupload.php" method="post" enctype="multipart/form-data" >
                            <input name="actiontype" value="upload" hidden />
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Account Type</label>
                                    <select name="accttype" id="accttype" class="custom-select form-control-border" id="exampleSelectBorder">
                                     
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Account</label>
                                    <select name="acctname" id="acctname" class="custom-select form-control-border" id="exampleSelectBorder">
                                    </select>
                                </div>

                                <div id="divRange" >

                                <div class="form-group">
                                    <label for="exampleInputPassword1">From Date</label>
                                    <input name="fromDate" id="fromDate" type="date" class="form-control form-control-border border-width-2" />
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">To Date</label>
                                    <input name="toDate" id="toDate" type="date" class="form-control form-control-border border-width-2" />
                                </div>

                                </div>
                               


                               <div id="divStd">   
                                        
                               <div class="form-group">
                                    <label for="exampleInputPassword1">File Type</label>
                                    <select name="acctfile" id="acctfile" class="custom-select form-control-border" id="exampleSelectBorder">
                                    </select>
                                </div>
                               

                                <div class="form-group">
                                    <label for="exampleInputFile">File input</label>
                                    <input name="fileToUpload" id="fileToUpload" type="file" class="form-control"  id="exampleInputFile">
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputPassword1">P.O Date</label>
                                    <input name="podate" id="podate" type="date" class="form-control form-control-border border-width-2" />
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">RDD</label>
                                    <input name="rdd" id="rdd" type="date" class="form-control form-control-border border-width-2" />
                                </div>

                                </div>
                               
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>

    </section>
</div>