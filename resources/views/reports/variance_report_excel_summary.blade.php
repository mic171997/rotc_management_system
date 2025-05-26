<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>
            CONSOlIDATED VARIANCE REPORT
        </title>

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
            /* display: table-cell;
            vertical-align: inherit; */
            display: table-cell;
            text-align: center;
            vertical-align: middle;
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

        .centered-cell {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 200px;
            /* Adjust the height as needed */
            width: 400px;
            /* Adjust the width as needed */
            border: 1px solid #000;
            /* Add a border for visualization */
        }
    </style>
    {{-- {{dd($data)}} --}}

    <body>
        {{-- {{dd(1)}} --}}


        @if($data['type'] == 'Variance Report Summary Unique Itemcode')
        <table>
            <thead>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        INVENTORY COUNT CONSOLIDATION SYSTEM
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        CONSOLIDATED VARIANCE REPORT SUMMARY - UNIQUE ITEMCODE
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
                        Advance Count Date: {{$data['date'][0] . ' - ' . $data['date'][1]}}
                    </th>
                </tr>

                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Actual Count Date: {{ $data['date'][2]. ' - ' .$data['date'][3]}}
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Nav Uploaded Date: {{ $data['date2']}}
                    </th>
                </tr>
                {{--
                <tr>
                    <th style="text-align: left; font-size: 12px;">Item Code : {{ $item['itemcode']}}
                </th>
                </tr> --}}
            </thead>
        </table>

        {{-- <table class="body1">
            <thead>
                <tr>
                    <th style="text-align: center; font-size: 12px;">
                        ITEM CODE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        VARIANT CODE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        DESCRIPTION
                    </th>
                    <th class="text-center" style="text-align: center;">
                        UOM
                    </th>

                    <th class="text-center" style="text-align: center;">
                        P COUNT APP
                    </th>

                    <th class="text-center" style="text-align: center;">
                        NET NAV SYS COUNT
                    </th>

                    <th class="text-center" style="text-align: center;">
                        VARIANCE
                    </th>
                </tr>
            </thead>
        </table> --}}

        <?php 
    $itemcount = 0;
                     $itemcount = count($data['data']['data']);
                         $totalappcount = 0 ;
                         $totalnavcount = 0 ;
                         $totalvariance = 0;
                         $totalvarianceneg = 0 ;
                         $totalvariancepos = 0 ; 
                         $variancecount= 0 ; 
                         $grandTotal = [];
    $grandTotalZeroCount = 0;
    $grandTotalZeroCountneg = 0;
    $grandTotalArray = [];
                         
                         ?>

        <table class="body1" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th style="text-align: center; font-size: 12px;">
                        ITEM CODE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        VARIANT CODE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        DESCRIPTION
                    </th>
                    <th class="text-center" style="text-align: center;">
                        UOM
                    </th>

                    @if ($data['business_unit'] == 'DISTRIBUTION')
                        <th class="text-center" style="text-align: center;">
                            LOT NUMBER
                        </th>
                        <th class="text-center" style="text-align: center;">
                            DATE EXPIRY
                        </th>
                    @endif

                    <th class="text-center" style="text-align: center;">
                        P COUNT APP
                    </th>

                    <th class="text-center" style="text-align: center;">
                        NET NAV SYS COUNT
                    </th>

                    <th class="text-center" style="text-align: center;">
                        VARIANCE
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['data']['data'] as $item)
                @php
                $itemcodecount = $item['grandtotal'];
                $itemTotal = 0;
                @endphp
                @foreach ($item['customerList'] as $key => $datas)
                {{-- {{dd(1)}} --}}
                <tr>
                    @if ($loop->first)
                    <td rowspan=" {{ count($item['customerList']) }}" style="
                     text-align: center;
                     vertical-align: middle;
                    ">
                        {{ $item['itemcode'] }}
                    </td>
                    @endif
                    {{-- <td style="text-align: center;"> {{ $item['itemcode']}}</td> --}}
                    <td style="text-align: center;">{{ $datas['variant_code']}}</td>
                    <td style="text-align: center;">{{ $datas['description']}}</td>
                    <td style="text-align: center;">{{ $datas['uom']}}</td>
                    @if ($data['business_unit'] == 'DISTRIBUTION')
                        <td style="text-align: center;">{{ $datas['uom']}}</td>
                        <td style="text-align: center;">{{ $datas['uom']}}</td>
                    @endif
                    <td style="text-align: center;">{{ number_format($datas['app_qty'],4)}}</td>
                    <td style="text-align: center;">{{ number_format($datas['nav_qty'],4)}}</td>
                    <td style="text-align: center;">{{ number_format($datas['variance'],4)}}</td>
                </tr>




                <?php 
              $grandTotalArray[$item['itemcode']][] = $item['grandtotal'];
                ?>
                @endforeach
                @endforeach

                @foreach ($grandTotalArray as $itemcode => $grandtotals)
                {{-- Check if any grandtotal value for the current itemcode is equal to 0 --}}
                @if (in_array(0 , $grandtotals))
                @php
                $grandTotalZeroCount++;
                @endphp
                @endif

                @if (in_array(0 , $grandtotals) === false)
                @php
                $grandTotalZeroCountneg++;
                @endphp
                @endif
                @endforeach
            </tbody>

            {{-- {{dd($grandTotalZeroCount)}} --}}


        </table>


        <table class="body1">
            <thead>
                <tr></tr>
                <tr>
                    <th class="text-center" style="text-align: center;">
                        TOTAL ITEM CODE (SKU)
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format($itemcount)}}
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="text-align: center;">
                        TOTAL ITEM CODE WITH VARIANCE (SKU)
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format($grandTotalZeroCountneg)}}
                    </th>
                </tr>

                <tr>
                    <th class="text-center" style="text-align: center;">
                        PERCENTAGE (SKU)
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format(( $itemcount - $grandTotalZeroCountneg) / $itemcount * 100,4)}} %
                    </th>
                </tr>

                <tr>
                </tr>
            </thead>
            <tbody>


        </table>
        @endif

        @if($data['type'] == 'Variance Report With Cost')
        <?php 
      
      $grandapptotal = 0 ;
                $grandnavtotal = 0 ;    
                $grandnavtotal1 = 0 ;    
                             ?>
        <table>
            <thead>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        INVENTORY COUNT CONSOLIDATION SYSTEM
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        CONSOLIDATED VARIANCE REPORT WITH COST
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
                        Advance Count Date: {{$data['date'][0] . ' - ' . $data['date'][1]}}
                    </th>
                </tr>

                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Actual Count Date: {{ $data['date'][2]. ' - ' .$data['date'][3]}}
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Nav Uploaded Date: {{ $data['date2']}}
                    </th>
                </tr>
                {{--
                <tr>
                    <th style="text-align: left; font-size: 12px;">Item Code : {{ $item['itemcode']}}
                </th>
                </tr> --}}
            </thead>
        </table>

        <table class="body1" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th style="text-align: center; font-size: 12px;">
                        ITEM CODE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        VARIANT CODE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        DESCRIPTION
                    </th>
                    <th class="text-center" style="text-align: center;">
                        UOM
                    </th>

                    @if ($data['business_unit'] == 'DISTRIBUTION')
                        <th class="text-center" style="text-align: center;">
                            LOT NUMBER
                        </th>
                        <th class="text-center" style="text-align: center;">
                            DATE EXPIRY
                        </th>
                    @endif

                    <th class="text-center" style="text-align: center;">
                        P COUNT APP
                    </th>

                    <th class="text-center" style="text-align: center;">
                        NET NAV SYS COUNT
                    </th>

                    <th class="text-center" style="text-align: center;">
                        VARIANCE
                    </th>
                    <th class="text-center" style="text-align: center;">
                        COST WITHOUT VAT
                    </th>
                    <th class="text-center" style="text-align: center;">
                        P COUNT APP AMOUNT
                    </th>
                    <th class="text-center" style="text-align: center;">
                        NET NAV SYS COUNT AMOUNT
                    </th>

                </tr>
            </thead>
            <tbody>
                {{-- {{dd($data['data'])}} --}}
                @foreach ($data['data']['data'] as $datas)
                {{-- {{dd(1)}} --}}
                <tr>
                    <td style="text-align: center;"> {{ $datas['itemcode'] }}</td>
                    <td style="text-align: center;"> {{ $datas['variant_code'] }}</td>
                    <td style="text-align: left;"> {{ $datas['description'] }}</td>
                    <td style="text-align: center;"> {{ $datas['uom'] }}</td>
                    @if ($data['business_unit'] == 'DISTRIBUTION')
                        <td style="text-align: center;">{{ $datas['uom']}}</td>
                        <td style="text-align: center;">{{ $datas['uom']}}</td>
                    @endif
                    <td style="text-align: center;"> {{ $datas['app_qty'] }}</td>
                    <td style="text-align: center;"> {{ $datas['nav_qty'] }}</td>
                    <td style="text-align: center;"> {{ $datas['variance'] }}</td>
                    <td style="text-align: center;"> {{ $datas['cost_no_vat'] }}</td>
                    <td style="text-align: center;"> {{ $datas['appamount'] }}</td>
                    <td style="text-align: center;"> {{ $datas['navamount'] }}</td>
                </tr>

                <?php 
                $grandapptotal += $datas['appamount'];
                 $grandnavtotal +=  $datas['navamount'];
                  ?>

                {{-- @if ( $datas['navamount'] < 0)  --}}
                <?php
                //  $grandnavtotal1 +=$datas['navamount'];
                 ?>
                {{-- @else --}}
                <?php
                    // $grandnavtotal +=  $datas['navamount'];
                    ?>
                {{-- @endif --}}
                @endforeach

            </tbody>

            {{-- {{dd($grandTotalZeroCount)}} --}}


        </table>

        <table class="body1">
            <thead>
                <tr></tr>
                <tr>
                    <th class="text-center" style="text-align: center;">
                        TOTAL NAVISION AMOUNT
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format($grandnavtotal,4)}}
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="text-align: center;">
                        TOTAL APP AMOUNT
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format($grandapptotal,4)}}
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="text-align: center;">
                        NAV AMOUNT - APP AMOUNT
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format($grandnavtotal - $grandapptotal,4)}}
                    </th>
                </tr>

                <tr>
                    <th class="text-center" style="text-align: center;">
                        PERCENTAGE
                    </th>
                    <th class="text-center" style="text-align: center; font-weight: bold;">
                        {{number_format(( $grandnavtotal - $grandapptotal) / $grandnavtotal * 100 ,4)}} %
                    </th>
                </tr>

                <tr>
                </tr>
            </thead>
            <tbody>


        </table>

        @endif
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

        {{-- {{dd($data)}} --}}

        {{-- @if (count($data['data']) > 1) --}}
        {{-- {{dump( $vendor_name)}} --}}
        {{-- {{$data['runDate']}} --}}


        {{-- {{dd($data['data'])}} --}}
        {{-- {{dd(1)}} --}}

    </body>

</html>