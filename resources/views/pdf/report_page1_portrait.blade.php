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

    <div style="clear:both;"></div> <!-- Break Line -->

    <div class="table-block">
        <div class="table-row">
            <div class="table-cell" style="font-weight: bold;">Model: </div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell" style="font-weight: bold;">Partial No: </div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
        </div>
        <div class="table-row">
            <div class="table-cell" style="font-weight: bold;">MATERIAL CODE: </div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell" style="font-weight: bold;">TOTAL QUANTITY: </div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
        </div>
    </div>

    <div style="clear:both;"></div> <!-- Break Line -->

    <div style="margin-bottom: 0px;">
        <p style="white-space: nowrap; overflow: visible; background-color: #ccc; border: 1px solid black; text-align: center; font-weight: bold; font-size:12px;">
            GBDP MAGNETIC PROPERTY INSPECTION REPORT
        </p>
    </div>

    <div style="clear:both; margin: 0px; padding: 0px;"></div> <!-- Break Line -->

    <div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
        <label class="bold" style="font-size: 12px; padding-left: 1px;">COATING INFORMATION</label>

        <div class="table-block">
            <div class="table-row">
                <div class="table-cell">Coating Date:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Coating Machine No:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Slurry Lot No:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell">MIN. TB CONTENT:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Sample Quantity:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Total Magnet Weight:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell">Loader Operator:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Unloader Operator:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Checker Operator:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell bold" style="font-size: 9px;">COATING DATA:</div>
                <div class="table-cell"></div>
                <div class="table-cell">Time Start:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
                <div class="table-cell">Time Finished:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
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
                                <td></td>
                                <td>1</td>
                                <td></td>
                                <td></td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>2</td>
                                <td></td>
                                <td></td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>3</td>
                                <td></td>
                                <td></td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>4</td>
                                <td></td>
                                <td></td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>5</td>
                                <td></td>
                                <td></td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>6</td>
                                <td></td>
                                <td></td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>7</td>
                                <td></td>
                                <td></td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>8</td>
                                <td></td>
                                <td></td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>9</td>
                                <td></td>
                                <td></td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>10</td>
                                <td></td>
                                <td></td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
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
                                <td></td>
                                <td>11</td>
                                <td></td>
                                <td></td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>12</td>
                                <td></td>
                                <td></td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>13</td>
                                <td></td>
                                <td></td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>14</td>
                                <td></td>
                                <td></td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>15</td>
                                <td></td>
                                <td></td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>16</td>
                                <td></td>
                                <td></td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>17</td>
                                <td></td>
                                <td></td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>18</td>
                                <td></td>
                                <td></td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>19</td>
                                <td></td>
                                <td></td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>20</td>
                                <td></td>
                                <td></td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
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
                                <td></td>
                                <td>21</td>
                                <td></td>
                                <td></td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>22</td>
                                <td></td>
                                <td></td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>23</td>
                                <td></td>
                                <td></td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>24</td>
                                <td></td>
                                <td></td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>25</td>
                                <td></td>
                                <td></td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>26</td>
                                <td></td>
                                <td></td>
                                <td>M-01</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>27</td>
                                <td></td>
                                <td></td>
                                <td>M-02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>28</td>
                                <td></td>
                                <td></td>
                                <td>M-03</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>29</td>
                                <td></td>
                                <td></td>
                                <td>M-04</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>30</td>
                                <td></td>
                                <td></td>
                                <td>M-05</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
                                <td>M-06</td>
                            </tr>
                            <tr>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td style="border: none;"></td>
                                <td></td>
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
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>M-02</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>M-03</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>M-04</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>M-05</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>M-06</td>
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
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">MAXIMUM:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">NA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">MINIMUM:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">NA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">AVERAGE:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">NA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
            <tr>
                <td style="width: 50px; vertical-align: top; font-weight: bold;">Remarks:</td>
                <td style="width: 100%;">
                    <div style="border-bottom: 1px solid black; height: 9px;"></div>
                    <div style="border-bottom: 1px solid black; height: 9px; margin-bottom: 5px;"></div>
                </td>
            </tr>
        </table>

    </div>  <!-- End of border -->

<div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
    <label class="bold" style="font-size: 12px; padding-left: 1px;">HEAT TREATMENT INFORMATION</label>

    <div class="table-block">
        <div class="table-row">

            <!-- Column 1: Labels -->
            <div class="table-cell" style="font-size:8px; vertical-align: top; width: 13%; padding: 0 1px 0 0;">
                <div style="margin-bottom: 2px;">Furnace Machine:</div>
                <div style="margin-bottom: 2px;">CYCLE No:</div>
                <div style="margin-bottom: 2px;">BATCH CYCLE No:</div>
                <div style="margin-bottom: 2px;">PATTERN No:</div>
                <div style="margin-bottom: 2px;">DATE START:</div>
                <div style="margin-bottom: 2px;">TIME START:</div>
                <div style="margin-bottom: 2px;">LOADER:</div>
                <div style="margin-bottom: 2px;">DATE FINISH:</div>
                <div style="margin-bottom: 2px;">TIME FINISH:</div>
                <div style="margin-bottom: 2px;">UNLOADER:</div>
                <div style="margin-bottom: 2px;">Cycle Pattern:</div>
                <div style="margin-bottom: 2px;">Current Pattern:</div>
            </div>

            <!-- Column 2: Underlines -->
            <div class="table-cell" style="font-size:8px; vertical-align: top; width: 12%; padding: 0; margin: 0;">
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
                <div style="margin-bottom: 2px;"><span class="underline">NA</span></div>
            </div>

            <!-- Column 3: Table -->
            <div class="table-cell" style="vertical-align: top; width: 73%;">
                 <table class="print-table">Add commentMore actions
                        <thead>
                            <tr>
                                <th colspan="13">MAGNET BOX LOCATION</th>
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
                                <th>I</th>
                                <th>J</th>
                                <th>K</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="10" style="margin: 0px; padding: 0px;">No. of Layer</td>
                                <td>9.5</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
            </div>

        </div>
    </div>
</div> <!-- END of Border -->

<div style="padding: 1px; border: 1px solid black; margin-top: 0px;">
    <label class="bold" style="font-size: 12px; padding-left: 1px;">OVEN HEATING INFORMATION</label>
    <div class="table-block">
        <div class="table-row">
            <div class="table-cell">Coating Date:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell">Coating Machine No:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell">Slurry Lot No:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
        </div>

        <div class="table-row">
            <div class="table-cell">MIN. TB CONTENT:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell">Sample Quantity:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell">Total Magnet Weight:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
        </div>

        <div class="table-row">
            <div class="table-cell">Loader Operator:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell">Unloader Operator:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
            <div class="table-cell">Checker Operator:</div>
            <div class="table-cell"><span class="underline" style="text-align: center;">NA</span></div>
        </div>
    </div> <!-- End of Oven Heating Info -->
    <label class="bold" style="font-size: 12px; padding-left: 1px;">MAGNETIC PROPERTY INSPECTION INFORMATION</label>

</div>


</body>
