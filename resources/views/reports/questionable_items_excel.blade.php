<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Questionable Items from Count</title>
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
        {{-- {{dd($data)}} --}}
        <table>
            <thead>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        INVENTORY COUNT CONSOLIDATION SYSTEM
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        QUESTIONABLE ITEMS
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        {{ $data['bu']}}
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        {{$data['dept']}}

                        - {{$data['section']}}
                    </th>
                </tr>

                <tr>
                    <th style="text-align: left; font-size: 12px;">
                        Questionable Count Date: {{ $data['date'] }}
                    </th>
                </tr>
            </thead>
        </table>
        <table class="body1">
            <thead>
                <tr>
                    <th class="text-center" style="text-align: center;" width="100%">
                        ITEM CODE
                    </th>
                    <th class="text-center" style="text-align: center;" width="100%">
                        BARCODE
                    </th>
                    <th class="text-center" style="text-align: center;" width="100%">
                        DESCRIPTION
                    </th>
                    <th class="text-center" style="text-align: center;" width="100%">
                        UOM
                    </th>
                    <th class="text-center" style="text-align: center;" width="100%">
                        QTY
                    </th>

                    <th class="text-center" style="text-align: center;" width="100%">
                        DATE ADDED
                    </th>
                    <th class="text-center" style="text-align: center;" width="100%">
                        ADDED BY
                    </th>
                </tr>

            </thead>
            <tbody>
                @foreach ($data['data'] as $key => $items)
                <tr>
                    <td style="text-align: center;" width="100%">{{ $items['itemcode'] }}
                    </td>
                    <td style="text-align: center;" width="100%">{{ $items['barcode'] }}</td>
                    <td style="text-align: left;">{{ $items['description'] }}</td>
                    <td style="text-align: center;" width="100%">
                        {{$items['uom']}}
                    </td>
                    <td style="text-align: center;" width="100%">{{ number_format($items['qty'], 0) }}</td>
                    <td style="text-align: left;" width="100%"> {{date("Y-m-d", strtotime($items['created_at']))}}</td>
                    <td style="text-align: left;" width="100%">{{ $items['name'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
                        Date: {{$data['runDate']}}
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Date:
                    </th>
                </tr>
                <tr>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Time: {{$data['runTime']}}
                    </th>
                    <th width="10%" style="text-align: left; font-size: 12px;"></th>
                    <th width="30%" style="text-align: left; font-size: 12px;">
                        Time:
                    </th>
                </tr>

            </thead>
        </table>
        {{-- {{dd(1)}} --}}
    </body>

</html>