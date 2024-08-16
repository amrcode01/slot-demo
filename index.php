<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Machine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="d-flex justify-content-center mt-5 p-4">
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header">Slot Machine</div>
                <div class="card-body">
                    <div class="d-flex justify-content-center mt-3 mb-3 gap-2">
                        <div class="d-flex align-items-center flex-column gap-1">
                            <div class="badge bg-success" id="my_saldo">Rp <?php echo number_format($_GET['saldo'] ?? 10000,0,'.','.')?></div>
                            <div class="badge bg-dark">Saldo Anda</div>
                        </div>
                        <div class="d-flex align-items-center flex-column gap-1">
                            <div class="badge bg-primary" id="my_saldo">Rp <?php echo number_format($_GET['bet'] ?? 500,0,'.','.')?></div>
                            <div class="badge bg-dark">Bet Anda</div>
                        </div>
                    </div>
                    <table id="matrixTable" style="overflow: hidden;">
                        <!-- Matriks akan dibuat di sini oleh JavaScript -->
                    </table>
                    <br>
                    <button class="btn btn-primary" onclick="generateMatrix()">Spin</button>
                    <button class="btn btn-warning" onclick="startMulty(this)" data="manual">Auto Spin</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js" integrity="sha384-2huaZvOR9iDzHqslqwpR87isEmrfxqyWOF7hr7BY6KG0+hVKLoEXMPUJw3ynWuhO" crossorigin="anonymous"></script>
    <script>
        var winPercentage = 5; // Persentase kemenangan (20%)
        var JumlahBet = 500;
        var proses = true;
        let person = prompt("Masukan Nama Anda ?");
        const socket = io("http://localhost:3333/kkn",{
            query: {
                level : "pemain",
                nama: person
            }
        });
        socket.on("connect", () => {
            console.log(socket.id); // x8WIv7-mJelg7on_ALbx
        });
        socket.on("change-persen", (data) => {
            if(socket.id == data.id){
                try{
                    let persen = parseFloat(data?.persen)
                    if(persen > 0){
                        winPercentage = persen;   
                    }
                } catch(err){
                    alert(err)
                }
            }
        });
        
    </script>
    <script>
        const fruits = [
            '🍒', // Cherry
            '🍋', // Lemon
            '🍊', // Orange
            '🍉', // Watermelon
            '🍇', // Grapes
            '🍓'  // Strawberry
        ];
        // Fungsi untuk mendapatkan buah acak atau mengatur kemenangan berdasarkan persentase
        function getFruit(row, col) {
            if (Math.random() * 100 < winPercentage) {
                // Memastikan kemenangan terjadi (row atau col) 
                return '🍒'; // Misalnya, Cherry sebagai simbol kemenangan
            } else {
                return getRandomFruit();
            }
        }

        // Fungsi untuk mendapatkan buah acak
        function getRandomFruit() {
            const randomIndex = Math.floor(Math.random() * fruits.length);
            return fruits[randomIndex];
        }

        // Fungsi untuk membuat matriks 3x3
        function generateMatrix(firstSpin = false) {
            const LastSaldo = getMySaldo();
            if(LastSaldo < JumlahBet){
                alert("Saldo Tidak Mencukupi")
                return false;
            }
            const table = document.getElementById("matrixTable");
            table.innerHTML = ""; // Kosongkan tabel sebelum diisi ulang

            let matrix = [];

            for (let i = 0; i < 3; i++) {
                let row = table.insertRow(); // Buat baris baru
                let rowData = [];

                for (let j = 0; j < 3; j++) {
                    let fruit = getFruit(i, j);
                    let cell = row.insertCell(); // Buat sel baru di baris
                    cell.textContent = fruit; // Isi sel dengan buah acak atau kemenangan
                    if(firstSpin == false){
                        cell.classList.add('spin');
                    }
                    rowData.push(fruit);
                    rowData.push(fruit);
                }
                matrix.push(rowData);
            }

            if(firstSpin == false){
                checkWin(matrix);
            }
            return true;
        }

        // Fungsi untuk memeriksa kemenangan
        function checkWin(matrix) {
            let isWin = false;
            let jumlahWin = 0;
            // Periksa kemenangan horizontal
            for (let i = 0; i < 3; i++) {
                if (matrix[i].every(fruit => fruit === matrix[i][0])) {
                    highlightRow(i);
                    isWin = true;
                    jumlahWin++;
                }
            }

            // Periksa kemenangan vertikal
            for (let j = 0; j < 3; j++) {
                if (matrix.every(row => row[j] === matrix[0][j])) {
                    highlightColumn(j);
                    isWin = true;
                    jumlahWin++;
                }
            }

            // Periksa kemenangan diagonal (kiri atas ke kanan bawah)
            if (matrix.every((row, i) => row[i] === matrix[0][0])) {
                highlightDiagonal('left');
                isWin = true;
                jumlahWin++;
            }

            // Periksa kemenangan diagonal (kanan atas ke kiri bawah)
            if (matrix.every((row, i) => row[2 - i] === matrix[0][2])) {
                highlightDiagonal('right');
                isWin = true;
                jumlahWin++;
            }

            if (isWin) {
                editSaldo("tambah",JumlahBet * jumlahWin)
            } else {
                editSaldo("kurang",JumlahBet)
            }
        }

        // Fungsi untuk menyorot baris yang menang
        function highlightRow(row) {
            const table = document.getElementById("matrixTable");
            for (let j = 0; j < 3; j++) {
                table.rows[row].cells[j].classList.add('win');
            }
        }

        // Fungsi untuk menyorot kolom yang menang
        function highlightColumn(col) {
            const table = document.getElementById("matrixTable");
            for (let i = 0; i < 3; i++) {
                table.rows[i].cells[col].classList.add('win');
            }
        }

        // Fungsi untuk menyorot diagonal yang menang
        function highlightDiagonal(direction) {
            const table = document.getElementById("matrixTable");
            if (direction === 'left') {
                for (let i = 0; i < 3; i++) {
                    table.rows[i].cells[i].classList.add('win');
                }
            } else if (direction === 'right') {
                for (let i = 0; i < 3; i++) {
                    table.rows[i].cells[2 - i].classList.add('win');
                }
            }
        }

        // Fungsi untuk memulai permainan
        const sleep = ms => new Promise(r => setTimeout(r, ms));


        function getMySaldo(){
            try{
                var MySaldo = document.querySelector("#my_saldo");
                var getJumlah = MySaldo.innerHTML.replace(/^\D+/g, '').replace(".","");
                getJumlah = parseInt(getJumlah)
            } catch(err){
                var getJumlah = 0;
            }
            return getJumlah;
        }

        function editSaldo(type,jumlah){
            var MySaldo = document.querySelector("#my_saldo");
            var getJumlah = getMySaldo();
            var finalSaldo;
            if(type == "tambah"){
                MySaldo.innerHTML = "Rp. "+rp(getJumlah + jumlah);
                finalSaldo = getJumlah + jumlah;
            } else{
                MySaldo.innerHTML = "Rp. "+rp(getJumlah - jumlah);
                finalSaldo = getJumlah - jumlah;
            }
            socket.emit("change-saldo", {id : socket.id,saldo : finalSaldo});
        }
        function rp(angka){
            try {
                var rupiah = '';		
                var angkarev = angka.toString().split('').reverse().join('');
                for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
                return rupiah.split('',rupiah.length-1).reverse().join('');
            } catch (error) {
                console.log("ERROR FUNC RP")
                return angka;
            }
        }

        async function startMulty(e){
            let type = e.getAttribute('data');
            if(type == 'manual'){ //to auto
                proses = true;
                e.setAttribute("data","auto")
                e.innerHTML = "Matikan Auto Spin"
            } else {
                proses = false;
                e.setAttribute("data","manual")
                e.innerHTML = "Auto Spin"
            }
            while(proses == true){
              var a = generateMatrix();
              if(a == false){
                  console.log("Proses Berhenti")
                  break;
              }
              await sleep(1800);
            }
          }

        generateMatrix(true);
    </script>

</body>
</html>
