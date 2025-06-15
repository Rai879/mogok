@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8 font-inter">
    {{-- Tailwind CSS CDN untuk styling --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome CDN untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Chart.js CDN untuk grafik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Gaya khusus untuk scrollbar */
        .custom-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        /* Menggunakan font Inter dari Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Dashboard Penjualan Hari ini</h1>

    {{-- Bagian Ringkasan Penjualan Hari Ini --}}       

    {{-- Bagian Kotak Ringkasan Penjualan Hari Ini --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Kotak Total Barang Terjual (Unik) Hari Ini --}}
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-xl p-6 flex items-center justify-between transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-600 bg-opacity-75 text-white mr-4">
                    <i class="fas fa-cubes text-2xl"></i> {{-- Ikon untuk barang --}}
                </div>
                <div>
                    <p class="text-white text-lg font-medium">Barang Terjual Hari Ini (Unik)</p>
                    <p class="text-white text-4xl font-bold mt-1">{{ number_format($totalItemsSoldToday) }}</p>
                </div>
            </div>
        </div>

        {{-- Kotak Total Kuantitas Terjual Hari Ini --}}
        <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-xl p-6 flex items-center justify-between transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-600 bg-opacity-75 text-white mr-4">
                    <i class="fas fa-boxes text-2xl"></i> {{-- Ikon untuk kuantitas --}}
                </div>
                <div>
                    <p class="text-white text-lg font-medium">Kuantitas Terjual Hari Ini</p>
                    <p class="text-white text-4xl font-bold mt-1">{{ number_format($totalQuantitySoldToday) }}</p>
                </div>
            </div>
        </div>

        {{-- Kotak Total Invoice Hari Ini --}}
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg shadow-xl p-6 flex items-center justify-between transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-600 bg-opacity-75 text-white mr-4">
                    <i class="fas fa-file-invoice text-2xl"></i> {{-- Ikon untuk invoice --}}
                </div>
                <div>
                    <p class="text-white text-lg font-medium">Invoice Hari Ini</p>
                    <p class="text-white text-4xl font-bold mt-1">{{ number_format($totalInvoicesToday) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Grafik Penjualan Bulanan --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Grafik Penjualan Bulanan</h2>

        <div class="flex justify-center mb-4">
            <button id="currentMonthBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
            </button>
            <button id="previousMonthBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                Bulan Lalu ({{ \Carbon\Carbon::now()->subMonth()->translatedFormat('F Y') }})
            </button>
        </div>

        <div class="relative h-96">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Ambil data penjualan dari backend
    const currentMonthSalesData = @json($currentMonthSales);
    const previousMonthSalesData = @json($previousMonthSales);

    let salesChart; // Variabel untuk menyimpan instance grafik

    // Fungsi untuk memperbarui grafik
    function updateChart(data, title) {
        // Mendapatkan label tanggal dari data
        const labels = data.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        // Mendapatkan nilai total penjualan dari data
        const values = data.map(item => item.total_sales);

        // Jika grafik sudah ada, update datanya
        if (salesChart) {
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = values;
            salesChart.data.datasets[0].label = 'Total Penjualan (' + title + ')';
            salesChart.update(); // Perbarui grafik
        } else {
            // Jika grafik belum ada, buat yang baru
            const ctx = document.getElementById('salesChart').getContext('2d');
            salesChart = new Chart(ctx, {
                type: 'line', // Tipe grafik garis
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Penjualan (' + title + ')',
                        data: values,
                        borderColor: 'rgb(75, 192, 192)', // Warna garis
                        tension: 0.1, // Kelengkungan garis
                        fill: false, // Jangan mengisi area di bawah garis
                        pointBackgroundColor: 'rgb(75, 192, 192)', // Warna titik data
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(75, 192, 192)',
                    }]
                },
                options: {
                    responsive: true, // Membuat grafik responsif
                    maintainAspectRatio: false, // Tidak mempertahankan rasio aspek, agar bisa mengisi kontainer
                    plugins: {
                        legend: {
                            display: true, // Tampilkan legenda
                            position: 'top', // Posisi legenda di atas
                            labels: {
                                font: {
                                    family: 'Inter' // Font untuk legenda
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                // Callback untuk memformat teks tooltip
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        // Format nilai y sebagai mata uang Rupiah
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal', // Judul sumbu X
                                font: {
                                    family: 'Inter'
                                }
                            },
                            ticks: {
                                font: {
                                    family: 'Inter'
                                }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Penjualan (Rp)', // Judul sumbu Y
                                font: {
                                    family: 'Inter'
                                }
                            },
                            beginAtZero: true, // Mulai sumbu Y dari nol
                            ticks: {
                                // Callback untuk memformat label sumbu Y sebagai mata uang Rupiah
                                callback: function(value, index, values) {
                                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
                                },
                                font: {
                                    family: 'Inter'
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Event listener untuk tombol "Bulan Ini"
    document.getElementById('currentMonthBtn').addEventListener('click', function() {
        updateChart(currentMonthSalesData, 'Bulan Ini');
        // Mengubah gaya tombol aktif
        this.classList.remove('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
        this.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
        document.getElementById('previousMonthBtn').classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
        document.getElementById('previousMonthBtn').classList.add('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
    });

    // Event listener untuk tombol "Bulan Lalu"
    document.getElementById('previousMonthBtn').addEventListener('click', function() {
        updateChart(previousMonthSalesData, 'Bulan Lalu');
        // Mengubah gaya tombol aktif
        this.classList.remove('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
        this.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
        document.getElementById('currentMonthBtn').classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
        document.getElementById('currentMonthBtn').classList.add('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
    });

    // Inisialisasi grafik dengan data bulan ini saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        updateChart(currentMonthSalesData, 'Bulan Ini');
        // Set tombol "Bulan Ini" sebagai aktif saat pertama kali dimuat
        document.getElementById('currentMonthBtn').classList.remove('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
        document.getElementById('currentMonthBtn').classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
    });
</script>
@endsection
