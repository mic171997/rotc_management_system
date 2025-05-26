<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
        <style media="screen">
            body {
                font-family: 'Segoe UI', 'Microsoft Sans Serif', sans-serif;
                font-size: 12px;
            }


            header:before,
            header:after {
                content: " ";
                display: table;
            }

            header:after {
                clear: both;
            }

            .title1 {
                float: center;
                margin: 0 auto;
                font-size: 18px;
                width: 100%;
                font-weight: bold;
                text-align: center;
            }

            .title2 {
                float: center;
                margin: 0 auto;
                width: 100%;
                margin-top: 30px;
                /* font-size: 19px; */
                font-weight: bold;
                text-align: center;
            }

            .from {
                float: left;
            }

            .to {
                float: right;
            }

            .fromto {
                padding-top: 20px;
                width: 600px;
                font-size: 14px;
            }

            .fromto1 {
                width: 180px;
                padding-top: 20px;
                /* font-size: 14px; */
            }

            .head1 {
                /* padding-top: 20px; */
                width: 100%;
                /* font-size: 11px; */
            }

            .head1 th {
                padding: 3px;
                text-align: right;
                /* font-size: 12px; */
            }

            .endt {
                /* padding-top: 10px; */
                width: 100%;
                font-size: 11px;
                text-align: center;
            }

            .body1 {
                margin-top: 10px;
                width: 100%;
                font-size: 11px;
                text-align: center;
            }

            .body1 th {
                border-bottom: 1px solid #000;
                padding: 5px 0px 5px 0px;
                text-align: center;
            }

            .body1 td {
                padding: 8px;
                line-height: 1.42857143;
                border-bottom: 1px solid rgba(0, 0, 0, 0.227);
                border-bottom-style: dashed;
            }

            .body2 {
                margin-top: 5px;
                width: 100%;
                font-size: 11px;
                text-align: center;
            }

            .body2 th {
                border-bottom: 0px solid #000;
                padding: 5px 0px 5px 0px;
                text-align: center;
            }

            .body2 td {
                padding: 2px;
            }

            .fromtocontent {
                margin: 10px;
                margin-right: 15px;
            }

            .panel {
                background-color: #e8e5e5;
                padding: 7px;
            }

            .items {
                clear: both;
                display: table;
                padding: 20px;
            }

            /* Factor out common styles for all of the "col-" classes.*/
            div[class^='col-'] {
                display: table-cell;
                padding: 7px;
            }

            /*for clarity name column styles by the percentage of width */
            .col-1-10 {
                width: 10%;
            }

            .col-1-52 {
                width: 52%;
            }



            .page-break {
                page-break-after: always;
            }

            .page-break:last-child {
                page-break-after: avoid;
                page-break-before: avoid;
            }


            h4 {
                margin: 3px;
            }

            img {
                display: inline-block;
                max-width: 100%;
                height: auto;
                /* vertical-align: middle; */
                /* border: 0; */
            }

            img.img-tabz {
                font-size: 190px;
                z-index: -1;
                position: absolute;
                margin-top: -14px;
            }

            span.span-text {
                position: absolute;
                z-index: 1;
            }

            .text-center {
                text-align: center;
            }
        </style>
    </head>

    <body>

        @foreach ($data['data'] as $rack)

        @php
        $countSize =count($data['data']);
        $startCount = null;
        @endphp

        @php
        --$countSize
        @endphp
        @foreach ($rack as $emp => $audit)
        <table>
            <thead>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        INVENTORY COUNT CONSOLIDATION SYSTEM
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        {{$title}}
                    </th>
                </tr>
                @if($data['business_unit'] != 'null')
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        {{ $data['business_unit']}}
                    </th>
                </tr>
                @endif
                @if($data['department'] != 'null')
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        {{$data['department']}}

                        @if($data['section'] != 'null')
                        - {{$data['section']}}
                        @endif
                    </th>
                </tr>
                @endif

                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        As of {{ $data['date']}}
                    </th>
                </tr>

                <tr>

                    <th style="text-align: left; font-size: 12px;">
                        Actual Count Date: {{ $data['date']}}
                    </th>
                </tr>
                @if($data['countType'] != 'null')
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Count Type: {{$data['countType']}}
                    </th>
                </tr>
                @endif
            </thead>
        </table>
        {{-- {{dd(1)}} --}}
        {{-- @foreach ($audit as $auditor => $vendor)


        @foreach ($vendor as $vendor_name => $categories) --}}
        {{-- <table>
            <tr>
                <th style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</th>
            </tr>
        </table> --}}

        {{-- @foreach ($categories as $category => $items) --}}
        {{-- <table>
            <tr>
                <th style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</th>
            </tr>
            <tr>
                <th style="text-align: left; font-size: 12px">Category: {{ $category }}</th>
            </tr>
        </table> --}}
        @php
        $grandTotal = 0;
        $grandTotalConvQty = 0;
        @endphp
        {{-- @foreach ($category as $cat_name => $items)
        {{dd($items)}}
        @endforeach --}}

        <table class="body1">
            <thead>
                <tr>
                    <th style="text-align: center">
                        ITEM CODE
                    </th>
                    <th style="text-align: center">
                        VARIANT CODE
                    </th>
                    <th style="text-align: center">
                        BARCODE
                    </th>
                    <th style="text-align: center">
                        DESCRIPTION
                    </th>
                    <th style="text-align: center">
                        UOM
                    </th>
                    @if ($data['business_unit'] == 'DISTRIBUTION')
                        <th style="text-align: center">
                            LOT #
                        </th>
                        <th style="text-align: center">
                            DATE EXPIRY
                        </th>
                    @endif
                    <th style="text-align: center">
                        COUNT
                    </th>
                    <th style="text-align: center">
                        SMALLEST SKU
                    </th>
                    <th style="text-align: center">
                        CONV. QTY
                    </th>
                    <th style="text-align: center">
                        RACK
                    </th>
                    <th style="text-align: center">
                        DATE SCANNED
                    </th>
                    {{-- <th class="text-center" style="vertical-align: middle;">
                        Date Expiry
                    </th> --}}
                </tr>

            </thead>
            <tbody>

                @foreach ($audit as $auditor => $vendor)


                @foreach ($vendor as $vendor_name => $categories)
                @foreach ($categories as $category => $items)

                @if(!$data['data'])
                <tr>
                    <td colspan="5" style="text-align: center">No data available.</td>
                </tr>
                @endif

                @php
                $skus =[];
                // $test = 'default';
                @endphp
                @foreach ($items as $key => $item)

                {{-- {{dd(end($items))}} --}}

                @php
                $firstItems = reset($items);
                $lastItems = end($items);

                if(isset($item['nav_uom'])){
                $skus[] = $item['nav_uom'];
                }
                else{
                $skus[] = $item['uom'];
                }

                // if($key === array_key_first($items)){
                // $test = $item['user_signature'];
                // }

                $countStart = date("Y-m-d h:i:s a", strtotime($firstItems['datetime_scanned']));
                $countEnd = date("Y-m-d h:i:s a", strtotime($lastItems['datetime_exported']));
                $timeStartCount = new DateTime($firstItems['datetime_scanned']);
                $timeEndCount = new DateTime($lastItems['datetime_exported']);
                // $timeDiff = $testStart->diff($testEnd);
                $timeDiff = date_diff($timeStartCount, $timeEndCount);
                $countTime = $timeDiff->format("%H:%I:%S");
                $grandTotal += $item['qty'];
                $grandTotalConvQty += $item['total_conv_qty'];
                // $countStart
                @endphp
                <tr>
                    <td style="text-align: left;">{{ $item['itemcode'] }}</td>
                    <td style="text-align: left;">{{ $item['variant_code'] }}</td>
                    <td style="text-align: left;">{{ $item['barcode'] }}</td>
                    <td style="text-align: left;">{{ $item['extended_desc'] }}</td>
                    <td style="text-align: center;">{{ $item['uom'] }}</td>
                    @if ($data['business_unit'] == 'DISTRIBUTION')
                        <td style="text-align: center;">{{ $item['lot_number'] }}</td>
                        <td style="text-align: center;">{{ $item['expiry'] }}</td>
                    @endif
                    <td style="text-align: center;">{{ number_format($item['qty'], 0) }}</td>
                    <td style="text-align: center;">
                        @if(isset($item['nav_uom']))
                        {{ $item['nav_uom']}}

                        @else
                        PCS
                        @endif

                    </td>
                    <td style="text-align: center;">{{ number_format($item['total_conv_qty'], 0) }}</td>
                    <td style="text-align: center;">{{ $item['rack_desc'] }}</td>
                    <td style="text-align: center;">{{ $item['datetime_scanned'] }}</td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
                @endforeach
                {{-- @endforeach --}}

                {{-- <tr>
                    <td colspan="4"
                        style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                        GRAND TOTAL >>>>
                    </td>

                    <td style="text-align:center; border-bottom-style: none; border-top-style: double;">
                        {{ number_format($grandTotal, 0)}}</td>
                    <td style="text-align:center; border-bottom-style: none;">
                    </td>
                    <td style="text-align:center; border-bottom-style: none; border-top-style: double;">
                        {{ number_format($grandTotalConvQty, 0)}}</td>
                </tr> --}}
            </tbody>
        </table>
        {{-- @endforeach --}}
        {{-- {{dd($countStart, $countEnd, join(", ",array_unique($skus)))}} --}}
        <table class="body2">
            <thead style="">
                {{-- {{dd($item)}} --}}
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Prepared by:
                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        <span class="span-text">
                            Store Representative:
                        </span>
                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        <span class="span-text">Verified by:</span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        {{$data['user']}}
                    </th>
                    <th></th>
                    <th style="font-size: 12px;">
                        <span class="span-text">
                            <img src="{{ public_path($items[0]['user_signature'])}}" style="" height="55px"
                                width="80px;" />
                        </span>
                    </th>
                    <th></th>
                    <th style="font-size: 12px;">
                        <img src="{{ public_path($items[0]['audit_signature'])}}" style="" height="55px"
                            width="80px;" />
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Designation:
                        {{-- {{dump($item['user_signature'])}} --}}
                        {{-- <img src="data:image/png;base64,{{ base64_encode($test) }}" /> --}}
                        {{-- <img src="data:image/png;base64,{{base64_encode($test)}}" /> --}}
                        {{-- <img src="data:image/png;base64,{{ base64_decode($image) }}" class="img-tabz" /> --}}
                        {{-- <img src="data:image/png;base64,{{$item[0]['user_signature']}}" /> --}}

                        {{$data['user_position']}}
                        {{-- {{dd$test}} --}}
                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        {{$emp}}

                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        {{$auditor}}

                    </th>
                </tr>
                {{-- {{dd(1)}} --}}
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Date: {{$data['runDate']}}
                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        Designation: {{$item['app_user_position']}}

                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        Designation: {{$item['audit_position']}}

                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Time: {{ $data['runTime']}}
                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        Date: {{date("Y-m-d", strtotime($item['datetime_exported']))}}

                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        Date: {{date("Y-m-d", strtotime($item['datetime_exported']))}}

                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Count Start: {{ $countStart }}
                    </th>
                    <th>
                    </th>
                    <th style="text-align: left; font-size: 12px;">
                        Time: {{date("h:i A", strtotime($item['datetime_exported']))}}
                    </th>
                    <th></th>
                    <th style="text-align: left; font-size: 12px;">
                        Time: {{date("h:i A", strtotime($item['datetime_exported']))}}
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Count End: {{ $countEnd }}
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Count Time: {{$countTime}}
                    </th>
                </tr>
                {{-- <tr>
                    <th style="text-align: left; font-size: 12px;">
                        SKU Total: {{join(", ",array_unique($skus))}}
                    </th>
                </tr> --}}
            </thead>
        </table>
        @endforeach
        @endforeach
        {{-- {{dd(1)}} --}}
        {{-- @endforeach --}}
        {{-- {{dd($timeStartCount, $timeEndCount, $timeDiff, $countTime)}} --}}
        {{-- {{dd($countStart->diff($countEnd))}} --}}
        {{-- {{dd($countStart, $countEnd, join(", ",array_unique($skus)))}} --}}

        {{-- @if (count($data['data']) > 1)
        <div class="page-break"></div>
        @endif --}}

        {{-- @endforeach --}}
        {{-- {{dd(current($items))}} --}}
        {{-- {{dd($data)}} --}}
    </body>

</html>