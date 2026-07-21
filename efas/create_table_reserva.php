<?php
// Script to create the database table for table reservations
// To run this, hit it in a browser or trigger it.
include("sistema_mod_include.php");

$conexao = conecta_mysql();
if (!$conexao) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS reserva_mesa (
    codigo_reserva INT AUTO_INCREMENT PRIMARY KEY,
    codigo_evento INT NOT NULL,
    numero_mesa INT NOT NULL,
    nome_participante VARCHAR(255) NOT NULL,
    email_participante VARCHAR(255) NOT NULL,
    telefone_participante VARCHAR(50) NOT NULL,
    codigo_situacao INT DEFAULT 1, -- 1 = Pendente, 2 = Confirmada/Paga, 3 = Cancelada
    data_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_reserva DECIMAL(10,2) NOT NULL,
    mp_preference_id VARCHAR(255) NULL,
    UNIQUE KEY unique_evento_mesa (codigo_evento, numero_mesa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if (mysqli_query($conexao, $sql)) {
    echo "Sucesso: Tabela 'reserva_mesa' criada ou já existente.";
} else {
    echo "Erro ao criar tabela: " . mysqli_error($conexao);
}

fecha_mysql($conexao);
?>
