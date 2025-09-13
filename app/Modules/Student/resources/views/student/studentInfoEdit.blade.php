<style>
    #searchInput {
        height: 35px;
        width: 250px;
        padding: 0 0 0 20px;
    }

    .totalCount {
        font-size: 14px;
        font-weight: 700;
    }
</style>
<div class="table-responsive">
    <input type="text" id="searchInput" placeholder="Search by Name or Mobile">
    <br>
    <label for="" class="totalCount mt-2">Total: <span id="resultCount">{{ $totalStudent }}</span></label>
    <br>
    <table class="table table-bordered table-striped ytable" id="dt-basic-example">
        <thead class="thead-themed">
            <tr>
                <th> Sl</th>
                <th style="width: 10%;" class="table-checkbox-header-center">
                    <span>Check All</span>
                    <input type="checkbox" class="all-check-box" id="chkbxAll" onclick="return checkAll()">
                </th>
                <th> First Name</th>
                <th> Last Name</th>
                <th> Gender</th>
                <th> Student Mobile</th>
                <th>
                    Status
                    <select id="bulk-select">
                        <option value=""></option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="cancel">Cancel</option>
                    </select>
                </th>
            </tr>
        </thead>
        <tbody>
            @if (!$students->isEmpty())
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="table-checkbox-column"> <input type="checkbox" class="allCheck all-check-box"
                                id="student_{{ $student->id }}" name="students[{{ $student->id }}]"
                                value="{{ $student->id }}" keyValue="{{ $student->id }}"
                                onclick="unCheck(this.id);isChecked()"> </td>
                        <td> <input type="text" class="form-control" name="first_name[{{ $student->id }}]"
                                value="{{ $student->first_name }}"></td>
                        <td><input type="text" class="form-control" name="last_name[{{ $student->id }}]"
                                value="{{ $student->last_name }}"></td>
                        <td>
                            {!! Form::select('gender[' . $student->id . ']', ['male' => 'Male', 'female' => 'Female'], $student->gender, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td><input type="number" name="cp_phone_no[{{ $student->id }}]" class="cp-phone-no form-control"
                                data-key="{{ $student->id }}" onchange="cpPhoneValidate({{ $student->id }})"
                                id="cpPhoneNo{{ $student->id }}" value="{{ $student->cp_phone_no }}">

                            <p class="error error-cp-phone-no" id="error_cpPhoneNo{{ $student->id }}"></p>
                        </td>
                        <td>
                            {!! Form::select(
                                'status[' . $student->id . ']',
                                ['active' => 'Active', 'inactive' => 'Inactive', 'cancel' => 'Cancel'],
                                $student->status,
                                ['class' => 'form-control single-status'],
                            ) !!}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Trigger the search function whenever a key is released in the search input
        $('#searchInput').keyup(function() {
            searchTable($(this).val());
        });

        // Function to search the table based on the given query
        function searchTable(query) {
            // Convert lowercase for case-insensitive search
            query = query.toLowerCase();
            var visibleRows = 0;

            // Iterate over each table row in the tbody
            $('#dt-basic-example tbody tr').each(function() {
                var firstName = $(this).find('input[name^="first_name"]').val().toLowerCase();
                var lastName = $(this).find('input[name^="last_name"]').val().toLowerCase();
                var mobile = $(this).find('input[name^="cp_phone_no"]').val().toLowerCase();

                // Check if the firstName, lastName or mobile contains the query
                if (firstName.includes(query) || lastName.includes(query) || mobile.includes(query)) {
                    $(this).show();
                    visibleRows++;
                } else {
                    $(this).hide();
                }
            });
            $('#resultCount').text(visibleRows);
        }

        $('#bulk-select').on('change', function(){
        
        if($(this).val() == 'active'){
            $('.single-status').each(function(){
                $(this).val('active')
            })
        }
        else if($(this).val() == 'inactive'){
            $('.single-status').each(function(){
                $(this).val('inactive')
            })
        }
        else{
            $('.single-status').each(function(){
                $(this).val('cancel')
            })
        }
        });
    });

    function cpPhoneValidate(elementId) {
        var values = [];
        $(".cp-phone-no").each(function() {
            values.push($(this).val());
        });
        var cpPhoneArray = values.sort();
        var duplicate = 0;
        for (var i = 0; i < cpPhoneArray.length - 1; i++) {
            if (cpPhoneArray[i + 1] == cpPhoneArray[i]) {
                duplicate = cpPhoneArray[i];
                if (duplicate != 0) {
                    $('#error_cpPhoneNo' + elementId).html('Duplicate Entry!!!');
                    $(".cp-phone-no").attr("disabled", true);
                    $('#cpPhoneNo' + elementId).attr("disabled", false);
                    $('#cpPhoneNo' + elementId).css("background-color", "yellow");
                    $('#submitBtn').attr("disabled", true);
                } else {
                    $('#error_cpPhoneNo' + elementId).html('');
                    $(".cp-phone-no").attr("disabled", false);
                    $('#cpPhoneNo' + elementId).css("background-color", "#fff");
                    $('#submitBtn').attr("disabled", false);

                }
            }
        }


    };

    
</script>
