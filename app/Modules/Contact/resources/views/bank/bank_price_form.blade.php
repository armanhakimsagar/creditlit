<div id="panel-1" class="panel">
    <div class="panel-hdr">
        <h2>
            <span class="fw-300"><i>@lang('Contact::label.BANK') @lang('Item::label.COUNTRY') @lang('Item::label.WISE') @lang('Item::label.PRICING')</i></span>
        </h2>
        <div class="panel-toolbar">
        </div>
    </div>

    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h3 class="mb-0">
                    <button class="btn btn-block text-left text-success" style="font-size: 14px;" type="button" data-toggle="collapse"
                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        This Change will effect also for these branch. click this arrow to see them:
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <ol>
                        @foreach ($affectedBranch as $item)
                            <li>{{$item->branch_name}}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="panel-container show">
        <div class="panel-content">
            <div class="frame-wrap">
                <div class="table-responsive pricing-add">
                    <input type="text" id="searchInput" placeholder="Search by Country Name">
                    <br>
                    <label for="" class="totalCount mt-2">Total: <span
                            id="resultCount">{{ $totalCountry }}</span></label>
                    <br>
                    <table class="table" id="countryTable">
                        <thead class="thead-themed">
                            <tr>
                                <th width="10%">No</th>
                                <th width="60%">Country Name</th>
                                <th width="30%">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$itemArr->isEmpty())
                                @foreach ($itemArr as $key => $item)
                                    @php
                                        $thisAmount = DB::table('customer_pricing')
                                            ->where('product_id', $item->id)
                                            ->where('customer_id', $id)
                                            ->value('price');
                                        $commonAmount = DB::table('pricing')
                                            ->where('product_id', $item->id)
                                            ->value('price');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            {!! Form::text('price[' . $item->id . ']', $thisAmount ? $thisAmount : $commonAmount, [
                                                'class' => 'form-control only-number-accept-in',
                                                'autocomplete' => 'off',
                                            ]) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="3" class="text-center">
                                    <h2>No Data Found</h2>
                                </td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">

    <div
        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">

        <button class="btn btn-primary generate-button ml-auto waves-effect waves-themed" type="submit">Save</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.dropdown-select2').select2();
    });

    $(document).ready(function() {
        // Trigger the search function whenever a key is released in the search input
        $('#searchInput').keyup(function() {
            searchTable($(this).val());
        });

        // Function to search the table based on the given query
        function searchTable(query) {
            query = query.toLowerCase();
            var visibleRows = 0;

            // Iterate over each table row in the tbody
            $('#countryTable tbody tr').each(function() {
                var name = $(this).find('td:nth-child(2)').text()
                    .toLowerCase();
                var price = $(this).find('td:nth-child(3)').text()
                    .toLowerCase();

                // Check if the name or SID contains the query
                if (name.includes(query) || price.includes(query)) {
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
