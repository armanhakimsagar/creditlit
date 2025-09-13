<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('name', 'Name', ['class' => 'col-form-label']) !!}<span class="required"> *</span>

                {!! Form::text('name', null, [
                    'id' => 'name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Role name',
                ]) !!}
                <span class="error"> {!! $errors->first('name') !!}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <div class="form-line">
                {!! Form::label('status', 'Status', ['class' => 'col-form-label']) !!} <span class="required"> *</span>

                {!! Form::Select(
                    'status',
                    ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                    $role->status ?? 'active',
                    [
                        'id' => 'status',
                        'class' => 'form-control selectheighttype',
                    ],
                ) !!}

                <span class="error"> {!! $errors->first('status') !!}</span>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-4 mt-5">
        {!! Form::label('permission', 'Permission', ['class' => 'col-form-label']) !!}
        <div class="custom-control custom-switch">
            {!! Form::checkbox('mark_all', 'mark_all', isset($same_address) ? 'checked' : null, [
                'id' => 'checkPermissionAll',
                'class' => 'custom-control-input',
            ]) !!}
            <label class="custom-control-label mark-all" for="checkPermissionAll">Mark All</label>
        </div>
    </div>
</div>


@php $i = 1; @endphp
<div class="group-container">
    @foreach ($groupName as $item)
        <div class="row mt-5">
            <div class="col-lg-4">

                @php
                    $permission = DB::table('permissions')
                        ->where('group_name', $item->group_name)
                        ->get();
                    $j = 1;
                @endphp

                @if ($pageTitle == 'Edit Role')
                    <div class="custom-control custom-checkbox mt-3">
                        <input type="checkbox" class="custom-control-input" id="group_{{ $i }}"
                            value="{{ $item->group_name }}"
                            onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)"
                            data-key="{{ $i }}"
                            {{ roleHasPermissions($role, $permission) ? 'checked' : '' }}>
                        <label class="custom-control-label"
                            for="group_{{ $i }}">{{ $item->group_name }}</label>
                    </div>
                @else
                    <div class="custom-control custom-checkbox mt-3">
                        <input type="checkbox" class="custom-control-input" id="group_{{ $i }}"
                            value="{{ $item->group_name }}"
                            onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)"
                            data-key="{{ $i }}">
                        <label class="custom-control-label"
                            for="group_{{ $i }}">{{ $item->group_name }}</label>
                    </div>
                @endif

            </div>
            <div class="col-lg-8 role-{{ $i }}-management-checkbox">
                @foreach ($permission as $permItem)
                    <div class="custom-control custom-checkbox mt-3">
                        <input type="checkbox" name="permissions[]"
                            onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', 'group_{{ $i }}', {{ count($permission) }})"
                            value="{{ $permItem->id }}"
                            class="custom-control-input permission-of-group{{ $i }} all-permission"
                            @if (isset($role)) {{ $role->hasPermissionTo($permItem->name) ? 'checked' : '' }} @endif
                            id="defaultUnchecked{{ $permItem->id }}">
                        <label class="custom-control-label"
                            for="defaultUnchecked{{ $permItem->id }}">{{ $permItem->user_show_name }}</label>
                    </div>
                    @php
                        $j++;
                    @endphp
                @endforeach
            </div>
        </div>
        @php $i++; @endphp
    @endforeach
</div>


<div class="row">
    <div class="col-md-3 align-items-end">
        <div class="form-group">
            <div class="form-line">
                @if ($pageTitle == 'Add Role')
                    <button class="btn btn-primary ml-auto waves-effect waves-themed float-left mt-5" id="btnsm"
                        type="submit"><i class="fas fa-plus" style="margin-right: 10px;"></i>Add New Role</button>
                @elseif($pageTitle == 'Edit Role')
                    <button class="btn btn-primary ml-auto waves-effect waves-themed float-left mt-5" id="btnsm"
                        type="submit"><i class="fas fa-plus" style="margin-right: 10px;"></i>Update Role</button>
                @endif
            </div>
        </div>
    </div>
</div>

</div>

<script>
    $(function() {
        checkSinglePermission();
        // Validation
        $("#class").validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true
                }

            },
            messages: {
                name: 'Please enter brand name',
                status: 'Please choose status'
            }
        });

        // Mark All & Unmark All permission label change
        $("#checkPermissionAll").click(function() {
            if ($(this).is(':checked')) {
                // check all the checkbox
                $('input[type=checkbox]').prop('checked', true);
                $(".mark-all").empty();
                $(".mark-all").append("<label>Unmark All</label>");
            } else {
                // un check all the checkbox
                $('input[type=checkbox]').prop('checked', false);
                $(".mark-all").empty();
                $(".mark-all").append("<label>Mark All</label>");
            }
        });

        // Status
        $('#status').select2();

    });



    // Check Single permission
    function checkSinglePermission(groupClassName, groupID, countTotalPermission) {
        const classCheckbox = $('.' + groupClassName + ' input');
        const groupIDCheckBox = $("#" + groupID);
        // if there is any occurance where something is not selected then make selected = false
        if ($('.' + groupClassName + ' input:checked').length == countTotalPermission) {
            groupIDCheckBox.prop('checked', true);
        } else {
            groupIDCheckBox.prop('checked', false);
        }
        implementAllChecked();
        // console.log(groupIDCheckBox);
    }



    function implementAllChecked() {
        const countPermissionGroups = {{ count($groupName) }};
        const countPermissions = {{ count($all_permissions) }};
        //  console.log((countPermissions + countPermissionGroups));
        //  console.log($('input[type="checkbox"]:checked').length);
        if ($('input[type="checkbox"]:checked').length >= (countPermissions + countPermissionGroups)) {
            $("#checkPermissionAll").prop('checked', true);
        } else {
            $("#checkPermissionAll").prop('checked', false);
        }
    }


    function checkPermissionByGroup(className, checkThis) {
        const groupIdName = $("#" + checkThis.id);
        const classCheckBox = $('.' + className + ' input');
        console.log(groupIdName);
        if (groupIdName.is(':checked')) {
            // check all the checkbox
            classCheckBox.prop('checked', true);
        } else {
            // un check all the checkbox
            classCheckBox.prop('checked', false);
        }
        implementAllChecked();
    }
</script>
