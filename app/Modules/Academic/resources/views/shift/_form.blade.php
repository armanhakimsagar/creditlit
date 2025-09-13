

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Shift Name', array('class' => 'col-form-label')) !!}<span
                        class="required"> *</span>

                {!! Form::text('name',old('name'),['id'=>'name','class' => 'form-control','required'=> 'required',  'placeholder'=>'Enter Shift name']) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
           <div class="col-md-10">
                <div class="form-group">
                    <div class="form-line">
                        {!!  Form::label('status', 'Status', array('class' => 'col-form-label')) !!} <span
                                class="required"> *</span>

                        {!! Form::Select('status',array('active'=>'Active','inactive'=>'Inactive','cancel' => 'Cancel'),old('status'),['id'=>'status', 'class'=>'form-control selectheight']) !!}
                        <span class="error"> {!! $errors->first('status') !!}</span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="is_trash" value="0">
            <div class="col-md-2">

                <div class=" float-right mt-5">
                    <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed" onclick="Validator();"  id="btnsm"   type="submit">Save</button>
                </div>
            </div>    
        </div>   
    </div>



</div>

<script>



    $(function () {

        $("#shift").validate({
            rules: {
                name: {
                    required: true,
                    // unique:true
                },
                // slug:{
                //     required:true
                // },
                status: {
                    required: true
                }

            },
            messages: {
                name: 'Please enter name',
                // slug:'Please enter slug',
                status: 'Please choose status'
            }
        });
    });

    function Validator(){
   //  ...bla bla bla... the checks
    if( $("#name").val() !=''){
     $("#btnsm").attr("disabled", true);
     $("#btnsm").html('Wait..');
      $("#shift").submit();
      return(true);
         }else{
      return(false);
        }
     }

</script>