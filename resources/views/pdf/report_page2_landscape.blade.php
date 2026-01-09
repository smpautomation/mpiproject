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
                        <td style="font-size: 12px; width: 50%;"><i style="font-weight: bold;">Tracer: </i><span>{{ $reportData->pulse_tracer_machine_number }}</span></td>
                    </tr>
                </table>

                <!-- Date -->
                <p style="margin: 0; padding-top: 5px; font-size: 12px;"><i style="font-weight: bold;">Date: </i><span>{{ $reportDate }}</span></p>
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
            <td style="font-weight: bold; font-size: 10px;"><i>Code: </i>{{ $tpmData->code_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Type Code: </i>{{ $tpmData->type }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Judge Code: </i>{{ $tpmData->order_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Press #: </i>{{ $tpmData->press_1 }} {{ $tpmData->press_2 }} {{ $tpmData->machine_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Singering Furnace #: </i>{{ $sinteringFurnaceNo }}</td>
        </tr>

        <!-- Row 2 -->
        <tr>
            <td style="font-weight: bold; font-size: 10px;"><i>Sintering #: </i>{{ $sinteringNo }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Coating: </i>{{ $tpmData->furnace_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Pass #: </i>{{ $tpmData->pass_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Mias. Employee: </i>{{ $miasEmp }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Factor Employee: </i>{{ $factorEmp }}</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Zone:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Br:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>iHc:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>iHk:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>[BH]m:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Hr95:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Hr98:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>iHc-iHk:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center; white-space: nowrap;"><i>Br-4&#960;Ia:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>bHc:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Squareness:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center; white-space: nowrap;"><i>4&#960;Id:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center; white-space: nowrap;"><i>4&#960;Is:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-size: 9px; font-family: DejaVu Sans, sans-serif; text-align: center; white-space: nowrap;"><i>4&#960;Ia:</i></th>
                <th style="width: 2.25%; font-weight: bold; font-size: 10px; text-align: center;"><i></i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Temperature:</i></th>
                <th style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Data Status:</i></th>
            </tr>
        </thead>
        <tbody>
            @php
                $tdStyle = 'width: 6%; font-size: 10px; text-align: center; padding: 2px; white-space: nowrap;';
                $tdStyleRem = 'width: 2%; font-size: 10px; text-align: center; padding: 2px; white-space: nowrap;';

                $metrics = [
                    'Br','iHc','iHk','BHMax','iHr95','iHr98',
                    'iHkiHc','Br4pai','bHc','Squareness',
                    '4paiIa','4paiId','4paiIs'
                ];

                $totalItems = count($tpmDataAll);
            @endphp

            @foreach ($tpmDataAll as $index => $item)
                <tr>
                    <td style="{{ $tdStyle }}">{{ $item->zone ?? '' }}</td>

                    @foreach ($metrics as $metric)
                        @php
                            $value = $item->{$metric} ?? '';
                            $remark = $item->remark->{$metric . '_remarks'} ?? '';
                            $isError = $remark === '1';
                        @endphp

                        {{-- value --}}
                        <td style="{{ $tdStyle }}">{{ $value }}</td>

                        {{-- remark --}}
                        <td style="{{ $tdStyleRem }}{{ $isError ? ' color: #c00; font-weight: bold;' : '' }}">
                            @if ($isError)
                                E
                            @elseif ($remark !== '' && $remark !== '0')
                                {{ $remark }}
                            @endif
                        </td>
                    @endforeach

                    <td style="{{ $tdStyle }}">{{ $item->temperature ?? '' }}</td>
                    <td style="{{ $tdStyle }}">{{ $item->data_status ?? '' }}</td>
                </tr>

            @endforeach
            <!-- Clean Spacer -->
            <tr>
                <td colspan="25" style="height: 10px;"></td>
            </tr>
            <tr>
                <td style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Average</i></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['Br'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['bHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['iHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['iHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['Hr95'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['Hr98'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['BHMax'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['4paila'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['4paild'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['4pails'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['Br4pai'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['iHciHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateAvg['Squareness'] }}</td>
            </tr>
            <tr>
                <td style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Max</i></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['Br'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['bHc'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['iHc'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['iHk'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['Hr95'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['Hr98'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['BHMax'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['4paila'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['4paild'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['4pails'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['Br4pai'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['iHciHk'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMax['Squareness'] }}</td>
            </tr>
            <tr>
                <td style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Min</i></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['Br'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['bHc'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['iHc'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['iHk'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['Hr95'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['Hr98'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['BHMax'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['4paila'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['4paild'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['4pails'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['Br4pai'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['iHciHk'] }}</td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $tpmAggregateMin['Squareness'] }}</td>
            </tr>
        </tbody>

    </table>

    <!-- Thick Horizontal Line -->
    <div style="height: 1px; border-bottom: 3px solid black; margin-top: 5px; margin-bottom: 5px;"></div>

    <div class="page-break"></div>

    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
        <tr>
            {{-- Chart Cell --}}
            <td style="width: 480px; vertical-align: top;">
                @if ($chartFilename)
                    <div style="border: 1px solid #000; width: 480px; height: 360px; margin: 0 auto; padding-right: 20px; padding-top: 20px;">
                        <img src="{{ public_path('charts/' . $chartFilename) }}"
                            alt="Chart Image"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                @endif
            </td>

            {{-- Side Content Cell --}}
            <td style="width: 200px; vertical-align: top; padding-left: 12px;">
                <div style="width: 100%; height: 340px; padding: 10px; font-size: 10px;">
                    <p style="font-weight: bold; margin: 0 0 6px;">Note:</p>

                    <p style="margin: 0 0 4px;">
                        SMP Lot ( <span>{{ $heatTreatmentData->mass_prod }}</span> Mass Production )
                    </p>

                    <p style="margin: 0 0 4px;">
                        Furnace Cycle No.: {{ $sinteringFurnaceNo }}-{{ $sinteringNo }}
                    </p>

                    <p style="margin: 0 0 4px;">
                        <span>{{ $controlModel['A'] }}</span> ( {{ $tpmData->code_no }} )
                    </p>

                    <p style="margin: 0 0 4px;">
                        Lot # {{ $controlLotNo['A'] }}
                    </p>

                    @if (!empty($noteReasonsSorted))
                        <div style="margin-top: 8px;">
                            <p style="margin: 0;">Remarks Encountered:</p>
                            <p style="color: #c00; font-weight: bold; font-size: 9px; margin: 2px 0 0;">
                                {{ implode(', ', array_map(fn($r) => preg_replace('/^\s*-\s*/', '', $r), $noteReasonsSorted)) }}
                            </p>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>
    <div style="height: 10px;"></div>
        <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
        <thead>
            <tr style="text-align: center; white-space: nowrap;">
                <th style="border: 1px solid #000; padding: 1px;">Date</th>
                <th style="border: 1px solid #000; padding: 1px; width: 50px;">Code No</th>
                <th style="border: 1px solid #000; padding: 1px; width: 50px;">Order No</th>
                <th style="border: 1px solid #000; padding: 1px; width: 40px;">Type</th>
                <th colspan="3" style="border: 1px solid #000; padding: 1px; width: 60px;">LOT#</th>
                <th style="border: 1px solid #000; padding: 1px;">Furnace#</th>
                <th style="border: 1px solid #000; padding: 1px;">Coating#</th>
                <th style="border: 1px solid #000; padding: 1px;">Zone</th>

                @foreach (['Br','iHc','iHk','BHMax','Hr95','Hr98','iHkiHc','Br4pai','bHc','Squareness','4paiIa','4paiId','4paiIs'] as $metric)
                    <th colspan="2" style="border: 1px solid #000; padding: 1px;">{{ $metric }}</th>
                @endforeach
                <th style="border: 1px solid #000; padding: 1px;">Tracer</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($tpmDataAll as $item)
            <tr style="text-align: center; white-space: nowrap;">
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->date }}</td>
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->code_no }}</td>
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->order_no }}</td>
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->type }}</td>

                {{-- LOT# columns --}}
                <td style="border: 1px solid #000; padding: 0px;">{{ $item->press_1 }}</td>
                <td style="border: 1px solid #000; padding: 0px;">{{ $item->press_2 }}</td>
                <td style="border: 1px solid #000; padding: 0px;">{{ $item->machine_no }}</td>

                <td style="border: 1px solid #000; padding: 1px;">{{ $item->sintering_furnace_no }}</td>
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->furnace_no }}</td>
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->zone }}</td>

                {{-- Metrics + Remarks --}}
                @php
                    $metrics = [
                        'Br', 'iHc', 'iHk', 'BHMax', 'iHr95', 'iHr98',
                        'iHkiHc', 'Br4pai', 'bHc', 'Squareness',
                        '4paiIa', '4paiId', '4paiIs'
                    ];
                @endphp

                @foreach ($metrics as $metric)
                    <td style="border: 1px solid #000; padding: 1px;">{{ $item->{$metric} }}</td>
                    <td style="border: 1px solid #000; padding: 0px; width: 20px;
                        {{ $item->remark->{$metric . '_remarks'} === '1' ? 'color: #c00; font-weight: bold;' : '' }}">
                        {{
                            $item->remark->{$metric . '_remarks'} === '1' ? 'E' :
                            ($item->remark->{$metric . '_remarks'} === '0' ? '' : $item->remark->{$metric . '_remarks'})
                        }}
                    </td>
                @endforeach
                <td style="border: 1px solid #000; padding: 1px;">{{ $item->Tracer }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



</body>
</html>
