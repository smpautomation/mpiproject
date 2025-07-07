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
    </style>
</head>
<body>
    <div style="text-align: center; margin-top: 0px;">
        <h1 style="margin: 0; font-size: 20px;">Property Data</h1>
        <p style="font-weight: bold">Group: </p>
        <p style="">Date: </p>
    </div>
</body>
</html>