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
    @foreach($nsaGroups as $setNo => $rows)
    @php
        $currentNSA = $rows->first(); // pick first record for that set
    @endphp
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <!-- Left Side: Group and Tracer side-by-side, then Date -->
            <td style="width: 33.33%; text-align: left; vertical-align: top;">
                <!-- Group and Tracer -->
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 12px; width: 50%;"><i style="font-weight: bold;">Tracer: </i><span>{{ $currentNSA->Tracer }}</span></td>
                    </tr>
                </table>

                <!-- Date -->
                <p style="margin: 0; padding-top: 5px; font-size: 12px;"><i style="font-weight: bold;">Date: </i><span>{{ $currentNSA->date }}</span></p>
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
            <td style="font-weight: bold; font-size: 10px;"><i>Code: </i>{{ $currentNSA->code_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Type Code: </i>{{ $currentNSA->type }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Judge Code: </i>{{ $currentNSA->order_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Press #: </i>{{ $currentNSA->press_1 }} {{ $currentNSA->press_2 }} {{ $currentNSA->machine_no }}</td>
            @php
                $furnacePrefix = $currentNSA->sintering_furnace_no
                    ? explode('-', $currentNSA->sintering_furnace_no)[0]
                    : '';
            @endphp

            <td style="font-weight: bold; font-size: 10px;">
                <i>Sintering Furnace #:</i> {{ $furnacePrefix }}
            </td>
        </tr>

        <!-- Row 2 -->
        <tr>
            @php
                $furnacePostfix = $currentNSA->sintering_furnace_no
                    ? explode('-', $currentNSA->sintering_furnace_no)[1]
                    : '';
            @endphp

            <td style="font-weight: bold; font-size: 10px;">
                <i>Sintering #: </i> {{ $furnacePostfix }}
            </td>
            <td style="font-weight: bold; font-size: 10px;"><i>Coating: </i>{{ $currentNSA->furnace_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Pass #: </i>{{ $currentNSA->pass_no }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Mias. Employee: </i>{{ $nsa_mias }}</td>
            <td style="font-weight: bold; font-size: 10px;"><i>Factor Employee: </i>{{ $nsa_factor }}</td>
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
            @endphp

            @foreach ($rows as $item)
            <tr>
                <td style="{{ $tdStyle }}">{{ $item->zone ?? '' }}</td>
                <td style="{{ $tdStyle }}">{{ $item->Br ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->Br_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->iHc ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->iHc_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->iHk ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->iHk_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->BHMax ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->BHMax_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->iHr95 ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->iHr95_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->iHr98 ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->iHr98_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->iHkiHc ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->iHkiHc_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->Br4pai ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->Br4pai_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->bHc ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->bHc_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->Squareness ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->Squareness_remarks ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->{"4paiIa"} ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->{"4paiIa_remarks"} ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->{"4paiId"} ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->{"4paiId_remarks"} ?? '' }}</td>

                <td style="{{ $tdStyle }}">{{ $item->{"4paiIs"} ?? '' }}</td>
                <td style="{{ $tdStyleRem }}">{{ $item->remark->{"4paiIs_remarks"} ?? '' }}</td>

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
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['Br'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['iHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['iHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['BHMax'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['Hr95'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['Hr98'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['iHciHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['Br4pai'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['bHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['Squareness'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['4paild'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['4pails'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['average']['4paila'] }}</td>
            </tr>
            <tr>
                <td style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Max</i></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['Br'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['iHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['iHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['BHMax'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['Hr95'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['Hr98'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['iHciHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['Br4pai'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['bHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['Squareness'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['4paild'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['4pails'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['max']['4paila'] }}</td>
            </tr>
            <tr>
                <td style="width: 6.25%; font-weight: bold; font-size: 10px; text-align: center; white-space: nowrap;"><i>Min</i></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['Br'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['iHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['iHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['BHMax'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['Hr95'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['Hr98'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['iHciHk'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['Br4pai'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['bHc'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['Squareness'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['4paild'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['4pails'] }}</td>
                <td style="width: 2.25%; font-size: 10px; text-align: center; white-space: nowrap;"></td>
                <td style="width: 6.25%; font-size: 10px; text-align: center; white-space: nowrap;">{{ $nsaAggregates[$setNo]['min']['4paila'] }}</td>
            </tr>
        </tbody>

    </table>

    <!-- Thick Horizontal Line -->
    <div style="height: 1px; border-bottom: 3px solid black; margin-top: 5px; margin-bottom: 5px;"></div>

    <p style="margin: 0 0 4px; color: rgb(65, 61, 252); font-size: 14px;">
        {{ $currentNSA->set_name }}
    </p>

    @php
        // Get total rows in current set
        $currentCount = $rows->count();

        // Get previous set's row count if exists
        $prevSetNo = $setNo - 1;
        $prevCount = isset($nsaGroups[$prevSetNo]) ? $nsaGroups[$prevSetNo]->count() : 0;

        // Sum rows of current + previous set
        $twoSetTotal = $currentCount + $prevCount;
    @endphp

    @if ($loop->last)
        <div class="page-break"></div>
    @elseif ($loop->iteration % 2 === 0)
        @if ($twoSetTotal <= 20)
            <div class="page-break"></div>
        @else
            <div class="page-break"></div> <!-- Break instantly for large sets -->
        @endif
    @endif

    @endforeach

    @foreach($nsaGroups as $setNo => $rows)

    @php
        $currentNSA = $rows->first(); // pick first record for that set
    @endphp

    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
        <tr>
            {{-- Chart Cell --}}
            <td style="width: 480px; vertical-align: top;">
                @php
                    $chartFile = $nsaChartFilenames[$setNo] ?? null;
                @endphp
                @if ($chartFile)
                    <div style="border: 1px solid #000; width: 480px; height: 360px; margin: 0 auto;">
                        <img src="{{ public_path('sec_charts/' . $chartFile) }}"
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

                    <p style="margin: 0 0 4px; color: rgb(65, 61, 252); font-size: 14px;">
                        {{ $currentNSA->set_name }}
                    </p>
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
            @foreach ($rows as $item)
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

    @endforeach



</body>
</html>
