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
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold; font-size: 9px;">{{ $controlModel['A'] ?? $controlModel['B'] ?? null }}</span></div>
            <div class="table-cell" style="font-weight: bold;">Partial No: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold;">{{ $heatTreatmentData->partial_no }}</span></div>
        </div>
        <div class="table-row">
            <div class="table-cell" style="font-weight: bold;">MATERIAL CODE: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold;">{{ $reportData->material_code }}</span></div>
            <div class="table-cell" style="font-weight: bold;">TOTAL QUANTITY: </div>
            <div class="table-cell"><span class="underline" style="text-align: center; font-weight: bold;">{{ $controlTotalQty['A'] ?? $controlTotalQty['B'] ?? null }}</span></div>
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
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $secondGbdp2ndCoatingInfo['date'] ?? 'NA' }}</span></div>
                <div class="table-cell">Coating Machine No:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $secondGbdp2ndCoatingInfo['machine_no'] ?? 'NA' }}</span></div>
                <div class="table-cell">Slurry Lot No:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $secondGbdp2ndCoatingInfo['slurry_lot_no'] ?? 'NA' }}</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell">MIN. TB CONTENT:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $secondGbdp2ndCoatingInfo['min_tb_content'] ?? 'NA' }}</span></div>
                <div class="table-cell">Total Magnet Weight:</div>
                <div class="table-cell"><span class="underline" style="text-align: center;">{{ $secondGbdp2ndCoatingInfo['total_magnet_weight'] ?? 'NA' }}</span></div>
            </div>

            <div class="table-row">
                <div class="table-cell bold" style="font-size: 9px;">COATING DATA:</div>
                <div class="table-cell"></div>
                <div class="table-cell"></div>
                <div class="table-cell"><br></div>
                <div class="table-cell"></div>
                <div class="table-cell"><br></div>
            </div>

            <div class="table-row">
                <p class="table-cell" style="font-size:7px;">UNIT    :    (µ g/mm2)</p>
            </div>
        </div> <!-- End of Coating Info -->

        @php
            $lotNo = $secondGbdp2ndCoatingData['Lot no'] ?? null;
            $concentrationData = $secondGbdp2ndCoatingData['Concentration Data'] ?? [];
            $coatingAmountData = collect($secondGbdp2ndCoatingData['Coating Amount Data'] ?? [])->keyBy('no');
            //$additionalSlurry = $secondGbdp2ndCoatingData['Additional Slurry Data'] ?? [];

            $rows = [];
            $currentNo = 1;

            foreach ($concentrationData as $range) {
                // each "range" has modules (M-01 to M-05, then M-06)
                $normalModules = array_filter($range['modules'], fn($m) => $m['module'] !== 'M-06');
                $m6Modules     = array_filter($range['modules'], fn($m) => $m['module'] === 'M-06');

                foreach ($normalModules as $module) {
                    // normal row with Lot/No/Coating
                    $rows[] = [
                        'lot' => $lotNo,
                        'no' => $currentNo,
                        'coating' => $coatingAmountData[$currentNo]['coating'] ?? null,
                        'concentration' => $module['value'],
                        'module' => $module['module'],
                    ];
                    $currentNo++;
                }

                foreach ($m6Modules as $module) {
                    // special row for M-06 (blank Lot/No/Coating)
                    $rows[] = [
                        'lot' => null,
                        'no' => null,
                        'coating' => null,
                        'concentration' => $module['value'],
                        'module' => 'M-06',
                    ];
                }
            }

            // now slice by 10 “No.” groups
            $table1Rows = array_slice($rows, 0, 14); // first 10 No + M6 blanks
            $table2Rows = array_slice($rows, 14, 14);
            $table3Rows = array_slice($rows, 28, 14);
        @endphp

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
                            @foreach ($table1Rows as $row)
                                <tr>
                                    <td>{{ $row['lot'] }}</td>
                                    <td>{{ $row['no'] }}</td>
                                    <td>{{ $row['coating'] }}</td>
                                    <td>{{ $row['concentration'] }}</td>
                                    <td>{{ $row['module'] }}</td>
                                </tr>
                            @endforeach
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
                            @foreach ($table2Rows as $row)
                                <tr>
                                    <td>{{ $row['lot'] }}</td>
                                    <td>{{ $row['no'] }}</td>
                                    <td>{{ $row['coating'] }}</td>
                                    <td>{{ $row['concentration'] }}</td>
                                    <td>{{ $row['module'] }}</td>
                                </tr>
                            @endforeach
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
                            @foreach ($table2Rows as $row)
                                <tr>
                                    <td><br></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
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
                                <td><br></td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
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
                                <td style="border: 1px solid black; padding: 2px 4px;">{{ $secondGbdp2ndCoatingInfo['maximum'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">MINIMUM:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">{{ $secondGbdp2ndCoatingInfo['minimum']  }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="display: table-cell; vertical-align: top; font-weight: bold; width: 33%;">
                    <table style="width: 100%; font-size: 8px; border-collapse: collapse; border: 1px solid black;">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; padding: 2px 4px;">AVERAGE:</td>
                                <td style="border: 1px solid black; padding: 2px 4px;">{{ $secondGbdp2ndCoatingInfo['average'] }}</td>
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
                            {{ $secondGbdp2ndCoatingInfo['remarks'] }}
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
                        ['Furnace Machine:', $heatTreatmentData->machine_no ?? null],
                        ['CYCLE No:', $heatTreatmentData->cycle_no ?? null],
                        ['BATCH CYCLE No:', $heatTreatmentData->batch_cycle_no ?? null],
                        ['PATTERN No:', $heatTreatmentData->pattern_no ?? null],
                        ['DATE START:', $heatTreatmentData->date_start ?? 'NA'],
                        ['TIME START:', $heatTreatmentData->time_start ?? 'NA'],
                        ['LOADER:', $heatTreatmentData->loader ?? null],
                        ['DATE FINISH:', $heatTreatmentData->date_finished ?? 'NA'],
                        ['TIME FINISH:', $heatTreatmentData->time_finished ?? 'NA'],
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
                                <th colspan="{{ isset($controlLotNo['L']) && $controlLotNo['L'] ? 13 : 12 }}">MAGNET BOX LOCATION</th>
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
                                @if (isset($controlLotNo['L']) && $controlLotNo['L'])
                                    <th>L</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="margin: 0px; padding: 0px;">No. of Layer</td>
                                <td>{{ $layer }}</td>
                                <td>{{ $controlLotNo['A'] ?? null }}</td>
                                <td>{{ $controlLotNo['B'] ?? null }}</td>
                                <td>{{ $controlLotNo['C'] ?? null }}</td>
                                <td>{{ $controlLotNo['D'] ?? null }}</td>
                                <td>{{ $controlLotNo['E'] ?? null }}</td>
                                <td>{{ $controlLotNo['F'] ?? null }}</td>
                                <td>{{ $controlLotNo['G'] ?? null }}</td>
                                <td>{{ $controlLotNo['H'] ?? null }}</td>
                                <td>{{ $controlLotNo['J'] ?? null }}</td>
                                <td>{{ $controlLotNo['K'] ?? null }}</td>
                                @if (isset($controlLotNo['L']) && $controlLotNo['L'])
                                     <td>{{ $controlLotNo['L'] ?? null }}</td>
                                @endif
                            </tr>
                    </table>
            </div>
        </div>
    </div>
    <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
        <tr>
            <td style="width: 50px; vertical-align: top; font-weight: bold;">Remarks:</td>
            <td style="width: 100%;">
                <div style="border-bottom: 1px solid black; height: 9px; line-height: 9px; text-align: center;">
                    <span style="display: inline-block; margin-bottom: -1px;">
                        {{ $heatTreatmentData->mass_prod }} MASS PRODUCTION
                    </span>
                </div>
                <div style="border-bottom: 1px solid black; height: 9px; margin-bottom: 5px; text-align: center;">
                    <span style="display: inline-block; margin-bottom: -1px;">
                        {{ implode(' / ', array_filter([
                            $heatTreatmentData->remarks1,
                            $heatTreatmentData->remarks2,
                            $heatTreatmentData->remarks3
                        ])) }}
                    </span>
                </div>
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
                @php
                    $showGX = $flags['showGX'] ?? false;
                    $noteReasons = $noteReasonsSorted ?? [];
                    $hasNGihc = in_array('- N.G iHc', $noteReasons);
                    $gx = $modelData['gx'] ?? [];
                @endphp
                @php
                    $showROB = $flags['showROB'] ?? false;
                    $actualDataColspan = $showROB ? 5 : 4;
                    $tableColspan = 2 + $actualDataColspan; // 2 fixed cols (Items + Standard)
                @endphp

                <table class="print-table" style="width: 100%; border-collapse: collapse;" border="1" cellpadding="3">
                    <thead>
                        <tr>
                            <th colspan="{{ $tableColspan }}" style="text-align: center;">MAGNETIC PROPERTY TABLE</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="text-align: center;">ITEMS</th>
                            <th rowspan="2" style="text-align: center;">STANDARD</th>
                            <th colspan="{{ $actualDataColspan }}" style="text-align: center;">ACTUAL DATA</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">AVERAGE</th>
                            <th style="text-align: center;">MAXIMUM</th>
                            <th style="text-align: center;">MINIMUM</th>
                            <th style="text-align: center;">Variance<br>(max - min)</th>
                            @if($showROB)
                                <th style="text-align: center;"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Br (G) --}}
                        <tr>
                            <td>Br (G)</td>
                            <td>{{ $brBounds['lower'] ?? 'NA' }} ~ {{ $brBounds['upper'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['brAverage'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['brMaximum'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['brMinimum'] ?? 'NA' }}</td>
                            <td>{{ $brVariance ?? 'NA' }}</td>
                            @if($showROB)
                                <td style="text-align: center;"></td>
                            @endif
                        </tr>

                        {{-- iHc GM --}}
                        <tr>
                            <td rowspan="{{ ($hasNGihc && $showGX) ? 2 : 1 }}">iHc (Oe)</td>
                            <td>
                                @if($showGX && $hasNGihc)
                                    GM
                                @endif
                                {{ $magneticProperty['ihcStandard'] ?? 'NA' }}
                            </td>
                            <td>{{ $magneticProperty['ihcAverage'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihcMaximum'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['ihcMinimum'] ?? 'NA' }}</td>
                            <td>{{ $ihcVariance ?? 'NA' }}</td>
                            @if($showROB)
                                <td style="text-align: center;"></td>
                            @endif
                        </tr>

                        {{-- iHc GX --}}
                        @if($showGX && $hasNGihc)
                        <tr>
                            <td>GX {{ $gx['iHcStandard'] ?? '-' }}</td>
                            <td>{{ $gx['iHcAverage'] ?? '-' }}</td>
                            <td>{{ $gx['iHcMaximum'] ?? '-' }}</td>
                            <td>{{ $gx['iHcMinimum'] ?? '-' }}</td>
                            <td>{{ $gx['iHcVariance'] ?? '-' }}</td>
                        </tr>
                        @endif

                        {{-- iHk GM --}}
                        <tr>
                            <td rowspan="{{ ($hasNGihc && $showGX) ? 2 : 1 }}">iHk (Oe)</td>
                            <td>
                                @if($showGX && $hasNGihc)
                                    GM
                                @endif
                                {{ $magneticProperty['iHkStandard'] ?? 'NA' }}
                            </td>
                            <td>{{ $magneticProperty['iHkAverage'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['iHkMaximum'] ?? 'NA' }}</td>
                            <td>{{ $magneticProperty['iHkMinimum'] ?? 'NA' }}</td>
                            <td>{{ $ihkVariance ?? 'NA' }}</td>
                            @if($showROB)
                                <td style="text-align: center;"></td>
                            @endif
                        </tr>

                        {{-- iHk GX --}}
                        @if($showGX && $hasNGihc)
                        <tr>
                            <td>GX -</td>
                            <td>{{ $gx['ihkAverage'] ?? '-' }}</td>
                            <td>{{ $gx['ihkMaximum'] ?? '-' }}</td>
                            <td>{{ $gx['ihkMinimum'] ?? '-' }}</td>
                            <td>{{ $gx['ihkVariance'] ?? '-' }}</td>
                        </tr>
                        @endif


                        @php
                            $showROB = $flags['showROB'] ?? false;
                            $rob = $modelData['rob'] ?? [];
                        @endphp

                        @if($showROB)
                            {{-- ROB Table Header Extension --}}
                            <tr>
                                <th colspan="7" style="text-align: center; background-color: #f0f0f0;">BH Tracer Measurement</th>
                            </tr>
                            <tr>
                                <th rowspan="2" style="border: 1px solid black; background-color: #f0f0f0;">ITEMS</th>
                                <th rowspan="2" style="border: 1px solid black; background-color: #f0f0f0;">SPECS</th>
                                <th style="text-align: center; border: 1px solid black; background-color: #f0f0f0;">Br Min</th>
                                <th style="text-align: center; border: 1px solid black; background-color: #f0f0f0;">Br Max</th>
                                <th style="text-align: center; border: 1px solid black; background-color: #f0f0f0;">iHc Min</th>
                                <th style="text-align: center; border: 1px solid black; background-color: #f0f0f0;">iHc Max</th>
                                <th rowspan="2" style="border: 1px solid black; background-color: #f0f0f0;">RESULT</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="text-align: center; background-color: #f0f0f0;">ACTUAL DATA</th>
                            </tr>

                            {{-- Br @ RT --}}
                            <tr>
                                <th style="background-color: #f0f0f0;">Br @ RT</th>
                                <td>{{ $rob['brRTStandard'] ?? 'NA' }} kg</td>
                                <td>{{ $rob['brRT_brMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['brRT_brMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['brRT_iHcMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['brRT_iHcMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['result'] ?? 'NA' }}</td>
                            </tr>

                            {{-- Br @ VT --}}
                            <tr>
                                <th style="background-color: #f0f0f0;">Br @ VT (180°C)</th>
                                <td>{{ $rob['brVTStandard'] ?? 'NA' }} kg</td>
                                <td>{{ $rob['brVT_brMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['brVT_brMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['brVT_iHcMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['brVT_iHcMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['result'] ?? 'NA' }}</td>
                            </tr>

                            {{-- HD5 --}}
                            <tr>
                                <th style="background-color: #f0f0f0;">HD5 (180°C)</th>
                                <td>{{ $rob['hd5Standard'] ?? 'NA' }} kOe</td>
                                <td>{{ $rob['HD5_brMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['HD5_brMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['HD5_iHcMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['HD5_iHcMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['result'] ?? 'NA' }}</td>
                            </tr>

                            {{-- JD5 --}}
                            <tr>
                                <th style="background-color: #f0f0f0;">JD5 (180°C)</th>
                                <td>{{ $rob['jd5Standard'] ?? 'NA' }} kG</td>
                                <td>{{ $rob['JD5_brMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['JD5_brMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['JD5_iHcMin'] ?? 'NA' }}</td>
                                <td>{{ $rob['JD5_iHcMax'] ?? 'NA' }}</td>
                                <td>{{ $rob['result'] ?? 'NA' }}</td>
                            </tr>
                        @endif

                        @php
                            $isTTM_model = $flags['isTTM_model'] ?? false;
                        @endphp

                        @if ($isTTM_model ?? false)
                            <tr>
                                <th rowspan="2" style="border: 1px solid #000; background-color: #f0f0f0;">Computation of Cpk from Br</th>
                                <th style="border: 1px solid #000; background-color: #f0f0f0;">STD DEV</th>
                                <th style="border: 1px solid #000; background-color: #f0f0f0;">Cp</th>
                                <th style="border: 1px solid #000; background-color: #f0f0f0; font-family: DejaVu Sans, sans-serif;">Cpk ≥ 1.00</th>
                                <th colspan="2" style="border: 1px solid #000; background-color: #f0f0f0;">Remarks</th>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000;">{{ $reportData->std_dev ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $reportData->cp ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $reportData->cpk ?? 'NA' }}</td>
                                <td colspan="2" style="border: 1px solid #000;">{{ $reportData->br_cpk_remarks ?? 'NA' }}</td>
                            </tr>
                        @endif

                        @if ($flags['show1x1x1Data_withoutCorner'])
                            <tr style="background-color: #f0f0f0;">
                                <td colspan="2" style="border: 1px solid #000;">Data of 1x1x1 mm samples</td>
                                <td style="border: 1px solid #000;">AVERAGE</td>
                                <td style="border: 1px solid #000;">MAXIMUM</td>
                                <td style="border: 1px solid #000;">MINIMUM</td>
                                <td style="border: 1px solid #000; font-family: DejaVu Sans, sans-serif;">Cpk ≥ 1.33</td>
                                <td style="border: 1px solid #000;">Remarks</td>
                            </tr>
                        @endif

                        @php
                            $d1x1x1 = $modelData['d1x1x1'] ?? [];
                        @endphp

                        @if ($flags['show1x1x1Data_Corner'])
                            <tr>
                                <th style="border: 1px solid #000; background-color: #f0f0f0;">Corner</th>
                                <td style="border: 1px solid #000; font-family: DejaVu Sans, sans-serif;">≥ {{ $d1x1x1['corner'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['corner_average'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['corner_maximum'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['corner_minimum'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['corner_cpk'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['corner_remarks'] ?? 'NA' }}</td>
                            </tr>
                        @endif

                        @if ($flags['show1x1x1Data_withoutCorner'])
                            <tr>
                                <th style="border: 1px solid #000; background-color: #f0f0f0;">Surface</th>
                                <td style="border: 1px solid #000; font-family: DejaVu Sans, sans-serif;">≥ {{ $d1x1x1['surface'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['surface_average'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['surface_maximum'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['surface_minimum'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['surface_cpk'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['surface_remarks'] ?? 'NA' }}</td>
                            </tr>

                            <tr>
                                <th style="border: 1px solid #000; background-color: #f0f0f0;">Core</th>
                                <td style="border: 1px solid #000; font-family: DejaVu Sans, sans-serif;">≥ {{ $d1x1x1['core'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['core_average'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['core_maximum'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['core_minimum'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['core_cpk'] ?? 'NA' }}</td>
                                <td style="border: 1px solid #000;">{{ $d1x1x1['core_remarks'] ?? 'NA' }}</td>
                            </tr>
                        @endif


                        @if ($flags['showVTData'])
                            @php
                                $vt = $modelData['vt'] ?? [];
                                $samples = $vt['sample'] ?? [];
                                $remarks = $vt['sample_remarks'] ?? [];
                                $results = $vt['iHcResult'] ?? [];

                                $sampleCount = count($samples);

                                // Determine column count
                                $colCount = match (true) {
                                    $sampleCount <= 3 => 3,
                                    $sampleCount === 4 => 4,
                                    default => 5,
                                };

                                $rowCount = ceil($sampleCount / $colCount);
                            @endphp

                            <tr>
                                <th colspan="2" style="text-align: center; background-color: #f0f0f0; border: 1px solid #000;">VT Data</th>
                                <th colspan="3" style="text-align: center; background-color: #f0f0f0; border: 1px solid #000;">Samples</th>
                                <th style="text-align: center; background-color: #f0f0f0; border: 1px solid #000;">Remarks</th>
                            </tr>

                            <tr>
                                <td style="text-align: center; border: 1px solid #000;">{{ $vt['temp'] ?? 'NA' }} °C</td>
                                <td style="text-align: center; border: 1px solid #000; font-family: DejaVu Sans, sans-serif;">
                                    iHc (kOe) ≥ {{ $vt['iHc'] ?? 'NA' }}
                                </td>
                                <td colspan="3" style="padding: 0; border: 1px solid #000;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            <tr>
                                                @for ($j = 0; $j < $colCount; $j++)
                                                    @php
                                                        $index = $i * $colCount + $j;
                                                        $sample = $samples[$index] ?? null;
                                                        $remark = $remarks[$index] ?? null;
                                                        $result = $results[$index] ?? null;
                                                    @endphp
                                                    <td style="border: 1px solid #ccc; text-align: center; font-size: 9px; width: 33.3%;">
                                                        @if ($sample)
                                                            <div style="background-color: #f9f9f9; white-space: nowrap;">
                                                                {{ $sample }} ({{ $remark }})
                                                            </div>
                                                            <div style="border-top: 1px solid #ccc;">
                                                                {{ $result }} kOe
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </table>
                                </td>
                                <td style="text-align: center; border: 1px solid #000;">
                                    {{ $vt['remarks'] ?? 'NA' }}
                                </td>
                            </tr>
                        @endif

                        @if ($flags['showCpkFrom_iHc'])
                            @php
                                $ihcCpk = $modelData['ihc_cpk'] ?? [];
                            @endphp

                            <tr>
                                <th colspan="2" style="border: 1px solid #000; background-color: #f0f0f0;"></th>
                                <th style="border: 1px solid #000; background-color: #f0f0f0;">STD DEV</th>
                                <th colspan="2" style="border: 1px solid #000; background-color: #f0f0f0; font-family: DejaVu Sans, sans-serif;">
                                    Cpk ≥ 1.33
                                </th>
                                <th colspan="2" style="border: 1px solid #000; background-color: #f0f0f0;">Remarks</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="border: 1px solid #000; background-color: #f0f0f0;">Computation of Cpk from iHc</th>
                                <td style="border: 1px solid #000; text-align: center;">{{ $ihcCpk['std_dev'] ?? 'NA' }}</td>
                                <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $ihcCpk['cpk'] ?? 'NA' }}</td>
                                <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $ihcCpk['remarks'] ?? 'NA' }}</td>
                            </tr>
                        @endif

                        @if ($flags['showBHData'])
                            @php
                                $bh = $modelData['bh'] ?? [];
                                $samples = $bh['sample'] ?? [];
                                $results = $bh['result'] ?? [];
                                $sampleCount = count($samples);

                                // Dynamic column count logic
                                $colCount = match (true) {
                                    $sampleCount <= 3 => 3,
                                    $sampleCount === 4 => 4,
                                    default => 5,
                                };

                                $rowCount = ceil($sampleCount / $colCount);
                            @endphp

                            <tr>
                                <th colspan="2" style="text-align: center; background-color: #f0f0f0; border: 1px solid #000;">
                                    BH Data @ {{ $bh['temp'] ?? 'NA' }} °C
                                </th>
                                <th colspan="3" style="text-align: center; background-color: #f0f0f0; border: 1px solid #000;">
                                    Samples
                                </th>
                                <th style="text-align: center; background-color: #f0f0f0; border: 1px solid #000;">
                                    Result
                                </th>
                            </tr>

                            <tr>
                                <td style="text-align: center; border: 1px solid #000;">
                                    {{ $bh['data'] ?? 'NA' }} kOe
                                </td>
                                <td style="text-align: center; font-family: DejaVu Sans, sans-serif; border: 1px solid #000;">
                                    ≥ {{ $bh['dataStandard'] ?? 'NA' }} kOe
                                </td>
                                <td colspan="3" style="padding: 0; border: 1px solid #000;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            <tr>
                                                @for ($j = 0; $j < $colCount; $j++)
                                                    @php
                                                        $index = $i * $colCount + $j;
                                                        $sample = $samples[$index] ?? null;
                                                        $result = $results[$index] ?? null;
                                                    @endphp
                                                    <td style="border: 1px solid #ccc; text-align: center; font-size: 9px;">
                                                        @if ($sample)
                                                            <div style="background-color: #f9f9f9; white-space: nowrap;">
                                                                {{ $sample }}
                                                            </div>
                                                            <div style="border-top: 1px solid #ccc;">
                                                                {{ $result }} kOe
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </table>
                                </td>
                                <td style="text-align: center; border: 1px solid #000;">
                                    {{ $bh['remarks'] ?? 'NA' }}
                                </td>
                            </tr>
                        @endif



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
</html>
