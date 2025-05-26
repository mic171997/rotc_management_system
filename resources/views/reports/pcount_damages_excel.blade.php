<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Actual Count (APP) Damages</title>
    <style media="screen">
        body {
            font-family: 'Segoe UI', 'Microsoft Sans Serif', sans-serif;
            font-size: 12px;
        }

        /*
            These next two styles are apparently the modern way to clear a float. This allows the logo
            and the word "Invoice" to remain above the From and To sections. Inserting an empty div
            between them with clear:both also works but is bad style.
            Reference:
            http://stackoverflow.com/questions/490184/what-is-the-best-way-to-clear-the-css-style-float
        */
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

        /* 
        .row {
            display: table-row;
            page-break-inside: avoid;
        } */

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
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    INVENTORY COUNT CONSOLIDATION SYSTEM
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



            @if($data['countType'] != 'null')
            <tr>
                <th>Count Type: {{$data['countType']}}</th>
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


    @php
    $countSize =count($data['data']);
    $startCount = null;
    @endphp
    @foreach ($data['data'] as $emp => $audit)
    @php
    --$countSize
    @endphp


    @foreach ($audit as $auditor => $rack_desc)
    {{-- {{dd($rack_desc)}} --}}


    @foreach ($rack_desc as $desc => $items)
    <table>
        <tr>
            <th style="text-align: left; font-size: 12px; font-weight: bold;">
                Location: {{ $desc }}
            </th>
        </tr>
    </table>
    @php
    $grandTotal = 0;
    $grandTotalConvQty = 0;
    @endphp

    <table class="body1">
        <thead>
            <tr>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Item Code
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Barcode
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Description
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Uom
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Count
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Smallest SKU
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Conv. Qty
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Rack
                </th>
                <th class="text-center" style="vertical-align: middle; font-weight: bold;">
                    Date Scanned
                </th>
                {{-- <th class="text-center" style="vertical-align: middle;">
                    Date Expiry
                </th> --}}
            </tr>

        </thead>
        <tbody>

            @if(!$data['data'])
            <tr>
                <td colspan="5" style="text-align: center">No data available.</td>
            </tr>
            @endif

            @php
            $skus =[];
            @endphp
            @foreach ($items as $key => $item)
            {{-- {{dd($items)}} --}}
            {{-- {{dd(end($items))}} --}}

            @php
            $firstItems = reset($items);
            $lastItems = end($items);

            $skus[] = $item['uom'];
            $countStart = date("Y-m-d h:i:s a", strtotime($firstItems['datetime_scanned']));
            $countEnd = date("Y-m-d h:i:s a", strtotime($lastItems['datetime_exported']));
            $timeStartCount = new DateTime($firstItems['datetime_scanned']);
            $timeEndCount = new DateTime($lastItems['datetime_exported']);
            // $timeDiff = $testStart->diff($testEnd);
            $timeDiff = date_diff($timeStartCount, $timeEndCount);
            $countTime = $timeDiff->format("%H:%I:%S");
            $grandTotal += $item['total_qty'];
            $grandTotalConvQty += $item['total_conv_qty'];
            // $countStart
            @endphp
            <tr>
                <td style="text-align: center;">{{ $item['itemcode'] }}
                </td>
                <td style="text-align: center;">{{ $item['barcode'] }}</td>
                <td style="text-align: left;">{{ $item['description'] }}</td>
                <td style="text-align: center;">{{ $item['uom'] }}</td>
                <td style="text-align: center;">{{ number_format($item['total_qty'], 0) }}</td>
                <td style="text-align: center;">
                    {{-- @if ($item['nav_uom'])
                    {{ $item['nav_uom'] }}
                    @else --}}
                    {{ $item['uom'] }}
                    {{-- @endif --}}
                </td>
                <td style="text-align: center;">{{ number_format($item['total_conv_qty'], 0) }}</td>
                <td style="text-align: center;">{{ $item['rack_desc'] }}</td>
                <td style="text-align: center;">{{ $item['datetime_scanned'] }}</td>
                {{-- <td style="text-align: center;">{{ $item['date_expiry'] }}</td> --}}
            </tr>
            @endforeach
            <tr>
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
            </tr>
        </tbody>
    </table>
    {{-- @endforeach --}}
    {{-- {{dd($countStart, $countEnd, join(", ",array_unique($skus)))}} --}}
    <table class="body2">
        <thead style="">
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

                    <br />
                    {{$data['user']}}
                </th>
                <th></th>
                <th style="text-align: left; font-size: 12px;">
                    {{-- <img src="data:image/png;base64,{{$item['app_user_sign']}}" class="img-tabz" /> --}}
                    {{-- <br /> --}}
                    <span class="span-text">{{$emp}}</span>
                </th>
                <th></th>
                <th style="text-align: left; font-size: 12px;">
                    {{-- <img src="data:image/png;base64,{{$item['audit_user_sign']}}" class="img-tabz" /> --}}
                    {{-- <br /> --}}
                    {{$auditor}}
                </th>
            </tr>
            {{-- <tr>
                <th style="text-align: left; font-size: 12px; border-top: 1px black solid;">
                    (Signature over printed name)
                </th>
                <th></th>
                <th style="text-align: left; font-size: 12px; border-top: 1px black solid;">
                    (Signature over printed name)
                </th>
                <th></th>
                <th style="text-align: left; font-size: 12px; border-top: 1px black solid;">
                    (Signature over printed name)
                </th>
            </tr> --}}
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    Designation: {{ $data['user_position']}}
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
                    Date:
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
                    Time:
                </th>
                <th></th>
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
                    Count Start: {{ $countStart }}
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
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    SKU Total: {{join(", ",array_unique($skus))}}
                </th>
            </tr>
        </thead>
    </table>
    @endforeach
    @endforeach
    @endforeach

</html>