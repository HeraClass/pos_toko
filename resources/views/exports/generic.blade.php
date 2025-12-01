<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {8
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .timestamp {
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="timestamp">Dibuat pada: {{ $timestamp ?? now()->format('d-m-Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($columns as $columnName)
                    <th>{{ $columnName }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach(array_keys($columns) as $columnKey)
                        <td>
                            @php
                                // Handle untuk array dan object
                                if (is_array($row)) {
                                    $value = $row[$columnKey] ?? '-';
                                } else {
                                    // Handle dot notation untuk object
                                    $keys = explode('.', $columnKey);
                                    $value = $row;
                                    foreach ($keys as $key) {
                                        if (is_object($value) && method_exists($value, $key)) {
                                            $value = $value->{$key}();
                                        } else {
                                            $value = $value->{$key} ?? '-';
                                        }
                                    }
                                }
                            @endphp
                            {{ $value }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(isset($page) && isset($pages))
        <div class="footer">
            Halaman {{ $page }} dari {{ $pages }}
        </div>
    @endif
</body>

</html>