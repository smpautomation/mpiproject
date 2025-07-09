<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Page 2 onwards</title>
    <style>
        @page {
            size: A4 landscape;
            margin-top: 5mm;
            margin-left: 15mm;
            margin-right: 15mm;
        }

        body { //reset
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }

        .table-block {
            display: table;
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
            margin-top: 2px;
            margin-left: 2px;
            padding: 0;
        }

        .table-row {
            display: table-row;
        }

        .table-cell {
            display: table-cell;
            font-size: 10px;
            padding: 0 2px;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            border-bottom: 1px solid black;
            display: inline-block;
            min-width: 80px;
        }

        table.print-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            font-size: 8px;
        }

        table.print-table th,
        table.print-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
        }

        table.print-table thead {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

    </style>
</head>
<body>
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <!-- Left Side: Group and Tracer side-by-side, then Date -->
            <td style="width: 33.33%; text-align: left; vertical-align: top;">
                <!-- Group and Tracer -->
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-weight: bold; font-size: 12px; width: 50%;"><i>Group:</i></td>
                        <td style="font-weight: bold; font-size: 12px; width: 50%;"><i>Tracer:</i></td>
                    </tr>
                </table>

                <!-- Date -->
                <p style="margin: 0; font-weight: bold; padding-top: 5px; font-size: 12px;"><i>Date:</i></p>
            </td>

            <!-- Center: Property Data -->
            <td style="width: 33.33%; text-align: center; vertical-align: top;">
                <h1 style="margin: 0; font-size: 24px;"><i>Property Data</i></h1>
            </td>

            <!-- Right Side: Optional future use or keep empty -->
            <td style="width: 33.33%;"></td>
        </tr>
    </table>
    <!-- Thick Horizontal Line -->
    <div style="height: 1px; border-bottom: 3px solid black; margin-top: 5px; margin-bottom: 5px;"></div>

    <table style="width: 100%; border-collapse: collapse;">
        <!-- Row 1 -->
        <tr>
            <td style="font-weight: bold; font-size: 10px;"><i>Code:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Type Code:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Judge Code:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Press #:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Singering Furnace #:</i></td>
        </tr>

        <!-- Row 2 -->
        <tr>
            <td style="font-weight: bold; font-size: 10px;"><i>Sintering #:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Coating:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Pass #:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Mias. Employee:</i></td>
            <td style="font-weight: bold; font-size: 10px;"><i>Factor Employee:</i></td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Zone:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Br:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>iHc:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>iHk:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>[BH]m:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Hr95:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Hr98:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>iHc-iHk:</i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center;"><i>Br-4&#960;Ia:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>bHc:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Squareness:</i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center;"><i>4&#960;Id:</i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center;"><i>4&#960;Is:</i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center;"><i>4&#960;Ia:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Temperature:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center;"><i>Data Status:</i></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center;">test</td>
            </tr>
        </tbody>
    </table>

    <!-- Thick Horizontal Line -->
    <div style="height: 1px; border-bottom: 3px solid black; margin-top: 5px; margin-bottom: 5px;"></div>

    <div class="page-break"></div>

    <div>
        GRAPH HERE
    </div>


</body>
</html>