<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title> @if($data['report'] =='Variance')
            CONSOLIDATED VARIANCE REPORT
            @else
            CONSOLIDATED VARIANCE SUMMARY REPORT
            @endif</title>

    </head>
    <style>
        body {
            /* font-family: 'Segoe UI', 'Microsoft Sans Serif', sans-serif; */
            font-size: 12px;
            /* line-height: 1.42857143; */
            color: #333;
            background-color: #fff;
        }

        /* html,
        body {
            height: 100%;
        }

        body {
            width: 100%;
        } */

        header:before,
        header:after {
            content: " ";
            display: table;
        }

        header:after {
            clear: both;
        }

        header {
            display: block;
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
            page-break-after: always;
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

        .body1:last-child {
            page-break-after: avoid;
        }

        .body2 {
            margin-top: 5px;
            width: 100%;
            font-size: 11px;
            text-align: center;
            page-break-after: always;
            page-break-inside: always;
        }

        .body2:first-child {
            page-break-before: avoid
        }

        .body2 th {
            border-bottom: 0px solid #000;
            padding: 5px 0px 5px 0px;
            text-align: center;
        }

        .body2 td {
            padding: 2px;
        }

        .body2 tbody tr:last-child {
            page-break-after: avoid;
            page-break-inside: avoid;
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
            page-break-before: avoid;
            page-break-after: avoid;
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
            height: 100px;
            width: 180px;
            image-resolution: 300dpi;
            image-resolution: from-image 300dpi;
        }

        span.span-text {
            position: absolute;
            z-index: 1;
        }

        div {
            display: block;
        }

        table {
            display: table;
            border-collapse: separate;
            box-sizing: border-box;
            text-indent: initial;
            border-spacing: 2px;
            border-color: grey;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        th {
            display: table-cell;
            vertical-align: inherit;
            font-weight: bold;
            text-align: -internal-center;
        }

        td {
            display: table-cell;
            vertical-align: inherit;
        }

        span.span-text {
            position: absolute;
            z-index: 1;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-xs-1,
        .col-sm-1,
        .col-md-1,
        .col-lg-1,
        .col-xs-2,
        .col-sm-2,
        .col-md-2,
        .col-lg-2,
        .col-xs-3,
        .col-sm-3,
        .col-md-3,
        .col-lg-3,
        .col-xs-4,
        .col-sm-4,
        .col-md-4,
        .col-lg-4,
        .col-xs-5,
        .col-sm-5,
        .col-md-5,
        .col-lg-5,
        .col-xs-6,
        .col-sm-6,
        .col-md-6,
        .col-lg-6,
        .col-xs-7,
        .col-sm-7,
        .col-md-7,
        .col-lg-7,
        .col-xs-8,
        .col-sm-8,
        .col-md-8,
        .col-lg-8,
        .col-xs-9,
        .col-sm-9,
        .col-md-9,
        .col-lg-9,
        .col-xs-10,
        .col-sm-10,
        .col-md-10,
        .col-lg-10,
        .col-xs-11,
        .col-sm-11,
        .col-md-11,
        .col-lg-11,
        .col-xs-12,
        .col-sm-12,
        .col-md-12,
        .col-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }


        .col-lg-12 {
            width: 100%;
        }
    </style>

    <body>
        <header>
            <div style="max-width: 100%">
                <div class="row" style="flex-wrap: wrap;">
                    <div
                        style="text-align: left; width: 400px; flex-basis: 0; flex-grow: 1; float: left; margin-bottom: 10px;">
                        <h4>INVENTORY COUNT CONSOLIDATION SYSTEM </h4>
                        @if($data['business_unit'] != 'null')
                        <h4>{{ $data['business_unit']}}</h4>
                        @endif</th>
                        <h4>
                            @if($data['department'] != 'null')
                            {{$data['department']}}
                            @endif
                            @if($data['section'] != 'null')
                            - {{$data['section']}}
                            @endif
                        </h4>
                        <h4>As of {{ $data['date']}}</h4>
                        <h4>Batch Date: {{ $data['date']}}</h4>
                        <h4>Actual Count Date: {{ $data['date']}}</h4>
                        <h4>Count Type: Annual</h4>
                    </div>
                    <div style="width: 1000px; flex-basis: 0; flex-grow: 1; margin-left: 110px;">
                        <div class="title1" style="text-align: center;">
                            @if($data['report'] =='Variance')
                            CONSOLIDATED VARIANCE REPORT
                            @else
                            CONSOLIDATED VARIANCE SUMMARY REPORT
                            @endif
                        </div>
                    </div>
                    <div style="max-width: 100%; flex-basis: 0; flex-grow: 1;"></div>
                </div>
            </div>


        </header>
        {{-- {{dd($data)}} --}}
        @php
        $countSize = count($data['data']);
        @endphp

        @if ($data['report'] =='Variance')

        @foreach ($data['data'] as $vendor_name => $categories)
        @php
        --$countSize;
        $grandTotal = 0;
        $variance = 0;
        $value = 0;
        @endphp
        <div>
            <h4 style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</h4>
        </div>

        @foreach ($categories as $category => $items)
        {{-- {{dd($category)}} --}}
        <div>
            <h4 style="text-align: left; font-size: 12px">Category: {{ $category }}</h4>
        </div>

        <table class="body1">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Item Code
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Barcode
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Description
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Uom
                    </th>
                    <th colspan="2" class="text-center" style="vertical-align: middle;">
                        Quantity
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Variance
                    </th>

                </tr>
                <tr>
                    <th class="text-center" style="vertical-align: middle;">
                        P COUNT APP
                    </th>
                    <th class="text-center" style="vertical-align: middle;">
                        NET NAV SYS COUNT</th>
                    {{-- <th class="text-center" style="vertical-align: middle;">
                        UNPOSTED</th> --}}
                </tr>
            </thead>
            <tbody>

                @foreach ($items as $key => $item)
                {{-- {{dd($item)}} --}}
                @php
                if($item['unposted'] != "-" && $item['nav_qty'] != "-")
                {
                $value = $item['nav_qty'] + $item['unposted'];
                }
                else if ($item['nav_qty'] == '-' && $item['unposted'] != '-'){
                $value = $item['unposted'];
                $variance = $item['unposted'] + $item['total_conv_qty'];
                }
                else if($item['unposted'] == '-' && $item['nav_qty'] != '-'){
                $value = $item['nav_qty'];
                $variance = $item['nav_qty'] + $item['total_conv_qty'];
                }

                if($item['nav_qty'] == '-' && $item['unposted'] == '-'){
                $value = '-';
                $variance = $item['total_conv_qty'];
                }


                // if($item['itemcode'] == '1902') dd($item, $value, $variance);
                // if(!is_numeric($value)) dd($item['itemcode']);
                // if($value == '-'){
                // $variance = $item['total_conv_qty'];
                // }



                if($item['nav_qty'] < 0) { $value==is_numeric($value) ? $variance=$item['total_conv_qty'] + $value :
                    $variance=$item['total_conv_qty']; } else{ if($item['nav_qty'] !='-' ) $value==is_numeric($value) ?
                    $variance=$item['total_conv_qty'] - $value : $variance=$item['total_conv_qty']; } @endphp <tr>
                    <td style="text-align: center;">{{ $item['itemcode'] }}
                    </td>
                    <td style="text-align: center;">{{ $item['barcode'] }}
                    </td>
                    <td style="text-align: left;">{{ $item['extended_desc'] }}</td>
                    <td style="text-align: center;">{{ $item['nav_uom'] ?: 'PCS' }}</td>
                    <td style="text-align: center;">{{ number_format($item['total_conv_qty'], 0) }}</td>
                    <td style="text-align: center;">
                        {{
                        $value == is_numeric($value) ? number_format($value, 0) : $value
                        }}
                    </td>
                    {{-- <td style="text-align: center;">{{ is_numeric($item['unposted']) ?
                        number_format($item['unposted'], 0) : $item['unposted'] }}</td> --}}
                    <td style="text-align: center;">{{ number_format($variance, 0) }}
                    </td>
                    </tr>
                    {{-- {{dd(1)}} --}}
                    {{-- ?php
                    $grandTotal += $variance;
                    ?> --}}
                    @endforeach
                    {{-- <tr>
                        <td colspan="6"
                            style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                            Grand Total >>>
                        </td>
                        <td style="text-align:center; border-bottom-style: none; border-top-style: double;"> {{
                            number_format($grandTotal, 0)}}
                        </td>
                    </tr> --}}
            </tbody>
        </table>
        @endforeach
        {{-- {{dd($data['data'] == 'MFI  RICE MILL');}} --}}
        {{-- @if (count($data['data']) == max($data['data']))
        <div class="page-break"></div>
        @endif --}}
        @endforeach
        @endif

        @if($data['report'] =='Summary')
        <table class="body1">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Item Code
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Barcode
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Description
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Uom
                    </th>
                    <th colspan="2" class="text-center" style="vertical-align: middle;">
                        Quantity
                    </th>
                    <th rowspan="2" class="text-center" style="vertical-align: middle;">
                        Variance
                    </th>

                </tr>
                <tr>
                    <th class="text-center" style="vertical-align: middle;">
                        P COUNT APP
                    </th>
                    <th class="text-center" style="vertical-align: middle;">
                        NET NAV SYS COUNT</th>
                    {{-- <th class="text-center" style="vertical-align: middle;">
                        UNPOSTED</th> --}}
                </tr>
            </thead>
            <tbody>
                <?php  
            $grandTotal = 0;
            $variance = 0;
            $value = 0;
             ?>
                @foreach ($data['data'] as $key => $item)
                {{-- {{dd($item)}} --}}
                @php
                if($item['unposted'] != "-" && $item['nav_qty'] != "-")
                {
                $value = $item['nav_qty'] + $item['unposted'];
                }
                else if ($item['nav_qty'] == '-' && $item['unposted'] != '-'){
                $value = $item['unposted'];
                $variance = $item['unposted'] + $item['total_conv_qty'];
                // if($item['itemcode'] == '1902') dd($variance);
                }
                else if($item['unposted'] == '-' && $item['nav_qty'] != '-'){
                $value = $item['nav_qty'];
                $variance = $item['nav_qty'] + $item['total_conv_qty'];
                }

                // if($item['itemcode'] == '1902') dd($item, $value, $variance);

                if($item['nav_qty'] == '-' && $item['unposted'] == '-'){
                $value = '-';
                $variance = $item['total_conv_qty'];
                }

                if($item['nav_qty'] < 0) { $value==is_numeric($value) ? $variance=$item['total_conv_qty'] + $value :
                    $variance=$item['total_conv_qty']; } else{ if($item['nav_qty'] !='-' ) $value==is_numeric($value) ?
                    $variance=$item['total_conv_qty'] - $value : $variance=$item['total_conv_qty']; //
                    if($item['itemcode']=='1902' ) dd($item, $variance); } @endphp <tr>
                    <td style="text-align: center;">{{ $item['itemcode'] }}
                    </td>
                    <td style="text-align: center;">{{ $item['barcode'] }}
                    </td>
                    <td style="text-align: left;">{{ $item['extended_desc'] }}</td>
                    <td style="text-align: center;">{{ $item['nav_uom'] ?: 'PCS' }}</td>
                    <td style="text-align: center;">{{ number_format($item['total_conv_qty'], 0) }}</td>
                    <td style="text-align: center;">
                        {{
                        $value == is_numeric($value) ? number_format($value, 0) : $value
                        }}
                    </td>
                    {{-- <td style="text-align: center;">{{ is_numeric($item['unposted']) ?
                        number_format($item['unposted'], 0) : $item['unposted'] }}</td> --}}
                    <td style="text-align: center;">{{ number_format($variance, 0) }}
                    </td>
                    </tr>
                    {{-- ?php
                    $grandTotal += $variance;
                    ?> --}}
                    @endforeach
                    {{-- <tr>
                        <td colspan="6"
                            style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                            Grand Total >>>
                        </td>
                        <td style="text-align:center; border-bottom-style: none; border-top-style: double;"> {{
                            number_format($grandTotal, 0)}}
                        </td>
                    </tr> --}}
            </tbody>
        </table>


        @endif
        {{-- {{dd($data)}} --}}
        <table class="body2">
            <thead style="">
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Prepared by:
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">Checked By:</th>
                </tr>
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px; border-bottom: 1px black solid">
                        {{-- {{dd($data);}} --}}
                        {{$data['user']}}
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px; border-bottom: 1px black solid">
                        {{-- Test --}}
                    </th>
                </tr>
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        (Signature over printed name)
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">(Signature over printed name)</th>
                </tr>
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Designation: {{$data['user_position']}}
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Designation:
                    </th>
                </tr>
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Date:
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Date:
                    </th>
                </tr>
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Time:
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Time:
                    </th>
                </tr>
            </thead>
        </table>
        {{-- @if (count($data['data']) > 1) --}}
        @if (count($data['data']) != 0)
        <div class="page-break"></div>
        @endif

        {{-- {{dump( $vendor_name)}} --}}
        {{$data['runDate']}}


        {{-- {{dd($data['data'])}} --}}
    </body>
    <script type="text/php">
        if ( isset($pdf) ) { 
        $pdf->page_script('
            if ($PAGE_COUNT > 1) {
                $date =now()->format("Y-m-d h:i A");
                $font = $fontMetrics->getFont("Helvetica");
                $size = 12;
                $finalPageCount = $PAGE_COUNT -1;
                $pageText = $PAGE_NUM . " of " . ($PAGE_COUNT - 1);
                $y = $pdf->get_height() - 24;
                $x = $pdf->get_width() - 15 - $fontMetrics->getTextWidth($pageText, $font, $size);
                if($PAGE_NUM <= $finalPageCount){
                    $pdf->text(35, 580, "RUN DATE & TIME: " . $date, $font, 7);
                    $pdf->text(500, 580, $pageText, $font, 8);
                }
            } 
        ');
    }
</script>

</html>