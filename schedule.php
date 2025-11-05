<?php
$arrival = $_POST['arrival'];
$burst = $_POST['burst'];
$priority = $_POST['priority'];
$algo = $_POST['algorithm'];
$quantum = !empty($_POST['quantum']) ? intval($_POST['quantum']) : 2;

$n = count($arrival);
$processes = [];
for ($i = 0; $i < $n; $i++) {
    $processes[] = [
        'id' => "P".($i+1),
        'arrival' => intval($arrival[$i]),
        'burst' => intval($burst[$i]),
        'priority' => intval($priority[$i])
    ];
}

function gantt($order) {
    echo "<div style='margin:10px 0;padding:10px;border:1px solid black;display:inline-block'>";
    foreach ($order as $p) echo "| ".$p['id']." ";
    echo "|</div><br>";
}

function print_table($order) {
    echo "<table border='1' cellpadding='6'>
            <tr><th>Proses</th><th>Arrival</th><th>Burst</th><th>Waiting</th><th>Turnaround</th></tr>";
    $avgW = $avgT = 0;
    foreach ($order as $p) {
        echo "<tr><td>{$p['id']}</td><td>{$p['arrival']}</td><td>{$p['burst']}</td>
              <td>{$p['waiting']}</td><td>{$p['turnaround']}</td></tr>";
        $avgW += $p['waiting'];
        $avgT += $p['turnaround'];
    }
    echo "</table><br>";
    echo "<strong>Avg Waiting Time:</strong> ".($avgW/count($order))."<br>";
    echo "<strong>Avg Turnaround Time:</strong> ".($avgT/count($order))."<br>";
}

switch ($algo) {
    case "fcfs":
        usort($processes, fn($a,$b)=>$a['arrival']<=>$b['arrival']);
        $time = 0;
        foreach($processes as &$p){
            if ($time < $p['arrival']) $time = $p['arrival'];
            $p['waiting'] = $time - $p['arrival'];
            $time += $p['burst'];
            $p['turnaround'] = $p['waiting'] + $p['burst'];
        }
        echo "<h2>FCFS Result</h2>";
        gantt($processes);
        print_table($processes);
    break;

    case "sjf":
        $remaining = $processes;
        $result = [];
        $time = 0;
        while (count($remaining) > 0) {
            $available = array_filter($remaining, fn($p)=>$p['arrival'] <= $time);
            if (empty($available)) {
                $time++;
                continue;
            }
            usort($available, fn($a,$b)=>$a['burst']<=>$b['burst']);
            $p = array_shift($available);
            foreach ($remaining as $k=>$v)
                if ($v['id']==$p['id']) unset($remaining[$k]);
            $p['waiting'] = $time - $p['arrival'];
            $time += $p['burst'];
            $p['turnaround'] = $p['waiting'] + $p['burst'];
            $result[] = $p;
        }
        echo "<h2>SJF Result</h2>";
        gantt($result);
        print_table($result);
    break;

    case "priority":
        usort($processes, fn($a,$b)=>$a['priority']<=>$b['priority']);
        $time = 0;
        foreach($processes as &$p){
            if ($time < $p['arrival']) $time = $p['arrival'];
            $p['waiting'] = $time - $p['arrival'];
            $time += $p['burst'];
            $p['turnaround'] = $p['waiting'] + $p['burst'];
        }
        echo "<h2>Priority Scheduling Result</h2>";
        gantt($processes);
        print_table($processes);
    break;

    case "rr":
        $queue = $processes;
        $result = [];
        $time = 0;
        foreach ($queue as &$p) $p['remaining'] = $p['burst'];
        while (!empty($queue)) {
            $p = array_shift($queue);
            if ($p['remaining'] > $quantum) {
                $p['remaining'] -= $quantum;
                $time += $quantum;
                $queue[] = $p;
            } else {
                $time += $p['remaining'];
                $p['turnaround'] = $time - $p['arrival'];
                $p['waiting'] = $p['turnaround'] - $p['burst'];
                $result[] = $p;
            }
        }
        echo "<h2>Round Robin Result</h2>";
        gantt($result);
        print_table($result);
    break;
}
?>
<br><a href="index.php">🔄 Kembali</a>
