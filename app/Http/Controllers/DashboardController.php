<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Pastikan Carbon diimpor untuk penanganan tanggal

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard dengan metrik penjualan hari ini dan data grafik bulanan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // --- Data untuk Ringkasan Penjualan Hari Ini ---
        $today = Carbon::today();

        // Total barang unik terjual hari ini (berdasarkan part_id yang berbeda)
        $totalItemsSoldToday = TransactionDetail::whereDate('created_at', $today)
                                                ->distinct('part_id')
                                                ->count('part_id');

        // Total kuantitas barang yang terjual hari ini
        $totalQuantitySoldToday = TransactionDetail::whereDate('created_at', $today)
                                                  ->sum('quantity');

        // Total jumlah invoice (transaksi) hari ini
        $totalInvoicesToday = Transaction::whereDate('created_at', $today)
                                         ->count();

        // --- Data untuk Grafik Penjualan Bulanan ---

        // Data penjualan untuk bulan ini
        $currentMonthSales = $this->getMonthlySalesData(Carbon::now()->month, Carbon::now()->year);

        // Data penjualan untuk bulan sebelumnya
        $previousMonth = Carbon::now()->subMonth();
        $previousMonthSales = $this->getMonthlySalesData($previousMonth->month, $previousMonth->year);

        // Mengembalikan view 'dashboard' dengan semua data yang sudah dihitung
        return view('dashboard', compact(
            'totalItemsSoldToday',
            'totalQuantitySoldToday',
            'totalInvoicesToday',
            'currentMonthSales',
            'previousMonthSales'
        ));
    }

    /**
     * Mengambil data penjualan harian untuk bulan dan tahun tertentu.
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Support\Collection
     */
    private function getMonthlySalesData(int $month, int $year)
    {
        // Mengambil total penjualan per hari untuk bulan dan tahun yang diberikan
        $salesData = Transaction::whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->groupBy(DB::raw('DATE(created_at)')) // Mengelompokkan berdasarkan tanggal saja
                                ->orderBy(DB::raw('DATE(created_at)')) // Mengurutkan berdasarkan tanggal
                                ->select(
                                    DB::raw('DATE(created_at) as date'),
                                    DB::raw('SUM(total_amount) as total_sales')
                                )
                                ->get();

        // Memastikan semua tanggal dalam sebulan ada, meskipun tidak ada penjualan
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $formattedSalesData = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
            $found = $salesData->firstWhere('date', $date);
            $formattedSalesData[] = [
                'date' => $date,
                'total_sales' => $found ? $found->total_sales : 0,
            ];
        }

        return collect($formattedSalesData);
    }
}
