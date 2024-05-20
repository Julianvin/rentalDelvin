<?php

// Class untuk menghitung harga sewa motor
class RentalMotor
{
    private $waktu;
    private $motor;
    private $hargaPerJam = [
        'vario' => 250000,
        'aerox' => 500000,
        'trail' => 750000,
        'ninja' => 1000000,
    ];

    public function __construct($motor, $waktu)
    {
        $this->motor = $motor;
        $this->waktu = intval($waktu);
    }

    public function getMotor()
    {
        return $this->motor;
    }

    public function getWaktu()
    {
        return $this->waktu;
    }

    public function hitungHarga()
    {
        if (array_key_exists($this->motor, $this->hargaPerJam)) {
            return $this->waktu * $this->hargaPerJam[$this->motor];
        }
        return 0;
    }
}

// Class untuk proses sewa motor
class SewaMotor
{
    private $nama;
    private $member;
    private $rentalMotor;
    private $totalPembayaran;

    public function __construct($nama, $motor, $waktu)
    {
        $this->nama = $nama;
        $this->member = $this->dapatkanStatusMember($nama);
        $this->rentalMotor = new RentalMotor($motor, $waktu);
        $this->totalPembayaran = $this->hitungTotalPembayaran();
    }

    private function dapatkanStatusMember($nama)
    {
        
        switch ($nama) {
            case 'delvin':
            case 'dimas':
            case 'budi':
                return 'member';
            default:
                return 'nonmember';
        }
    }

    private function hitungTotalPembayaran()
    {
        $harga = $this->rentalMotor->hitungHarga();
        $diskon = ($this->member === 'member') ? 0.05 : 0;
        return $harga - ($harga * $diskon);
    }

    public function getStruk()
    {
        $totalFormatted = number_format($this->totalPembayaran, 0, ',', '.');
        return "
            <div class='card mt-4'>
                <div class='card-body'>
                    <h4 class='card-title'>Struk Penyewaan Motor</h4>
                    <p class='card-text'>Pelanggan: $this->nama</p>
                    <p class='card-text'>Motor: " . $this->rentalMotor->getMotor() . "</p>
                    <p class='card-text'>Waktu: " . $this->rentalMotor->getWaktu() . " Hari</p>
                    <p class='card-text'>Harga Total: Rp. $totalFormatted</p>
                    <p class='card-text'>penyewa dengan nama '$this->nama' berstatus $this->member</p>
                </div>
            </div>";
    }
}
if (isset($_POST['sewa'])) {
    $nama = $_POST['nama'];
    $motor = $_POST['motor'];
    $waktu = $_POST['waktu'];

    // Proses sewa motor
    $sewaMotor = new SewaMotor($nama, $motor, $waktu);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penyewaan Motor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #007bff;
        }

        .form-control {
            border: 2px solid #007bff;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card-text {
            color: #6c757d;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Form Penyewaan Motor</h2>
                        <form action="" method="post" onsubmit="return validateForm()">
                            <div class="form-group">
                                <label for="nama">Masukkan nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="waktu">Masukkan waktu penyewaan (dalam waktu Hari)</label>
                                <input type="number" class="form-control" name="waktu" id="waktu" required min="1">
                            </div>
                            <div class="form-group">
                                <label for="motor">Pilih motor</label>
                                <select class="form-control" name="motor" id="motor" required>
                                    <option value="vario">Pilih Motor</option>
                                    <option value="vario">Vario 150</option>
                                    <option value="aerox">Aerox</option>
                                    <option value="trail">Trail</option>
                                    <option value="ninja">Ninja</option>
                                </select>
                            </div>
                            <button type="submit" name="sewa" class="btn btn-primary btn-block">Sewa</button>
                        </form>
                        <?php
                        if (isset($sewaMotor)) {
                            echo $sewaMotor->getStruk();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validateForm() {
            let nama = document.getElementById('nama').value;
            let waktu = document.getElementById('waktu').value;
            let motor = document.getElementById('motor').value;
            if (!nama || !waktu || !motor) {
                alert("Semua field harus diisi!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>

