<?php

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">

.imgHover:hover{
    border-radius: 15px;
    box-shadow: 0 0 0 4pt #3c8dbc;
    transition: box-shadow 0.5s ease-in-out;
}
</style>

<div class="Lab-default-index">
   <div class="body-content">
        <div class="box box-primary color-palette-box" style="padding:0px">
        <div class="box-header" style="background-color: #fffff;color:#000" data-widget="collapse">
          <h3 class="box-title" data-widget="collapse"><i class="fa fa-tag"></i>Finance</h3>
          <div class="box-tools pull-right" >
                <button type="button" style="color:#000" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
        </div>      
        <div class="box-body">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              
               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                  <a href="#" title="Billing"><img class="imgHover" src="/images/billingsquare.png" style="height:120px;width: 120px" ></a>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
            
             <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                 <a href="/reports/finance/financialreports/" title="Financial Reports"><img class="imgHover" src="/images/financialsquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              

               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                   <a href="#" title="Cashier"><img class="imgHover" src="/images/cashiersquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
           
               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                 <a href="/reports/finance/analytic" title="Customer Wallet"><img class="imgHover" src="/images/customerwalletsquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
            
            <!-- /.col -->
            
            <!-- /.col -->  
          </div>
        </div>
        <!-- /.box-body -->
        </div>
        
        <div class="box box-primary color-palette-box" style="padding:0px">
        <div class="box-header with-border" style="background-color: #fffff;color:#000" data-widget="collapse">
          <h3 class="box-title" data-widget="collapse"><i class="fa fa-tag"></i>Laboratory</h3>
          <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" style="color:#000" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
        </div>      
        <div class="box-body">
          <div class="row">
            <div class="col-md-2 col-sm-6 col-xs-12">
               <!-- col-md-offset-1  if 5 columns --> 
             <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                 <a href="#" title="Tagging"><img class="imgHover" src="/images/taggingsquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div> 
            
            <!-- /.col -->
           <div class="col-md-2 col-sm-6 col-xs-12">
             
               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                  <a href="/reports/lab/accomplishment/firms" title="Request/Customer/Firms"><img class="imgHover" src="/images/requestsquare.png" style="height:120px;width: 120px" ></a>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-md-2 col-sm-6 col-xs-12">
             
               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                   <a href="/lab/sampleregister" title="Accomplishment"><img class="imgHover" src="/images/sampleregistersquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
            
            <div class="col-md-2 col-sm-6 col-xs-12">
             
               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                   <a href="/reports/lab/accomplishment/" title="Accomplishment"><img class="imgHover" src="/images/accomplishmentsquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
            
            <div class="col-md-2 col-sm-6 col-xs-12">
             

               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                 <a href="/reports/lab/statistic/daily" title="Summary of Samples"><img class="imgHover" src="/images/summarysamplessquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
			
	    <div class="col-md-2 col-sm-6 col-xs-12">
             

               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                 <a href="/reports/lab/statistic/customers" title="Customer Served"><img class="imgHover" src="/images/customerservedsquare.png" style="height:120px;width: 120px"></a>
              </div>
            </div>
            
            <!-- /.col -->
            
            <!-- /.col -->  
          </div>
        </div>
        <!-- /.box-body -->
        </div>
        
        <div class="box box-primary color-palette-box" style="padding:0px">
        <div class="box-header with-border" style="background-color: #fffff;color:#000" data-widget="collapse">
          <h3 class="box-title" data-widget="collapse"><i class="fa fa-tag"></i>Customer</h3>
          <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" style="color:#000" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
        </div>      
        <div class="box-body">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              
               <div  style="padding-top: 1px;padding-bottom: 1px;display:block;text-align: center">
                  <a href="#" title="Order of Payment"><img class="imgHover" src="/images/customerlistsquare.png" style="height:120px;width: 120px" ></a>
              </div>
            </div>
            <!-- /.col -->
            
            <!-- /.col -->
            
            
           
            
            <!-- /.col -->
            
            <!-- /.col -->  
          </div>
        </div>
        <!-- /.box-body -->
        </div>
        
        
        
        
        
    </div>
</div>
