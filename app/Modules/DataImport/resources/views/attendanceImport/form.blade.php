<style type="text/css" media="screen">
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 90% !important;
    }
</style>

@if(!empty($uploadIdentifier) && $uploadIdentifier == 'price')

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-4">
                <label class="col-form-label">Import File</label>
                <span class="required"> *</span>
            </div>
            <div class="col-md-8">
                <a class="btn btn-primary btn-sm font-10 waves-effect waves-themed" href="javascript:;">
                    Choose File...
                    <input name="excel_file" type="file" accept=".xlsx,.csv" required style="position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:&quot;progid:DXImageTransform.Microsoft.Alpha(Opacity=0)&quot;;opacity:0;background-color:transparent;color:transparent;" size="40" onchange="$(&quot;#upload-file-info&quot;).html($(this).val());">
                </a>
                &nbsp;
                    <span class="label label-info" id="upload-file-info"></span>
            </div>
        </div>
       
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-5">
                <label class="col-form-label">Customer Category</label>
                <span class="required"> *</span>
            </div>
            <div class="col-md-7">
                {!! Form::select('customer_cat_id',$customerCategoryList,null,['class' => 'form-control']) !!}
            </div>
        </div>
        
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-4">
                <label class="col-form-label">Effective Date</label>
                <span class="required"> *</span>
            </div>
            <div class="col-md-8">
                <input id="effective_date" class="form-control" required="required" name="effective_date" type="text" value="{{ date('d-m-Y') }}" autocomplete="off" placeholder ='Enter Effective date'>
            </div>
        </div>
        
    </div>


    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                <button class="btn btn-primary ml-auto waves-effect waves-themed" id="btnsm" type="submit">Upload</button>
            </div>
        </div>
    </div>
</div>
    @else
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
    
                <div class="form-line">
                    <label class="col-form-label">Import File</label>
                    <span class="required"> *</span>
                    <a class="btn btn-primary btn-sm font-10 waves-effect waves-themed" href="javascript:;">
                        Choose File...
                        <input name="excel_file" type="file" accept=".xlsx,.csv" required style="position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:&quot;progid:DXImageTransform.Microsoft.Alpha(Opacity=0)&quot;;opacity:0;background-color:transparent;color:transparent;" size="40" onchange="$(&quot;#upload-file-info&quot;).html($(this).val());">
                    </a>
                    &nbsp;
                    <span class="label label-info" id="upload-file-info"></span>
                </div>
            </div>
        </div>
    
    
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-line">
                    <button class="btn btn-primary ml-auto waves-effect waves-themed" id="btnsm" type="submit">Upload</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        $(document).ready(function(){

    $('#effective_date').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            autoClose: true
        });
    });
    </script>