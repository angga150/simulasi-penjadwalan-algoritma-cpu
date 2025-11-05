<!DOCTYPE html>
<html>
<head>
    <title>Simulasi Penjadwalan CPU</title>
</head>
<body>
    <h2>Simulasi Algoritma Penjadwalan CPU</h2>
    <form action="schedule.php" method="post">
        <table border="1" cellpadding="5">
            <tr>
                <th>Proses</th>
                <th>Arrival Time</th>
                <th>Burst Time</th>
                <th>Priority</th>
            </tr>
            <?php for($i=1; $i<=4; $i++): ?>
            <tr>
                <td>P<?=$i?></td>
                <td><input type="number" name="arrival[]" required></td>
                <td><input type="number" name="burst[]" required></td>
                <td><input type="number" name="priority[]" required></td>
            </tr>
            <?php endfor; ?>
        </table>

        <br>
        <label>Pilih Algoritma:</label><br>
        <select name="algorithm" required>
            <option value="fcfs">FCFS</option>
            <option value="sjf">SJF (Non-Preemptive)</option>
            <option value="priority">Priority Scheduling</option>
            <option value="rr">Round Robin</option>
        </select>

        <br><br>
        Quantum (untuk Round Robin):
        <input type="number" name="quantum">

        <br><br>
        <button type="submit">Proses</button>
    </form>
</body>
</html>
