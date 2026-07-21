<?php
require_once("configuracoes.php");

function conecta_mysql() {
    global $conexao;
    if (!isset($conexao) || !$conexao) {
        $conexao = mysqli_connect(BD_HOST, BD_USUARIO, BD_SENHA, BD_BANCO);
        if ($conexao) {
            mysqli_set_charset($conexao, "utf8");
            
            // Self-healing database: Automatically create table if missing
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
            mysqli_query($conexao, $sql);
        }
    }
    return $conexao;
}

function fecha_mysql($conexao) {
    if ($conexao) {
        mysqli_close($conexao);
    }
}

function protege_campo($string) {
    $conexao = conecta_mysql();
    if ($conexao) {
        $str = mysqli_real_escape_string($conexao, trim($string));
    } else {
        $str = addslashes(trim($string));
    }
    // Remove tags HTML/PHP básicas para proteção
    return strip_tags($str);
}
?>
