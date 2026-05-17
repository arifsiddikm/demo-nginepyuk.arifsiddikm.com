<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function generateTicket(Booking $booking): string
    {
        $booking->load('property.category');
        $pdf = Pdf::loadView('pdf.ticket', compact('booking'));
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'ticket_' . $booking->booking_code . '_' . time() . '.pdf';
        $path = storage_path('app/tickets/' . $fileName);

        if (!file_exists(storage_path('app/tickets'))) {
            mkdir(storage_path('app/tickets'), 0755, true);
        }

        $pdf->save($path);
        return $path;
    }

    public function downloadTicket(Booking $booking)
    {
        $booking->load('property.category');
        $pdf = Pdf::loadView('pdf.ticket', compact('booking'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('Tiket-' . $booking->booking_code . '.pdf');
    }
}
