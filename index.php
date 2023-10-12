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
    $totalChanges = 0;
    while ($table_row = $tables_result->fetch_row()) {
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
        sleep(1); // Introduz o atraso de 1 segundo
    }
    $tables_result->close();
    $conn->close();
    echo "Total de alterações feitas: $totalChanges\n";
} else {
    echo "Não foi possível obter a lista de tabelas.\n";
}
?>
