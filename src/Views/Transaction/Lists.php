

<div class="content-wrapper" style="padding-top: 13px;">

    <link rel="stylesheet" href="../assets/css/table.css">
    <section class="content">

        <div class="container-fluid">


            <div class="row mt-4">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> Transaction </h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control " placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body " >
                           
                             
                                <form method="post" action="modulecontrol?Transaction&Transactions" >

                                    <div id="toolbar">
                                        <label>From</label>
                                        <input value="<?php echo date('m/d/Y'); //$_POST["from"]; ?>" type="date" name="from" id="from"  size="5" />
                                        <label>To</label>
                                        <input value="<?php echo date('m/d/Y'); //$_POST["to"]; ?>" type="date" name="to" id="to"  size="5" />
                                        <input type="submit" id="btnSearch" value="Search"  class="btn btn-sm btn-info"/>
                                        <input type="button" id="btnDelete" value="Delete" onclick="Delete()"  class="btn btn-sm btn-danger"/>
                                    </div>

                                </form>



                                
                                    <div class="responsive">
                                            <table class="table table-head-fixed table-sm table-bordered dt-responsive text-nowrap" id="tblData">
                                                <thead>
                                                    <tr>
                                                        <th data-sortable="true"><input id="select-all" type="checkbox"  /> </th>
                                                        <th> Transaction Number </th>
                                                        <th> Account Name </th>
                                                        <th> Account Type </th>
                                                        <th> Input Time </th>
                                                        <th> File Type </th>
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
        </div>
    </section>