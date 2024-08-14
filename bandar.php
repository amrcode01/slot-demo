
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="d-flex align-items-center gap-2 flex-column mt-5 p-4">
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">Bandar Judi</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input class="form-control" type="number" id="persen" placeholder="1-100"/>
                        </div>
                        <div class="mt-1">
                            <button onclick="postMessage()" class="btn btn-primary w-100">
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">List Pemain</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="members-table">
                              <thead>
                                <tr>
                                  <th>User ID</th>
                                  <th>Name</th>
                                </tr>
                              </thead>
                              <tbody>
                                <!-- Users will be added here -->
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
        Pusher.logToConsole = false;
        var pusher = new Pusher('72c02cdab86cc52e6e5d', {
            cluster: 'ap1'
        });
        var channel = pusher.subscribe('my-channel');
        channel.bind("pusher:subscription_succeeded", (members) => {
            console.log(members)
        });
        channel.bind('my-event', function(data) {
            console.log(data);
        });
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