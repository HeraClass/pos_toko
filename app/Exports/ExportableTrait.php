<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

trait ExportableTrait
{
    /**
     * Export data to PDF
     */
    public function exportToPdf($data, $columns, $title, $view = 'exports.generic')
    {
        $pdf = Pdf::loadView($view, [
            'data' => $data,
            'columns' => $columns,
            'title' => $title,
            'timestamp' => now()->format('d-m-Y H:i:s'),
        ]);

        return $pdf->download($this->getFilename($title, 'pdf'));
    }

    /**
     * Export data to CSV
     */
    public function exportToCsv($data, $columns, $title)
    {
        $filename = $this->getFilename($title, 'csv');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Header
            fputcsv($file, array_values($columns));

            // Data
            foreach ($data as $row) {
                $csvRow = [];
                foreach (array_keys($columns) as $column) {
                    $csvRow[] = $this->getCellValue($row, $column);
                }
                fputcsv($file, $csvRow);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get value from model/array, handle method, property, dot notation
     */
    private function getCellValue($row, $column)
    {
        // Jika row adalah array (bukan object)
        if (is_array($row)) {
            return $row[$column] ?? '-';
        }

        // Handle dot notation untuk object
        if (str_contains($column, '.')) {
            $parts = explode('.', $column);
            $value = $row;
            foreach ($parts as $part) {
                if (is_object($value) && method_exists($value, $part)) {
                    $value = $value->{$part}();
                } elseif (is_object($value) && isset($value->{$part})) {
                    $value = $value->{$part};
                } elseif (is_array($value) && isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    $value = null;
                    break;
                }
            }
            return $value ?? '-';
        }

        // Handle method call untuk object
        if (is_object($row) && method_exists($row, $column)) {
            return $row->{$column}() ?? '-';
        }

        // Handle property untuk object
        if (is_object($row)) {
            return $row->{$column} ?? '-';
        }

        // Fallback
        return '-';
    }

    /**
     * Generate filename
     */
    private function getFilename($title, $extension)
    {
        return \Illuminate\Support\Str::slug($title) . '_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
    }
}