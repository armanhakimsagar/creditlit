<style>
    #searchInput {
        height: 35px;
        width: 250px;
        padding: 0 0 0 20px;
    }

    .totalCount{
        font-size: 14px;
        font-weight: 700;
    }
</style>
<div class="table-responsive">
    <input type="text" id="searchInput" placeholder="Search...">
    <br>
    <label for="" class="totalCount mt-2">Total: <span id="resultCount">{{$totalStudent}}</span></label>
    <br>
    <table class="table table-bordered table-striped ytable" id="dt-basic-example">
        <thead class="thead-themed">
            <tr>
                <th> Sl</th>
                <th style="width: 10%;" class="table-checkbox-header-center">
                    <span>Check All</span>
                    <input type="checkbox" class="all-check-box" id="chkbxAll"
                        onclick="return checkAll()">
                </th>
                <th> Student Id</th>
                <th> Student Name</th>
                <th> Father Name</th>
                <th> Mobile</th>
                <th> NID</th>
                <th> Education</th>
            </tr>
        </thead>
        <tbody>
            @if(!$students->isEmpty())
            @foreach ($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="table-checkbox-column"> <input type="checkbox" class="allCheck all-check-box" id="student_{{ $student->id }}" name="students[{{ $student->id }}]" value="{{ $student->id }}" keyValue="{{ $student->id }}" onclick="unCheck(this.id);isChecked()"> </td>
                <td> <a href="{{route(app('SID'),$student->id)}}" target="_blank" style="text-decoration: none;">{{ $student->contact_id }}</a> </td>
                <td><a href="{{route(app('studentName'),$student->id)}}" target="_blank" style="text-decoration: none;">{{ $student->full_name }}</a></td>
                 <td><input type="text" class="form-control" name="father_name[{{$student->id}}]" value="{{ $student->father_name }}"></td>
                  
                <td><input type="number" class="form-control" name="father_phone[{{$student->id}}]" value="{{ $student->father_phone }}"></td>

                <td><input type="text" class="form-control" name="father_nid[{{$student->id}}]" value="{{ $student->father_nid }}"></td> 

                <td><input type="text" class="form-control" name="father_education[{{$student->id}}]" value="{{ $student->father_education }}"></td> 
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
                var name = $(this).find('td:nth-child(3)').text()
                    .toLowerCase();
                var sid = $(this).find('td:nth-child(4)').text()
                    .toLowerCase();
                var fatherName = $(this).find('input[name^="father_name"]').val().toLowerCase();
                var mobile = $(this).find('input[name^="father_phone"]').val().toLowerCase();

                // Check if the Name, SID, FatherName or mobile contains the query
                if (name.includes(query) || sid.includes(query) || fatherName.includes(query) || mobile.includes(query)) {
                    $(this).show();
                    visibleRows++;
                } else {
                    $(this).hide();
                }
            });
            $('#resultCount').text(visibleRows);
        }
    });
</script>