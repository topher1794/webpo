<div class="content-wrapper" style="padding-top: 13px;">



    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <h2 class="text-center display-4">Search Stock</h2>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form id="frmData">
                        <div class="input-group">
                            <input name="materialcode" type="search" class="form-control form-control-lg" placeholder="Type your keywords here">
                            <div class="input-group-append">
                                <button id="btnSubmit" type="button" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"># of Stocks</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 80%">Application</th>
                                        <th  style="width: 20%">Stock Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="trBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

<script>
    var Company = "<?php echo $_SESSION["company"]; ?>";
</script>