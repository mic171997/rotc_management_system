<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Consolidate Adv Actual Count</title>
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

            div[class^='col-'] {
                display: table-cell;
                padding: 7px;
            }

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
                        Advance Count Date: {{ $advStart . ' - ' . $advEnd}}
                    </th>
                </tr>

                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Actual Count Date: {{ $actualStart . ' - ' .$actualEnd}}
                    </th>
                </tr>
            </thead>
        </table>
        <table class="body1">
            <thead>
                <tr>
                    <th class="text-center" style="text-align: center;">
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
                    {{-- @if ($data['business_unit'] == 'DISTRIBUTION')
                        <th class="text-center" style="text-align: center;">
                            LOT #
                        </th>
                        <th class="text-center" style="text-align: center;">
                            DATE EXPIRY
                        </th>
                    @endif --}}
                    <th class="text-center" style="text-align: center;">
                        ACTUAL COUNT QTY
                    </th>
                    <th class="text-center" style="text-align: center;">
                        ADVANCE COUNT QTY
                    </th>
                    <th class="text-center" style="text-align: center;">
                        QUESTIONABLE QTY
                    </th>
                    <th class="text-center" style="text-align: center;">
                        TOTAL QTY
                    </th>
                </tr>

            </thead>
            <tbody>
                @foreach ($data['data'] as $key => $items)
                <tr>
                    <td style="text-align: center;">{{ $items['itemcode'] }}
                    </td>
                    <td style="text-align: center;">{{ $items['variant_code'] }}</td>
                    <td style="text-align: left;">{{ $items['description'] }}</td>
                    <td style="text-align: center;">
                        @if(isset($items['nav_uom']))
                        {{ $items['nav_uom'] }}
                        @else
                        {{$items['uom']}}
                        @endif
                    </td>
                    {{-- @if ($data['business_unit'] == 'DISTRIBUTION')
                    <td style="text-align: center;">{{ $items['lot_number'] }}</td>
                    <td style="text-align: center;">{{ $items['expiry'] }}</td>
                    @endif --}}
                    <td style="text-align: center;">{{ number_format($items['ActualCountQty'], 0) }}</td>
                    <td style="text-align: center;">{{ number_format($items['AdvCountQty'], 0) }}</td>
                    <td style="text-align: center;">{{ number_format($items['QuestCountQty'], 0) }}</td>
                    <td style="text-align: center;">{{ number_format($items['total'], 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>

</html>