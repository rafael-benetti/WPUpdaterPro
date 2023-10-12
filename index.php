<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'wp';

$search = 'dominioantigo.com.br';
$replace = 'dominionovo.com.br';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$tables_query = "SHOW TABLES";
$tables_result = $conn->query($tables_query);

if ($tables_result) {
    $totalTables = $tables_result->num_rows;
    $currentTable = 0;
    $totalChanges = 0;
    
    echo "<div style='text-align: center; margin-top: 50px;'>";
    echo "<h2>Progresso da Atualização das tabelas</h2>";
    echo "<div id='progress' style='width: 60%; margin: 0 auto; background-color: #ddd;'><div id='progress-bar' style='width: 0%; height: 30px; background-color: #4CAF50; text-align: center; line-height: 30px;'>0%</div></div>";
    
    while ($table_row = $tables_result->fetch_row()) {
        $currentTable++;
        $table_name = $table_row[0];
        $columns_query = "SHOW COLUMNS FROM $table_name";
        $columns_result = $conn->query($columns_query);
        
        if ($columns_result) {
            while ($column_row = $columns_result->fetch_assoc()) {
                $column_name = $column_row['Field'];
                $sql = "UPDATE $table_name SET $column_name = REPLACE($column_name, '$search', '$replace')";
                $result = $conn->query($sql);
                if ($result) {
                    $totalChanges += $conn->affected_rows;
                }
            }
        }
        
        $progress = ($currentTable / $totalTables) * 100;
        echo "<script>document.getElementById('progress-bar').style.width = '$progress%'; document.getElementById('progress-bar').innerHTML = '$progress%';</script>";
        flush(); 
    }
    
    echo "</div>";
    $tables_result->close();
    $conn->close();
    
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<h2>Resumo das Alterações</h2>";
    echo "Total de alterações feitas: $totalChanges";
    echo "</div>";
} else {
    echo "Não foi possível obter a lista de tabelas.\n";
}
?>
