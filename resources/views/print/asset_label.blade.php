<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Label Aset</title>
    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            width: 50%;
            /* 2 columns */
            /* padding: 6px; */
            vertical-align: top;
        }

        .label-box {
            /* padding: 5px; */
            min-height: 25px;
        }

        .qr {
            float: left;
            width: 20px;
            height: 20px;
            margin-right: 6px;
            border: 1px solid #aaa;
            text-align: center;
        }

        .qr img {
            max-width: 100%;
            max-height: 100%;
        }

        .text {
            overflow: hidden;
            /* prevent float issues */
        }

        .description {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 3px;
        }

        .label {
            font-size: 8px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            @php $col = 0; @endphp
            @foreach($asset_labels as $asset)
            <td>
                <div class="label-box">
                    <div class="qr">
                        <img src="{{ $asset->print_barcode }}" alt="QR">
                    </div>
                    <div class="text">
                        <div class="description">{{ $asset['description'] }}</div>
                        <div class="label">{{ $asset['label'] }}</div>
                    </div>
                </div>
            </td>
            @php $col++; @endphp
            @if($col % 2 == 0)
        </tr>
        <tr>
            @endif
            @endforeach
        </tr>
    </table>
</body>

</html>