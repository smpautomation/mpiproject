<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Page 1</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 7mm;
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
            font-size: 8px;
        }

        .table-row {
            display: table-row;
        }

        .table-cell {
            display: table-cell;
            font-size: 8px;
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
            font-size: 7px;
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

    </style>
</head>
<body>
    <div style="float:left; width:25%;">
        <img src="{{ public_path('photo/shinetsu.png') }}" alt="Logo" style="width:130px; height:20px; padding: 0px; margin: 0px;" />
        <p style="font-size: 9px; padding-top: 3px; margin: 0px;">SHINETSU MAGNETICS PHILS., INC.</p>
    </div>

    <div style="float:right; width:25%; text-align: right;">
        <img src="{{ public_path('photo/carmark_logo.png') }}" alt="Logo" style="display: block; width:80px; height:40px; padding: 0px; margin: 0px;" />
    </div>

    <div style="clear:both; padding: 0px; margin: 0px;"></div> <!-- Break Line -->

    <div class="table-block">
        <div class="table-row">
            <div class="table-cell" style="font-weight: bold;">Model: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold; font-size: 9px;">{{ $tpmCat->actual_model }}</span></div>
            <div class="table-cell" style="font-weight: bold;">Partial No: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold;">{{ $reportData->partial_number }}</span></div>
        </div>
        <div class="table-row">
            <div class="table-cell" style="font-weight: bold;">MATERIAL CODE: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold;">{{ $reportData->material_code }}</span></div>
            <div class="table-cell" style="font-weight: bold;">TOTAL QUANTITY: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold;">{{ $reportData->total_quantity }}</span></div>
        </div>
    </div>

    <div style="clear:both; padding: 0px; margin: 0px;"></div> <!-- Break Line -->

    <div style="margin-bottom: 0px; padding: 0px; margin: 0px;">
        <p style="white-space: nowrap; overflow: visible; background-color: #ccc; border: 1px solid black; text-align: center; font-weight: bold; font-size:12px;">
            GBDP MAGNETIC PROPERTY INSPECTION REPORT
        </p>
    </div>

    <div style="clear:both; margin: 0px; padding: 0px;"></div> <!-- Break Line -->

    <div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
        <label class="bold" style="font-size: 10px; padding-left: 1px;">COATING INFORMATION</label>

        <div class="table-block">
            <div class="table-row">
                <div class="table-cell">Coating Date:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->date ?? 'NA' }}</span></div>
                <div class="table-cell">Coating Machine No:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->machine_no ?? 'NA' }}</span></div>
                <div class="table-cell">Slurry Lot No:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->slurry_lot_no ?? 'NA' }}</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell">MIN. TB CONTENT:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->min_tb_content ?? 'NA' }}</span></div>
                <div class="table-cell">Sample Quantity:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->sample_qty ?? 'NA' }}</span></div>
                <div class="table-cell">Total Magnet Weight:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->total_magnet_weight ?? 'NA' }}</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell">Loader Operator:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->loader_operator ?? 'NA' }}</span></div>
                <div class="table-cell">Unloader Operator:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->unloader_operator ?? 'NA' }}</span></div>
                <div class="table-cell">Checker Operator:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->checker_operator ?? 'NA' }}</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell bold" style="font-size: 9px;">COATING DATA:</div>
                <div class="table-cell"></div>
                <div class="table-cell">Time Start:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->time_start ?? 'NA' }}</span></div>
                <div class="table-cell">Time Finished:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $coatingData->time_finish ?? 'NA' }}</span></div>
            </div>

            <div class="table-row">
                <p class="table-cell" style="font-size:7px;">UNIT    :    (µ g/mm2)</p>
            </div>
        </div> <!-- End of Coating Info -->

        <div class="table-block">
            <div class="table-row">
                <div class="table-cell">
                    <!-- 1st table -->
                    <table class="print-table" style="margin-top:1px;">
                        <thead>
                            <tr>
                                <th>Lot No.</th>
                                <th>No.</th>
                                <th>Coating</th>
                                <th colspan="2">Concentration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>1</td>
                                <td>{{ $coatingDetails['1_M01_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['1_M01_Concentration'] ?? null }}</td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>2</td>
                                <td>{{ $coatingDetails['2_M02_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['2_M02_Concentration'] ?? null }}</td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>3</td>
                                <td>{{ $coatingDetails['3_M03_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['3_M03_Concentration'] ?? null }}</td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>4</td>
                                <td>{{ $coatingDetails['4_M04_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['4_M04_Concentration'] ?? null }}</td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>5</td>
                                <td>{{ $coatingDetails['5_M05_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['5_M05_Concentration'] ?? null }}</td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['1to5_M06_1_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['1to5_M06_2_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>6</td>
                                <td>{{ $coatingDetails['6_M01_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['6_M01_Concentration'] ?? null }}</td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>7</td>
                                <td>{{ $coatingDetails['7_M02_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['7_M02_Concentration'] ?? null }}</td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>8</td>
                                <td>{{ $coatingDetails['8_M03_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['8_M03_Concentration'] ?? null }}</td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>9</td>
                                <td>{{ $coatingDetails['9_M04_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['9_M04_Concentration'] ?? null }}</td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>10</td>
                                <td>{{ $coatingDetails['10_M05_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['10_M05_Concentration'] ?? null }}</td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['6to10_M06_3_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['6to10_M06_4_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="table-cell">
                    <!-- 2nd table -->
                    <table class="print-table" style="margin-top:1px;">
                        <thead>
                            <tr>
                                <th>Lot No.</th>
                                <th>No.</th>
                                <th>Coating</th>
                                <th colspan="2">Concentration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>11</td>
                                <td>{{ $coatingDetails['11_M01_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['11_M01_Concentration'] ?? null }}</td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>12</td>
                                <td>{{ $coatingDetails['12_M02_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['12_M02_Concentration'] ?? null }}</td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>13</td>
                                <td>{{ $coatingDetails['13_M03_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['13_M03_Concentration'] ?? null }}</td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>14</td>
                                <td>{{ $coatingDetails['14_M04_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['14_M04_Concentration'] ?? null }}</td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>15</td>
                                <td>{{ $coatingDetails['15_M05_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['15_M05_Concentration'] ?? null }}</td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['11to15_M06_5_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['11to15_M06_6_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>16</td>
                                <td>{{ $coatingDetails['16_M01_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['16_M01_Concentration'] ?? null }}</td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>17</td>
                                <td>{{ $coatingDetails['17_M02_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['17_M02_Concentration'] ?? null }}</td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>18</td>
                                <td>{{ $coatingDetails['18_M03_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['18_M03_Concentration'] ?? null }}</td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>19</td>
                                <td>{{ $coatingDetails['19_M04_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['19_M04_Concentration'] ?? null }}</td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>20</td>
                                <td>{{ $coatingDetails['20_M05_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['20_M05_Concentration'] ?? null }}</td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['16to20_M06_7_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['16to20_M06_8_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-cell">
                    <!-- 3rd table -->
                    <table class="print-table" style="margin-top:1px;">
                        <thead>
                            <tr>
                                <th>Lot No.</th>
                                <th>No.</th>
                                <th>Coating</th>
                                <th colspan="2">Concentration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>21</td>
                                <td>{{ $coatingDetails['21_M01_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['21_M01_Concentration'] ?? null }}</td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>22</td>
                                <td>{{ $coatingDetails['22_M02_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['22_M02_Concentration'] ?? null }}</td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>23</td>
                                <td>{{ $coatingDetails['23_M03_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['23_M03_Concentration'] ?? null }}</td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>24</td>
                                <td>{{ $coatingDetails['24_M04_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['24_M04_Concentration'] ?? null }}</td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>25</td>
                                <td>{{ $coatingDetails['25_M05_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['25_M05_Concentration'] ?? null }}</td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['21to25_M06_9_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['21to25_M06_10_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>26</td>
                                <td>{{ $coatingDetails['26_M01_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['26_M01_Concentration'] ?? null }}</td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>27</td>
                                <td>{{ $coatingDetails['27_M02_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['27_M02_Concentration'] ?? null }}</td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>28</td>
                                <td>{{ $coatingDetails['28_M03_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['28_M03_Concentration'] ?? null }}</td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>29</td>
                                <td>{{ $coatingDetails['29_M04_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['29_M04_Concentration'] ?? null }}</td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td>{{ $tpmCat->jhcurve_lotno }}</td>
                                <td>30</td>
                                <td>{{ $coatingDetails['30_M05_Amount'] ?? null }}</td>
                                <td>{{ $coatingDetails['30_M05_Concentration'] ?? null }}</td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['26to30_M06_11_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td>{{ $coatingDetails['26to30_M06_12_Concentration'] ?? null }}</td>
                                <td>M-06</td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- END of 3rd table -->

                <div class="table-cell">
                    <!-- 4rd table -->
                    <table class="print-table" style="margin-top:1px;">
                        <thead>
                            <tr>
                                <th rowspan="2">MODULE</th>
                                <th colspan="3">ADDITIONAL SLURRY</th>
                                <th rowspan="2">LITERS</th>
                            </tr>
                            <tr>
                                <th>NEW</th>
                                <th>HOMO</th>
                                <th>TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>M-01</td>
                                <td>{{ $coatingDetails['add_slurry_m-01_new'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-01_homo'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-01_time'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-01_liters'] }}</td>
                            </tr>
                            <tr>
                                <td>M-02</td>
                                <td>{{ $coatingDetails['add_slurry_m-02_new'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-02_homo'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-02_time'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-02_liters'] }}</td>
                            </tr>
                            <tr>
                                <td>M-03</td>
                                <td>{{ $coatingDetails['add_slurry_m-03_new'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-03_homo'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-03_time'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-03_liters'] }}</td>
                            </tr>
                            <tr>
                                <td>M-04</td>
                                <td>{{ $coatingDetails['add_slurry_m-04_new'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-04_homo'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-04_time'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-04_liters'] }}</td>
                            </tr>
                            <tr>
                                <td>M-05</td>
                                <td>{{ $coatingDetails['add_slurry_m-05_new'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-05_homo'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-05_time'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-05_liters'] }}</td>
                            </tr>
                            <tr>
                                <td>M-06</td>
                                <td>{{ $coatingDetails['add_slurry_m-06_new'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-06_homo'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-06_time'] }}</td>
                                <td>{{ $coatingDetails['add_slurry_m-06_liters'] }}</td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- End of 4th table -->
            </div>
        </div>

        <div style="display: table; margin: 0 auto; border-spacing: 10px; padding: 0px;">
            <div style="display: table-row;">
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 7px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">MAXIMUM:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">{{ $coatingData->maximum }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">MINIMUM:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">{{ $coatingData->minimum }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">AVERAGE:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">{{ $coatingData->average }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
            <tr>
                <td style="width: 50px; vertical-align: top; font-weight: bold;">Remarks:</td>
                <td style="width: 100%;">
                    <div style="border-bottom: 1px solid black; height: 9px; line-height: 9px;">
                        <span style="display: inline-block; margin-bottom: -1px;">
                            {{ $coatingData->remarks }}
                        </span>
                    </div>
                    <div style="border-bottom: 1px solid black; height: 9px; margin-bottom: 5px;"></div>
                </td>
            </tr>
        </table>

    </div>  <!-- End of border -->

<div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
    <label class="bold" style="font-size: 10px; padding-left: 1px;">HEAT TREATMENT INFORMATION</label>

    <div class="table-block">
        <div class="table-row">

            <!-- Column 1: Labels -->
            <div class="table" style="display: table; vertical-align: top; width: 100%; table-layout: fixed; font-size: 4px; line-height: 1; padding-right: 10px;">

                @php
                    $rows = [
                        ['Furnace Machine:', $heatTreatmentData->furnace_machine ?? null],
                        ['CYCLE No:', $heatTreatmentData->cycle_no ?? null],
                        ['BATCH CYCLE No:', $heatTreatmentData->batch_cycle_no ?? null],
                        ['PATTERN No:', $heatTreatmentData->pattern_no ?? null],
                        ['DATE START:', $heatTreatmentData->date_start ?? 'NA'],
                        ['TIME START:', $heatTreatmentData->time_start ?? 'NA'],
                        ['LOADER:', $heatTreatmentData->loader ?? null],
                        ['DATE FINISH:', $heatTreatmentData->date_finish ?? 'NA'],
                        ['TIME FINISH:', $heatTreatmentData->time_finish ?? 'NA'],
                        ['UNLOADER:', $heatTreatmentData->unloader ?? null],
                        ['Cycle Pattern:', $heatTreatmentData->cycle_pattern ?? null],
                        ['Current Pattern:', $heatTreatmentData->current_pattern ?? null],
                    ];
                @endphp

                @foreach($rows as [$label, $value])
                    <div class="table-row" style="display: table-row; vertical-align: top;">
                        <!-- Label -->
                        <div class="table-cell" style="display: table-cell; width: 40%; padding-right: 2px; height: 12px; line-height: 12px; vertical-align: top;">
                            {{ $label }}
                        </div>
                        <!-- Value -->
                        <div class="table-cell" style="display: table-cell; width: 60%; height: 12px; line-height: 12px; vertical-align: top; border-bottom: 1px solid black;">
                            {{ $value }}
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- spacer -->
            <div style="vertical-align: top; width: 25%;">&nbsp;</div>

            <!-- Column 3: Table -->
            <div class="table-cell" style="vertical-align: top; width: 60%; padding-right:5px;">
                 <table class="print-table">Add commentMore actions
                        <thead>
                            <tr>
                                <th colspan="12">MAGNET BOX LOCATION</th>
                            </tr>
                            <tr>
                                <th colspan="2">BOX No.</th>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                                <th>E</th>
                                <th>F</th>
                                <th>G</th>
                                <th>H</th>
                                <th>J</th>
                                <th>K</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="margin: 0px; padding: 0px;">No. of Layer</td>
                                <td>{{ $tpmData->layer_no }}</td>
                                <td>{{ $magnetBoxLocation['box_a']}}</td>
                                <td>{{ $magnetBoxLocation['box_b'] }}</td>
                                <td>{{ $magnetBoxLocation['box_c'] }}</td>
                                <td>{{ $magnetBoxLocation['box_d'] }}</td>
                                <td>{{ $magnetBoxLocation['box_e'] }}</td>
                                <td>{{ $magnetBoxLocation['box_f'] }}</td>
                                <td>{{ $magnetBoxLocation['box_g'] }}</td>
                                <td>{{ $magnetBoxLocation['box_h'] }}</td>
                                <td>{{ $magnetBoxLocation['box_j'] }}</td>
                                <td>{{ $magnetBoxLocation['box_k'] }}</td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
        <tr>
            <td style="width: 50px; vertical-align: top; font-weight: bold;">Remarks:</td>
            <td style="width: 100%;">
                <div style="border-bottom: 1px solid black; height: 9px; line-height: 9px;">
                    <span style="display: inline-block; margin-bottom: -1px;">
                        {{ $heatTreatmentData->remarks }}
                    </span>
                </div>
                <div style="border-bottom: 1px solid black; height: 9px; margin-bottom: 5px;"></div>
            </td>
        </tr>
    </table>
</div> <!-- END of Border -->

<div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
    <label class="bold" style="font-size: 10px; padding-left: 1px;">OVEN HEATING INFORMATION</label>
    <div class="table-block">
        <div class="table-row">
            <div class="table-cell">Oven Machine No:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->oven_machine_no ?? 'NA' }}</span></div>
            <div class="table-cell">Date:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->date_oven_info ?? 'NA' }}</span></div>
            <div class="table-cell">Shift:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->shift_oven_info ?? 'NA' }}</span></div>
        </div>

        <div class="table-row">
            <div class="table-cell">Time Loading:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->time_loading ?? 'NA' }}</span></div>
            <div class="table-cell">Time Unloading:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->time_unloading ?? 'NA' }}</span></div>
            <div class="table-cell">Operator:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->operator_oven_info ?? 'NA' }}</span></div>
        </div>

        <div class="table-row">
            <div class="table-cell">Temperature:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->temp_time_loading ?? 'NA' }}</span></div>
            <div class="table-cell">Temperature:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->temp_time_unloading ?? 'NA' }}</span></div>
        </div>
    </div> <!-- End of Oven Heating Info -->
    <label class="bold" style="font-size: 10px; padding-left: 1px;">MAGNETIC PROPERTY INSPECTION INFORMATION</label>
    <div class="table-block">
        <div class="table-row">
            <div class="table-cell">Pulse Tracer Machine No:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->pulse_tracer_machine_number ?? 'NA' }}</span></div>
            <div class="table-cell">Date:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->date ?? 'NA' }}</span></div>
            <div class="table-cell">Shift:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->shift ?? 'NA' }}</span></div>
            <div class="table-cell">Operator:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">{{ $reportData->operator ?? 'NA' }}</span></div>
        </div>
    </div>
    <div class="table-block">
        <div class="table-row">
            <div class="table-cell">
                <table class="print-table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                STANDARD SAMPLE DIMENSION <br> and Material Grade
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                LENGTH (mm)
                            </td>
                            <td>
                                {{ $reportData->length ?? 'NA' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                WIDTH (mm)
                            </td>
                            <td>
                                {{ $reportData->width ?? 'NA' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                THICKNESS (mm)
                            </td>
                            <td>
                                {{ $reportData->thickness ?? 'NA' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                MATERIAL GRADE
                            </td>
                            <td>
                                {{ $reportData->material_grade ?? 'NA' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                MPI Sample Qty.
                            </td>
                            <td>
                                {{ $reportData->mpi_sample_quantity ?? 'NA' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-cell">
                <table class="print-table">
                    <thead>
                        <tr>
                            <th colspan="6">
                                MAGNETIC PROPERTY TABLE
                            </th>
                        </tr>
                        <tr>
                            <th rowspan="2">
                                ITEMS
                            </th>
                            <th rowspan="2">
                                STANDARD
                            </th>
                            <th colspan="4">ACTUAL DATA</th>
                        </tr>
                        <tr>
                            <th>AVERAGE</th>
                            <th>MAXIMUM</th>
                            <th>MINIMUM</th>
                            <th>Variance<br>(max-min)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Br (G)
                            </td>
                            <td>{{ $brBounds['lower'] ?? 'NA' }} ~ {{ $brBounds['upper'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['brAverage'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['brMaximum'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['brMinimum'] ?? 'NA' }}</td>
                            <td>{{ $brVariance ?? 'NA' }}</td>
                        </tr>
                        <tr>
                            <td>
                                iHc (Oe)
                            </td>
                            <td>{{ $magneticProperty['ihcStandard'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihcAverage'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihcMaximum'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihcMinimum'] ?? 'NA' }}</td>
                            <td>{{ $ihcVariance ?? 'NA' }}</td>
                        </tr>
                        <tr>
                            <td>
                                iHk (Oe)
                            </td>
                            <td>{{ $magneticProperty['ihkStandard'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihkAverage'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihkMaximum'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihkMinimum'] ?? 'NA' }}</td>
                            <td>{{ $ihkVariance ?? 'NA' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="table-block">
        <div class="table-row">
            <div class="table-cell" style="width: 95%;">
                <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
                    <tr>
                        <td style="width: 50px; vertical-align: top; font-weight: bold;">Remarks:</td>
                        <td style="width: 100%;">
                            <div style="border-bottom: 1px solid black; height: 9px; line-height: 9px;">
                                <span style="display: inline-block; margin-bottom: -1px;">
                                    {{ $reportData->remarks2 }}, {{ $reportData->remarks3 }}
                                </span>
                            </div>
                            <div style="border-bottom: 1px solid black; height: 9px; margin-bottom: 5px; color: #0066ff; line-height: 9px;">
                                <span style="display: inline-block; margin-bottom: -1px;">
                                    {{ $reportData->remarks }}
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-cell" style="width: 5%;">
                <p style="text-align: center;">E</p>
            </div>
        </div>
    </div>
</div>
<div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
    <label class="bold" style="font-size: 11px; padding-left: 1px;">SMP Judgment:</label>
</div>
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <!-- Left Section -->
        <td style="width: 65%; padding: 1px; border: 1px solid black;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 5px;">
                <tr>

                    <!-- Spacer -->
                    <td style="width: 4%;"></td>

                    @php
                        $judgement = strtoupper(trim($reportData->smp_judgement));
                        $stampPath = match ($judgement) {
                            'REJECT' => public_path('photo/reject_stamp.png'),
                            'HOLD'   => public_path('photo/hold_stamp.png'),
                            '', null, 'NULL' => public_path('photo/template.png'),
                            default   => public_path('photo/pass_stamp.png'),
                        };
                    @endphp
                    
                    <td style="width: 18%; padding: 0;">
                        <table style="width: 100%; border: 1px solid black; text-align: center; border-collapse: collapse;">
                            <thead style="border-bottom: 1px solid black; font-size: 9px;">
                                <tr>
                                    <th style="padding: 2px;">SMP Judgment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="height: 50px; vertical-align: middle; padding: 0;">
                                        <img src="{{ $stampPath }}"
                                            style="height: 60px; margin-top: -5px; margin-bottom: -5px;"
                                            alt="Stamp">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Spacer -->
                    <td style="width: 2%;"></td>

                    <td style="width: 18%; vertical-align: top;">
                        <table style="border: 1px solid black; width: 120%; text-align: center; border-collapse: collapse;">
                            <thead style="border-bottom: 1px solid black; font-size: 10px;">
                                <tr>
                                    <th style="padding: 2px;">Prepared By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- Add horizontal padding inside cell --}}
                                    <td style="height: 50px; vertical-align: middle; padding: 0 5px; position: relative;">
                                        @php
                                            $hasPreparedName = !empty($reportData->prepared_by_firstname) || !empty($reportData->prepared_by_surname);
                                        @endphp

                                        @if ($hasPreparedName)
                                            <div style="position: relative; width: 100%; height: 60px;">
                                                {{-- Adjusted template position --}}
                                                <img src="{{ public_path('photo/template.png') }}"
                                                    style="position: absolute; top: -6px; left: 0; width: 100%; height: 70px;"
                                                    alt="Stamp Overlay">

                                                <div style="
                                                    position: relative;
                                                    font-weight: bold;
                                                    color: #dc2626;
                                                    font-size: 13px;
                                                    line-height: 1.2;
                                                    text-align: center;
                                                ">
                                                    @if (!empty($reportData->prepared_by_firstname))
                                                        <div style="font-size: {{ $preparedFirstFontSize }}; margin-bottom: 2px;">
                                                            {{ $reportData->prepared_by_firstname }}
                                                        </div>
                                                    @endif
                                                    @if (!empty($reportData->prepared_by_date))
                                                        <div style="margin: 4px 0;">{{ $preparedByDate }}</div>
                                                    @endif
                                                    @if (!empty($reportData->prepared_by_surname))
                                                        <div style="font-size: {{ $preparedLastFontSize }};">
                                                            {{ $reportData->prepared_by_surname }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ public_path('photo/template.png') }}"
                                                style="height: 60px; margin: -5px 3px;"
                                                alt="Prepared By Stamp">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>





                    <!-- Spacer -->
                    <td style="width: 4%;"></td>

                    <td style="width: 18%; vertical-align: top;">
                        <table style="border: 1px solid black; width: 120%; text-align: center; border-collapse: collapse;">
                            <thead style="border-bottom: 1px solid black; font-size: 10px;">
                                <tr>
                                    <th style="padding: 2px;">Checked By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- Add horizontal padding inside cell --}}
                                    <td style="height: 50px; vertical-align: middle; padding: 0 5px; position: relative;">
                                        @php
                                            $hasCheckedName = !empty($reportData->checked_by_firstname) || !empty($reportData->checked_by_surname);
                                        @endphp

                                        @if ($hasCheckedName)
                                            <div style="position: relative; width: 100%; height: 60px;">
                                                {{-- Adjusted template position --}}
                                                <img src="{{ public_path('photo/template.png') }}"
                                                    style="position: absolute; top: -6px; left: 0; width: 100%; height: 70px;"
                                                    alt="Stamp Overlay">

                                                <div style="
                                                    position: relative;
                                                    font-weight: bold;
                                                    color: #dc2626;
                                                    font-size: 13px;
                                                    line-height: 1.2;
                                                    text-align: center;
                                                ">
                                                    @if (!empty($reportData->checked_by_firstname))
                                                        <div style="font-size: {{ $checkedFirstFontSize }}; margin-bottom: 2px;">
                                                            {{ $reportData->checked_by_firstname }}
                                                        </div>
                                                    @endif
                                                    @if (!empty($reportData->checked_by_date))
                                                        <div style="margin: 4px 0;">{{ $checkedByDate }}</div>
                                                    @endif
                                                    @if (!empty($reportData->checked_by_surname))
                                                        <div style="font-size: {{ $checkedLastFontSize }};">
                                                            {{ $reportData->checked_by_surname }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ public_path('photo/template.png') }}"
                                                style="height: 60px; margin: -5px 3px;"
                                                alt="Checked By Stamp">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Spacer -->
                    <td style="width: 4%;"></td>

                    <td style="width: 18%; vertical-align: top;">
                        <table style="border: 1px solid black; width: 120%; text-align: center; border-collapse: collapse;">
                            <thead style="border-bottom: 1px solid black; font-size: 10px;">
                                <tr>
                                    <th style="padding: 2px;">Approved By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- Add horizontal padding inside cell --}}
                                    <td style="height: 50px; vertical-align: middle; padding: 0 5px; position: relative;">
                                        @php
                                            $hasApprovedName = !empty($reportData->approved_by_firstname) || !empty($reportData->approved_by_surname);
                                        @endphp

                                        @if ($hasApprovedName)
                                            <div style="position: relative; width: 100%; height: 60px;">
                                                {{-- Adjusted template position --}}
                                                <img src="{{ public_path('photo/template.png') }}"
                                                    style="position: absolute; top: -6px; left: 0; width: 100%; height: 70px;"
                                                    alt="Stamp Overlay">

                                                <div style="
                                                    position: relative;
                                                    font-weight: bold;
                                                    color: #dc2626;
                                                    font-size: 13px;
                                                    line-height: 1.2;
                                                    text-align: center;
                                                ">
                                                    @if (!empty($reportData->approved_by_firstname))
                                                        <div style="font-size: {{ $approvedFirstFontSize }}; margin-bottom: 2px;">
                                                            {{ $reportData->approved_by_firstname }}
                                                        </div>
                                                    @endif
                                                    @if (!empty($reportData->approved_by_date))
                                                        <div style="margin: 4px 0;">{{ $approvedByDate }}</div>
                                                    @endif
                                                    @if (!empty($reportData->approved_by_surname))
                                                        <div style="font-size: {{ $approvedLastFontSize }};">
                                                            {{ $reportData->approved_by_surname }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ public_path('photo/template.png') }}"
                                                style="height: 60px; margin: -5px 3px;"
                                                alt="Checked By Stamp">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Spacer -->
                    <td style="width: 6%;"></td>

                </tr>
            </table>
        </td>
        
        <!-- Right Section -->
        <td style="width: 35%; padding: 1px; border: 1px solid black; vertical-align: top;">
            <div style="font-size:9px; font-weight: bold; padding-bottom: 2px; padding-left: 4px;">
                Note: (Reason of REJECT/HOLD)
            </div>

            @if (!empty($noteReasonsSorted))
                <div style="font-size: 9px; font-weight: bold; color: #dc2626; margin-top: 3px; padding-left: 4px;">
                    @foreach ($noteReasonsSorted as $reason)
                        <div>{{ $reason }}</div>
                    @endforeach
                </div>
            @else
                <span style="font-size: 9px; color: #999;">No rejection notes available.</span>
            @endif
        </td>


    </tr>
</table>
<div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 40%; font-size: 11px; padding: 2px;">
                <strong>SEC / C - MARK Judgement:</strong>
            </td>
            <td style="width: 60%; font-size: 6px; padding: 2px; border-left: 1px solid black; vertical-align: top;">
                Note: (Reason of REJECTED / Content of Condition Approved)
            </td>
        </tr>
        <tr>
            <td style="height: 40px; border-top: 1px solid black;"></td>
            <td style="height: 40px; border-left: 1px solid black;"></td>
        </tr>
    </table>
</div>

</body>
