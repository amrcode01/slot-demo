
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="d-flex align-items-center gap-2 flex-column mt-5 p-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">List Pemain</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="members-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Persentase</th>
                                        <th>User Agent</th>
                                        <th>Connection Time</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody id="userTableBody">
                                    <!-- Rows will be added here dynamically -->
                                </tbody>
                            </table>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        }
    </script>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js" integrity="sha384-2huaZvOR9iDzHqslqwpR87isEmrfxqyWOF7hr7BY6KG0+hVKLoEXMPUJw3ynWuhO" crossorigin="anonymous"></script>
    <script>        
        const socket = io("http://localhost:3333/kkn");
        socket.on("admin-list-user", (data) => {
            addUserToTable(data);
        });
        socket.on("admin-list-user-delete", (userId) => {
            removeUserFromTable(userId);
        });
        function addUserToTable(data) {
            const tableBody = document.getElementById('userTableBody');
            const row = document.createElement('tr');
            row.setAttribute('id', data.id);

            const idCell = document.createElement('td');
            idCell.textContent = data.id;
            row.appendChild(idCell);

            const namaCell = document.createElement('td');
            namaCell.textContent = data.nama;
            row.appendChild(namaCell);

            const userAgentCell = document.createElement('td');
            userAgentCell.textContent = data.user_agent;
            row.appendChild(userAgentCell);

            const waktuKonekCell = document.createElement('td');
            waktuKonekCell.textContent = data.waktu_konek;
            row.appendChild(waktuKonekCell);

            const addressCell = document.createElement('td');
            addressCell.textContent = data.address;
            row.appendChild(addressCell);

            tableBody.appendChild(row);
        }
        // Fungsi untuk menghapus user dari tabel
        function removeUserFromTable(userId) {
            const row = document.getElementById(userId);
            if (row) {
                row.remove();
            }
        }
        // Event listener untuk menghapus user
    </script>
    <script>
        function postMessage(){
            let persen = document.querySelector("#persen").value;
            toastr["info"]("Sedang Melakukan Request", "Tunggu")
            $.post("send.php", {data: {
                persen : persen
            }}, function(result){
                toastr["success"](result, "Berhasil")
            });
        }
    </script>
</html>