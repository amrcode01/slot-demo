<?php
  require __DIR__ . '/vendor/autoload.php';

  $options = array(
    'cluster' => 'ap1',
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    '72c02cdab86cc52e6e5d',
    '95175a2befb677280367',
    '1849663',
    $options
  );

  $data = $_POST['data'];
  $pusher->trigger('my-channel', 'changePersentase', $data);
  echo "Berhasil Mengirim Data";
?>