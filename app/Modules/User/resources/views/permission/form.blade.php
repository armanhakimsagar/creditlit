<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('userShowName', 'User Show Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('user_show_name', null, [
                    'id' => 'userShowName',
                    'class' => 'form-control',
                    'placeholder' => 'Enter permission name',
                ]) !!}
                <span class="error"> {!! $errors->first('user_show_name') !!}</span>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('routeName', 'Route Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('route_name', isset($permission->name) ? $permission->name : null, [
                    'id' => 'routeName',
                    'class' => 'form-control',
                    'placeholder' => 'Enter route name',
                ]) !!}
                <span class="error"> {!! $errors->first('route_name') !!}</span>
            </div>
        </div>
    </div>



    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('groupName', 'Group Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('group_name', null, [
                    'id' => 'groupName',
                    'class' => 'form-control',
                    'placeholder' => 'Enter group name',
                ]) !!}
                <span class="error"> {!! $errors->first('group_name') !!}</span>
            </div>
        </div>
    </div>



    <div class="col-md-1 align-items-end">
        <div class="form-group">
            <div class="form-line">
                <button class="btn btn-primary btn-sm ml-auto waves-effect waves-themed float-left mt-5" id="btnsm"
                    type="submit">Save</button>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    $(function() {
        $("#permission").validate({
            rules: {
                user_show_name: {
                    required: true,
                },
                route_name: {
                    required: true
                },
                group_name: {
                    required: true
                }

            },
            messages: {
                user_show_name: 'Please enter name',
                route_name: 'Please enter route name',
                group_name: 'Please enter group name',
            }
        });

        $('input:text[name="user_show_name"]').on('input', function(e) {
            var name = $('#userShowName').val();
            var lowerCase = name.toLowerCase();
            var routeName = lowerCase.replace(/ /g, '.');
            $('#routeName').val(routeName);
        });
    });
</script>
